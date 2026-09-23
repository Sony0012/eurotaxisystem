<?php
$server = '195.35.62.133';
$ftp_user_name = 'u747826271';
$ftp_user_pass = '@Admineuro2026';
$conn_id = ftp_connect($server);
if (!$conn_id) {
    die("FTP connection has failed!");
}
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
if (!$login_result) {
    die("FTP login failed!");
}
echo "Connected via FTP! \n";
ftp_pasv($conn_id, true);

$files = [
    'resources/views/layouts/app.blade.php',
    'app/Http/Controllers/Api/NotificationController.php',
    'app/Services/NotificationService.php'
];

foreach ($files as $file) {
    $local_file = "C:\\xampp\\htdocs\\eurotaxisystem\\" . str_replace('/', '\\', $file);
    $remote_file = "domains/eurotaxisystem.site/public_html/" . $file;
    $upload = ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY);
    if (!$upload) {
        echo "FTP upload of {$file} failed!\n";
    } else {
        echo "Uploaded {$file} successfully!\n";
    }
}

ftp_close($conn_id);
