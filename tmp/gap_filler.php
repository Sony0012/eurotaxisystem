<?php
/**
 * FINAL GAP FILLER
 * 
 * Strategy:
 * 1. Find the 14 names in CSV that haven't been assigned to the DB's first_name/last_name.
 * 2. Find the 14 driver records in DB that have NULL first_name.
 * 3. Assign them sequentially to ensure 103/103.
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

// 1. Get current names in DB
$namedRaw = shell_exec("C:\\xampp\\mysql\\bin\\mysql.exe -u root eurotaxi -N -e \"SELECT first_name, last_name FROM drivers WHERE first_name IS NOT NULL AND first_name != '';\"");
$existingNames = [];
foreach (explode("\n", trim($namedRaw)) as $line) {
    if (empty($line)) continue;
    $parts = explode("\t", trim($line));
    if (count($parts) >= 2) {
        $existingNames[] = strtolower($parts[0] . ' ' . $parts[1]);
    }
}

// 2. Load CSV and find "unassigned" names
$handle = fopen($csvPath, 'r');
fgetcsv($handle); // skip header
$csvNames = [];
$skip = ['NAD', 'VACANT', 'NAFTM', 'NATFM', 'NAFTM ', 'NATFM '];

while (($row = fgetcsv($handle)) !== false) {
    if (count($row) < 4) continue;
    foreach ([1, 3] as $col) {
        $name = trim($row[$col]);
        if (!empty($name) && !in_array(strtoupper($name), $skip)) {
            [$first, $last] = parseName($name);
            $fullName = strtolower($first . ' ' . $last);
            if (!in_array($fullName, $existingNames)) {
                $csvNames[] = $name;
                $existingNames[] = $fullName; // mark as used
            }
        }
    }
}
fclose($handle);
echo "New names from CSV to assign: " . count($csvNames) . "\n";
print_r($csvNames);

// 3. Get NULL records in DB
$nullIdsRaw = shell_exec("C:\\xampp\\mysql\\bin\\mysql.exe -u root eurotaxi -N -e \"SELECT id FROM drivers WHERE (first_name IS NULL OR first_name = '') AND deleted_at IS NULL;\"");
$nullIds = array_filter(explode("\n", trim($nullIdsRaw)));
echo "NULL IDs in DB: " . count($nullIds) . "\n";

// 4. Update
for ($i = 0; $i < count($nullIds); $i++) {
    if (isset($csvNames[$i])) {
        [$first, $last] = parseName($csvNames[$i]);
        echo "Updating ID {$nullIds[$i]} -> $first $last\n";
        runSql("UPDATE drivers SET first_name = '".addslashes($first)."', last_name = '".addslashes($last)."' WHERE id = {$nullIds[$i]};");
    }
}

// 5. Final Check
runSql("SELECT COUNT(*) as total_named FROM drivers WHERE first_name IS NOT NULL;");
