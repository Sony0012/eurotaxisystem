const { Client } = require('ssh2');
const fs = require('fs');

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

$aksh = app(\\\\App\\\\Services\\\\AkshGpsService::class);
$imei = '17026288091'; // Example IMEI from logs
$session = $aksh->getSession($imei, null, true);

if ($session) {
    // Try sending 808DGXC with Paramter = 0 (Kill)
    $payload = [
        'DeviceID'    => $session['device_id'],
        'CommandType' => '808DGXC',
        'Paramter'    => '0', // 0 usually means cut-off
        'Key'         => $session['key']
    ];
    $url = $session['api_address'] . '/UpdateCommandByAPP';
    $response = \\\\Illuminate\\\\Support\\\\Facades\\\\Http::asForm()->timeout(15)->post($url, $payload);
    
    echo "AKSH Response: " . $response->body() . "\\n";
} else {
    echo "AKSH Login Failed.\\n";
}
`;

const conn = new Client();
conn.on('ready', () => {
    conn.exec(`cd ${BASE_REMOTE} && php -r "${phpScript.replace(/"/g, '\\"')}"`, (err, stream) => {
        if (err) throw err;
        stream.on('close', (code, signal) => {
            conn.end();
        }).on('data', (data) => {
            console.log(data.toString());
        }).stderr.on('data', (data) => {
            console.error(data.toString());
        });
    });
}).connect(config);
