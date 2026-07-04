<?php
$array = ["125" => "\u{1F606}"];
echo "Default json_encode: " . json_encode($array) . "\n";
echo "Unescaped json_encode: " . json_encode($array, JSON_UNESCAPED_UNICODE) . "\n";

