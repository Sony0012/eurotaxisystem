<?php
$content = file_get_contents("chat_live.blade.php");
// Extract all scripts
preg_match_all("/<script>(.*?)<\/script>/s", $content, $matches);
foreach ($matches[1] as $i => $js) {
    echo "========= SCRIPT $i =========\n";
    // Check for common JS errors manually with regex or just output it
    $lines = explode("\n", $js);
    foreach ($lines as $ln => $line) {
        if (trim($line) === "};" || trim($line) === "}") {
            // Check balance roughly
        }
    }
}
echo "Total scripts: " . count($matches[1]) . "\n";
file_put_contents("script_only.js", $matches[1][1] ?? "no script");

