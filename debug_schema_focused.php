<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;
$columns = DB::select('DESCRIBE maintenance');
foreach ($columns as $column) {
    echo "COL: {$column->Field} | TYPE: {$column->Type} | NULL: {$column->Null}\n";
}
