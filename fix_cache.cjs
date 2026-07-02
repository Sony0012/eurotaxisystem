const { Client } = require('ssh2');

const config = {
    host: '195.35.62.133',
    port: 65002,
    username: 'u747826271',
    password: '@Admineuro2026',
    readyTimeout: 20000
};

const command = [
    'cd /home/u747826271/domains/eurotaxisystem.site/public_html',
    'mkdir -p bootstrap/cache',
    'chmod -R 775 bootstrap/cache',
    'chmod -R 775 storage',
    'php artisan optimize:clear'
].join(' && ');

console.log('--- FIXING SERVER PERMISSIONS ---');
console.log(`Connecting to ${config.host}:${config.port}...`);

const conn = new Client();

conn.on('ready', () => {
    console.log('SSH Connection Ready. Running fix commands...');
    conn.exec(command, (err, stream) => {
        if (err) {
            console.error('Execution Error:', err);
            conn.end();
            return;
        }
        stream.on('close', (code, signal) => {
            console.log(`\nStream Closed with code: ${code}`);
            conn.end();
        }).on('data', (data) => {
            process.stdout.write(`STDOUT: ${data}`);
        }).stderr.on('data', (data) => {
            process.stderr.write(`STDERR: ${data}`);
        });
    });
}).on('error', (err) => {
    console.error('Connection Error:', err);
}).connect(config);
