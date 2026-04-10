<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$deleted = DB::table('users')->where('role', 'driver')->delete();
echo "Successfully deleted $deleted driver records from the users table.\n";

$remaining = DB::table('users')->count();
echo "Total users remaining: $remaining\n";
