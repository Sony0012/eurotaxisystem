const { Client } = require('ssh2');
const fs = require('fs');

const sshConfig = {
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

const conn = new Client();
conn.on('ready', () => {
    conn.sftp((err, sftp) => {
        if (err) throw err;
        sftp.fastPut('probe_aksh_relay.php', BASE_REMOTE + '/probe_aksh_relay.php', {}, (uploadErr) => {
            if (uploadErr) throw uploadErr;
            console.log('Uploaded probe_aksh_relay.php, running...\n');
            conn.exec(`cd ${BASE_REMOTE} && php probe_aksh_relay.php 2>&1`, (execErr, stream) => {
                if (execErr) throw execErr;
                stream.on('close', () => {
                    conn.exec(`rm -f ${BASE_REMOTE}/probe_aksh_relay.php`, () => conn.end());
                }).on('data', (data) => {
                    process.stdout.write(data);
                }).stderr.on('data', (data) => {
                    process.stderr.write('STDERR: ' + data);
                });
            });
        });
    });
}).connect(sshConfig);
