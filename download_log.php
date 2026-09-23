<?php
$remote = "u747826271@195.35.62.133";
$port = 65002;
$remotePath = "/home/u747826271/domains/eurotaxisystem.site/public_html/database/migrations/2026_07_03_233028_add_reactions_to_chat_messages_table.php";
exec("ssh -p $port $remote rm $remotePath", $output);
echo implode("\n", $output);

