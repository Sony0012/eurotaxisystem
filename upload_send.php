<?php
$localFile = "C:/xampp/htdocs/eurotaxisystem/test_send.php";
$remoteFile = "/home/u747826271/domains/eurotaxisystem.site/public_html/test_send.php";
exec("scp -P 65002 \"$localFile\" u747826271@195.35.62.133:$remoteFile");

