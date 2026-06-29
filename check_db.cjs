const { Client } = require('ssh2');
const sshConfig = {
    host: '195.35.62.133', port: 65002,
    username: 'u747826271', password: '@Admineuro2026',
    readyTimeout: 60000,
    algorithms: {
        kex: ['diffie-hellman-group14-sha256', 'diffie-hellman-group14-sha1', 'diffie-hellman-group1-sha1'],
        cipher: ['aes128-ctr', 'aes192-ctr', 'aes256-ctr'],
        serverHostKey: ['ssh-rsa', 'ecdsa-sha2-nistp256'],
        hmac: ['hmac-sha2-256', 'hmac-sha1']
    }
};
const BASE = '/home/u747826271/domains/eurotaxisystem.site/public_html';
const conn = new Client();
conn.on('error', (err) => console.error('SSH Error:', err.message));
conn.on('ready', () => {
    // We will use tinker to check the unit
    const script = `
        $u = \\DB::table('units')->where('plate_number', 'NEF 4940')->first();
        echo "Plate: " . $u->plate_number . "\\n";
        echo "IMEI: " . $u->imei . "\\n";
        echo "Provider: " . $u->gps_provider . "\\n";
    `;
    conn.exec(`cd ${BASE} && php artisan tinker --execute="${script}"`, (err, stream) => {
        stream
            .on('close', () => conn.end())
            .on('data', d => process.stdout.write(d))
            .stderr.on('data', d => process.stderr.write(d));
    });
}).connect(sshConfig);
