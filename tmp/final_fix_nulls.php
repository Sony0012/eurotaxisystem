<?php
/**
 * Final pass: handle remaining 12 null-name drivers.
 * 
 * For each null driver:
 * 1. If linked to a unit -> fuzzy match plate to CSV, assign name
 * 2. If NOT linked to any unit -> these are phantom records (from 'NAFTM ' etc.)
 *    We can either: soft-delete them, or set a placeholder name.
 *    We'll soft-delete them to keep the DB clean.
 */

$csvPath  = "C:\\Users\\bertl\\OneDrive\\Desktop\\Taxi taxi driver (1).csv";
$mysqlExe = "C:\\xampp\\mysql\\bin\\mysql.exe";
$sqlOut   = "C:\\xampp\\htdocs\\eurotaxisystem\\tmp\\final_fix_nulls.sql";

// Get null drivers with their unit info
$q = "SELECT d.id, d.license_number, "
   . "COALESCE((SELECT u.plate_number FROM units u WHERE u.driver_id = d.id AND u.deleted_at IS NULL LIMIT 1),'') as pp, "
   . "COALESCE((SELECT u.plate_number FROM units u WHERE u.secondary_driver_id = d.id AND u.deleted_at IS NULL LIMIT 1),'') as sp "
   . "FROM drivers d WHERE (d.first_name IS NULL OR d.first_name='') AND d.deleted_at IS NULL";

exec("$mysqlExe -u root eurotaxi -N -e \"$q\"", $lines);

$skip = ['NAD','VACANT','NAFTM','NATFM'];

// Load CSV
$handle = fopen($csvPath, 'r');
fgetcsv($handle);
$csvRows = [];
while (($row = fgetcsv($handle)) !== false) {
    if (count($row) < 4) continue;
    $plate = strtoupper(preg_replace('/\s+/', ' ', trim($row[2])));
    $csvRows[$plate] = ['setA' => trim($row[1]), 'setB' => trim($row[3])];
}
fclose($handle);

function normPlate($p) { return preg_replace('/\s+/', '', strtoupper($p)); }
function parseName($n) {
    $parts = explode(',', $n, 2);
    return count($parts) === 2 ? [trim($parts[1]), trim($parts[0])] : [trim($n), ''];
}
function esc($s) { return str_replace(["'","\\"], ["\\'","\\\\"], $s); }

$sql = [];
$sql[] = "-- Final null driver fix - " . date('Y-m-d H:i:s');
$sql[] = "";

$softDeleteIds = [];
$updateCount   = 0;

foreach ($lines as $line) {
    $parts = explode("\t", trim($line));
    if (count($parts) < 4) continue;
    
    [$id, $license, $pp, $sp] = $parts;
    $pp = strtoupper(trim($pp));
    $sp = strtoupper(trim($sp));
    
    $targetPlate = !empty($pp) ? $pp : $sp;
    $role = !empty($pp) ? 'setA' : 'setB';
    
    if (empty($targetPlate)) {
        // No unit linked — phantom record, soft-delete it
        $softDeleteIds[] = $id;
        $sql[] = "-- Driver $id: no unit linked, soft-deleting";
        $sql[] = "UPDATE drivers SET deleted_at=NOW() WHERE id=$id;";
        $sql[] = "";
        echo "SOFT DELETE: Driver $id (no unit)\n";
        continue;
    }
    
    // Fuzzy match
    $normTarget = normPlate($targetPlate);
    $matchedName = null;
    
    if (isset($csvRows[$targetPlate])) {
        $matchedName = $csvRows[$targetPlate][$role];
    } else {
        foreach ($csvRows as $csvPlate => $data) {
            $nc = normPlate($csvPlate);
            // Full normalized match or 6-char prefix fuzzy
            if ($nc === $normTarget || (strlen($nc)>=6 && substr($nc,0,6)===substr($normTarget,0,6))) {
                $matchedName = $data[$role];
                break;
            }
        }
    }
    
    if ($matchedName && !in_array(strtoupper($matchedName), $skip) && !empty(trim($matchedName))) {
        [$first, $last] = parseName($matchedName);
        $sql[] = "-- Driver $id (plate=$targetPlate, role=$role) => '$matchedName'";
        $sql[] = "UPDATE drivers SET first_name='" . esc($first) . "', last_name='" . esc($last) . "' WHERE id=$id;";
        $sql[] = "";
        $updateCount++;
        echo "UPDATE: Driver $id => $first $last\n";
    } else {
        // Has a unit but no matching CSV row - use UNKNOWN placeholder
        $sql[] = "-- Driver $id (plate=$targetPlate): no CSV match, using plate as identifier";
        $sql[] = "UPDATE drivers SET first_name='Driver', last_name='[$targetPlate]' WHERE id=$id;";
        $sql[] = "";
        echo "PLACEHOLDER: Driver $id => Driver [$targetPlate]\n";
    }
}

$sql[] = "";
$sql[] = "-- Final count verification";
$sql[] = "SELECT 'Total active drivers' as label, COUNT(*) as count FROM drivers WHERE deleted_at IS NULL";
$sql[] = "UNION ALL SELECT 'With names', COUNT(*) FROM drivers WHERE first_name IS NOT NULL AND first_name != '' AND deleted_at IS NULL";
$sql[] = "UNION ALL SELECT 'Still null', COUNT(*) FROM drivers WHERE (first_name IS NULL OR first_name='') AND deleted_at IS NULL;";
$sql[] = "";
$sql[] = "-- Sample of restored drivers";
$sql[] = "SELECT d.id, d.first_name, d.last_name, u.plate_number FROM drivers d";
$sql[] = "LEFT JOIN units u ON u.driver_id = d.id WHERE d.first_name IS NOT NULL";
$sql[] = "ORDER BY d.last_name LIMIT 20;";

file_put_contents($sqlOut, implode("\n", $sql));
echo "\nSQL file saved: $sqlOut\n";
echo "Updates: $updateCount, Soft deletes: " . count($softDeleteIds) . "\n";
