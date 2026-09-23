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
$base = $session['api_address'];
$devID = $session['device_id'];
$key   = $session['key'];
$sn    = $session['sn'];
$model = $session['model'];

echo "SN={$sn} DeviceID={$devID} Model={$model}\n\n";

// ============================================================
// STEP 1: Get sendCommand state BEFORE sending anything
// ============================================================
$r = Http::asForm()->timeout(10)->post($base . '/GetDeviceStatus2025', [
    'DeviceID'  => $devID,
    'TimeZones' => '8:00',
    'Language'  => 'en',
    'FilterWarn'=> '',
    'Key'       => $key
]);
$before = json_decode(preg_replace('/<[^>]+>/', '', $r->body()), true);
echo "BEFORE sendCommand: " . ($before['sendCommand'] ?? 'N/A') . "\n";
echo "BEFORE state: " . ($before['state'] ?? 'N/A') . "\n\n";

// ============================================================
// STEP 2: Try GetCommandList with SN + DeviceID
// ============================================================
echo "--- GetCommandList with SN + DeviceID ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/GetCommandList', [
    'SN'       => $sn,
    'DeviceID' => $devID,
    'Key'      => $key
]);
echo $r->body() . "\n\n";

// ============================================================
// STEP 3: GetDeviceSetInfo - shows what commands device has
// ============================================================
echo "--- GetDeviceSetInfo ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/GetDeviceSetInfo', [
    'DeviceID' => $devID,
    'Key'      => $key
]);
echo $r->body() . "\n\n";

// ============================================================
// STEP 4: GetDeviceDetail2025 with TimeZones param
// ============================================================
echo "--- GetDeviceDetail2025 ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/GetDeviceDetail2025', [
    'DeviceID'  => $devID,
    'TimeZones' => '8:00',
    'Key'       => $key
]);
echo $r->body() . "\n\n";

// ============================================================
// STEP 5: Send DY command and check sendCommand state change
// ============================================================
echo "--- Sending DY via UpdateCommandByAPP ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/UpdateCommandByAPP', [
    'DeviceID'    => $devID,
    'CommandType' => 'DY',
    'Paramter'    => '',
    'Key'         => $key
]);
echo "DY Response: " . $r->body() . "\n";

sleep(2); // wait 2 seconds

// Check if sendCommand changed
$r2 = Http::asForm()->timeout(10)->post($base . '/GetDeviceStatus2025', [
    'DeviceID'  => $devID,
    'TimeZones' => '8:00',
    'Language'  => 'en',
    'FilterWarn'=> '',
    'Key'       => $key
]);
$after = json_decode(preg_replace('/<[^>]+>/', '', $r2->body()), true);
echo "AFTER DY sendCommand: " . ($after['sendCommand'] ?? 'N/A') . "\n";
echo "AFTER DY state: " . ($after['state'] ?? 'N/A') . "\n\n";

// ============================================================
// STEP 6: Reset by sending KY (restore)
// ============================================================
echo "--- Sending KY via UpdateCommandByAPP (RESTORE) ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/UpdateCommandByAPP', [
    'DeviceID'    => $devID,
    'CommandType' => 'KY',
    'Paramter'    => '',
    'Key'         => $key
]);
echo "KY Response: " . $r->body() . "\n\n";

// ============================================================
// STEP 7: Try UpdateStatusByAPP - direct relay control
// ============================================================
echo "--- UpdateStatusByAPP relay=1 (kill) ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/UpdateStatusByAPP', [
    'DeviceID' => $devID,
    'Status'   => '1',
    'Key'      => $key
]);
echo $r->body() . "\n\n";

// ============================================================
// STEP 8: Try SetDeviceRelay direct
// ============================================================
echo "--- SetDeviceRelay ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/SetDeviceRelay', [
    'DeviceID' => $devID,
    'Relay'    => '1',
    'Key'      => $key
]);
echo $r->body() . "\n\n";

// ============================================================
// STEP 9: UpdateCommandByAPP with 0x64 - check sendCommand
// ============================================================
echo "--- Sending 0x64 via UpdateCommandByAPP ---\n";
$r = Http::asForm()->timeout(10)->post($base . '/UpdateCommandByAPP', [
    'DeviceID'    => $devID,
    'CommandType' => '0x64',
    'Paramter'    => '',
    'Key'         => $key
]);
echo "0x64 Response: " . $r->body() . "\n";
sleep(2);

$r3 = Http::asForm()->timeout(10)->post($base . '/GetDeviceStatus2025', [
    'DeviceID'  => $devID,
    'TimeZones' => '8:00',
    'Language'  => 'en',
    'FilterWarn'=> '',
    'Key'       => $key
]);
$after2 = json_decode(preg_replace('/<[^>]+>/', '', $r3->body()), true);
echo "AFTER 0x64 sendCommand: " . ($after2['sendCommand'] ?? 'N/A') . "\n";
echo "AFTER 0x64 state: " . ($after2['state'] ?? 'N/A') . "\n\n";

// RESET again
Http::asForm()->timeout(10)->post($base . '/UpdateCommandByAPP', [
    'DeviceID'    => $devID, 'CommandType' => 'KY', 'Paramter' => '', 'Key' => $key
]);

echo "=== DONE ===\n";
