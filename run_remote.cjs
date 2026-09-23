const { Client } = require('ssh2');

const config = {
    host: '195.35.62.133',
    port: 65002,
    username: 'u747826271',
    password: '@Admineuro2026',
    algorithms: {
        kex: ['diffie-hellman-group14-sha256', 'diffie-hellman-group14-sha1', 'diffie-hellman-group1-sha1'],
        cipher: ['aes128-ctr', 'aes192-ctr', 'aes256-ctr', 'aes128-gcm', 'aes256-gcm'],
        serverHostKey: ['ssh-rsa', 'ecdsa-sha2-nistp256'],
        hmac: ['hmac-sha2-256', 'hmac-sha1']
    }
};

const BASE_REMOTE = '/home/u747826271/domains/eurotaxisystem.site/public_html';

const phpScript = `
<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\\\\Contracts\\\\Console\\\\Kernel')->bootstrap();

$service = app(\\\\App\\\\Services\\\\TracksolidService::class);
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
    
    return \\\\Illuminate\\\\Support\\\\Facades\\\\Http::timeout(10)->asForm()->post('https://open.tracksolidpro.com/route/rest', $params)->json();
}

$res = sendApiRequest($service, 'jimi.open.instruction.dictionary.get', [
    'imei' => $imei
]);
echo json_encode($res);
`;

const conn = new Client();
conn.on('ready', () => {
    conn.exec(`cd ${BASE_REMOTE} && php -r "${phpScript.replace(/"/g, '\\"')}"`, (err, stream) => {
        if (err) throw err;
        let output = '';
        stream.on('close', (code, signal) => {
            console.log(output);
            conn.end();
        }).on('data', (data) => {
            output += data;
        }).stderr.on('data', (data) => {
            console.error('STDERR: ' + data);
        });
    });
}).connect(config);
