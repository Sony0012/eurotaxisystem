<?php
$content = file_get_contents('resources/views/boundaries/index.blade.php');
$start = strpos($content, 'function updateDriverDebtDisplay(driverId');
$end = strpos($content, '                document.getElementById(\'driverId\').dispatchEvent');
$block = substr($content, $start, $end - $start);
$content = str_replace($block, "", $content);
$content = str_replace("function payFullBalance() {", $block . "\nfunction payFullBalance() {", $content);
file_put_contents('resources/views/boundaries/index.blade.php', $content);
echo "Fixed";
