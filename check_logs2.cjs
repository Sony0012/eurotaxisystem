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
    // List log files
    conn.exec(`ls -lht ${BASE}/storage/logs/ && echo "---" && tail -100 ${BASE}/storage/logs/laravel.log 2>&1 | tail -80`, (err, stream) => {
        stream
            .on('close', () => conn.end())
            .on('data', d => process.stdout.write(d))
            .stderr.on('data', d => process.stderr.write(d));
    });
}).connect(sshConfig);
