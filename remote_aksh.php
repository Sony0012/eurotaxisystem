<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$aksh = app(\App\Services\AkshGpsService::class);
// Let's get any AKSH unit
$unit = \Illuminate\Support\Facades\DB::table('units')->where('gps_provider', 'aksh')->whereNotNull('imei')->first();
if (!$unit) {
    echo "No AKSH unit found.\n";
    exit;
}
$imei = $unit->imei;

$session = $aksh->getSession($imei, $unit->gps_password, true);

if ($session) {
    // Test 808DGXC
    $payload = [
        'DeviceID'    => $session['device_id'],
        'CommandType' => '808DGXC',
        'Paramter'    => '0',
        'Key'         => $session['key']
    ];
    $url = $session['api_address'] . '/UpdateCommandByAPP';
    $response = \Illuminate\Support\Facades\Http::asForm()->timeout(15)->post($url, $payload);
    
    echo "AKSH Response for 808DGXC (0): " . $response->body() . "\n";
    
    // Test DY
    $payload2 = [
        'DeviceID'    => $session['device_id'],
        'CommandType' => 'DY',
        'Paramter'    => '',
        'Key'         => $session['key']
    ];
    $response2 = \Illuminate\Support\Facades\Http::asForm()->timeout(15)->post($url, $payload2);
    echo "AKSH Response for DY: " . $response2->body() . "\n";

    // Test Relay
    $payload3 = [
        'DeviceID'    => $session['device_id'],
        'CommandType' => 'Relay',
        'Paramter'    => '1',
        'Key'         => $session['key']
    ];
    $response3 = \Illuminate\Support\Facades\Http::asForm()->timeout(15)->post($url, $payload3);
    echo "AKSH Response for Relay (1): " . $response3->body() . "\n";

} else {
    echo "AKSH Login Failed for {$imei}.\n";
}
