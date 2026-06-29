const { Client } = require('ssh2');
const fs = require('fs');
const path = require('path');

const config = {
    host: '195.35.62.133',
    port: 65002,
    username: 'u747826271',
    password: '@Admineuro2026',
    readyTimeout: 30000
};

const BASE_REMOTE = '/home/u747826271/domains/eurotaxisystem.site/public_html';
const BASE_LOCAL = 'c:/xampp/htdocs/eurotaxisystem';

const directoriesToSync = [
    'app',
    'resources/views',
    'routes',
    'database/migrations'
];

let filesToUpload = [];

function walkDir(dir) {
    const list = fs.readdirSync(dir);
    list.forEach(file => {
        const filePath = path.join(dir, file);
        const stat = fs.statSync(filePath);
        if (stat && stat.isDirectory()) {
            walkDir(filePath);
        } else {
            if (file.endsWith('.php') || file.endsWith('.js') || file.endsWith('.css')) {
                const relativePath = path.relative(BASE_LOCAL, filePath).replace(/\\/g, '/');
                filesToUpload.push({
                    local: filePath,
                    remote: `${BASE_REMOTE}/${relativePath}`,
                    remoteDir: `${BASE_REMOTE}/${path.dirname(relativePath).replace(/\\/g, '/')}`
                });
            }
        }
    });
}

directoriesToSync.forEach(d => {
    walkDir(path.join(BASE_LOCAL, d));
});

console.log(`Found ${filesToUpload.length} files to upload.`);

const conn = new Client();
conn.on('ready', () => {
    console.log('SSH Connected! Starting upload...');
    conn.sftp((err, sftp) => {
        if (err) {
            console.error('SFTP Error:', err);
            conn.end();
            return;
        }

        let done = 0;
        
        function uploadNext(index) {
            if (index >= filesToUpload.length) {
                console.log('\nAll files uploaded successfully!');
                console.log('Clearing view and cache...');
                conn.exec('cd /home/u747826271/domains/eurotaxisystem.site/public_html && php artisan view:clear && php artisan cache:clear', (err, stream) => {
                    if (err) {
                        console.error('Artisan Command Error:', err);
                        conn.end();
                        return;
                    }
                    stream.on('close', (code) => {
                        console.log(`✅ Cache cleared with code: ${code}`);
                        conn.end();
                    }).on('data', (data) => process.stdout.write(data))
                      .stderr.on('data', (data) => process.stderr.write(data));
                });
                return;
            }

            const f = filesToUpload[index];
            
            // First ensure directory exists (simplistic approach: just try to mkdir, ignore if exists)
            sftp.mkdir(f.remoteDir, true, (err) => {
                sftp.fastPut(f.local, f.remote, {}, (err) => {
                    if (err) {
                        console.error('ERROR uploading:', f.local, err.message);
                    } else {
                        console.log(`[${index+1}/${filesToUpload.length}] ✅ UPLOADED: ${f.remote}`);
                    }
                    uploadNext(index + 1);
                });
            });
        }
        
        uploadNext(0);
    });
}).on('error', err => {
    console.error('Connection Error:', err.message);
}).connect(config);
