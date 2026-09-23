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

if (!$unit) {
    echo "No AKSH unit found.\n";
    exit;
}

$imei = $unit->imei;
echo "Testing IMEI: {$imei}\n";

$session = $aksh->getSession($imei, $unit->gps_password, true);
if (!$session) {
    echo "AKSH Login FAILED.\n";
    exit;
}

echo "Login OK. DeviceID={$session['device_id']} Model={$session['model']} SN={$session['sn']}\n\n";

$url = $session['api_address'] . '/UpdateCommandByAPP';
$urlSend = $session['api_address'] . '/SendCommandByAPP';

// ===========================================================
// Try ALL possible CommandType formats — DO NOT actually
// trigger relay in most tests; only test non-destructive ones.
// The ones marked [RELAY] WILL trigger the relay if device responds.
// ===========================================================
$tests = [
    // --- Using UpdateCommandByAPP ---
    ['ep' => $url, 'label' => 'UpdateCmd: 0x64 (hex kill)',         'payload' => ['DeviceID'=>$session['device_id'], 'CommandType'=>'0x64', 'Paramter'=>'',  'Key'=>$session['key']]],
    ['ep' => $url, 'label' => 'UpdateCmd: 100 (decimal kill)',       'payload' => ['DeviceID'=>$session['device_id'], 'CommandType'=>'100',  'Paramter'=>'',  'Key'=>$session['key']]],
    ['ep' => $url, 'label' => 'UpdateCmd: DY (legacy kill)',         'payload' => ['DeviceID'=>$session['device_id'], 'CommandType'=>'DY',   'Paramter'=>'',  'Key'=>$session['key']]],
    ['ep' => $url, 'label' => 'UpdateCmd: 808DGXC param=0',         'payload' => ['DeviceID'=>$session['device_id'], 'CommandType'=>'808DGXC','Paramter'=>'0','Key'=>$session['key']]],
    ['ep' => $url, 'label' => 'UpdateCmd: 808DGXC param=1 [RELAY]', 'payload' => ['DeviceID'=>$session['device_id'], 'CommandType'=>'808DGXC','Paramter'=>'1','Key'=>$session['key']]],
    ['ep' => $url, 'label' => 'UpdateCmd: RELAY param=1 [RELAY]',   'payload' => ['DeviceID'=>$session['device_id'], 'CommandType'=>'Relay', 'Paramter'=>'1', 'Key'=>$session['key']]],

    // --- Using SendCommandByAPP (has SN + Model extra fields) ---
    ['ep' => $urlSend, 'label' => 'SendCmd: 0x64 with Model',       'payload' => ['SN'=>$session['sn'], 'DeviceID'=>$session['device_id'], 'CommandType'=>'0x64', 'Model'=>$session['model'], 'Paramter'=>'', 'Key'=>$session['key']]],
    ['ep' => $urlSend, 'label' => 'SendCmd: 100 with Model',        'payload' => ['SN'=>$session['sn'], 'DeviceID'=>$session['device_id'], 'CommandType'=>'100',  'Model'=>$session['model'], 'Paramter'=>'', 'Key'=>$session['key']]],
    ['ep' => $urlSend, 'label' => 'SendCmd: DY with Model',         'payload' => ['SN'=>$session['sn'], 'DeviceID'=>$session['device_id'], 'CommandType'=>'DY',   'Model'=>$session['model'], 'Paramter'=>'', 'Key'=>$session['key']]],
    ['ep' => $urlSend, 'label' => 'SendCmd: 808DGXC param=0 Model', 'payload' => ['SN'=>$session['sn'], 'DeviceID'=>$session['device_id'], 'CommandType'=>'808DGXC','Model'=>$session['model'],'Paramter'=>'0','Key'=>$session['key']]],
];

foreach ($tests as $test) {
    try {
        $resp = Http::asForm()->timeout(10)->post($test['ep'], $test['payload']);
        $body = trim($resp->body());
        // Strip XML wrapper
        $parsed = preg_replace('/<[^>]+>/', '', $body);
        echo "[{$test['label']}] HTTP={$resp->status()} Response='{$parsed}'\n";
    } catch (\Exception $e) {
        echo "[{$test['label']}] ERROR: {$e->getMessage()}\n";
    }
    usleep(500000); // 0.5s between each test
}

echo "\n--- DONE ---\n";
echo "NOTE: Response '0' = success/queued, negative = error, other strings = error message.\n";

// Also try GetCommandList to see what commands this device supports
echo "\n--- Checking available commands for this device ---\n";
try {
    $cmdListResp = Http::asForm()->timeout(10)->post($session['api_address'] . '/GetCommandList', [
        'DeviceID' => $session['device_id'],
        'Key'      => $session['key']
    ]);
    echo "GetCommandList response:\n" . $cmdListResp->body() . "\n";
} catch (\Exception $e) {
    echo "GetCommandList ERROR: " . $e->getMessage() . "\n";
}
