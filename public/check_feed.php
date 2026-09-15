<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check the driver_behavior columns for driver_id=11 (Joel)
$columns = DB::select("SHOW COLUMNS FROM driver_behavior");
$debts = DB::table('driver_behavior')
    ->where('driver_id', 11)
    ->get();

echo json_encode([
    'columns' => $columns,
    'joel_debts' => $debts,
], JSON_PRETTY_PRINT | JSON_PARTIAL_OUTPUT_ON_ERROR);
