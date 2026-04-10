<?php

namespace App\Tmp;

use App\Models\Driver;
use App\Models\Unit;
use Illuminate\Support\Facades\Log;

class CsvImporter {
    public static function run() {
        $csvPath = 'C:\\Users\\bertl\\OneDrive\\Desktop\\Taxi taxi driver (1).csv';
        if (!file_exists($csvPath)) {
            echo "CSV not found: $csvPath\n";
            return;
        }

        $lines = file($csvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $header = array_shift($lines); // Skip header

        $unitsCreated = 0;
        $driversCreated = 0;

        foreach ($lines as $index => $line) {
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

            $d1_id = self::getDriverId($setA);
            $d2_id = ($setA !== $setB) ? self::getDriverId($setB) : null;

            if ($d1_id) $driversCreated++;
            if ($d2_id) $driversCreated++;

            try {
                Unit::create([
                    'plate_number' => $plate,
                    'status' => $status,
                    'driver_id' => $d1_id,
                    'secondary_driver_id' => $d2_id,
                    'make' => 'Toyota',
                    'model' => 'Vios',
                    'year' => 2023,
                ]);
                $unitsCreated++;
                echo "Imported Unit: $plate\n";
            } catch (\Exception $e) {
                echo "Error importing $plate: " . $e->getMessage() . "\n";
            }
        }

        echo "\nDONE. Units: $unitsCreated, Drivers Checked/Created.\n";
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

        $driver = Driver::firstOrCreate(
            ['first_name' => $first, 'last_name' => $last],
            ['license_number' => 'TBD-' . strtoupper(substr(md5($name), 0, 8)), 'is_active' => true]
        );

        return $driver->id;
    }
}

CsvImporter::run();
