<?php
$csvPath = "C:\\Users\\bertl\\OneDrive\\Desktop\\Taxi taxi driver (1).csv";
$handle = fopen($csvPath, 'r');
fgetcsv($handle); // skip header
$plates = [];
while (($row = fgetcsv($handle)) !== false) {
    if (count($row) >= 3 && !empty(trim($row[2]))) {
        $plates[] = strtoupper(preg_replace('/\s+/', '', $row[2]));
    }
}
fclose($handle);
echo "Unique Plate Count in CSV: " . count(array_unique($plates)) . "\n";
echo "Plates in CSV: " . implode(',', array_unique($plates)) . "\n";
