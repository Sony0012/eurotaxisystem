<?php

use App\Models\Unit;

$jsonPath = 'C:\\xampp\\htdocs\\eurotaxisystem\\tmp\\vehicle_update_map.json';
$map = json_decode(file_get_contents($jsonPath), true);
$imagePlates = array_keys($map);

// Get all plates in DB
$allDbPlates = Unit::pluck('plate_number')->toArray();

$notInImages = [];

foreach ($allDbPlates as $dbPlate) {
    $found = false;
    foreach ($imagePlates as $imgPlate) {
        if (str_replace(' ', '', strtoupper($dbPlate)) === str_replace(' ', '', strtoupper($imgPlate))) {
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $notInImages[] = $dbPlate;
    }
}

echo "=== 17 UNITS NOT IN IMAGES ===\n\n";
foreach ($notInImages as $p) {
    echo "- $p\n";
}
echo "\nTotal Count: " . count($notInImages) . "\n";
