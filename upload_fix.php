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

$upload = ftp_put($conn_id, "domains/eurotaxisystem.site/public_html/fix_data_live.php", "fix_data_live.php", FTP_BINARY);
if (!$upload) {
    echo "FTP upload of fix_data_live.php failed!\n";
} else {
    echo "Uploaded fix_data_live.php successfully!\n";
}

$upload2 = ftp_put($conn_id, "domains/eurotaxisystem.site/public_html/app/Http/Controllers/DashboardController.php", "app/Http/Controllers/DashboardController.php", FTP_BINARY);
if (!$upload2) {
    echo "FTP upload of DashboardController.php failed!\n";
} else {
    echo "Uploaded DashboardController.php successfully!\n";
}

$upload3 = ftp_put($conn_id, "domains/eurotaxisystem.site/public_html/app/Http/Controllers/Api/DashboardController.php", "app/Http/Controllers/Api/DashboardController.php", FTP_BINARY);
if (!$upload3) {
    echo "FTP upload of Api/DashboardController.php failed!\n";
} else {
    echo "Uploaded Api/DashboardController.php successfully!\n";
}

ftp_close($conn_id);
