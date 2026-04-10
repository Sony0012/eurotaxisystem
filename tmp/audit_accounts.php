<?php
use Illuminate\Support\Facades\DB;
use App\Models\User;

$tables = DB::select('SHOW TABLES');
echo "TABLES:\n";
foreach ($tables as $table) {
    foreach ($table as $key => $value) { echo "- $value\n"; }
}

echo "\nUSERS:\n";
foreach (User::all() as $u) {
    echo $u->id . ': ' . $u->first_name . ' ' . $u->last_name . ' (' . $u->username . ') - ' . $u->role . "\n";
}
