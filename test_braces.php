<?php
$js = file_get_contents("script_only.js");
$lines = explode("\n", $js);
$open = 0;
foreach ($lines as $i => $line) {
    // Strip string literals and comments roughly
    $line = preg_replace("/\/\/.*$/", "", $line);
    $line = preg_replace("/\/\*.*?\*\//s", "", $line);
    $line = preg_replace("/\".*?\"/", "", $line);
    $line = preg_replace("/\".*?\"/", "", $line); // single quotes
    $line = preg_replace("/\`.*?\`/", "", $line);
    
    $open += substr_count($line, "{");
    $open -= substr_count($line, "}");
    if ($open < 0) {
        echo "Error: Negative braces at line " . ($i+1) . "!\n";
        echo "Line: " . $lines[$i] . "\n";
        break;
    }
}
echo "Final braces count: $open\n";

