<?php
use App\Models\User;
use Illuminate\Support\Facades\DB;

$deleted = DB::table('users')->where('role', 'driver')->delete();
echo "DeletedCount: " . $deleted . "\n";
echo "RemainingCount: " . DB::table('users')->count() . "\n";
