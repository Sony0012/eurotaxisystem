<?php
$content = file_get_contents("chat_temp.txt");
preg_match_all("/<script>(.*?)<\/script>/s", $content, $matches);
foreach ($matches[1] as $i => $js) {
    file_put_contents("test_script_$i.js", $js);
    echo "Script $i:\n";
    exec("node -c test_script_$i.js 2>&1", $out, $ret);
    echo implode("\n", $out) . "\n";
    if ($ret !== 0) echo "SYNTAX ERROR IN SCRIPT $i\n";
}

