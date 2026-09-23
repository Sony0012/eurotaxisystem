const { Client } = require('ssh2');
const fs = require('fs');

const config = {
    host: '195.35.62.133',
    port: 65002,
    username: 'u747826271',
    password: '@Admineuro2026',
    readyTimeout: 30000
};

const conn = new Client();
conn.on('ready', () => {
    console.log('SSH Connected!');
    conn.sftp((err, sftp) => {
        if (err) throw err;
        
        console.log('SFTP Connected! Uploading app_query.php...');
        sftp.fastPut('c:/xampp/htdocs/eurotaxisystem/app_query.php', '/home/u747826271/domains/eurotaxisystem.site/public_html/app_query.php', (err) => {
            if (err) {
                console.error('SFTP Upload failed:', err);
                conn.end();
                return;
            }
            
            console.log('Uploaded! Running app_query.php on live server...');
            conn.exec('cd /home/u747826271/domains/eurotaxisystem.site/public_html && php app_query.php', (err, stream) => {
                if (err) {
                    console.error('Execution failed:', err);
                    conn.end();
                    return;
                }
                
                let output = '';
                stream.on('close', (code, signal) => {
                    console.log('\n--- Output from live server ---');
                    console.log(output);
                    console.log('-------------------------------\n');
                    
                    console.log('Deleting temporary app_query.php from server...');
                    sftp.unlink('/home/u747826271/domains/eurotaxisystem.site/public_html/app_query.php', (err) => {
                        if (err) console.error('Failed to delete app_query.php:', err);
                        else console.log('Successfully deleted app_query.php.');
                        conn.end();
                    });
                }).on('data', (data) => {
                    output += data;
                }).stderr.on('data', (data) => {
                    process.stderr.write('STDERR: ' + data);
                });
            });
        });
    });
}).on('error', err => {
    console.error('Connection Error:', err.message);
}).connect(config);
