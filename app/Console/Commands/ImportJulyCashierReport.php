<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use ZipArchive;
use SimpleXMLElement;

class ImportJulyCashierReport extends Command
{
    protected $signature = 'fleet:import-july-cashier {--file= : Path to Cashiers Report Jul 2026 (Fixed).xlsx}';
    protected $description = 'Designate drivers/units and import July 2026 Cashiers Report boundaries and driver pondo';

    public function handle()
    {
        $filePath = $this->option('file');
        if (empty($filePath)) {
            $candidates = [
                'C:\\Users\\bertl\\Downloads\\Cashiers Report Jul 2026 (Fixed).xlsx',
                'C:\\Users\\bertl\\Downloads\\Cashiers Report Jul 2026.xlsx',
                base_path('Cashiers_Report_Jul_2026_Fixed.xlsx'),
                base_path('Cashiers Report Jul 2026 (Fixed).xlsx'),
                '/home/u747826271/domains/eurotaxisystem.site/public_html/Cashiers_Report_Jul_2026_Fixed.xlsx',
            ];
            foreach ($candidates as $cand) {
                if (file_exists($cand)) {
                    $filePath = $cand;
                    break;
                }
            }
        }

        if (empty($filePath) || !file_exists($filePath)) {
            $this->error("Excel file not found. Specify via --file=/path/to/file.xlsx");
            return 1;
        }

        $this->info("Loading Excel file: {$filePath}");

        $zip = new ZipArchive();
        if ($zip->open($filePath) !== TRUE) {
            $this->error("Failed to open xlsx file as zip.");
            return 1;
        }

        $wb = simplexml_load_string($zip->getFromName('xl/workbook.xml'));
        $rels = simplexml_load_string($zip->getFromName('xl/_rels/workbook.xml.rels'));
        $ssXml = simplexml_load_string($zip->getFromName('xl/sharedStrings.xml'));

        $relMap = [];
        foreach ($rels->Relationship as $r) {
            $relMap[(string)$r['Id']] = (string)$r['Target'];
        }

        // Helper to get cell value
        $getCellVal = function($cell) use ($ssXml) {
            if (!$cell) return '';
            $t = (string)$cell['t'];
            if ($t === 'inlineStr' && isset($cell->is->t)) {
                return trim((string)$cell->is->t);
            }
            if ($t === 's') {
                $idx = (int)$cell->v;
                return trim((string)$ssXml->si[$idx]->t);
            }
            return trim((string)$cell->v);
        };

        // Find all July sheets
        $julySheets = [];
        foreach ($wb->sheets->sheet as $s) {
            $name = (string)$s['name'];
            if (preg_match('/^07(\d{2})26$/', $name, $matches)) {
                $day = $matches[1];
                $rAttrs = $s->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships');
                $target = 'xl/' . $relMap[(string)$rAttrs['id']];
                $julySheets[$name] = [
                    'file' => $target,
                    'date' => sprintf('2026-07-%02d', (int)$day)
                ];
            }
        }
        ksort($julySheets);

        $this->info("Found " . count($julySheets) . " July sheets.");

        // Step 1: Pre-scan all sheets to collect shifts and records
        $allRecords = [];
        $plateDriverShifts = []; // [plate][driverName] => count
        $distinctDrivers = [];
        $distinctPlates = [];

        foreach ($julySheets as $sheetName => $info) {
            $date = $info['date'];
            $sheetXml = simplexml_load_string($zip->getFromName($info['file']));

            foreach ($sheetXml->sheetData->row as $row) {
                $r = (int)$row['r'];
                if ($r < 6) continue;

                $rowMap = [];
                foreach ($row->c as $c) {
                    $ref = (string)$c['r'];
                    $col = preg_replace('/[0-9]/', '', $ref);
                    $rowMap[$col] = $getCellVal($c);
                }

                $plate = strtoupper(trim(preg_replace('/\s+/', ' ', $rowMap['B'] ?? '')));
                $driverRaw = trim($rowMap['D'] ?? '');
                $fuel = trim($rowMap['C'] ?? '');

                if ($plate === '' || $driverRaw === 'Grand Total' || stripos($driverRaw, 'Total') !== false) {
                    continue;
                }
                if (!preg_match('/^[A-Z]{2,3}\s*\d{3,4}$/', $plate)) {
                    continue;
                }
                if (empty($driverRaw) || $driverRaw === '0') {
                    continue;
                }

                $pondo = (float)($rowMap['H'] ?? 0);
                $short = (float)($rowMap['Q'] ?? 0);
                $net   = (float)($rowMap['R'] ?? 0);

                // Contextual notes
                $noteParts = [];
                $misc = (float)($rowMap['I'] ?? 0);
                $penalty = (float)($rowMap['J'] ?? 0);
                $shopGas = (float)($rowMap['L'] ?? 0);
                $wIssue = (float)($rowMap['M'] ?? 0);
                $cc = (float)($rowMap['P'] ?? 0);

                if ($shopGas > 0) $noteParts[] = "Shop/Gas: ₱" . number_format($shopGas, 2);
                if ($misc > 0) $noteParts[] = "Misc: ₱" . number_format($misc, 2);
                if ($penalty > 0) $noteParts[] = "Penalty: ₱" . number_format($penalty, 2);
                if ($wIssue > 0) $noteParts[] = "Holiday Issue: ₱" . number_format($wIssue, 2);
                if ($cc > 0) $noteParts[] = "CC: ₱" . number_format($cc, 2);

                $notes = !empty($noteParts) ? implode(' | ', $noteParts) : null;

                $distinctPlates[$plate] = true;
                $distinctDrivers[$driverRaw] = true;
                $plateDriverShifts[$plate][$driverRaw] = ($plateDriverShifts[$plate][$driverRaw] ?? 0) + 1;

                $allRecords[] = [
                    'date' => $date,
                    'plate' => $plate,
                    'driverRaw' => $driverRaw,
                    'net' => $net,
                    'pondo' => $pondo,
                    'short' => $short,
                    'notes' => $notes,
                ];
            }
        }
        $zip->close();

        $this->info("Pre-scan complete: " . count($allRecords) . " records, " . count($distinctPlates) . " plates, " . count($distinctDrivers) . " drivers.");

        // Helper to normalize names
        $normalizeName = function($name) {
            $name = trim($name);
            if (strpos($name, ',') === false && preg_match('/^([^\.]+)\.\s*(.*)$/', $name, $m)) {
                $name = $m[1] . ', ' . $m[2];
            }
            $parts = explode(',', $name);
            $last = trim($parts[0] ?? '');
            $first = trim($parts[1] ?? '');
            $first = str_replace('.', '', $first);
            return [
                'last' => ucwords(strtolower($last)),
                'first' => !empty($first) ? ucwords(strtolower($first)) : 'Driver',
                'first_initial' => !empty($first) ? strtoupper(substr($first, 0, 1)) : '',
                'key' => strtoupper($last) . ', ' . (!empty($first) ? strtoupper(substr($first, 0, 1)) : '')
            ];
        };

        // Step 2: Ensure all Drivers exist in DB
        $this->info("Step 2: Resolving and registering Drivers...");
        $dbDrivers = DB::table('drivers')->whereNull('deleted_at')->get(['id', 'first_name', 'last_name']);
        $driverMap = []; // [rawName] => driver_id
        $dbLookupByKey = [];
        $dbLookupByLast = [];

        foreach ($dbDrivers as $d) {
            $norm = $normalizeName($d->last_name . ', ' . $d->first_name);
            $dbLookupByKey[$norm['key']] = $d->id;
            $dbLookupByLast[strtoupper($norm['last'])][] = $d->id;
        }

        $newDriversCreated = 0;
        foreach (array_keys($distinctDrivers) as $rawName) {
            $norm = $normalizeName($rawName);
            $key = $norm['key'];
            $last = strtoupper($norm['last']);

            if (isset($dbLookupByKey[$key])) {
                $driverMap[$rawName] = $dbLookupByKey[$key];
            } elseif (isset($dbLookupByLast[$last]) && count($dbLookupByLast[$last]) === 1) {
                $driverMap[$rawName] = $dbLookupByLast[$last][0];
            } else {
                // Insert new driver
                $newId = DB::table('drivers')->insertGetId([
                    'first_name' => $norm['first'],
                    'last_name' => $norm['last'],
                    'nickname' => $norm['first'],
                    'license_number' => 'LIC-' . strtoupper(substr(md5($rawName), 0, 8)),
                    'license_expiry' => '2028-12-31',
                    'daily_boundary_target' => 1100.00,
                    'driver_type' => 'regular',
                    'driver_status' => 'assigned',
                    'created_at' => '2026-07-01 00:00:00',
                    'updated_at' => '2026-07-01 00:00:00',
                ]);
                $driverMap[$rawName] = $newId;
                $dbLookupByKey[$key] = $newId;
                $dbLookupByLast[$last][] = $newId;
                $newDriversCreated++;
            }
        }
        $this->info("Drivers resolved: " . count($driverMap) . " mapped ({$newDriversCreated} newly created).");

        // Step 3: Ensure all Units exist in DB
        $this->info("Step 3: Resolving Units...");
        $dbUnits = DB::table('units')->whereNull('deleted_at')->pluck('id', 'plate_number')->toArray();
        $unitMap = []; // [plate] => unit_id
        foreach ($dbUnits as $plate => $id) {
            $unitMap[strtoupper(trim(preg_replace('/\s+/', ' ', $plate)))] = $id;
        }

        $newUnitsCreated = 0;
        foreach (array_keys($distinctPlates) as $plate) {
            if (!isset($unitMap[$plate])) {
                $newUnitId = DB::table('units')->insertGetId([
                    'plate_number' => $plate,
                    'make' => 'Toyota',
                    'model' => 'Vios',
                    'year' => 2018,
                    'status' => 'active',
                    'unit_type' => 'new',
                    'max_drivers' => 2,
                    'boundary_rate' => 1100.00,
                    'purchase_cost' => 500000.00,
                    'created_at' => '2026-07-01 00:00:00',
                    'updated_at' => '2026-07-01 00:00:00',
                ]);
                $unitMap[$plate] = $newUnitId;
                $newUnitsCreated++;
            }
        }
        $this->info("Units resolved: " . count($unitMap) . " mapped ({$newUnitsCreated} newly created).");

        // Step 4: Designate Drivers for each Unit based on July frequency
        $this->info("Step 4: Designating drivers to units based on July usage...");
        $unitsDesignated = 0;
        $unitDesignationMap = []; // [unit_id] => ['primary' => id, 'secondary' => id]

        foreach ($plateDriverShifts as $plate => $driverShifts) {
            $unitId = $unitMap[$plate];
            arsort($driverShifts);

            $driverIds = [];
            foreach (array_keys($driverShifts) as $rawDriver) {
                $dId = $driverMap[$rawDriver] ?? null;
                if ($dId && !in_array($dId, $driverIds)) {
                    $driverIds[] = $dId;
                }
            }

            $primaryId = $driverIds[0] ?? null;
            $secondaryId = (isset($driverIds[1]) && $driverShifts[array_keys($driverShifts)[1]] >= 2) ? $driverIds[1] : null;

            DB::table('units')->where('id', $unitId)->update([
                'driver_id' => $primaryId,
                'secondary_driver_id' => $secondaryId,
                'current_turn_driver_id' => $primaryId,
                'status' => 'active',
                'updated_at' => '2026-07-01 00:00:00'
            ]);

            if ($primaryId) {
                DB::table('drivers')->where('id', $primaryId)->update(['driver_status' => 'assigned']);
            }
            if ($secondaryId) {
                DB::table('drivers')->where('id', $secondaryId)->update(['driver_status' => 'assigned']);
            }

            $unitDesignationMap[$unitId] = [
                'primary' => $primaryId,
                'secondary' => $secondaryId
            ];
            $unitsDesignated++;
        }
        $this->info("Successfully designated drivers for {$unitsDesignated} units.");

        // Step 5: Insert Boundaries and Sync Driver Funds
        $this->info("Step 5: Inserting Boundaries & syncing Driver Funds (Pondo)...");
        DB::beginTransaction();

        try {
            $boundariesInserted = 0;
            $boundariesSkipped = 0;
            $pondoRecorded = 0;

            // Preload existing boundaries for July to avoid duplicates (unique on unit_id + date)
            $existingBoundaries = DB::table('boundaries')
                ->whereBetween('date', ['2026-07-01', '2026-07-31'])
                ->whereNull('deleted_at')
                ->select(['id', 'unit_id', 'driver_id', 'date'])
                ->get();

            $existingMap = [];
            foreach ($existingBoundaries as $eb) {
                $key = "{$eb->unit_id}_{$eb->date}";
                $existingMap[$key] = $eb->id;
            }

            // Running pondo balances
            $pondoBalances = DB::table('driver_funds')
                ->whereNull('deleted_at')
                ->selectRaw("driver_id, SUM(CASE WHEN type = 'deposit' THEN amount ELSE -amount END) as bal")
                ->groupBy('driver_id')
                ->pluck('bal', 'driver_id')
                ->toArray();

            foreach ($allRecords as $rec) {
                $unitId = $unitMap[$rec['plate']] ?? null;
                $driverId = $driverMap[$rec['driverRaw']] ?? null;
                $date = $rec['date'];

                if (!$unitId || !$driverId) {
                    continue;
                }

                $key = "{$unitId}_{$date}";
                if (isset($existingMap[$key])) {
                    $boundariesSkipped++;
                    continue;
                }

                $actualBoundary = $rec['net']; // Net is boundary
                $shortage = $rec['short'];
                $driverFund = $rec['pondo'];
                $boundaryAmount = max($actualBoundary + $shortage, 1100.00);

                if ($shortage > 0) {
                    $status = 'shortage';
                } elseif ($actualBoundary > $boundaryAmount) {
                    $status = 'excess';
                } elseif ($actualBoundary > 0) {
                    $status = 'paid';
                } else {
                    $status = 'pending';
                }
                $hasIncentive = ($shortage == 0 && $actualBoundary > 0) ? 1 : 0;

                $primaryId = $unitDesignationMap[$unitId]['primary'] ?? null;
                $secondaryId = $unitDesignationMap[$unitId]['secondary'] ?? null;
                $isExtraDriver = ($driverId != $primaryId && $driverId != $secondaryId) ? 1 : 0;

                $boundaryId = DB::table('boundaries')->insertGetId([
                    'unit_id' => $unitId,
                    'driver_id' => $driverId,
                    'expected_driver_id' => $primaryId,
                    'date' => $date,
                    'boundary_amount' => $boundaryAmount,
                    'actual_boundary' => $actualBoundary,
                    'driver_fund' => $driverFund,
                    'damage_payment' => 0.00,
                    'shortage' => $shortage,
                    'excess' => 0.00,
                    'status' => $status,
                    'notes' => $rec['notes'],
                    'is_extra_driver' => $isExtraDriver,
                    'has_incentive' => $hasIncentive,
                    'counted_for_incentive' => $hasIncentive,
                    'created_at' => "{$date} 17:00:00",
                    'updated_at' => "{$date} 17:00:00",
                ]);

                $existingMap[$key] = $boundaryId;
                $boundariesInserted++;

                // Record Pondo deposit
                if ($driverFund > 0) {
                    $curBal = (float)($pondoBalances[$driverId] ?? 0.00);
                    $newBal = $curBal + $driverFund;
                    $pondoBalances[$driverId] = $newBal;

                    DB::table('driver_funds')->insert([
                        'driver_id' => $driverId,
                        'boundary_id' => $boundaryId,
                        'type' => 'deposit',
                        'amount' => $driverFund,
                        'balance_after' => $newBal,
                        'description' => "Daily boundary savings (Unit {$rec['plate']})",
                        'date' => $date,
                        'created_at' => "{$date} 17:00:00",
                        'updated_at' => "{$date} 17:00:00",
                    ]);
                    $pondoRecorded++;
                }
            }

            DB::commit();

            $this->info("Boundaries inserted: {$boundariesInserted} (Skipped: {$boundariesSkipped}).");
            $this->info("Driver Pondo (Savings) transactions recorded: {$pondoRecorded}.");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error importing boundaries: " . $e->getMessage());
            return 1;
        }

        // Step 6: Clear Dashboard caches
        Cache::forget('web_dashboard_stats');
        Cache::forget('api_dashboard_stats_7');
        Cache::forget('api_dashboard_stats_30');
        $this->info("Caches cleared. Import completed successfully!");

        return 0;
    }
}
