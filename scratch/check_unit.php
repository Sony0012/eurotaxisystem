<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$unit = DB::table('units')->where('plate_number', 'AAK 4591')->first();
print_r([
    'motor' => $unit->motor_no,
    'chassis' => $unit->chassis_no,
    'purchase_date' => $unit->purchase_date
]);
