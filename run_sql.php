<?php
$remote = "u747826271@195.35.62.133";
$port = 65002;
$remotePath = "/home/u747826271/domains/eurotaxisystem.site/public_html/";
$cmd = 'php artisan tinker --execute="DB::table(\'chat_messages\')->update([\'reactions\' => null]); echo \'Cleared reactions\';"';
exec("ssh -p $port $remote \"cd $remotePath && $cmd\"", $output);
echo implode("\n", $output);
