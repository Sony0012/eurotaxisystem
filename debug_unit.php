<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$unit = Illuminate\Support\Facades\DB::table('units')->where('plate_number', 'AAK 9196')->first();
echo "Unit AAK 9196:\n";
echo "Primary Driver ID: " . $unit->driver_id . "\n";
echo "Secondary Driver ID: " . $unit->secondary_driver_id . "\n";

$d1 = Illuminate\Support\Facades\DB::table('drivers')->where('id', $unit->driver_id)->first();
$d2 = Illuminate\Support\Facades\DB::table('drivers')->where('id', $unit->secondary_driver_id)->first();

echo "Primary: " . ($d1 ? $d1->first_name . ' ' . $d1->last_name . ' (Status: ' . $d1->driver_status . ')' : 'NULL') . "\n";
echo "Secondary: " . ($d2 ? $d2->first_name . ' ' . $d2->last_name . ' (Status: ' . $d2->driver_status . ')' : 'NULL') . "\n";
