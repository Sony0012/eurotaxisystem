const { Client } = require('ssh2');

const conn = new Client();

conn.on('keyboard-interactive', (name, instructions, instructionsLang, prompts, finish) => {
    console.log('keyboard-interactive event:', name, instructions, prompts);
    finish(['@Admineuro2026']);
}).on('ready', () => {
    console.log('✅ SSH Connection Ready via KB Interactive!');
    conn.end();
}).on('error', (err) => {
    console.error('❌ SSH Error:', err.message);
}).connect({
    host: '195.35.62.133',
    port: 65002,
    username: 'u747826271',
    password: '@Admineuro2026',
    tryKeyboard: true
});
