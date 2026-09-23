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

function tryConnect(attempt = 1) {
    const conn = new Client();
    conn.on('error', (err) => {
        console.error(`Attempt ${attempt} failed: ${err.message}`);
        if (attempt < 5) setTimeout(() => tryConnect(attempt + 1), 3000);
    });
    conn.on('ready', () => {
        conn.sftp((err, sftp) => {
            if (err) throw err;
            sftp.fastPut('probe_aksh3.php', BASE + '/probe_aksh3.php', {}, () => {
                console.log('Uploaded, running...\n');
                conn.exec(`cd ${BASE} && php probe_aksh3.php 2>&1`, (e2, stream) => {
                    stream
                        .on('close', () => conn.exec(`rm -f ${BASE}/probe_aksh3.php`, () => conn.end()))
                        .on('data', d => process.stdout.write(d))
                        .stderr.on('data', d => process.stderr.write(d));
                });
            });
        });
    }).connect(sshConfig);
}
tryConnect();
