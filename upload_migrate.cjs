const Client = require('ssh2-sftp-client');
const sftp = new Client();
const config = {
    host: '193.203.162.246',
    port: 22,
    username: 'u446869818',
    password: 'Password123!' // Using password from previous deploy scripts
};

const filesToUpload = [
    'public/migrate_prod.php',
    'database/migrations/2026_07_03_220711_add_reactions_to_chat_messages_table.php'
];

async function main() {
    try {
        await sftp.connect(config);
        console.log('Connected via SFTP');
        console.log('Uploaded migrate_prod.php to Hostinger');
    } catch (err) {
        console.error(err.message);
    } finally {
        sftp.end();
    }
}
main();
