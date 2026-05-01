<?php
/**
 * Hostinger Database Connection Test
 * Upload this to your Hostinger to verify database connection
 */

// Include Laravel bootstrap
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>🔍 Euro Taxi System - Database Connection Test</h2>\n";

// Test 1: Database Connection
echo "<h3>1. Testing Database Connection...</h3>\n";
try {
    $pdo = DB::connection()->getPdo();
    echo "<span style='color: green;'>✅ Database Connection: SUCCESS</span><br>\n";
    echo "Database: " . DB::connection()->getDatabaseName() . "<br>\n";
    echo "Driver: " . DB::connection()->getDriverName() . "<br>\n";
} catch (Exception $e) {
    echo "<span style='color: red;'>❌ Database Connection: FAILED</span><br>\n";
    echo "Error: " . $e->getMessage() . "<br>\n";
}

// Test 2: Check Key Tables
echo "<h3>2. Checking Key Tables...</h3>\n";
$tables = ['users', 'units', 'drivers', 'maintenance', 'boundaries'];
foreach ($tables as $table) {
    try {
        $count = DB::table($table)->count();
        echo "<span style='color: green;'>✅ Table '$table': $count records</span><br>\n";
    } catch (Exception $e) {
        echo "<span style='color: red;'>❌ Table '$table': NOT FOUND</span><br>\n";
    }
}

// Test 3: Test Sample Query
echo "<h3>3. Testing Sample Query...</h3>\n";
try {
    $units = DB::table('units')
        ->leftJoin('drivers as d', 'units.driver_id', '=', 'd.id')
        ->select('units.plate_number', 'units.make', 'units.model', 
                DB::raw("CONCAT(COALESCE(d.first_name,''), ' ', COALESCE(d.last_name,'')) as driver_name"))
        ->whereNull('units.deleted_at')
        ->limit(5)
        ->get();
    
    echo "<span style='color: green;'>✅ Query Test: SUCCESS</span><br>\n";
    echo "<table border='1' style='border-collapse: collapse; margin-top: 10px;'>\n";
    echo "<tr><th>Plate Number</th><th>Vehicle</th><th>Driver</th></tr>\n";
    
    foreach ($units as $unit) {
        echo "<tr>\n";
        echo "<td>" . htmlspecialchars($unit->plate_number) . "</td>\n";
        echo "<td>" . htmlspecialchars($unit->make . ' ' . $unit->model) . "</td>\n";
        echo "<td>" . htmlspecialchars($unit->driver_name ?: 'Unassigned') . "</td>\n";
        echo "</tr>\n";
    }
    echo "</table>\n";
} catch (Exception $e) {
    echo "<span style='color: red;'>❌ Query Test: FAILED</span><br>\n";
    echo "Error: " . $e->getMessage() . "<br>\n";
}

// Test 4: Environment Check
echo "<h3>4. Environment Check...</h3>\n";
echo "App Environment: " . config('app.env') . "<br>\n";
echo "App Debug: " . (config('app.debug') ? 'ON' : 'OFF') . "<br>\n";
echo "App URL: " . config('app.url') . "<br>\n";

// Test 5: File Permissions
echo "<h3>5. File Permissions Check...</h3>\n";
$paths = ['storage', 'bootstrap/cache'];
foreach ($paths as $path) {
    if (is_writable($path)) {
        echo "<span style='color: green;'>✅ $path: WRITABLE</span><br>\n";
    } else {
        echo "<span style='color: orange;'>⚠️ $path: NOT WRITABLE (may cause issues)</span><br>\n";
    }
}

echo "<h3>🎉 Test Complete!</h3>\n";
echo "<p><strong>If all tests show green, your system is fully connected!</strong></p>\n";
echo "<p><small>Delete this file after testing for security.</small></p>\n";
?>
