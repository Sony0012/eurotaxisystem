<?php
/**
 * FINAL DISPATCH SCRIPT
 * 
 * Logic:
 * 1. Read CSV row-by-row.
 * 2. Match Plate to DB Unit.
 * 3. Match Names (Set A, Set B) to DB Drivers (First Last).
 * 4. Update units.driver_id and units.secondary_driver_id.
 */

$csvPath = "C:\\Users\\bertl\\OneDrive\\Desktop\\Taxi taxi driver (1).csv";
$mysqlExe = "C:\\xampp\\mysql\\bin\\mysql.exe -u root eurotaxi -e ";

function runSql($sql) {
    global $mysqlExe;
    $cmd = $mysqlExe . '"' . str_replace('"', '\"', $sql) . '"';
    return shell_exec($cmd);
}

function normalizePlate($p) {
    return strtoupper(preg_replace('/\s+/', '', trim($p)));
}

function parseName($fullName) {
    if (empty($fullName)) return ['', ''];
    $fullName = trim($fullName);
    $parts = explode(',', $fullName, 2);
    if (count($parts) === 2) {
        return [trim($parts[1]), trim($parts[0])]; // [first, last]
    }
    $words = explode(' ', trim($fullName));
    if (count($words) >= 2) {
        $last = array_pop($words);
        $first = implode(' ', $words);
        return [$first, $last];
    }
    return [trim($fullName), ''];
}

// 1. Load Drivers from DB for fast lookup
echo "Loading drivers...\n";
$driversRaw = shell_exec("C:\\xampp\\mysql\\bin\\mysql.exe -u root eurotaxi -N -e \"SELECT id, first_name, last_name FROM drivers WHERE first_name IS NOT NULL;\"");
$drivers = [];
foreach (explode("\n", trim($driversRaw)) as $line) {
    if (empty($line)) continue;
    $parts = explode("\t", $line);
    if (count($parts) >= 3) {
        $key = strtolower(trim($parts[1]) . ' ' . trim($parts[2]));
        $drivers[$key] = $parts[0];
    }
}
echo "Drivers loaded: " . count($drivers) . "\n";

// 2. Load Units
echo "Loading units...\n";
$unitsRaw = shell_exec("C:\\xampp\\mysql\\bin\\mysql.exe -u root eurotaxi -N -e \"SELECT id, plate_number FROM units;\"");
$dbUnits = [];
foreach (explode("\n", trim($unitsRaw)) as $line) {
    if (empty($line)) continue;
    $parts = explode("\t", $line);
    $dbUnits[normalizePlate($parts[1])] = $parts[0];
}
echo "Units loaded: " . count($dbUnits) . "\n";

// 3. Process CSV
echo "Dispatching...\n";
$handle = fopen($csvPath, 'r');
fgetcsv($handle); // skip header
$skipNames = ['NAD', 'VACANT', 'NAFTM', 'NATFM', 'NAFTM ', 'NATFM '];
$dispatchCount = 0;

while (($row = fgetcsv($handle)) !== false) {
    if (count($row) < 4) continue;
    $pPlate = normalizePlate($row[2]);
    if (!isset($dbUnits[$pPlate])) continue;
    
    $unitId = $dbUnits[$pPlate];
    $setA = trim($row[1]);
    $setB = trim($row[3]);
    
    $driverId = "NULL";
    $secondaryId = "NULL";
    
    if (!empty($setA) && !in_array(strtoupper($setA), $skipNames)) {
        [$f, $l] = parseName($setA);
        $nameKey = strtolower($f . ' ' . $l);
        if (isset($drivers[$nameKey])) $driverId = $drivers[$nameKey];
    }
    
    if (!empty($setB) && !in_array(strtoupper($setB), $skipNames)) {
        [$f, $l] = parseName($setB);
        $nameKey = strtolower($f . ' ' . $l);
        if (isset($drivers[$nameKey])) $secondaryId = $drivers[$nameKey];
    }
    
    echo "Unit $pPlate: Driver=$driverId, Secondary=$secondaryId\n";
    runSql("UPDATE units SET driver_id = $driverId, secondary_driver_id = $secondaryId WHERE id = $unitId;");
    $dispatchCount++;
}
fclose($handle);

echo "Total dispatched units: $dispatchCount\n";
echo "Active Drivers stats:\n";
runSql("SELECT COUNT(*) as active_drivers FROM drivers WHERE id IN (SELECT driver_id FROM units WHERE deleted_at IS NULL) OR id IN (SELECT secondary_driver_id FROM units WHERE deleted_at IS NULL);");
