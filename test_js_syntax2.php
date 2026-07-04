<?php
$content = file_get_contents("chat_temp.txt");
$s1 = strpos($content, "<script>");
$e1 = strpos($content, "</script>", $s1);
$js1 = substr($content, $s1+8, $e1 - $s1 - 8);

// Remove blade directives so Node can parse it
$js1 = preg_replace("/\{\{.*?\}\}/", "\"BLADE\"", $js1);
file_put_contents("script1.js", $js1);
exec("node -c script1.js 2>&1", $out, $ret);
echo "Script 1:\n" . implode("\n", $out) . "\nReturn: $ret\n";

$s2 = strpos($content, "<script>", $e1);
$e2 = strpos($content, "</script>", $s2);
$js2 = substr($content, $s2+8, $e2 - $s2 - 8);
$js2 = preg_replace("/\{\{.*?\}\}/", "\"BLADE\"", $js2);
file_put_contents("script2.js", $js2);
exec("node -c script2.js 2>&1", $out2, $ret2);
echo "Script 2:\n" . implode("\n", $out2) . "\nReturn: $ret2\n";

