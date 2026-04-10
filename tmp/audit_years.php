<?php

use App\Models\Unit;

$jsonPath = 'C:\\xampp\\htdocs\\eurotaxisystem\\tmp\\vehicle_update_map.json';
$map = json_decode(file_get_contents($jsonPath), true);

$missingFromDB = [];
$updatedInDB = 0;

// 1. Check units from image map
foreach ($map as $plate => $year) {
    $unit = Unit::where('plate_number', $plate)->first();
    if (!$unit) {
        $cleanPlate = str_replace(' ', '', $plate);
        $unit = Unit::whereRaw("REPLACE(plate_number, ' ', '') = ?", [$cleanPlate])->first();
    }
    
    if (!$unit) {
        $missingFromDB[$plate] = $year;
    }
}

// 2. Identify ALL units in DB that still have empty/null year
$unchangedInDB = Unit::whereNull('year')->orWhere('year', '')->get(['plate_number']);

echo "=== AUDIT REPORT ===\n\n";
echo "1. PLATE NUMBERS FROM IMAGE LIST NOT FOUND IN SYSTEM (Hindi Nabago):\n";
foreach ($missingFromDB as $p => $y) {
    echo "- $p ($y Model)\n";
}

echo "\n2. EXISTING UNITS IN SYSTEM WITH MISSING YEAR MODEL (Still Null/Empty):\n";
foreach ($unchangedInDB as $u) {
    echo "- " . $u->plate_number . "\n";
}

echo "\n3. TOTAL UNITS UPDATED IN PREVIOUS STEP: 68\n";
