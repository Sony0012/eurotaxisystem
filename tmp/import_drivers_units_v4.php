<?php

namespace App\Tmp;

use Illuminate\Support\Facades\DB;

class CsvImporterV4 {
    public static function run() {
        $csvPath = 'C:\\Users\\bertl\\OneDrive\\Desktop\\Taxi taxi driver (1).csv';
        $logPath = 'c:\\xampp\\htdocs\\eurotaxisystem\\tmp\\import_log_v4.txt';
        
        // Comprehensive year mapping from images
        $yearMapping = [
            'VFL 543' => 2013, 'AAK 4591' => 2014, 'AAA 4591' => 2014, 'AAQ 1743' => 2014,
            'AAK 9196' => 2015, 'ALA 3699' => 2015, 'ABG 7479' => 2015, 'ABL 6901' => 2015,
            'ABL 1667' => 2015, 'AEA 9630' => 2015, 'ABF 7471' => 2015, 'ABP 2705' => 2015,
            'ABP 7643' => 2015, 'AOA 8917' => 2015, 'NBW 7071' => 2016, 'DAD 7555' => 2017,
            'DCQ 1551' => 2017, 'NBX 4348' => 2017, 'NAE 7193' => 2017, 'NAD 1140' => 2017,
            'NAC 4989' => 2017, 'NDG 7105' => 2017, 'NCN 8583' => 2017, 'NAM 1610' => 2017,
            'NCW 5011' => 2018, 'NDA 8106' => 2019, 'NEA 1292' => 2019, 'NEI 4883' => 2019,
            'NDI 2585' => 2019, 'NEN 2955' => 2019, 'NEN 2957' => 2019, 'DAJ 7468' => 2019,
            'NDA 8102' => 2019, 'NDA 5429' => 2019, 'NGF 1484' => 2020, 'DBA 2302' => 2020,
            'NEU 5546' => 2020, 'NEF 4940' => 2020, 'DAZ 9769' => 2020, 'DBA 5420' => 2020,
            'EAE 1247' => 2020, 'CAT 6073' => 2020, 'DBA 1887' => 2020, 'NAN 1349' => 2020,
            'NFZ 8295' => 2020, 'NGA 5044' => 2020, 'EAD 7438' => 2020, 'CAV 2607' => 2020,
            'EAE 1919' => 2021, 'VAA 9864' => 2021, 'NGO 2629' => 2021, 'NEV 5065' => 2021,
            'DAT 1367' => 2021, 'NEW 6279' => 2021, 'EAF 6347' => 2021, 'CAV 9662' => 2021,
            'CAV 9716' => 2021, 'EAE 5883' => 2021, 'NGP 1877' => 2021, 'NGB 2854' => 2021,
            'CAV 6803' => 2021, 'DAU 9027' => 2021, 'NEO 67116' => 2021, 'NGA 7736' => 2021,
            'NGB 6033' => 2021, 'EAF 7245' => 2021, 'EAE 4949' => 2021, 'NEP 9750' => 2021,
            'NET 6100' => 2021, 'NEW 3821' => 2021, 'CBM 1979' => 2021, 'DAT 2567' => 2021,
            'NEP 2440' => 2021, 'NFH 3664' => 2022, 'CAX 5430' => 2022,
        ];
        
        file_put_contents($logPath, "Import Refinement Started: " . date('Y-m-d H:i:s') . "\n");

        if (!file_exists($csvPath)) {
            file_put_contents($logPath, "CSV not found: $csvPath\n", FILE_APPEND);
            return;
        }

        $lines = file($csvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $header = array_shift($lines);

        $unitsUpdated = 0;

        foreach ($lines as $line) {
            $row = str_getcsv($line);
            if (empty($row[2])) continue;

            $setA = trim($row[1] ?? '');
            $plate = trim($row[2] ?? '');
            $setB = trim($row[3] ?? '');
            $remarks = trim($row[5] ?? '');
            
            $status = 'active';
            $upA = strtoupper($setA);
            $upB = strtoupper($setB);
            $upRem = strtoupper($remarks);
            
            // Refined Status Detection
            if ($upA === 'NAD' || $upB === 'NAD') {
                $status = 'vacant';
            } elseif (strpos($upA, 'NAFTM') !== false || strpos($upB, 'NAFTM') !== false || 
                      strpos($upA, 'NATFM') !== false || strpos($upB, 'NATFM') !== false ||
                      strpos($upRem, 'SHOP') !== false) {
                $status = 'maintenance';
            }

            try {
                // ALWAYS get driver IDs if they are actual names
                $d1_id = self::getDriverId($setA);
                $d2_id = ($setA !== $setB) ? self::getDriverId($setB) : null;

                $year = $yearMapping[$plate] ?? 2023;

                DB::table('units')->updateOrInsert(
                    ['plate_number' => $plate],
                    [
                        'status' => $status,
                        'driver_id' => $d1_id,
                        'secondary_driver_id' => $d2_id,
                        'make' => 'Toyota',
                        'model' => 'Vios',
                        'year' => $year,
                        'updated_at' => now(),
                    ]
                );
                $unitsUpdated++;
                file_put_contents($logPath, "Processed Unit: $plate (Status: $status, D1: $d1_id, D2: $d2_id)\n", FILE_APPEND);
            } catch (\Exception $e) {
                file_put_contents($logPath, "Error processing unit $plate: " . $e->getMessage() . "\n", FILE_APPEND);
            }
        }

        // Also ensure the 2 extra units are present
        $extraUnits = [
            ['plate_number' => 'NAN 1349', 'year' => 2020, 'status' => 'vacant'],
            ['plate_number' => 'ABP 2705', 'year' => 2015, 'status' => 'vacant'],
        ];
        foreach ($extraUnits as $eu) {
            DB::table('units')->updateOrInsert(
                ['plate_number' => $eu['plate_number']],
                [
                    'year' => $eu['year'],
                    'status' => $eu['status'],
                    'make' => 'Toyota',
                    'model' => 'Vios',
                    'updated_at' => now(),
                ]
            );
        }

        file_put_contents($logPath, "\nDONE. Processed: $unitsUpdated units + 2 extra.\n", FILE_APPEND);
        echo "DONE. Check tmp/import_log_v4.txt for results.\n";
    }

    private static function getDriverId($name) {
        $clean = strtoupper(trim($name));
        if (empty($name) || in_array($clean, ['NAD', 'NAFTM', 'NATFM', 'NAFTM ', 'NATFM '])) return null;

        $name = trim($name, '" ');
        $first = '';
        $last = '';

        if (strpos($name, ',') !== false) {
            $parts = explode(',', $name);
            $last = trim($parts[0]);
            $first = trim($parts[1] ?? '');
        } elseif (strpos($name, '.') !== false) {
            $parts = explode('.', $name);
            $last = trim($parts[0]);
            $first = trim($parts[1] ?? '');
        } else {
            $parts = explode(' ', $name);
            $last = $parts[0];
            $first = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';
        }

        $driver = DB::table('drivers')->where('first_name', $first)->where('last_name', $last)->first();
        if (!$driver) {
            $id = DB::table('drivers')->insertGetId([
                'first_name' => $first,
                'last_name' => $last,
                'license_number' => 'TBD-' . strtoupper(substr(md5($name), 0, 8)),
                'license_expiry' => '2027-01-01',
                'driver_status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return $id;
        }

        return $driver->id;
    }
}

try {
    CsvImporterV4::run();
} catch (\Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
