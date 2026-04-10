<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$tables = ['units', 'drivers'];
$output = '';

foreach ($tables as $table) {
    $output .= "Table: $table\n";
    $columns = DB::select("DESCRIBE $table");
    foreach ($columns as $column) {
        $output .= "  {$column->Field} ({$column->Type}) - Null: {$column->Null}, Default: {$column->Default}\n";
    }
    $output .= "\n";
}

file_put_contents('tmp/schema_dump.txt', $output);
echo "Schema dumped to tmp/schema_dump.txt\n";
