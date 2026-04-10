<?php
/**
 * Targeted fix for the 14 remaining NULL-name drivers.
 * 
 * Strategy: Find what unit each null-driver is linked to (via driver_id / secondary_driver_id),
 * then fuzzy-match the unit's plate_number against CSV rows (handles typos in CSV).
 * Assign the name from CSV SetA (for primary) or SetB (for secondary).
 */

$csvPath  = "C:\\Users\\bertl\\OneDrive\\Desktop\\Taxi taxi driver (1).csv";
$mysqlExe = "C:\\xampp\\mysql\\bin\\mysql.exe";
$sqlOut   = "C:\\xampp\\htdocs\\eurotaxisystem\\tmp\\fix14_names.sql";

// 1. Get null-driver IDs and their linked unit plate numbers
$nullDriversQuery = "SELECT d.id as driver_id, d.license_number, "
    . "COALESCE((SELECT u.plate_number FROM units u WHERE u.driver_id = d.id AND u.deleted_at IS NULL LIMIT 1), '') as primary_plate, "
    . "COALESCE((SELECT u.plate_number FROM units u WHERE u.secondary_driver_id = d.id AND u.deleted_at IS NULL LIMIT 1), '') as secondary_plate "
    . "FROM drivers d WHERE (d.first_name IS NULL OR d.first_name = '') AND d.deleted_at IS NULL;";

$cmd = "$mysqlExe -u root eurotaxi -N -e \"$nullDriversQuery\"";
exec($cmd, $lines, $ret);

$nullDrivers = [];
foreach ($lines as $line) {
    $parts = explode("\t", trim($line));
    if (count($parts) >= 3) {
        $nullDrivers[] = [
            'id'            => $parts[0],
            'license'       => $parts[1],
            'primary_plate' => strtoupper(trim($parts[2])),
            'sec_plate'     => isset($parts[3]) ? strtoupper(trim($parts[3])) : '',
        ];
    }
}

echo "Found " . count($nullDrivers) . " null-name drivers:\n";
foreach ($nullDrivers as $d) {
    echo "  ID={$d['id']} primary_plate='{$d['primary_plate']}' sec_plate='{$d['sec_plate']}'\n";
}

// 2. Load CSV into memory: plate => [setA, setB]
$skip = ['NAD', 'VACANT', 'NAFTM', 'NATFM'];
$handle = fopen($csvPath, 'r');
fgetcsv($handle); // skip header
$csvRows = [];
while (($row = fgetcsv($handle)) !== false) {
    if (count($row) < 4) continue;
    $plate = strtoupper(trim(preg_replace('/\s+/', ' ', $row[2])));
    $setA  = trim($row[1]);
    $setB  = trim($row[3]);
    $csvRows[$plate] = ['setA' => $setA, 'setB' => $setB];
}
fclose($handle);

// 3. Fuzzy match: normalize plate (remove spaces) then match
function normPlate($p) { return preg_replace('/\s+/', '', strtoupper($p)); }

function parseName($fullName) {
    $parts = explode(',', $fullName, 2);
    if (count($parts) === 2) return [trim($parts[1]), trim($parts[0])]; // [first, last]
    return [trim($fullName), ''];
}

function escape($s) { return str_replace(["'", "\\"], ["\\'", "\\\\"], $s); }

$sql = [];
$sql[] = "-- Targeted fix for 14 null-name drivers";
$sql[] = "-- " . date('Y-m-d H:i:s');
$sql[] = "";

foreach ($nullDrivers as $d) {
    $driverId = $d['id'];
    $targetPlate = !empty($d['primary_plate']) ? $d['primary_plate'] : $d['sec_plate'];
    $role = !empty($d['primary_plate']) ? 'primary' : 'secondary';
    
    if (empty($targetPlate)) {
        $sql[] = "-- Driver $driverId: no linked unit, SKIPPING";
        continue;
    }
    
    $normTarget = normPlate($targetPlate);
    $match = null;
    $matchedName = null;
    
    // Try exact match first
    if (isset($csvRows[$targetPlate])) {
        $match = $csvRows[$targetPlate];
        $matchedName = ($role === 'primary') ? $match['setA'] : $match['setB'];
    } else {
        // Fuzzy match: compare normalized plate
        foreach ($csvRows as $csvPlate => $data) {
            if (normPlate($csvPlate) === $normTarget) {
                $match = $data;
                $matchedName = ($role === 'primary') ? $match['setA'] : $match['setB'];
                break;
            }
        }
        // If still no match, try prefix (first 7 chars)
        if (!$match && strlen($normTarget) >= 6) {
            foreach ($csvRows as $csvPlate => $data) {
                $n = normPlate($csvPlate);
                if (substr($n, 0, 6) === substr($normTarget, 0, 6)) {
                    $match = $data;
                    // If primary plate matches, use setA; else setB
                    $matchedName = ($role === 'primary') ? $match['setA'] : $match['setB'];
                    break;
                }
            }
        }
    }
    
    if ($match && $matchedName && !in_array(strtoupper($matchedName), $skip) && !empty($matchedName)) {
        [$first, $last] = parseName($matchedName);
        $first_esc = escape($first);
        $last_esc  = escape($last);
        $sql[] = "-- Driver $driverId (plate=$targetPlate, role=$role) => '$matchedName'";
        $sql[] = "UPDATE drivers SET first_name='$first_esc', last_name='$last_esc' WHERE id=$driverId;";
        $sql[] = "";
        echo "MATCHED: Driver $driverId (plate=$targetPlate) => $matchedName\n";
    } else {
        $sql[] = "-- Driver $driverId (plate=$targetPlate): NO MATCH FOUND in CSV";
        echo "NO MATCH: Driver $driverId (plate=$targetPlate)\n";
    }
}

$sql[] = "";
$sql[] = "SELECT 'Total drivers' as label, COUNT(*) as count FROM drivers WHERE deleted_at IS NULL";
$sql[] = "UNION ALL";
$sql[] = "SELECT 'With names', COUNT(*) FROM drivers WHERE first_name IS NOT NULL AND first_name != '' AND deleted_at IS NULL";
$sql[] = "UNION ALL";
$sql[] = "SELECT 'Still null', COUNT(*) FROM drivers WHERE (first_name IS NULL OR first_name = '') AND deleted_at IS NULL;";

file_put_contents($sqlOut, implode("\n", $sql));
echo "\nSQL saved: $sqlOut\n";
