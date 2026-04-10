<?php
use App\Models\User;
use App\Models\Driver;

$trashedDrivers = User::onlyTrashed()->where('role', 'driver')->get();
$foundInDriversTable = 0;
$missingInDriversTable = [];

foreach ($trashedDrivers as $ud) {
    $exists = Driver::where('first_name', $ud->first_name)
                    ->where('last_name', $ud->last_name)
                    ->exists();
    if ($exists) {
        $foundInDriversTable++;
    } else {
        $missingInDriversTable[] = $ud->first_name . ' ' . $ud->last_name . ' (' . $ud->username . ')';
    }
}

echo "Drivers in Users table (trashed): " . $trashedDrivers->count() . "\n";
echo "Already in Drivers table: $foundInDriversTable\n";
echo "Missing in Drivers table: " . count($missingInDriversTable) . "\n";

if (!empty($missingInDriversTable)) {
    echo "\nMissing drivers to be migrated:\n";
    foreach ($missingInDriversTable as $m) { echo "- $m\n"; }
}
