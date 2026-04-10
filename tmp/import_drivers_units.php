<?php

use App\Models\Driver;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;

// Use absolute path for the CSV
$csvPath = 'C:\Users\bertl\OneDrive\Desktop\Taxi taxi driver (1).csv';

if (!file_exists($csvPath)) {
    die("CSV file not found at: $csvPath\n");
}

function parseName($name) {
    if (empty($name) || in_array(strtoupper(trim($name)), ['NAD', 'NAFTM', 'NATFM'])) {
        return null;
    }

    $name = trim($name, '" ');
    
    // Handle "Last, First"
    if (strpos($name, ',') !== false) {
        $parts = explode(',', $name);
        return [
            'last' => trim($parts[0]),
            'first' => trim($parts[1])
        ];
    }
    
    // Handle "Salazar. Angel" or "Last First"
    if (strpos($name, '.') !== false) {
        $parts = explode('.', $name);
        return [
            'last' => trim($parts[0]),
            'first' => trim($parts[1])
        ];
    }

    // Default: Split by space (last word is last name or first word is first name)
    // Most names in the list are "Last, First" or "Last. First" or "Last First"
    // If it's just "Name Name", assume "Last First" or "First Last"?
    // User examples: "Laurente, R", "Romera, Ricky"
    $parts = explode(' ', $name);
    if (count($parts) >= 2) {
        return [
            'last' => trim($parts[0]),
            'first' => trim(implode(' ', array_slice($parts, 1)))
        ];
    }

    return [
        'last' => $name,
        'first' => ''
    ];
}

$file = fopen($csvPath, 'r');
$header = fgetcsv($file); // Skip header

$unitsCreated = 0;
$driversCreated = 0;

while (($row = fgetcsv($file)) !== false) {
    if (empty($row[2])) continue; // Skip empty rows

    $setA = trim($row[1] ?? '');
    $plate = trim($row[2] ?? '');
    $setB = trim($row[3] ?? '');
    $remarks = trim($row[5] ?? '');

    $status = 'active';
    if (strtoupper($setA) === 'NAD' || strtoupper($setB) === 'NAD') {
        $status = 'vacant';
    } elseif (strtoupper($setA) === 'NAFTM' || strtoupper($setB) === 'NAFTM' || strtoupper($setA) === 'NATFM' || strtoupper($setB) === 'NATFM') {
        $status = 'maintenance';
    }

    echo "Processing Unit: $plate (Status: $status)\n";

    $driver1_id = null;
    $driver2_id = null;

    $pA = parseName($setA);
    $pB = parseName($setB);

    if ($pA) {
        $d1 = Driver::firstOrCreate(
            ['first_name' => $pA['first'], 'last_name' => $pA['last']],
            ['license_number' => 'TBD-' . strtoupper(substr(md5($setA), 0, 8)), 'is_active' => true]
        );
        $driver1_id = $d1->id;
        if ($d1->wasRecentlyCreated) $driversCreated++;
    }

    if ($pB && $setA !== $setB) {
        $d2 = Driver::firstOrCreate(
            ['first_name' => $pB['first'], 'last_name' => $pB['last']],
            ['license_number' => 'TBD-' . strtoupper(substr(md5($setB), 0, 8)), 'is_active' => true]
        );
        $driver2_id = $d2->id;
        if ($d2->wasRecentlyCreated) $driversCreated++;
    }

    Unit::create([
        'plate_number' => $plate,
        'status' => $status,
        'driver_id' => $driver1_id,
        'secondary_driver_id' => $driver2_id,
        'make' => 'Toyota', // Assume default or from remarks?
        'model' => 'Vios',  // Assume default
        'year' => 2023,     // Assume default
    ]);
    $unitsCreated++;
}

fclose($file);

echo "Import Summary:\n";
echo "- Units Created: $unitsCreated\n";
echo "- Drivers Created: $driversCreated\n";
