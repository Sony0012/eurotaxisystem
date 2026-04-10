<?php
use Illuminate\Support\Facades\Schema;
$cols = Schema::getColumnListing('units');
file_put_contents('C:\\xampp\\htdocs\\eurotaxisystem\\tmp\\unit_columns.txt', implode(', ', $cols));
echo "Columns saved.\n";
