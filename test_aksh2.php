<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$unit = DB::table('units')->where('plate_number', 'NEF 4940')->first();
if ($unit) {
    echo "IMEI: " . $unit->imei . "\n";
    $aksh = new App\Services\AkshGpsService();
    $session = $aksh->getSession($unit->imei, $unit->gps_password);
    print_r($session);

    $apiAddress = $session['api_address'];
    $trackPayload = [
        'DeviceID'  => $session['device_id'],
        'Model'     => $session['model'],
        'TimeZones' => '8:00',
        'MapType'   => 'Google',
        'Language'  => 'en',
        'Key'       => $session['key']
    ];
    $response = Illuminate\Support\Facades\Http::asForm()->timeout(10)->post($apiAddress . '/GetTracking', $trackPayload);
    echo "Raw tracking response: \n" . $response->body() . "\n";
}
