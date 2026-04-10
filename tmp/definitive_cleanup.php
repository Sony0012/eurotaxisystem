<?php
/**
 * DEFINITIVE UNIT CLEANUP
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

// 1. Restore everything to start from a clean slate
runSql("UPDATE units SET deleted_at = NULL;");

// 2. Get CSV Plates
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
echo "Target unique plates: " . count($csvPlates) . "\n";

// 3. Process DB Units
$unitsRaw = shell_exec("C:\\xampp\\mysql\\bin\\mysql.exe -u root eurotaxi -N -e \"SELECT id, plate_number FROM units;\"");
$activeCount = 0;
$archivedCount = 0;
$seenPlates = [];

foreach (explode("\n", trim($unitsRaw)) as $line) {
    if (empty($line)) continue;
    $parts = explode("\t", trim($line));
    $id = $parts[0];
    $plate = normalizePlate($parts[1]);
    
    // If it's a valid plate from CSV AND we haven't activated a unit for it yet
    if (in_array($plate, $csvPlates) && !isset($seenPlates[$plate])) {
        // Keep active
        $seenPlates[$plate] = $id;
        $activeCount++;
    } else {
        // Archive
        runSql("UPDATE units SET deleted_at = NOW() WHERE id = $id;");
        $archivedCount++;
    }
}

echo "Final Active Count: $activeCount (Target: 91)\n";
echo "Archived Count: $archivedCount\n";
