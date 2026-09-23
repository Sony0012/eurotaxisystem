<?php
$localFile = "C:/xampp/htdocs/eurotaxisystem/resources/views/partials/chat-drawer.blade.php";
$remoteFile = "/home/u747826271/domains/eurotaxisystem.site/public_html/resources/views/partials/chat-drawer.blade.php";
$port = 65002;
$remote = "u747826271@195.35.62.133";
exec("scp -P $port \"$localFile\" $remote:$remoteFile", $output, $returnVar);
if ($returnVar === 0) {
    echo "Upload successful.";
} else {
    echo "Upload failed. Return Code: " . $returnVar . "\n";
    echo implode("\n", $output);
}

