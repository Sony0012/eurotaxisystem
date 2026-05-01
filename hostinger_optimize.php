<?php
/**
 * Hostinger Optimization Script
 * Run this after deployment to optimize performance
 */

echo "<h2>🚀 Hostinger Optimization Script</h2>\n";

// Include Laravel bootstrap
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h3>Clearing Caches...</h3>\n";

// Clear application cache
try {
    Artisan::call('cache:clear');
    echo "<span style='color: green;'>✅ Application Cache Cleared</span><br>\n";
} catch (Exception $e) {
    echo "<span style='color: red;'>❌ Cache Clear Failed: " . $e->getMessage() . "</span><br>\n";
}

// Clear configuration cache
try {
    Artisan::call('config:clear');
    echo "<span style='color: green;'>✅ Configuration Cache Cleared</span><br>\n";
} catch (Exception $e) {
    echo "<span style='color: red;'>❌ Config Clear Failed: " . $e->getMessage() . "</span><br>\n";
}

// Clear route cache
try {
    Artisan::call('route:clear');
    echo "<span style='color: green;'>✅ Route Cache Cleared</span><br>\n";
} catch (Exception $e) {
    echo "<span style='color: red;'>❌ Route Clear Failed: " . $e->getMessage() . "</span><br>\n";
}

// Clear view cache
try {
    Artisan::call('view:clear');
    echo "<span style='color: green;'>✅ View Cache Cleared</span><br>\n";
} catch (Exception $e) {
    echo "<span style='color: red;'>❌ View Clear Failed: " . $e->getMessage() . "</span><br>\n";
}

// Optimize for production
echo "<h3>Optimizing for Production...</h3>\n";

try {
    Artisan::call('config:cache');
    echo "<span style='color: green;'>✅ Configuration Cached</span><br>\n";
} catch (Exception $e) {
    echo "<span style='color: red;'>❌ Config Cache Failed: " . $e->getMessage() . "</span><br>\n";
}

try {
    Artisan::call('route:cache');
    echo "<span style='color: green;'>✅ Routes Cached</span><br>\n";
} catch (Exception $e) {
    echo "<span style='color: red;'>❌ Route Cache Failed: " . $e->getMessage() . "</span><br>\n";
}

try {
    Artisan::call('view:cache');
    echo "<span style='color: green;'>✅ Views Cached</span><br>\n";
} catch (Exception $e) {
    echo "<span style='color: red;'>❌ View Cache Failed: " . $e->getMessage() . "</span><br>\n";
}

echo "<h3>🎉 Optimization Complete!</h3>\n";
echo "<p><strong>Your Euro Taxi System is now optimized for Hostinger!</strong></p>\n";
echo "<p><small>Delete this file after running for security.</small></p>\n";
?>
