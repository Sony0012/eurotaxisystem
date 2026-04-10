<?php
use Illuminate\Support\Facades\Schema;
$cols = Schema::getColumnListing('drivers');
file_put_contents('C:\\xampp\\htdocs\\eurotaxisystem\\tmp\\driver_columns.txt', implode(', ', $cols));
echo "Columns saved.\n";
