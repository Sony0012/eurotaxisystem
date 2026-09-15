<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$alerts = DB::table('system_alerts')->where('title', 'like', '%Payment%')->orderBy('id', 'desc')->take(5)->get();
echo json_encode($alerts, JSON_PRETTY_PRINT | JSON_PARTIAL_OUTPUT_ON_ERROR);
