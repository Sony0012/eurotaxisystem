<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tables = DB::select('SHOW TABLES');
$dbName = config('database.connections.mysql.database');
$tableKey = "Tables_in_$dbName";

$allTables = [];
foreach ($tables as $table) {
    $allTables[] = $table->$tableKey;
}

$user = DB::table('users')->where('name', 'like', '%Sunico%')->orWhere('full_name', 'like', '%Sunico%')->first();
$staff = DB::table('staff')->where('name', 'like', '%Sunico%')->first();

file_put_contents('probe_results.json', json_encode([
    'tables' => $allTables,
    'user' => $user,
    'staff' => $staff
], JSON_PRETTY_PRINT));
echo "Done saving to probe_results.json\n";
