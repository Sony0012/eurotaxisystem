<?php

use App\Models\Unit;
use Illuminate\Support\Facades\DB;

$jsonPath = 'C:\\xampp\\htdocs\\eurotaxisystem\\tmp\\vehicle_update_map.json';
$map = json_decode(file_get_contents($jsonPath), true);

$updated = 0;
$notFound = [];

foreach ($map as $plate => $year) {
    // Search for unit by plate number (case insensitive and space insensitive if needed)
    $unit = Unit::where('plate_number', $plate)->first();
    
    if (!$unit) {
        // Try without spaces
        $cleanPlate = str_replace(' ', '', $plate);
        $unit = Unit::whereRaw("REPLACE(plate_number, ' ', '') = ?", [$cleanPlate])->first();
    }

    if ($unit) {
        $unit->year = $year;
        $unit->save();
        $updated++;
    } else {
        $notFound[] = $plate;
    }
}

echo "Successfully updated: $updated units.\n";
if (!empty($notFound)) {
    echo "Units not found in database: " . implode(', ', $notFound) . "\n";
}
