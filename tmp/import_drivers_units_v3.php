<?php

namespace App\Tmp;

use App\Models\Driver;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CsvImporterV3 {
    public static function run() {
        $csvPath = 'C:\\Users\\bertl\\OneDrive\\Desktop\\Taxi taxi driver (1).csv';
        $logPath = 'c:\\xampp\\htdocs\\eurotaxisystem\\tmp\\import_log.txt';
        
        file_put_contents($logPath, "Import Started: " . date('Y-m-d H:i:s') . "\n");

        if (!file_exists($csvPath)) {
            file_put_contents($logPath, "CSV not found: $csvPath\n", FILE_APPEND);
            return;
        }

        $lines = file($csvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $header = array_shift($lines);

        $unitsCreated = 0;
        $driversCreated = 0;

        foreach ($lines as $line) {
            $row = str_getcsv($line);
            if (empty($row[2])) continue;

            $setA = trim($row[1] ?? '');
            $plate = trim($row[2] ?? '');
            $setB = trim($row[3] ?? '');
            
            $status = 'active';
            $upA = strtoupper($setA);
            $upB = strtoupper($setB);
            
            if ($upA === 'NAD' || $upB === 'NAD') {
                $status = 'vacant';
            } elseif (strpos($upA, 'NAFTM') !== false || strpos($upB, 'NAFTM') !== false || strpos($upA, 'NATFM') !== false || strpos($upB, 'NATFM') !== false) {
                $status = 'maintenance';
            }

            try {
                $d1_id = self::getDriverId($setA);
                $d2_id = ($setA !== $setB) ? self::getDriverId($setB) : null;

                DB::table('units')->updateOrInsert(
                    ['plate_number' => $plate],
                    [
                        'status' => $status,
                        'driver_id' => $d1_id,
                        'secondary_driver_id' => $d2_id,
                        'make' => 'Toyota',
                        'model' => 'Vios',
                        'year' => 2023,
                        'updated_at' => now(),
                    ]
                );
                $unitsCreated++;
                file_put_contents($logPath, "Imported Unit: $plate\n", FILE_APPEND);
            } catch (\Exception $e) {
                file_put_contents($logPath, "Error importing unit $plate: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND);
            }
        }

        file_put_contents($logPath, "\nDONE. Units: $unitsCreated\n", FILE_APPEND);
        echo "DONE. Check tmp/import_log.txt for results.\n";
    }

    private static function getDriverId($name) {
        if (empty($name) || in_array(strtoupper(trim($name)), ['NAD', 'NAFTM', 'NATFM'])) return null;

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

        // Use DB directly to avoid any Eloquent issues
        $driver = DB::table('drivers')->where('first_name', $first)->where('last_name', $last)->first();
        if (!$driver) {
            $id = DB::table('drivers')->insertGetId([
                'first_name' => $first,
                'last_name' => $last,
                'license_number' => 'TBD-' . strtoupper(substr(md5($name), 0, 8)),
                'license_expiry' => '2027-01-01', // Placeholder
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
    CsvImporterV3::run();
} catch (\Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
