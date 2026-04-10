<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;
$indexes = DB::select('SHOW INDEX FROM units');
foreach ($indexes as $index) {
    echo "TABLE: {$index->Table} | NON_UNIQUE: {$index->Non_unique} | KEY_NAME: {$index->Key_name} | COLUMN: {$index->Column_name}\n";
}
