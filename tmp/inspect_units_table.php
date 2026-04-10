<?php
use Illuminate\Support\Facades\DB;

$createTable = DB::select("SHOW CREATE TABLE units")[0]->{'Create Table'};
file_put_contents('tmp/units_create_log.txt', $createTable);
echo "Units table create statement written to tmp/units_create_log.txt\n";
