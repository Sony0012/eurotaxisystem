<?php
use Illuminate\Support\Facades\DB;
$tables = DB::select('SHOW TABLES');
foreach ($tables as $table) {
    echo array_values((array)$table)[0] . "\n";
}
