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
    // Check the key lines in AkshGpsService.php on Hostinger
    conn.exec(`grep -n "ROOT CAUSE\\|SendCommandByAPP\\|REAL-TIME push\\|DELIVERED\\|queued\\|UpdateCommandByAPP" ${BASE}/app/Services/AkshGpsService.php | head -30`, (err, stream) => {
        stream
            .on('close', () => {
                // Also check last modified time of the file
                conn.exec(`stat ${BASE}/app/Services/AkshGpsService.php | grep "Modify"`, (e2, s2) => {
                    s2.on('data', d => process.stdout.write('Last Modified: ' + d));
                    s2.on('close', () => conn.end());
                });
            })
            .on('data', d => process.stdout.write(d))
            .stderr.on('data', d => process.stderr.write(d));
    });
}).connect(sshConfig);
