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

// Fix: GetDeviceSetInfo returns malformed JSON - use regex to extract fields
function getSetInfo($base, $devID, $key) {
    $r = \Illuminate\Support\Facades\Http::asForm()->timeout(10)->post($base . '/GetDeviceSetInfo', [
        'DeviceID' => $devID, 'Key' => $key
    ]);
    $raw = preg_replace('/<[^>]+>/', '', $r->body());
    echo "  RAW SetInfo: " . trim($raw) . "\n";
    // Extract 808SKLY using regex since JSON might be malformed
    preg_match('/"808SKLY"\s*:\s*"([^"]*)"/', $raw, $m);
    $skly = $m[1] ?? 'NOT_FOUND';
    echo "  808SKLY={$skly}\n";
    return $skly;
}

echo "=== INITIAL STATE ===\n";
$initial = getSetInfo($base, $devID, $key);
echo "\n";

// ============================================================
// KEY TEST: Does UpdateCommandByAPP 808SKLY=1 change the relay?
// ============================================================
echo "--- Sending UpdateCommandByAPP 808SKLY Paramter=1 (KILL) ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/UpdateCommandByAPP', [
    'DeviceID'    => $devID,
    'CommandType' => '808SKLY',
    'Paramter'    => '1',
    'Key'         => $key
]);
echo "Response: " . trim(preg_replace('/<[^>]+>/', '', $r->body())) . "\n";
sleep(2);
$after = getSetInfo($base, $devID, $key);

if ($after === '1') {
    echo "\n*** SUCCESS: 808SKLY changed to 1 - THIS IS THE KILL COMMAND! ***\n";
} elseif ($after === '0') {
    echo "\n*** UNCHANGED: 808SKLY is still 0 - command was NOT applied ***\n";
}
echo "\n";

// ============================================================
// Test 808DGXC (断供/断车) with Paramter=1 = KILL
// ============================================================
echo "--- Sending 808DGXC Paramter=1 (KILL) ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/UpdateCommandByAPP', [
    'DeviceID'    => $devID,
    'CommandType' => '808DGXC',
    'Paramter'    => '1',
    'Key'         => $key
]);
echo "Response: " . trim(preg_replace('/<[^>]+>/', '', $r->body())) . "\n";
sleep(2);
getSetInfo($base, $devID, $key);
echo "\n";

// ============================================================
// GetCommandList with all required params
// ============================================================
echo "--- GetCommandList with PageNo + PageCount ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/GetCommandList', [
    'SN'        => $sn,
    'DeviceID'  => $devID,
    'PageNo'    => '1',
    'PageCount' => '20',
    'Key'       => $key
]);
echo $r->body() . "\n\n";

// ============================================================
// UpdateStatusByAPP with UTC parameter  
// ============================================================
echo "--- UpdateStatusByAPP with UTC param ---\n";
$utc = gmdate('Y-m-d H:i:s');
$r = Http::asForm()->timeout(10)->post($base . '/UpdateStatusByAPP', [
    'DeviceID' => $devID,
    'Status'   => '1',
    'Bat'      => '100',
    'UTC'      => $utc,
    'Key'      => $key
]);
echo "Response: " . $r->body() . "\n\n";

// ============================================================
// RESTORE: set 808SKLY back to 0
// ============================================================
echo "--- RESTORE: UpdateCommandByAPP 808SKLY Paramter=0 ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/UpdateCommandByAPP', [
    'DeviceID'    => $devID,
    'CommandType' => '808SKLY',
    'Paramter'    => '0',
    'Key'         => $key
]);
echo "Response: " . trim(preg_replace('/<[^>]+>/', '', $r->body())) . "\n";
sleep(2);
getSetInfo($base, $devID, $key);

echo "\n=== DONE ===\n";
