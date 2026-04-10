<?php
$mysqlExe = "C:\\xampp\\mysql\\bin\\mysql.exe -u root eurotaxi -e ";
$plates = ['NGP 1877', 'NGQ 4918', 'UWN 226', 'VAA 9864', 'VFL 543'];

foreach ($plates as $p) {
    echo "Processing $p...\n";
    $exists = shell_exec($mysqlExe . "\"SELECT COUNT(*) FROM units WHERE plate_number = '$p';\"");
    if (strpos($exists, '1') !== false) {
        echo "Updating existing: $p\n";
        shell_exec($mysqlExe . "\"UPDATE units SET deleted_at = NULL, status = 'active' WHERE plate_number = '$p';\"");
    } else {
        echo "Inserting new: $p\n";
        shell_exec($mysqlExe . "\"INSERT INTO units (plate_number, unit_number, status, boundary_rate, created_at, updated_at) VALUES ('$p', '$p', 'active', 1200, NOW(), NOW());\"");
    }
}
echo "Active unit count: " . shell_exec($mysqlExe . "\"SELECT COUNT(*) FROM units WHERE deleted_at IS NULL;\"");
