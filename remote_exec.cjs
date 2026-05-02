const { Client } = require('ssh2');

const config = {
    host: '195.35.62.133',
    port: 65002,
    username: 'u747826271',
    password: '@Admineuro2026',
    readyTimeout: 20000
};

// Command to get .env DB settings and check if table exists
const command = `cd /home/u747826271/domains/eurotaxisystem.site/public_html && grep DB_ .env && php artisan tinker --execute="try { echo 'TABLE_EXISTS: ' . (Schema::hasTable('login_audit') ? 'YES' : 'NO') . \"\\n\"; echo 'DB_DATABASE: ' . DB::connection()->getDatabaseName() . \"\\n\"; } catch (\\Exception $e) { echo 'ERROR: ' . $e->getMessage() . \"\\n\"; }"`;

console.log('--- REMOTE DB CHECK ---');
const conn = new Client();

conn.on('ready', () => {
    conn.exec(command, (err, stream) => {
        if (err) throw err;
        stream.on('close', (code, signal) => {
            conn.end();
        }).on('data', (data) => {
            process.stdout.write(data);
        }).stderr.on('data', (data) => {
            process.stderr.write(data);
        });
    });
}).connect(config);
