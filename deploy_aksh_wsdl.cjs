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

const conn = new Client();
conn.on('ready', () => {
    // Fetch the HTML page for UpdateCommandByAPP to see parameters
    conn.exec(`curl -s "http://app.aika168.com:8088/openapiv3.asmx?op=UpdateCommandByAPP" | grep -A 20 "POST /openapiv3.asmx/UpdateCommandByAPP"`, (err, stream) => {
        if (err) throw err;
        stream.on('close', (code, signal) => {
            conn.exec(`curl -s "http://app.aika168.com:8088/openapiv3.asmx?op=SendCommandByAPP" | grep -A 20 "POST /openapiv3.asmx/SendCommandByAPP"`, (err2, stream2) => {
                stream2.on('close', () => { conn.end(); }).on('data', d => console.log(d.toString()));
            });
        }).on('data', (data) => {
            console.log(data.toString());
        });
    });
}).connect(config);
