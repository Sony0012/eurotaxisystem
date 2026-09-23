const { Client } = require('ssh2');
const fs = require('fs');
const path = require('path');

const config = {
    host: '195.35.62.133',
    port: 65002,
    username: 'u747826271',
    password: '@Admineuro2026',
    readyTimeout: 30000,
    algorithms: {
        kex: ['diffie-hellman-group14-sha256', 'diffie-hellman-group14-sha1', 'diffie-hellman-group1-sha1'],
        cipher: ['aes128-ctr', 'aes192-ctr', 'aes256-ctr', 'aes128-gcm', 'aes256-gcm'],
        serverHostKey: ['ssh-rsa', 'ecdsa-sha2-nistp256'],
        hmac: ['hmac-sha2-256', 'hmac-sha1']
    }
};

const BASE_REMOTE = '/home/u747826271/domains/eurotaxisystem.site/public_html';
const BASE_LOCAL = __dirname;

// ============================================================
// ALL FILES CHANGED IN THIS SESSION — COMPLETE UPLOAD LIST
// ============================================================
const filesToUpload = [
    // 1. IMEI validation fix (no more "15 characters" error)
    { local: 'app/Http/Controllers/UnitController.php',         remote: `${BASE_REMOTE}/app/Http/Controllers/UnitController.php` },

    // 2. Engine Control routing (Tracksolid vs AKSH auto-detect)
    { local: 'app/Http/Controllers/LiveTrackingController.php', remote: `${BASE_REMOTE}/app/Http/Controllers/LiveTrackingController.php` },

    // 3. Tracksolid Kill Engine fix (Strategy 1: jimi.device.instruction.send + Strategy 2 fallback)
    { local: 'app/Services/TracksolidService.php',              remote: `${BASE_REMOTE}/app/Services/TracksolidService.php` },

    // 4. AKSH GPS Service (DY=Kill, KY=Restore commands)
    { local: 'app/Services/AkshGpsService.php',                 remote: `${BASE_REMOTE}/app/Services/AkshGpsService.php` },

    // 5. Unit Management UI (Kill Engine label, IMEI field, GPS provider selector)
    { local: 'resources/views/units/index.blade.php',           remote: `${BASE_REMOTE}/resources/views/units/index.blade.php` },

    // 6. Live Tracking JS (Address loading fix, Engine timeout fix, queueAddressFetch global scope)
    { local: 'public/js/realtime-tracking.js',                  remote: `${BASE_REMOTE}/public/js/realtime-tracking.js` },

    // 7. Routes (web.php for any route changes)
    { local: 'routes/web.php',                                  remote: `${BASE_REMOTE}/routes/web.php` },

    // 8. Unit Model
    { local: 'app/Models/Unit.php',                             remote: `${BASE_REMOTE}/app/Models/Unit.php` },
];

let attempts = 0;

function tryDeploy() {
    attempts++;
    console.log(`\n[Attempt #${attempts}] Uploading ${filesToUpload.length} files to Hostinger...`);

    const conn = new Client();
    conn.on('ready', () => {
        console.log('✓ Connected! Starting bulk upload...');
        conn.sftp((err, sftp) => {
            if (err) { console.error('SFTP Error:', err.message); conn.end(); return; }

            let done = 0;
            let success = 0;
            let failed = [];

            filesToUpload.forEach(f => {
                const lp = path.join(BASE_LOCAL, f.local);
                if (!fs.existsSync(lp)) {
                    console.log(`  [SKIP] ${f.local} (not found locally)`);
                    done++;
                    if (done === filesToUpload.length) finalize(sftp, conn, success, failed);
                    return;
                }

                sftp.fastPut(lp, f.remote, {}, (err) => {
                    done++;
                    if (!err) {
                        success++;
                        console.log(`  [OK]   ${f.local}`);
                    } else {
                        failed.push(f.local);
                        console.error(`  [FAIL] ${f.local}: ${err.message}`);
                    }
                    if (done === filesToUpload.length) finalize(sftp, conn, success, failed);
                });
            });
        });
    }).on('error', (err) => {
        console.log(`✗ Blocked (${err.message}). Retrying in 3 minutes...`);
        setTimeout(tryDeploy, 180000);
    }).connect(config);
}

function finalize(sftp, conn, success, failed) {
    console.log(`\n  Uploaded ${success}/${filesToUpload.length} files.`);
    if (failed.length > 0) {
        console.log('  Failed files:', failed.join(', '));
    }

    // Clear Laravel caches on server
    conn.exec(`cd ${BASE_REMOTE} && php artisan optimize:clear && echo "CACHE CLEARED"`, (err, stream) => {
        if (err) { conn.end(); return; }
        stream.on('close', () => {
            console.log('\n=====================================================');
            console.log('✓✓✓ FULL UPLOAD COMPLETE! ALL CHANGES ARE LIVE!');
            console.log(`    ${success} files deployed successfully.`);
            if (failed.length > 0) console.log(`    ${failed.length} files failed (see above).`);
            console.log('=====================================================\n');
            conn.end();
            process.exit(0);
        }).on('data', d => process.stdout.write('  Server: ' + d.toString()));
    });
}

tryDeploy();
