<?php
$array = ["125" => "??"];
echo "Default json_encode: " . json_encode($array) . "\n";
echo "Unescaped json_encode: " . json_encode($array, JSON_UNESCAPED_UNICODE) . "\n";

