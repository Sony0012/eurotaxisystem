<?php
/**
 * MISSING UNIT RESTORER
 * 
 * Logic:
 * 1. Get all 91 unique plates from CSV.
 * 2. Get all ACTIVE plates from DB.
 * 3. Find plates in CSV NOT in active DB.
 * 4. For those missing, try to restore a deleted unit with the same plate (fuzzy match).
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

// 2. Get currently active plates
$activeRaw = shell_exec("C:\\xampp\\mysql\\bin\\mysql.exe -u root eurotaxi -N -e \"SELECT plate_number FROM units WHERE deleted_at IS NULL;\"");
$activePlates = [];
foreach (explode("\n", trim($activeRaw)) as $line) {
    if (empty($line)) continue;
    $activePlates[] = normalizePlate($line);
}

// 3. Find missing
$missing = array_diff($csvPlates, $activePlates);
echo "Missing plates from active DB: " . count($missing) . "\n";
print_r($missing);

// 4. Try to restore from DELETED units
$deletedRaw = shell_exec("C:\\xampp\\mysql\\bin\\mysql.exe -u root eurotaxi -N -e \"SELECT id, plate_number FROM units WHERE deleted_at IS NOT NULL;\"");
$deletedUnits = [];
foreach (explode("\n", trim($deletedRaw)) as $line) {
    if (empty($line)) continue;
    $parts = explode("\t", trim($line));
    $deletedUnits[$parts[0]] = normalizePlate($parts[1]);
}

foreach ($missing as $mPlate) {
    echo "Attempting to restore plate $mPlate...\n";
    $restored = false;
    foreach ($deletedUnits as $id => $dPlate) {
        // Try exact first
        if ($dPlate === $mPlate) {
            runSql("UPDATE units SET deleted_at = NULL WHERE id = $id;");
            echo "  Successfully restored ID $id (Exact match $dPlate)\n";
            $restored = true;
            unset($deletedUnits[$id]);
            break;
        }
    }
    
    if (!$restored) {
        // Try fuzzy
        foreach ($deletedUnits as $id => $dPlate) {
            if (strpos($dPlate, $mPlate) !== false || strpos($mPlate, $dPlate) !== false) {
                runSql("UPDATE units SET deleted_at = NULL WHERE id = $id;");
                echo "  Successfully restored ID $id (Fuzzy match $dPlate for $mPlate)\n";
                $restored = true;
                unset($deletedUnits[$id]);
                break;
            }
        }
    }
}

// 5. Final Active Count
runSql("SELECT COUNT(*) as final_active FROM units WHERE deleted_at IS NULL;");
