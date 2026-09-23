<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$service = app(\App\Services\TracksolidService::class);
$token = $service->getAccessToken();
$imei = '352503095087433';

function sendApiRequest($service, $method, $paramsArray) {
    $params = array_merge([
        'method'       => $method,
        'app_key'      => '8FB345B8693CCD004CAAFE1513251786',
        'access_token' => $service->getAccessToken(),
        'timestamp'    => date('Y-m-d H:i:s'),
        'format'       => 'json',
        'v'            => '1.0',
        'sign_method'  => 'md5',
    ], $paramsArray);
    
    ksort($params);
    $raw = '';
    foreach($params as $k => $v) {
        if($k !== 'sign' && !is_null($v) && $v !== '') {
            $raw .= $k.$v;
        }
    }
    
    $secret = '9A075D1BDB741E3A68AC72F3E22CD4B3';
    $params['sign'] = strtoupper(md5($secret . $raw . $secret));
    
    return \Illuminate\Support\Facades\Http::timeout(10)->asForm()->post('https://open.10000track.com/route/rest', $params)->json();
}

$res = sendApiRequest($service, 'jimi.open.instruction.send', [
    'imei' => $imei,
    'inst_param_json' => json_encode(["inst_id" => 111, "inst_template" => "RELAY,1#", "params" => [], "is_cover" => true])
]);
echo json_encode($res);
