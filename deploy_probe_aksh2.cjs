const { Client } = require('ssh2');
const fs = require('fs');

// Use the sftp fallback deploy logic
const sshConfig = {
    host: '195.35.62.133', port: 65002,
    username: 'u747826271', password: '@Admineuro2026',
    readyTimeout: 60000,
    algorithms: {
        kex: ['diffie-hellman-group14-sha256', 'diffie-hellman-group14-sha1', 'diffie-hellman-group1-sha1'],
        cipher: ['aes128-ctr', 'aes192-ctr', 'aes256-ctr', 'aes128-gcm', 'aes256-gcm'],
        serverHostKey: ['ssh-rsa', 'ecdsa-sha2-nistp256'],
        hmac: ['hmac-sha2-256', 'hmac-sha1']
    }
};

const BASE = '/home/u747826271/domains/eurotaxisystem.site/public_html';

function tryConnect(attempt = 1) {
    const conn = new Client();
    conn.on('error', (err) => {
        console.error(`Attempt ${attempt} failed: ${err.message}`);
        if (attempt < 5) {
            setTimeout(() => tryConnect(attempt + 1), 3000);
        }
    });
    conn.on('ready', () => {
