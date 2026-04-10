<?php
/**
 * FINAL 91 UNITS ENSURER
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

// 1. Get ALL 91 unique plates from CSV with their original formatting
$handle = fopen($csvPath, 'r');
fgetcsv($handle);
$csvPlates = [];
while (($row = fgetcsv($handle)) !== false) {
    if (count($row) >= 3 && !empty(trim($row[2]))) {
        $norm = normalizePlate($row[2]);
        if (!isset($csvPlates[$norm])) {
            $csvPlates[$norm] = trim($row[2]);
        }
    }
}
fclose($handle);
echo "Unique Plate Count in CSV: " . count($csvPlates) . "\n";

// 2. Ensure each plate exists and is active in DB
foreach ($csvPlates as $norm => $original) {
    // Try to find by normalized plate in the DB
    $dbUnitRaw = shell_exec("C:\\xampp\\mysql\\bin\\mysql.exe -u root eurotaxi -N -e \"SELECT id, plate_number FROM units;\"");
    $foundId = null;
    foreach (explode("\n", trim($dbUnitRaw)) as $line) {
        if (empty($line)) continue;
        $parts = explode("\t", trim($line));
        if (normalizePlate($parts[1]) === $norm) {
            $foundId = $parts[0];
            break;
        }
    }
    
    if ($foundId) {
        echo "Updating existing unit ID $foundId for plate $original\n";
        runSql("UPDATE units SET deleted_at = NULL, status = 'active', plate_number = '$original' WHERE id = $foundId;");
    } else {
        echo "Creating new unit for plate $original\n";
        runSql("INSERT INTO units (plate_number, unit_number, status, boundary_rate, created_at, updated_at) VALUES ('$original', '$original', 'active', 1200, NOW(), NOW());");
    }
}

// 3. Archive any unit whose plate is NOT in the CSV 91 list
$dbFinalRaw = shell_exec("C:\\xampp\\mysql\\bin\\mysql.exe -u root eurotaxi -N -e \"SELECT id, plate_number FROM units WHERE deleted_at IS NULL;\"");
foreach (explode("\n", trim($dbFinalRaw)) as $line) {
    if (empty($line)) continue;
    $parts = explode("\t", trim($line));
    $id = $parts[0];
    $norm = normalizePlate($parts[1]);
    if (!isset($csvPlates[$norm])) {
        echo "Archiving extra unit ID $id (Plate {$parts[1]})\n";
        runSql("UPDATE units SET deleted_at = NOW() WHERE id = $id;");
    }
}

echo "Final verification:\n";
runSql("SELECT COUNT(*) as active_units FROM units WHERE deleted_at IS NULL;");
