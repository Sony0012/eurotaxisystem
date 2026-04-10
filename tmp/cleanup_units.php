<?php
/**
 * UNIT CLEANUP SCRIPT
 * 
 * Logic:
 * 1. Read all 91 unique plates from CSV.
 * 2. Find all units in DB.
 * 3. Soft-delete units in DB whose plate is NOT in the CSV list.
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

// 1. Get CSV Plates
$handle = fopen($csvPath, 'r');
fgetcsv($handle); // skip header
$csvPlates = [];
while (($row = fgetcsv($handle)) !== false) {
    if (count($row) >= 3 && !empty(trim($row[2]))) {
        $csvPlates[] = normalizePlate($row[2]);
    }
}
fclose($handle);
$csvPlates = array_unique($csvPlates);
echo "Unique Plate Count in CSV: " . count($csvPlates) . "\n";

// 2. Load DB Units
$unitsRaw = shell_exec("C:\\xampp\\mysql\\bin\\mysql.exe -u root eurotaxi -N -e \"SELECT id, plate_number FROM units;\"");
$deletedCount = 0;
foreach (explode("\n", trim($unitsRaw)) as $line) {
    if (empty($line)) continue;
    $parts = explode("\t", trim($line));
    if (count($parts) >= 2) {
        $id = $parts[0];
        $plate = normalizePlate($parts[1]);
        
        if (!in_array($plate, $csvPlates)) {
            echo "Archiving extra unit: Plate $plate (ID $id)\n";
            runSql("UPDATE units SET deleted_at = NOW() WHERE id = $id;");
            $deletedCount++;
        }
    }
}

echo "Total extra units archived: $deletedCount\n";
echo "Active unit count should now be: " . (99 - $deletedCount) . "\n";
runSql("SELECT COUNT(*) as active_units FROM units WHERE deleted_at IS NULL;");
