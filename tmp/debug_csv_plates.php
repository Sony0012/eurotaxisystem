<?php
$csvPath = "C:\\Users\\bertl\\OneDrive\\Desktop\\Taxi taxi driver (1).csv";
$handle = fopen($csvPath, 'r');
fgetcsv($handle);
$rawPlates = [];
$normPlates = [];
while (($row = fgetcsv($handle)) !== false) {
    if (count($row) >= 3 && !empty(trim($row[2]))) {
        $p = trim($row[2]);
        $rawPlates[] = $p;
        $normPlates[] = strtoupper(preg_replace('/\s+/', '', $p));
    }
}
fclose($handle);
echo "Raw count: " . count($rawPlates) . "\n";
echo "Raw Unique: " . count(array_unique($rawPlates)) . "\n";
echo "Normalized Unique: " . count(array_unique($normPlates)) . "\n";

$diff = array_diff_assoc($rawPlates, array_unique($rawPlates));
echo "Raw Duplicates: "; print_r($diff);

// Find normalized collisions
$collisionCheck = [];
foreach($rawPlates as $p) {
    $n = strtoupper(preg_replace('/\s+/', '', $p));
    $collisionCheck[$n][] = $p;
}
foreach($collisionCheck as $n => $originals) {
    if (count(array_unique($originals)) > 1) {
        echo "Collision for ($n): " . implode(' vs ', array_unique($originals)) . "\n";
    }
}
