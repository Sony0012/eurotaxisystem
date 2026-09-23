<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$driver = App\Models\Driver::where('first_name', 'Almar')->first();
if ($driver) {
    echo "Driver ID: " . $driver->id . PHP_EOL;
    $bounds = App\Models\Boundary::where('driver_id', $driver->id)->get();
    foreach($bounds as $b) {
        echo $b->date . ': ' . $b->actual_boundary . PHP_EOL;
    }
    echo "Count: " . $bounds->count() . PHP_EOL;
    echo "Sum: " . $bounds->sum('actual_boundary') . PHP_EOL;
    echo "Avg: " . $bounds->avg('actual_boundary') . PHP_EOL;
} else {
    echo "Not found";
}
