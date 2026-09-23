const { Client } = require('ssh2');

const conn = new Client();

console.log('Connecting with debug enabled...');

conn.on('debug', (info) => {
    console.log('DEBUG:', info);
}).on('ready', () => {
    console.log('✅ SSH Connection Ready!');
    conn.end();
}).on('error', (err) => {
    console.error('❌ SSH Error:', err);
}).connect({
    host: '195.35.62.133',
    port: 65002,
    username: 'u747826271',
    password: '@Admineuro2026',
    readyTimeout: 30000,
    tryKeyboard: true
});
