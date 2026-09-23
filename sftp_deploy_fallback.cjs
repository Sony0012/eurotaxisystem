const { Client } = require('ssh2');
const fs = require('fs');
const path = require('path');

const config = {
    host: '193.203.162.246',
    port: 22,
    username: 'u446869818',
    password: 'Password123!',
    readyTimeout: 30000,
    algorithms: {
        kex: ['diffie-hellman-group14-sha256', 'diffie-hellman-group14-sha1', 'diffie-hellman-group1-sha1'],
        cipher: ['aes128-ctr', 'aes192-ctr', 'aes256-ctr', 'aes128-gcm', 'aes256-gcm'],
        serverHostKey: ['ssh-rsa', 'ecdsa-sha2-nistp256'],
        hmac: ['hmac-sha2-256', 'hmac-sha1']
    }
};

const BASE_REMOTE = '/home/u446869818/domains/eurotaxisystem.site/public_html';
const BASE_LOCAL  = __dirname;

const filesToUpload = [
    { local: 'app/Http/Controllers/UnitController.php', remote: `${BASE_REMOTE}/app/Http/Controllers/UnitController.php` },
    { local: 'app/Http/Controllers/LiveTrackingController.php', remote: `${BASE_REMOTE}/app/Http/Controllers/LiveTrackingController.php` },
    { local: 'resources/views/units/index.blade.php', remote: `${BASE_REMOTE}/resources/views/units/index.blade.php` },
    { local: 'public/js/realtime-tracking.js', remote: `${BASE_REMOTE}/public/js/realtime-tracking.js` },
];

console.log('--- SFTP DEPLOY START (FALLBACK) ---');
const conn = new Client();

conn.on('ready', () => {
    conn.sftp((err, sftp) => {
        if (err) throw err;
        let done = 0;
        filesToUpload.forEach(f => {
            const lp = path.join(BASE_LOCAL, f.local);
            if(!fs.existsSync(lp)) {
                console.log('Skipping ' + f.local + ' (Not found)');
                done++;
                return;
            }
            sftp.fastPut(lp, f.remote, {}, (err) => {
                if (err) console.error('Upload Error for ' + f.local + ':', err);
                else console.log('Uploaded:', f.local);
                done++;
                if (done === filesToUpload.length) {
                    conn.exec(`cd ${BASE_REMOTE} && php artisan optimize:clear && echo "CACHE CLEARED"`, (err, stream) => {
                        if (err) throw err;
                        stream.on('close', () => {
                            conn.end();
                            console.log('Deploy via Fallback IP Finished');
                        }).on('data', d => console.log('Output:', d.toString()));
                    });
                }
            });
        });
    });
}).on('error', err => {
    console.error('Connection Error:', err.message);
}).connect(config);
