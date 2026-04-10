<?php
/**
 * SMART Driver Name Restoration
 * Uses MD5-based license_number as the primary key to match CSV names to driver records.
 * The seeder created: license_number = 'IMP-' . strtoupper(substr(md5($name), 0, 10))
 * So we can hash each CSV name, build the same license_number, and match it exactly.
 * 
 * This bypasses all plate-number typo issues entirely.
 */

$csvPath = "C:\\Users\\bertl\\OneDrive\\Desktop\\Taxi taxi driver (1).csv";
$sqlOut  = "C:\\xampp\\htdocs\\eurotaxisystem\\tmp\\smart_update_names.sql";

if (!file_exists($csvPath)) {
    die("ERROR: CSV not found: $csvPath\n");
}

$skip = ['NAD', 'VACANT', 'NAFTM', 'NATFM', 'NAFTM ', 'NATFM '];

function cleanName($n) {
    return trim($n);
}

function parseName($fullName) {
    $parts = explode(',', $fullName, 2);
    if (count($parts) === 2) {
        return [trim($parts[0]), trim($parts[1])]; // [last, first]
    }
    // Handle "First Last" format without comma
    $words = explode(' ', trim($fullName));
    if (count($words) >= 2) {
        $last = array_pop($words);
        $first = implode(' ', $words);
        return [$last, $first];
    }
    return ['', trim($fullName)];
}

function makeKey($name) {
    $normalized = strtolower(str_replace([' ', '.', ','], '', trim($name)));
    return 'IMP-' . strtoupper(substr(md5($normalized), 0, 10));
}

function escape($s) {
    return str_replace(["'", "\\"], ["\\'", "\\\\"], $s);
}

$handle = fopen($csvPath, 'r');
fgetcsv($handle); // skip header

$sql = [];
$sql[] = "-- Smart Driver Name Restoration (MD5 Key Matching)";
$sql[] = "-- Generated: " . date('Y-m-d H:i:s');
$sql[] = "";
$sql[] = "SET FOREIGN_KEY_CHECKS = 0;";
$sql[] = "";

$processed = [];
$rowNum = 0;

while (($row = fgetcsv($handle)) !== false) {
    $rowNum++;
    if (count($row) < 4) continue;

    $setA  = cleanName($row[1]);
    $plate = strtoupper(trim($row[2]));
    $setB  = cleanName($row[3]);

    foreach (['A' => $setA, 'B' => $setB] as $set => $name) {
        if (empty($name) || in_array(strtoupper(trim($name)), array_map('strtoupper', $skip))) {
            continue;
        }

        // Avoid processing same person twice
        $key = strtolower(str_replace([' ', '.', ','], '', $name));
        if (isset($processed[$key])) continue;
        $processed[$key] = true;

        $licenseKey = makeKey($name);
        [$last, $first] = parseName($name);

        $first_esc   = escape($first);
        $last_esc    = escape($last);
        $license_esc = escape($licenseKey);

        $sql[] = "-- Set$set: '$name' => License=$licenseKey";
        $sql[] = "UPDATE drivers SET first_name='$first_esc', last_name='$last_esc'";
        $sql[] = "  WHERE license_number='$license_esc';";
        $sql[] = "";
    }
}

fclose($handle);

// Also handle the case where Set B == Set A (same driver), update secondary using plate
// But MD5 already handles them both since we skip duplicates above

$sql[] = "-- === VERIFICATION ===";
$sql[] = "SELECT d.id, d.first_name, d.last_name, d.license_number, u.plate_number";
$sql[] = "  FROM drivers d";
$sql[] = "  LEFT JOIN units u ON u.driver_id = d.id OR u.secondary_driver_id = d.id";
$sql[] = "  WHERE d.first_name IS NOT NULL AND d.first_name != ''";
$sql[] = "  ORDER BY d.last_name, d.first_name LIMIT 30;";
$sql[] = "";
$sql[] = "SELECT 'Total drivers' as label, COUNT(*) as count FROM drivers WHERE deleted_at IS NULL";
$sql[] = "UNION ALL";
$sql[] = "SELECT 'With names', COUNT(*) FROM drivers WHERE first_name IS NOT NULL AND first_name != '' AND deleted_at IS NULL";
$sql[] = "UNION ALL";
$sql[] = "SELECT 'Still null', COUNT(*) FROM drivers WHERE (first_name IS NULL OR first_name = '') AND deleted_at IS NULL;";

file_put_contents($sqlOut, implode("\n", $sql));
echo "Generated: $sqlOut\n";
echo "Total SQL statements: " . count(array_filter($sql, fn($l) => str_starts_with(trim($l), 'UPDATE'))) . " UPDATEs\n";
echo "Unique names processed: " . count($processed) . "\n";
