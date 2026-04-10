<?php
use App\Models\Unit;
use App\Models\Driver;
use Illuminate\Support\Facades\DB;

$filePath = "C:\\Users\\bertl\\OneDrive\\Desktop\\Taxi taxi driver (1).csv";
if (!file_exists($filePath)) {
    die("CSV not found at $filePath\n");
}

$file = fopen($filePath, 'r');
fgetcsv($file); // Skip header

$updatedCount = 0;
$skippedCount = 0;

function parseAndSaveDriver($name, $driverId) {
    if (empty($name) || in_array($name, ['NAD', 'VACANT', 'NAFTM', 'NATFM'])) {
        return false;
    }
    
    $driver = Driver::find($driverId);
    if (!$driver) return false;

    $parts = explode(',', $name);
    if (count($parts) >= 2) {
        $driver->last_name = trim($parts[0]);
        $driver->first_name = trim($parts[1]);
    } else {
        $driver->first_name = trim($name);
        $driver->last_name = '';
    }
    
    // Also set a nickname if it was empty
    if (empty($driver->nickname)) {
        $driver->nickname = $driver->first_name;
    }

    return $driver->save();
}

while (($row = fgetcsv($file)) !== FALSE) {
    if (count($row) < 4) continue;

    $setA = trim($row[1]);
    $plate = trim($row[2]);
    $setB = trim($row[3]);

    if (empty($plate)) continue;

    $unit = Unit::where('plate_number', $plate)->first();
    if ($unit) {
        if ($unit->driver_id) {
            if (parseAndSaveDriver($setA, $unit->driver_id)) $updatedCount++;
            else $skippedCount++;
        }
        if ($unit->secondary_driver_id) {
            if (parseAndSaveDriver($setB, $unit->secondary_driver_id)) $updatedCount++;
            else $skippedCount++;
        }
    } else {
        $skippedCount++;
    }
}

fclose($file);

echo "Restoration Complete:\n";
echo "Updated: $updatedCount driver records\n";
echo "Skipped: $skippedCount records\n";

// Audit one record
$d = Driver::whereNotNull('first_name')->first();
if ($d) {
    echo "Sample Restored: ID " . $d->id . " - " . $d->first_name . " " . $d->last_name . "\n";
}
