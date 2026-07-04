<?php
require __DIR__ . '/vendor/autoload.php';
use phpseclib3\Net\SFTP;

$host = '195.35.62.133';
$port = 65002;
$username = 'u747826271';
$password = '@Admineuro2026';

$sftp = new SFTP($host, $port);
if (!$sftp->login($username, $password)) {
    exit("Login Failed\n");
}

$remote_file = '/home/u747826271/domains/eurotaxisystem.site/public_html/app/Http/Controllers/DashboardController.php';
$local_file = __DIR__ . '/live_DashboardController.php';

$sftp->get($remote_file, $local_file);
echo "Downloaded successfully.\n";
