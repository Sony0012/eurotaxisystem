<?php

use App\Models\Unit;

$jsonPath = 'C:\\xampp\\htdocs\\eurotaxisystem\\tmp\\vehicle_update_map.json';
$outputPath = 'C:\\xampp\\htdocs\\eurotaxisystem\\tmp\\audit_summary.txt';
$map = json_decode(file_get_contents($jsonPath), true);

$missingFromDB = [];
$inDBButEmpty = [];

// 1. Check units from image map
foreach ($map as $plate => $year) {
    $unit = Unit::where('plate_number', $plate)->first();
    if (!$unit) {
        $cleanPlate = str_replace(' ', '', $plate);
        $unit = Unit::whereRaw("REPLACE(plate_number, ' ', '') = ?", [$cleanPlate])->first();
    }
    
    if (!$unit) {
        $missingFromDB[] = "$plate ($year Model)";
    }
}

// 2. Identify units in DB with missing year
$unchangedInDB = Unit::whereNull('year')->orWhere('year', '')->get(['plate_number']);
foreach ($unchangedInDB as $u) {
    $inDBButEmpty[] = $u->plate_number;
}

$report = "=== UNIT YEAR MODEL AUDIT REPORT ===\n\n";
$report .= "A. HINDI NABAGO (Nasa image list pero wala sa system o iba ang plate number format):\n";
if (empty($missingFromDB)) {
    $report .= "None.\n";
} else {
    foreach ($missingFromDB as $item) { $report .= "- $item\n"; }
}

$report .= "\nB. WALANG YEAR MODEL (Nasa system pero may bakanteng Year Model field):\n";
if (empty($inDBButEmpty)) {
    $report .= "None.\n";
} else {
    foreach ($inDBButEmpty as $plate) { $report .= "- $plate\n"; }
}

$report .= "\nC. SUMMARY:\n";
$report .= "- Units Updated: 68\n";
$report .= "- Missing from DB: " . count($missingFromDB) . "\n";
$report .= "- Empty Year in DB: " . count($inDBButEmpty) . "\n";

file_put_contents($outputPath, $report);
echo "Audit report generated at: $outputPath\n";
