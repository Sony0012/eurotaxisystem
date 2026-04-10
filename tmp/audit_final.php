<?php

use App\Models\User;
use App\Models\Driver;

$trashedDrivers = User::onlyTrashed()->where('role', 'driver')->get();
$foundInDriversTable = 0;
$missingInDriversTable = [];

foreach ($trashedDrivers as $ud) {
    if (Driver::where('first_name', $ud->first_name)->where('last_name', $ud->last_name)->exists()) {
        $foundInDriversTable++;
    } else {
        $missingInDriversTable[] = $ud->first_name . ' ' . $ud->last_name . ' (' . $ud->username . ')';
    }
}

$output = "Total Trashed Drivers: " . $trashedDrivers->count() . "\n";
$output .= "Found in Drivers Table: $foundInDriversTable\n";
$output .= "Missing in Drivers Table: " . count($missingInDriversTable) . "\n\n";

if (!empty($missingInDriversTable)) {
    $output .= "Missing Details:\n- " . implode("\n- ", $missingInDriversTable);
}

file_put_contents('C:\\xampp\\htdocs\\eurotaxisystem\\tmp\\audit_final.txt', $output);
echo "Done.\n";
