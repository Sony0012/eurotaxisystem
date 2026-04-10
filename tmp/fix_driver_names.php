<?php
/**
 * Driver Name Restoration Script
 * Reads CSV and generates SQL UPDATE statements directly.
 * Logic:
 *   - Set A = driver name (col index 1)
 *   - Plate = plate number (col index 2)
 *   - Set B = secondary driver name (col index 3)
 *   - If Set A == Set B => same person, only one driver on that unit (driver_id)
 *   - If Set A != Set B => two drivers (driver_id = Set A, secondary_driver_id = Set B)
 *   - Skip: NAD, VACANT, NAFTM, NATFM, empty
 */

$csvPath = "C:\\Users\\bertl\\OneDrive\\Desktop\\Taxi taxi driver (1).csv";
$sqlOut  = "C:\\xampp\\htdocs\\eurotaxisystem\\tmp\\update_driver_names.sql";

if (!file_exists($csvPath)) {
    die("ERROR: CSV not found: $csvPath\n");
}

$skip = ['NAD', 'VACANT', 'NAFTM', 'NATFM', ''];

function cleanName($n) {
    return trim($n);
}

function parseName($fullName) {
    // Format: "LastName, FirstName" or just a single name
    $parts = explode(',', $fullName, 2);
    if (count($parts) === 2) {
        return [trim($parts[0]), trim($parts[1])]; // [last, first]
    }
    return ['', trim($fullName)]; // [last, first]
}

function escape($s) {
    return addslashes($s);
}

$handle = fopen($csvPath, 'r');
fgetcsv($handle); // skip header

$sql = [];
$sql[] = "-- Driver Name Restoration SQL";
$sql[] = "-- Generated: " . date('Y-m-d H:i:s');
$sql[] = "";
$sql[] = "SET FOREIGN_KEY_CHECKS = 0;";
$sql[] = "";

$processedPlates = [];
$rowNum = 0;

while (($row = fgetcsv($handle)) !== false) {
    $rowNum++;
    if (count($row) < 4) continue;

    $setA  = cleanName($row[1]);
    $plate = strtoupper(trim($row[2]));
    $setB  = cleanName($row[3]);

    if (empty($plate)) continue;

    $sql[] = "-- Row $rowNum: Plate=$plate | SetA=$setA | SetB=$setB";

    // Build SQL for Set A name on driver linked via driver_id
    if (!in_array(strtoupper($setA), array_map('strtoupper', $skip)) && !empty($setA)) {
        [$lastA, $firstA] = parseName($setA);
        $plate_esc = escape($plate);
        $first_esc = escape($firstA);
        $last_esc  = escape($lastA);

        // Update driver_id's driver using unit->driver_id
        $sql[] = "UPDATE drivers d";
        $sql[] = "  INNER JOIN units u ON u.driver_id = d.id";
        $sql[] = "  SET d.first_name = '$first_esc', d.last_name = '$last_esc'";
        $sql[] = "  WHERE u.plate_number = '$plate_esc' AND u.deleted_at IS NULL;";
        $sql[] = "";
    }

    // Build SQL for Set B name on secondary_driver
    // Only if Set B is different from Set A (different person)
    if (!in_array(strtoupper($setB), array_map('strtoupper', $skip)) && !empty($setB) && strtolower($setA) !== strtolower($setB)) {
        [$lastB, $firstB] = parseName($setB);
        $plate_esc = escape($plate);
        $first_esc = escape($firstB);
        $last_esc  = escape($lastB);

        $sql[] = "UPDATE drivers d";
        $sql[] = "  INNER JOIN units u ON u.secondary_driver_id = d.id";
        $sql[] = "  SET d.first_name = '$first_esc', d.last_name = '$last_esc'";
        $sql[] = "  WHERE u.plate_number = '$plate_esc' AND u.deleted_at IS NULL;";
        $sql[] = "";
    }
}

fclose($handle);

$sql[] = "-- Verification";
$sql[] = "SELECT d.id, d.first_name, d.last_name, u.plate_number, 'primary' as role";
$sql[] = "  FROM drivers d INNER JOIN units u ON u.driver_id = d.id";
$sql[] = "  WHERE d.first_name IS NOT NULL ORDER BY u.plate_number LIMIT 20;";
$sql[] = "";
$sql[] = "SELECT COUNT(*) as total_with_names FROM drivers WHERE first_name IS NOT NULL AND first_name != '';";

file_put_contents($sqlOut, implode("\n", $sql));
echo "Generated SQL file: $sqlOut\n";
echo "Total SQL lines: " . count($sql) . "\n";
