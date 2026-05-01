<?php
/**
 * Hostinger Real-Time Features Test
 * Test if real-time features work on Hostinger
 */

echo "<h2>🔍 Euro Taxi System - Real-Time Features Test</h2>\n";

// Test 1: Check JavaScript Files
echo "<h3>1. Checking Real-Time JavaScript Files...</h3>\n";
$js_files = [
    'public/js/realtime-dashboard.js' => 'Dashboard Real-Time Updates',
    'public/js/realtime-tracking.js' => 'GPS Live Tracking',
    'resources/js/app.js' => 'Main App JavaScript'
];

foreach ($js_files as $file => $description) {
    if (file_exists($file)) {
        echo "<span style='color: green;'>✅ $description: File exists</span><br>\n";
    } else {
        echo "<span style='color: red;'>❌ $description: File missing</span><br>\n";
    }
}

// Test 2: Check API Routes
echo "<h3>2. Checking Real-Time API Routes...</h3>\n";
$api_routes = [
    '/api/dashboard/realtime' => 'Real-Time Dashboard Data',
    '/live-tracking/units-live' => 'Live GPS Tracking',
    '/api/revenue-trend' => 'Revenue Trend Data'
];

foreach ($api_routes as $route => $description) {
    echo "<span style='color: blue;'>🔗 $description: $route</span><br>\n";
}

// Test 3: Check Database Tables for Real-Time Data
echo "<h3>3. Checking Real-Time Database Tables...</h3>\n";

// Include Laravel bootstrap
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$realtime_tables = [
    'gps_tracking' => 'GPS Location Data',
    'gps_devices' => 'GPS Device Management',
    'units' => 'Vehicle Units',
    'drivers' => 'Driver Information'
];

foreach ($realtime_tables as $table => $description) {
    try {
        $count = DB::table($table)->count();
        echo "<span style='color: green;'>✅ $description: $count records</span><br>\n";
    } catch (Exception $e) {
        echo "<span style='color: red;'>❌ $description: Table not found</span><br>\n";
    }
}

// Test 4: Check Tracksolid Service
echo "<h3>4. Checking Tracksolid GPS Service...</h3>\n";
try {
    $service = new App\Services\TracksolidService();
    echo "<span style='color: green;'>✅ Tracksolid Service: Available</span><br>\n";
    
    // Test API connection (optional - may cause timeout)
    echo "<span style='color: orange;'>⚠️ API Test: Skipped (may timeout)</span><br>\n";
} catch (Exception $e) {
    echo "<span style='color: red;'>❌ Tracksolid Service: " . $e->getMessage() . "</span><br>\n";
}

// Test 5: Check Live Tracking Controller
echo "<h3>5. Checking Live Tracking Controller...</h3>\n";
try {
    $controller = new App\Http\Controllers\LiveTrackingController(
        new App\Services\TracksolidService(),
        new App\Services\CodingService()
    );
    echo "<span style='color: green;'>✅ Live Tracking Controller: Available</span><br>\n";
} catch (Exception $e) {
    echo "<span style='color: red;'>❌ Live Tracking Controller: " . $e->getMessage() . "</span><br>\n";
}

echo "<h3>6. Real-Time Features Status</h3>\n";

// Check if real-time features are properly configured
$checks = [
    'APP_ENV' => config('app.env'),
    'APP_DEBUG' => config('app.debug') ? 'true' : 'false',
    'CACHE_DRIVER' => config('cache.default'),
    'SESSION_DRIVER' => config('session.driver'),
];

foreach ($checks as $key => $value) {
    echo "<strong>$key:</strong> $value<br>\n";
}

echo "<h3>🎯 Recommendations for Hostinger</h3>\n";
echo "<ul style='background: #f5f5f5; padding: 15px; border-radius: 5px;'>\n";
echo "<li><strong>Dashboard Updates:</strong> Should work - uses JavaScript polling</li>\n";
echo "<li><strong>GPS Tracking:</strong> Depends on Tracksolid API connection</li>\n";
echo "<li><strong>Live Map:</strong> Requires internet connection for map tiles</li>\n";
echo "<li><strong>Performance:</strong> Consider increasing polling interval</li>\n";
echo "<li><strong>Caching:</strong> Enable Redis if available on Hostinger</li>\n";
echo "</ul>\n";

echo "<h3>🔧 Manual Test Steps</h3>\n";
echo "<ol style='background: #e8f4fd; padding: 15px; border-radius: 5px;'>\n";
echo "<li>Open your website dashboard</li>\n";
echo "<li>Watch for auto-updating statistics (every 5 seconds)</li>\n";
echo "<li>Go to Live Tracking page</li>\n";
echo "<li>Check if map loads and shows vehicle positions</li>\n";
echo "<li>Test search and filter functionality</li>\n";
echo "<li>Check browser console for JavaScript errors</li>\n";
echo "</ol>\n";

echo "<h3>🚨 Common Issues & Solutions</h3>\n";
echo "<ul style='background: #fff3cd; padding: 15px; border-radius: 5px;'>\n";
echo "<li><strong>JavaScript Errors:</strong> Check browser console (F12)</li>\n";
echo "<li><strong>API Timeouts:</strong> Increase polling interval to 10-15 seconds</li>\n";
echo "<li><strong>GPS Not Updating:</strong> Check Tracksolid API credentials</li>\n";
echo "<li><strong>Map Not Loading:</strong> Check internet connection</li>\n";
echo "<li><strong>Slow Performance:</strong> Enable caching on Hostinger</li>\n";
echo "</ul>\n";

echo "<p><small>Delete this file after testing for security.</small></p>\n";
?>
