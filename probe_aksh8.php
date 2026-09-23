<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Http;

$aksh = app(\App\Services\AkshGpsService::class);
$unit = \Illuminate\Support\Facades\DB::table('units')
    ->where('gps_provider', 'aksh')
    ->whereNotNull('imei')
    ->first();

$imei = $unit->imei;
$session = $aksh->getSession($imei, $unit->gps_password, true);
$base  = $session['api_address'];
$devID = $session['device_id'];
$sn    = $session['sn'];
$model = $session['model'];
$key   = $session['key'];

echo "=== TEST SendCommandByAPP (Immediate Push) with 808DYD ===\n";
$r = Http::asForm()->timeout(10)->post($base . '/SendCommandByAPP', [
    'SN'          => $sn,
    'DeviceID'    => $devID,
    'CommandType' => '808DYD',
    'Model'       => $model,
    'Paramter'    => '',
    'Key'         => $key
]);
echo "Response: " . trim(preg_replace('/<[^>]+>/', '', $r->body())) . "\n";

echo "=== TEST SendCommandByAPP (Immediate Push) with 808HFYD ===\n";
$r2 = Http::asForm()->timeout(10)->post($base . '/SendCommandByAPP', [
    'SN'          => $sn,
    'DeviceID'    => $devID,
    'CommandType' => '808HFYD',
    'Model'       => $model,
    'Paramter'    => '',
    'Key'         => $key
]);
echo "Response: " . trim(preg_replace('/<[^>]+>/', '', $r2->body())) . "\n";
