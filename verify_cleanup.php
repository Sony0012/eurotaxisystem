<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tablesToCheck = [
    'boundaries', 'boundary_rules', 'drivers', 'units', 'maintenance', 'users', 'staff'
];

$results = [];
foreach ($tablesToCheck as $table) {
    if (Schema::hasTable($table)) {
        $results[$table] = DB::table($table)->count();
    } else {
        $results[$table] = 'MISSING';
    }
}

$user = DB::table('users')->where('id', 18)->first();

echo json_encode([
    'counts' => $results,
    'user_exists' => $user ? true : false,
    'user_name' => $user ? $user->full_name : 'NOT FOUND'
], JSON_PRETTY_PRINT);
