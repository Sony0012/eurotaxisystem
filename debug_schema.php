<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = ['units', 'maintenance', 'drivers', 'users'];
foreach ($tables as $table) {
    echo "\n--- Table: $table ---\n";
    $columns = DB::select("DESCRIBE $table");
    foreach ($columns as $column) {
        printf("%-20s %-20s %-10s %-10s %-10s\n", 
            $column->Field, 
            $column->Type, 
            $column->Null, 
            $column->Key, 
            $column->Default
        );
    }
}
