<?php
/**
 * FINAL SUPER ROBUST RESTORATION SCRIPT
 * 
 * Logic:
 * 1. Restore all drivers and units (remove soft deletes).
 * 2. Clear first_name, last_name, nickname from ALL drivers to start fresh.
 * 3. Read CSV row by row.
 * 4. Match CSV plate to DB units.
 * 5. Update the driver records currently linked to that unit (driver_id and secondary_driver_id)
 *    with the names from Set A and Set B.
 */

$csvPath = "C:\\Users\\bertl\\OneDrive\\Desktop\\Taxi taxi driver (1).csv";
$mysqlExe = "C:\\xampp\\mysql\\bin\\mysql.exe -u root eurotaxi -e ";

function runSql($sql) {
    global $mysqlExe;
    $cmd = $mysqlExe . '"' . str_replace('"', '\"', $sql) . '"';
    echo "Running: $sql\n";
    return shell_exec($cmd);
}

function parseName($fullName) {
    if (empty($fullName)) return ['', '', ''];
    $parts = explode(',', $fullName, 2);
    if (count($parts) === 2) {
        $last = trim($parts[0]);
        $first = trim($parts[1]);
        return [$first, $last, ''];
    }
    // Handle "First Last"
    $words = explode(' ', trim($fullName));
    if (count($words) >= 2) {
        $last = array_pop($words);
        $first = implode(' ', $words);
        return [$first, $last, ''];
    }
    return [trim($fullName), '', ''];
}

function normalizePlate($p) {
    return strtoupper(preg_replace('/\s+/', '', $p));
}

// 1. Restore records
echo "Restoring all records...\n";
runSql("UPDATE drivers SET deleted_at = NULL;");
runSql("UPDATE units SET deleted_at = NULL;");

// 2. Clear old names
echo "Clearing old name data...\n";
runSql("UPDATE drivers SET first_name = NULL, last_name = NULL, nickname = NULL;");

// 3. Load units from DB
echo "Fetching units from DB...\n";
$unitsRaw = shell_exec("C:\\xampp\\mysql\\bin\\mysql.exe -u root eurotaxi -N -e \"SELECT id, plate_number, driver_id, secondary_driver_id FROM units;\"");
$dbUnits = [];
foreach (explode("\n", trim($unitsRaw)) as $line) {
    if (empty($line)) continue;
    $parts = explode("\t", $line);
    if (count($parts) >= 4) {
        $plate = normalizePlate($parts[1]);
        $dbUnits[$plate] = [
            'id' => $parts[0],
            'driver_id' => $parts[2],
            'secondary_driver_id' => $parts[3]
        ];
    }
}

// 4. Process CSV
echo "Processing CSV...\n";
$handle = fopen($csvPath, 'r');
fgetcsv($handle); // skip header

$skipNames = ['NAD', 'VACANT', 'NAFTM', 'NATFM', 'NAFTM ', 'NATFM '];
$updatedDriverIds = [];

while (($row = fgetcsv($handle)) !== false) {
    if (count($row) < 4) continue;

    $setA = trim($row[1]);
    $plateRaw = trim($row[2]);
    $setB = trim($row[3]);
    
    $plateNorm = normalizePlate($plateRaw);
    
    if (isset($dbUnits[$plateNorm])) {
        $unit = $dbUnits[$plateNorm];
        echo "Match: Plate $plateRaw ($plateNorm) found in DB (ID {$unit['id']})\n";

        // Set A -> Driver
        if (!empty($setA) && !in_array(strtoupper($setA), $skipNames)) {
            [$first, $last] = parseName($setA);
            $dId = $unit['driver_id'];
            if ($dId && $dId != 0 && !isset($updatedDriverIds[$dId])) {
                runSql("UPDATE drivers SET first_name = '".addslashes($first)."', last_name = '".addslashes($last)."' WHERE id = $dId;");
                $updatedDriverIds[$dId] = $setA;
                echo "  Set A: Updated Driver ID $dId -> $first $last\n";
            }
        }

        // Set B -> Secondary Driver
        if (!empty($setB) && !in_array(strtoupper($setB), $skipNames)) {
            [$first, $last] = parseName($setB);
            $dId = $unit['secondary_driver_id'];
            if ($dId && $dId != 0 && !isset($updatedDriverIds[$dId])) {
                runSql("UPDATE drivers SET first_name = '".addslashes($first)."', last_name = '".addslashes($last)."' WHERE id = $dId;");
                $updatedDriverIds[$dId] = $setB;
                echo "  Set B: Updated Driver ID $dId -> $first $last\n";
            }
        }
    } else {
        // Try fuzzy match if exact plate fails
        foreach ($dbUnits as $dbPlate => $data) {
            if (strpos($dbPlate, $plateNorm) !== false || strpos($plateNorm, $dbPlate) !== false) {
                $unit = $data;
                echo "Fuzzy Match: Plate $plateRaw matched with DB Plate $dbPlate\n";
                
                // Set A -> Driver
                if (!empty($setA) && !in_array(strtoupper($setA), $skipNames)) {
                    [$first, $last] = parseName($setA);
                    $dId = $unit['driver_id'];
                    if ($dId && $dId != 0 && !isset($updatedDriverIds[$dId])) {
                        runSql("UPDATE drivers SET first_name = '".addslashes($first)."', last_name = '".addslashes($last)."' WHERE id = $dId;");
                        $updatedDriverIds[$dId] = $setA;
                    }
                }
                break;
            }
        }
    }
}
fclose($handle);

// 5. Final verification
echo "\nFinal Verification:\n";
echo "Total drivers updated: " . count($updatedDriverIds) . "\n";
echo "Querying status...\n";
runSql("SELECT COUNT(*) as total_drivers FROM drivers;");
runSql("SELECT COUNT(*) as active_drivers FROM drivers WHERE deleted_at IS NULL;");
runSql("SELECT COUNT(*) as named_drivers FROM drivers WHERE first_name IS NOT NULL;");
runSql("SELECT id, first_name, last_name FROM drivers WHERE first_name IS NOT NULL LIMIT 10;");
