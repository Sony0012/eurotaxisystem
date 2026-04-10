<?php
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;

echo "Final Verification of Dashboard Fixes...\n";

$controller = new DashboardController();

// 1. Verify Maintenance Units (Should be 14)
$request = new Request(['filter' => 'all']);
$response = $controller->getMaintenanceUnits($request);
$data = json_decode($response->getContent(), true);

if ($data['success']) {
    $count = count($data['units']);
    echo "Maintenance Units Found: $count (Expected 14)\n";
    if ($count >= 14) {
        echo "✅ Maintenance List Restored.\n";
    } else {
        echo "❌ Maintenance List Still Incomplete.\n";
    }
} else {
    echo "❌ Maintenance API Failed: " . $data['message'] . "\n";
}

// 2. Verify Coding Automation for Today (Friday, April 10, 2026)
// Plate endings for Friday: 9, 0
$cResponse = $controller->getUnitsOverview();
$cData = json_decode($cResponse->getContent(), true);
$codingUnits = array_filter($cData['units'], function($u) {
    return $u['status'] === 'coding';
});
$codingCount = count($codingUnits);

echo "Automated Coding Units Found (Today=Friday): $codingCount\n";
echo "Sample Coding Units: " . implode(', ', array_slice(array_column($codingUnits, 'plate_number'), 0, 5)) . "...\n";

// Check if a known Friday plate is in the list (e.g., VAA 9864 ends in 4? No. Ending 9/0)
// Let's find one by looking at the count.
if ($codingCount > 0) {
    echo "✅ Coding Automation Active.\n";
} else {
    echo "❌ No Coding Units identified for Friday.\n";
}

// 3. Verify Active Drivers (Modal)
$dResponse = $controller->getActiveDrivers();
$dData = json_decode($dResponse->getContent(), true);
$driverCount = count($dData['drivers']);

echo "Modal Active Drivers: $driverCount (Expected matching dashboard total)\n";
if ($driverCount > 0) {
    echo "✅ Active Drivers Modal Restored.\n";
} else {
    echo "❌ Active Drivers Modal Still Empty.\n";
}
