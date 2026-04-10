<?php
use Illuminate\Support\Facades\DB;

$mUnits = DB::table('units')->where('status', 'maintenance')->get();
echo "Maintenance Units: " . count($mUnits) . "\n";
foreach ($mUnits as $u) {
    echo "Plate: {$u->plate_number}, Driver1: " . ($u->driver_id ?: 'NONE') . ", Driver2: " . ($u->secondary_driver_id ?: 'NONE') . "\n";
}

$vUnits = DB::table('units')->where('status', 'vacant')->get();
echo "\nVacant Units: " . count($vUnits) . "\n";
foreach ($vUnits as $u) {
    echo "Plate: {$u->plate_number}, Driver1: " . ($u->driver_id ?: 'NONE') . ", Driver2: " . ($u->secondary_driver_id ?: 'NONE') . "\n";
}
