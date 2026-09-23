const Client = require('ssh2-sftp-client');

const passwords = [
    '@Admineuro2026',
    'Password123!',
    '@Eurotaxi123',
    'Admin@2026'
];

async function testAllPasswords() {
    for (const pw of passwords) {
        const sftp = new Client();
        console.log(`Testing password: "${pw}"...`);
        try {
            await sftp.connect({
                host: '195.35.62.133',
                port: 65002,
                username: 'u747826271',
                password: pw,
                readyTimeout: 10000
            });
            console.log(`🎉 SUCCESS with password: "${pw}"!`);
            await sftp.end();
            return pw;
        } catch (e) {
            console.log(`❌ Failed with password "${pw}": ${e.message}`);
        }
    }
    console.log('All passwords failed.');
}

testAllPasswords();
