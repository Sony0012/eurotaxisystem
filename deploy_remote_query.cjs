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

const conn = new Client();
conn.on('ready', () => {
    conn.sftp((err, sftp) => {
        if (err) throw err;
        sftp.fastPut('remote_query.php', BASE_REMOTE + '/remote_query.php', {}, (err) => {
            if (err) throw err;
            conn.exec(`cd ${BASE_REMOTE} && php remote_query.php`, (err, stream) => {
                if (err) throw err;
                let output = '';
                stream.on('close', (code, signal) => {
                    fs.writeFileSync('remote_query_output.json', output);
                    console.log('Saved to remote_query_output.json');
                    conn.end();
                }).on('data', (data) => {
                    output += data;
                }).stderr.on('data', (data) => {
                    console.error('STDERR: ' + data);
                });
            });
        });
    });
}).connect(config);
