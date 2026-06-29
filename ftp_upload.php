<?php

$ftp_server = "195.35.62.133";
$ftp_username = "u747826271";
$ftp_userpass = "@Admineuro2026";

$file = 'app/Http/Controllers/SuperAdminController.php';
$remote_file = '/domains/eurotaxisystem.site/public_html/app/Http/Controllers/SuperAdminController.php';

// set up basic connection
$ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");

// login with username and password
$login = ftp_login($ftp_conn, $ftp_username, $ftp_userpass);
ftp_pasv($ftp_conn, true);

if (!$login) {
    echo "FTP connection has failed!";
    exit;
}

echo "Connected successfully to $ftp_server.\n";

// upload a file
if (ftp_put($ftp_conn, $remote_file, $file, FTP_ASCII)) {
    echo "Successfully uploaded $file.\n";
} else {
    echo "Error uploading $file.\n";
}

// close the connection
ftp_close($ftp_conn);
?>
