<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$units = Illuminate\Support\Facades\DB::table('units')->whereNotNull('driver_id')->whereNotNull('secondary_driver_id')->get();
echo "Units with 2 drivers:\n";
foreach ($units as $u) {
    echo "Unit ID: {$u->id}, Driver1: {$u->driver_id}, Driver2: {$u->secondary_driver_id}\n";
    $d1 = Illuminate\Support\Facades\DB::table('users as u')->leftJoin('drivers as d', 'u.id', '=', 'd.user_id')->where('u.id', $u->driver_id)->first();
    $d2 = Illuminate\Support\Facades\DB::table('users as u')->leftJoin('drivers as d', 'u.id', '=', 'd.user_id')->where('u.id', $u->secondary_driver_id)->first();
    echo "  Driver1 User: " . ($d1 ? "FOUND (ID: {$d1->id}, Driver Table UserID: {$d1->user_id})" : "NOT FOUND") . "\n";
    echo "  Driver2 User: " . ($d2 ? "FOUND (ID: {$d2->id}, Driver Table UserID: {$d2->user_id})" : "NOT FOUND") . "\n";
}
