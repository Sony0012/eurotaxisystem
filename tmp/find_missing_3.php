<?php
$csvPath = "C:\\Users\\bertl\\OneDrive\\Desktop\\Taxi taxi driver (1).csv";
$dbPlatesRaw = file_get_contents("C:\\xampp\\htdocs\\eurotaxisystem\\tmp_db_plates.txt");

function normalizePlate($p) {
    return strtoupper(preg_replace('/\s+/', '', trim($p)));
}

$handle = fopen($csvPath, 'r');
fgetcsv($handle);
$csvPlates = [];
while (($row = fgetcsv($handle)) !== false) {
    if (count($row) >= 3 && !empty(trim($row[2]))) {
        $csvPlates[] = normalizePlate($row[2]);
    }
}
fclose($handle);
$csvPlates = array_unique($csvPlates);

$dbPlates = [];
foreach (explode("\n", trim($dbPlatesRaw)) as $p) {
    if (empty($p)) continue;
    $dbPlates[] = normalizePlate($p);
}

$missing = array_diff($csvPlates, $dbPlates);
echo "Missing Plates:\n";
print_r($missing);
