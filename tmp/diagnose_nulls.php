<?php
/**
 * Diagnose the 14 remaining NULL drivers.
 * 
 * Fetch all NULL driver license_numbers from DB via mysql CLI,
 * then brute-force compare against every name in the CSV using the same MD5 hash logic.
 */

$csvPath = "C:\\Users\\bertl\\OneDrive\\Desktop\\Taxi taxi driver (1).csv";
$mysqlExe = "C:\\xampp\\mysql\\bin\\mysql.exe";

// 1. Get null driver license numbers from DB
$cmd = "$mysqlExe -u root eurotaxi -N -e \"SELECT id, license_number FROM drivers WHERE (first_name IS NULL OR first_name = '') AND deleted_at IS NULL;\"";
$lines = [];
exec($cmd, $lines, $ret);

echo "=== NULL Drivers in DB ===\n";
$nullDrivers = [];
foreach ($lines as $line) {
    $parts = explode("\t", $line);
    if (count($parts) === 2) {
        $nullDrivers[$parts[0]] = trim($parts[1]);
        echo "Driver ID {$parts[0]}: license={$parts[1]}\n";
    }
}

echo "\n=== All CSV Names and Their MD5 Keys ===\n";

function makeKey($name) {
    $normalized = strtolower(str_replace([' ', '.', ','], '', trim($name)));
    return 'IMP-' . strtoupper(substr(md5($normalized), 0, 10));
}

$skip = ['NAD', 'VACANT', 'NAFTM', 'NATFM'];
$handle = fopen($csvPath, 'r');
fgetcsv($handle); // skip header

$csvNames = [];
while (($row = fgetcsv($handle)) !== false) {
    if (count($row) < 4) continue;
    foreach ([1, 3] as $col) {
        $name = trim($row[$col]);
        if (!empty($name) && !in_array(strtoupper($name), $skip)) {
            $key = strtolower(str_replace([' ', '.', ','], '', $name));
            if (!isset($csvNames[$key])) {
                $csvNames[$key] = ['name' => $name, 'hash' => makeKey($name)];
            }
        }
    }
}
fclose($handle);

// Build reverse lookup: hash => name
$hashToName = [];
foreach ($csvNames as $info) {
    $hashToName[$info['hash']] = $info['name'];
}

// Match null drivers
echo "\n=== Matching NULL Drivers to CSV Names ===\n";
foreach ($nullDrivers as $id => $license) {
    if (isset($hashToName[$license])) {
        echo "MATCH: Driver $id (license=$license) => '{$hashToName[$license]}'\n";
    } else {
        echo "NO MATCH: Driver $id (license=$license)\n";
    }
}

// Also dump all CSV hashes for comparison
echo "\n=== All CSV name hashes ===\n";
foreach ($csvNames as $info) {
    echo $info['hash'] . " => " . $info['name'] . "\n";
}
