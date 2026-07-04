<?php
$localFile = "C:/xampp/htdocs/eurotaxisystem/test_db.php";
$remoteFile = "/home/u747826271/domains/eurotaxisystem.site/public_html/test_db.php";
exec("scp -P 65002 \"$localFile\" u747826271@195.35.62.133:$remoteFile", $output, $returnVar);

