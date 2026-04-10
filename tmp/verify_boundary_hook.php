<?php
use App\Models\Unit;
use App\Models\BoundaryRule;

echo "Verification Start...\n";

$plate = 'VAA 9864';
$unit = Unit::where('plate_number', $plate)->first();
if (!$unit) {
    echo "Unit $plate not found!\n";
    exit;
}

$originalYear = $unit->year;
$originalRate = $unit->boundary_rate;
echo "Unit: $plate | Year: $originalYear | Current Rate: $originalRate\n";

echo "Changing Year to 2014...\n";
$unit->year = 2014;
$unit->save();

$unit->refresh();
echo "Updated Year: {$unit->year} | New Rate: {$unit->boundary_rate}\n";

if ($unit->boundary_rate == 1100) {
    echo "SUCCESS: Rate automatically updated to 1100 based on Legacy Rule.\n";
} else {
    echo "FAILURE: Rate did not update correctly.\n";
}

// Restore
echo "Restoring Year to $originalYear...\n";
$unit->year = $originalYear;
$unit->save();
$unit->refresh();
echo "Restored Year: {$unit->year} | Restored Rate: {$unit->boundary_rate}\n";
