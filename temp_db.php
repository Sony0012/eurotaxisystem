<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$u = DB::table('units')->where('plate_number', 'NEF 4940')->first();
echo json_encode($u, JSON_PRETTY_PRINT);
