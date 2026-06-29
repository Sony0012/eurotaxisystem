<?php
$content = file_get_contents('resources/views/dashboard.blade.php');
preg_match_all('/<script>(.*?)<\/script>/s', $content, $matches);

foreach ($matches[1] as $index => $script) {
    $open = substr_count($script, '{');
    $close = substr_count($script, '}');
    echo "Script block " . ($index + 1) . ": Open braces = $open, Close braces = $close\n";
    if ($open !== $close) {
        echo "MISMATCH FOUND IN BLOCK " . ($index + 1) . "!\n";
    }
}
