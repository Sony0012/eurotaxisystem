<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$expenses = DB::table('expenses')->orderBy('id', 'desc')->take(5)->get();
$driverName = 'Joel Sumando'; // Hardcode just to see if there's an issue
$payments = DB::table('expenses')
    ->where('category', 'Damage Recovery')
    ->where('description', 'like', "%{$driverName}%")
    ->where('amount', '<', 0)
    ->get();
    
echo json_encode([
    'expenses' => $expenses,
    'payments' => $payments,
    'drivers' => DB::table('drivers')->where('id', 11)->orWhere('id', 124)->select('id', 'first_name', 'last_name')->get()
], JSON_PRETTY_PRINT | JSON_PARTIAL_OUTPUT_ON_ERROR);
