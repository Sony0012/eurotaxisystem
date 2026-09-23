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
$key   = $session['key'];
$sn    = $session['sn'];
$model = $session['model'];

echo "SN={$sn} DeviceID={$devID} Model={$model}\n\n";

function getStatus($base, $devID, $key) {
    $r = \Illuminate\Support\Facades\Http::asForm()->timeout(10)->post($base . '/GetDeviceSetInfo', [
        'DeviceID' => $devID, 'Key' => $key
    ]);
    $raw = preg_replace('/<[^>]+>/', '', $r->body());
    $d = json_decode($raw, true);
    echo "  808SKLY=" . ($d['808SKLY'] ?? 'N/A') . " state=" . ($d['state'] ?? 'N/A') . "\n";
    return $d;
}

echo "=== INITIAL STATE ===\n";
getStatus($base, $devID, $key);
echo "\n";

// ============================================================
// TRY 1: UpdateDeviceSetInfo with 808SKLY=1 (KILL)
// The GetDeviceSetInfo showed 808SKLY="0" — try setting it to "1"
// ============================================================
echo "--- Test 1: UpdateDeviceSetInfo 808SKLY=1 (kill) ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/UpdateDeviceSetInfo', [
    'DeviceID' => $devID,
    '808SKLY'  => '1',
    'Key'      => $key
]);
echo "Response: " . $r->body() . "\n";
sleep(1);
getStatus($base, $devID, $key);
echo "\n";

// ============================================================
// TRY 2: UpdateStatusByAPP with Bat parameter
// ============================================================
echo "--- Test 2: UpdateStatusByAPP with Bat param ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/UpdateStatusByAPP', [
    'DeviceID' => $devID,
    'Status'   => '1',
    'Bat'      => '0',
    'Key'      => $key
]);
echo "Response: " . $r->body() . "\n\n";

// ============================================================
// TRY 3: UpdateCommandByAPP with 808SKLY=1 as CommandType
// ============================================================
echo "--- Test 3: UpdateCommandByAPP CommandType=808SKLY Paramter=1 ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/UpdateCommandByAPP', [
    'DeviceID'    => $devID,
    'CommandType' => '808SKLY',
    'Paramter'    => '1',
    'Key'         => $key
]);
echo "Response: " . $r->body() . "\n";
sleep(1);
getStatus($base, $devID, $key);
echo "\n";

// ============================================================
// TRY 4: SendCommandByAPP with 808SKLY
// ============================================================
echo "--- Test 4: SendCommandByAPP CommandType=808SKLY Paramter=1 ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/SendCommandByAPP', [
    'SN'          => $sn,
    'DeviceID'    => $devID,
    'CommandType' => '808SKLY',
    'Model'       => $model,
    'Paramter'    => '1',
    'Key'         => $key
]);
echo "Response: " . $r->body() . "\n";
sleep(1);
getStatus($base, $devID, $key);
echo "\n";

// ============================================================
// TRY 5: GetCommandList with PageNo
// ============================================================
echo "--- Test 5: GetCommandList with PageNo ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/GetCommandList', [
    'SN'       => $sn,
    'DeviceID' => $devID,
    'PageNo'   => '1',
    'Key'      => $key
]);
echo $r->body() . "\n\n";

// ============================================================
// TRY 6: UpdateCommandByAPP with DGXC (断供/断车 in Chinese) 
// DGXC = 808DGXC - cut fuel, common Aika command
// ============================================================
echo "--- Test 6: UpdateCommandByAPP 808DGXC Paramter=0 ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/UpdateCommandByAPP', [
    'DeviceID'    => $devID,
    'CommandType' => '808DGXC',
    'Paramter'    => '0',
    'Key'         => $key
]);
echo "Response: " . $r->body() . "\n";
sleep(1);
getStatus($base, $devID, $key);
echo "\n";

// RESET - send restore command  
echo "--- RESETTING with KY ---\n";
Http::asForm()->timeout(10)->post($base . '/UpdateCommandByAPP', [
    'DeviceID' => $devID, 'CommandType' => 'KY', 'Paramter' => '', 'Key' => $key
]);
sleep(1);
getStatus($base, $devID, $key);

echo "\n=== DONE ===\n";
