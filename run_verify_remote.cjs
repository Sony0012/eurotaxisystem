const fs = require('fs');
const { Client } = require('ssh2');

const config = {
    host: '195.35.62.133',
    port: 65002,
    username: 'u747826271',
    password: '@Admineuro2026',
    readyTimeout: 30000
};

const REMOTE_DIR = '/home/u747826271/domains/eurotaxisystem.site/public_html';
const conn = new Client();

conn.on('ready', () => {
    conn.sftp((err, sftp) => {
        if (err) throw err;
        const localPath = 'C:\\Users\\bertl\\.gemini\\antigravity\\brain\\0dba22fd-732a-4391-bc29-6a7e4dbf8d19\\scratch\\verify_remote_html.php';
        const remotePath = `${REMOTE_DIR}/verify_remote_html.php`;
        
        sftp.fastPut(localPath, remotePath, (err) => {
            if (err) throw err;
            console.log('✓ verify_remote_html.php uploaded');
            
            conn.exec(`cd ${REMOTE_DIR} && /opt/alt/php82/usr/bin/php verify_remote_html.php && rm verify_remote_html.php`, (err, stream) => {
                if (err) throw err;
                let data = '';
                stream.on('data', (chunk) => data += chunk.toString());
                stream.on('close', () => {
                    console.log('--- REMOTE OUTPUT ---');
                    console.log(data);
                    conn.end();
                });
            });
        });
    });
}).connect(config);
