<?php
use Illuminate\Support\Facades\Schema;
$cols = Schema::getColumnListing('users');
file_put_contents('C:\\xampp\\htdocs\\eurotaxisystem\\tmp\\user_columns_audit.txt', implode(', ', $cols));
echo "Columns saved.\n";
