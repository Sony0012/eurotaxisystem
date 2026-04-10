<?php
use Illuminate\Support\Facades\DB;

$missingUnits = [
    [
        'plate_number' => 'NAN 1349',
        'year' => 2020,
        'make' => 'Toyota', // Defaulting to Toyota as most units are
        'model' => 'Vios',  // Defaulting to Vios as most units are
        'status' => 'vacant',
    ],
    [
        'plate_number' => 'ABP 2705',
        'year' => 2015,
        'make' => 'Toyota',
        'model' => 'Vios',
        'status' => 'vacant',
    ]
];

foreach ($missingUnits as $unit) {
    DB::table('units')->insert([
        'plate_number' => $unit['plate_number'],
        'year' => $unit['year'],
        'make' => $unit['make'],
        'model' => $unit['model'],
        'status' => $unit['status'],
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "Added missing unit: " . $unit['plate_number'] . " (Year: " . $unit['year'] . ")\n";
}
