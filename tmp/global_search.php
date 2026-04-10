<?php
use Illuminate\Support\Facades\DB;
$tables = DB::select('SHOW TABLES');
$found = [];
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    $columns = DB::getSchemaBuilder()->getColumnListing($tableName);
    foreach ($columns as $column) {
        try {
            $count = DB::table($tableName)->where($column, 'LIKE', '%Sunico%')->count();
            if ($count > 0) {
                $found[] = "Table: $tableName, Column: $column, Count: $count";
            }
        } catch (\Exception $e) {
            // Skip binary/blob columns if they cause errors
        }
    }
}
echo implode("\n", $found);
