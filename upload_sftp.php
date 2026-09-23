<?php
require __DIR__ . '/vendor/autoload.php';

use phpseclib3\Net\SFTP;

$host = '195.35.62.133';
$port = 65002;
$username = 'u747826271';
$password = '@Admineuro2026';

echo "Connecting to SFTP... \n";
$sftp = new SFTP($host, $port);
if (!$sftp->login($username, $password)) {
    exit("Login Failed\n");
}

echo "Login successful!\n";

$files = [
    'resources/views/partials/chat-drawer.blade.php',
    'database/migrations/2026_07_03_233028_add_reactions_to_chat_messages_table.php'
];

$base_dir = '/home/u747826271/domains/eurotaxisystem.site/public_html/';

foreach ($files as $file) {
    $local_path = __DIR__ . '/' . $file;
    $remote_path = $base_dir . $file;
    
    echo "Uploading $file...\n";
    if ($sftp->put($remote_path, $local_path, SFTP::SOURCE_LOCAL_FILE)) {
        echo "Uploaded $file successfully!\n";
    } else {
        echo "Failed to upload $file!\n";
    }
}
echo "Done.\n";
