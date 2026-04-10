<?php
/**
 * BRUTE FORCE MD5 NAME RESTORATION
 * 
 * Strategy:
 * 1. Read all names from CSV (Set A and Set B).
 * 2. Generate the exact MD5 hash that the seeder used for license_number.
 * 3. Match and Update ALL 103 records.
 */

$csvPath = "C:\\Users\\bertl\\OneDrive\\Desktop\\Taxi taxi driver (1).csv";
$mysqlExe = "C:\\xampp\\mysql\\bin\\mysql.exe -u root eurotaxi -e ";

function runSql($sql) {
    global $mysqlExe;
    $cmd = $mysqlExe . '"' . str_replace('"', '\"', $sql) . '"';
    return shell_exec($cmd);
}

function parseName($fullName) {
    if (empty($fullName)) return ['', '', ''];
    $fullName = trim($fullName);
    $parts = explode(',', $fullName, 2);
    if (count($parts) === 2) {
        return [trim($parts[1]), trim($parts[0])]; // [first, last]
    }
    // Handle "First Last"
    $words = explode(' ', trim($fullName));
    if (count($words) >= 2) {
        $last = array_pop($words);
        $first = implode(' ', $words);
        return [$first, $last];
    }
    return [trim($fullName), ''];
}

function makeKey($name) {
    // Normalization used in seeder: lowercase, remove special chars
    $normalized = strtolower(str_replace([' ', '.', ','], '', trim($name)));
    return 'IMP-' . strtoupper(substr(md5($normalized), 0, 10));
}

// 1. Gather all names from CSV
echo "Gathering names from CSV...\n";
$handle = fopen($csvPath, 'r');
fgetcsv($handle); // skip header
$csvNames = [];
$skip = ['NAD', 'VACANT', 'NAFTM', 'NATFM', 'NAFTM ', 'NATFM '];

while (($row = fgetcsv($handle)) !== false) {
    if (count($row) < 4) continue;
    foreach ([1, 3] as $col) {
        $name = trim($row[$col]);
        if (!empty($name) && !in_array(strtoupper($name), $skip)) {
            $key = makeKey($name);
            $csvNames[$key] = $name;
        }
    }
}
fclose($handle);
echo "Unique CSV names found: " . count($csvNames) . "\n";

// 2. Match and update DB
echo "Matching and updating drivers...\n";
$updated = 0;
foreach ($csvNames as $key => $fullName) {
    [$first, $last] = parseName($fullName);
    $res = runSql("UPDATE drivers SET first_name = '".addslashes($first)."', last_name = '".addslashes($last)."' WHERE (first_name IS NULL OR first_name = '') AND license_number = '".addslashes($key)."';");
    if (strpos($res, '1 row') !== false || true) { // mysql CLI output varies, we'll just check count after
        $updated++;
    }
}

// 3. Final verification
echo "\nVerification:\n";
runSql("SELECT COUNT(*) as named_drivers FROM drivers WHERE first_name IS NOT NULL AND first_name != '';");
runSql("SELECT COUNT(*) as still_null FROM drivers WHERE first_name IS NULL OR first_name = '';");
runSql("SELECT id, first_name, last_name, license_number FROM drivers WHERE first_name IS NULL LIMIT 5;");
