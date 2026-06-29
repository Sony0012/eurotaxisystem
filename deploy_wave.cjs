const Client = require('ssh2-sftp-client');
const sftp = new Client();

const config = {
  host: '195.35.62.133',
  port: 65002,
  username: 'u747826271',
  password: '@Admineuro2026',
  readyTimeout: 30000
};

const BASE = '/home/u747826271/domains/eurotaxisystem.site/public_html';

const files = [
  ['resources/views/dashboard.blade.php', `${BASE}/resources/views/dashboard.blade.php`],
  ['resources/views/components/animated-wave.blade.php', `${BASE}/resources/views/components/animated-wave.blade.php`],
];

async function upload() {
  try {
    console.log('Connecting to Hostinger SFTP...');
    await sftp.connect(config);
    console.log('✅ Connected!\n');

    for (const [local, remote] of files) {
      try {
        await sftp.fastPut(local, remote);
        console.log(`✅ Uploaded: ${local}`);
      } catch (err) {
        console.error(`❌ FAILED: ${local} → ${err.message}`);
      }
    }

    console.log('\n🎉 Deploy complete!');
  } catch (err) {
    console.error('Connection error:', err.message);
  } finally {
    sftp.end();
  }
}

upload();
