<?php
use Illuminate\Support\Facades\DB;
$count = DB::table('users')->where('role', 'driver')->delete();
echo "Deleted $count driver accounts from users table.\n";
$remaining = DB::table('users')->count();
echo "Total users remaining: $remaining\n";
