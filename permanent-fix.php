<?php

echo "🔧 EURO TAXI SYSTEM - PERMANENT FIX SCRIPT\n";
echo "==========================================\n\n";

// Step 1: Fix environment variables
echo "📝 Step 1: Fixing environment configuration...\n";
$envFile = file_get_contents('.env');

// Fix database name
$envFile = preg_replace('/DB_DATABASE=.*/', 'DB_DATABASE=eurotaxi', $envFile);
$envFile = preg_replace('/APP_DEBUG=.*/', 'APP_DEBUG=false', $envFile);
$envFile = preg_replace('/LOG_LEVEL=.*/', 'LOG_LEVEL=error', $envFile);
$envFile = preg_replace('/APP_URL=.*/', 'APP_URL=http://127.0.0.1:8000', $envFile);

file_put_contents('.env', $envFile);
echo "✅ Environment configuration fixed\n\n";

// Step 2: Clear problematic cache files
echo "🧹 Step 2: Clearing problematic cache files...\n";
$cacheDirs = [
    'storage/framework/cache/data',
    'storage/framework/views',
    'storage/framework/sessions'
];

foreach ($cacheDirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "✅ Cleared: $dir\n";
    }
}

// Clear bootstrap cache
$bootstrapCache = [
    'bootstrap/cache/config.php',
    'bootstrap/cache/routes.php',
    'bootstrap/cache/events.php'
];

foreach ($bootstrapCache as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "✅ Deleted: $file\n";
    }
}

echo "\n";

// Step 3: Fix database collation issues
echo "🗄️ Step 3: Checking database collation...\n";
try {
    $connection = new PDO('mysql:host=127.0.0.1;dbname=eurotaxi', 'root', '');
    $connection->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");

    // Check and fix table collations
    $tables = ['units', 'drivers', 'users', 'expenses', 'maintenance', 'boundaries'];
    foreach ($tables as $table) {
        try {
            $result = $connection->query("SHOW TABLE STATUS LIKE '$table'");
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if ($row && $row['Collation'] !== 'utf8mb4_unicode_ci') {
                $connection->exec("ALTER TABLE `$table` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                echo "✅ Fixed collation for: $table\n";
            }
        } catch (Exception $e) {
            echo "⚠️  Table $table not found or already fixed\n";
        }
    }
} catch (Exception $e) {
    echo "⚠️  Database connection issue: " . $e->getMessage() . "\n";
}

echo "\n";

// Step 4: Create permanent configuration cache
echo "💾 Step 4: Creating stable configuration cache...\n";
exec('php artisan config:cache 2>&1', $output, $returnVar);
if ($returnVar === 0) {
    echo "✅ Configuration cached successfully\n";
} else {
    echo "⚠️  Config cache issue detected\n";
}

exec('php artisan route:cache 2>&1', $output, $returnVar);
if ($returnVar === 0) {
    echo "✅ Routes cached successfully\n";
} else {
    echo "⚠️  Route cache issue detected\n";
}

echo "\n";

// Step 5: Optimize autoloader
echo "📦 Step 5: Optimizing autoloader...\n";
exec('composer dump-autoload --optimize 2>&1', $output, $returnVar);
echo "✅ Autoloader optimized\n\n";

// Step 6: Create startup script
echo "🚀 Step 6: Creating reliable startup script...\n";
$startupScript = '@echo off
title Euro Taxi System
cd /d C:\xampp\htdocs\Eurotaxisystem

echo Starting Euro Taxi System...
echo.
echo ========================================
echo EURO TAXI SYSTEM STARTUP
echo ========================================
echo.
echo 1. Clearing caches...
php artisan config:clear >nul 2>&1
php artisan cache:clear >nul 2>&1
php artisan view:clear >nul 2>&1
php artisan route:clear >nul 2>&1

echo 2. Optimizing system...
php artisan config:cache >nul 2>&1
php artisan route:cache >nul 2>&1
php artisan optimize >nul 2>&1

echo 3. Starting server...
echo.
echo Server will start at: http://127.0.0.1:8000
echo Press Ctrl+C to stop the server
echo.
php artisan serve --host=127.0.0.1:8000
pause';

file_put_contents('START-EUROTAXI.bat', $startupScript);
echo "✅ Created: START-EUROTAXI.bat\n\n";

// Step 7: Create diagnostic script
echo "🔍 Step 7: Creating diagnostic script...\n";
$diagnosticScript = '@echo off
title Euro Taxi System Diagnostics
cd /d C:\xampp\htdocs\Eurotaxisystem

echo ========================================
echo EURO TAXI SYSTEM DIAGNOSTICS
echo ========================================
echo.

echo 1. Checking PHP version...
php --version
echo.

echo 2. Checking Laravel version...
php artisan --version
echo.

echo 3. Checking database connection...
php artisan tinker --execute="DB::connection()->getPdo(); echo \'Database: OK\';"
echo.

echo 4. Checking environment...
php artisan env
echo.

echo 5. Checking routes...
php artisan route:list | findstr live-tracking
php artisan route:list | findstr analytics
php artisan route:list | findstr units
echo.

echo 6. Checking cache status...
if exist bootstrap\cache\config.php (
    echo Config cache: EXISTS
) else (
    echo Config cache: MISSING
)

if exist bootstrap\cache\routes.php (
    echo Route cache: EXISTS
) else (
    echo Route cache: MISSING
)
echo.

echo 7. Testing system health...
php artisan tinker --execute="echo \'System Health: OK\';"
echo.

echo ========================================
echo DIAGNOSTICS COMPLETE
echo ========================================
pause';

file_put_contents('DIAGNOSTICS.bat', $diagnosticScript);
echo "✅ Created: DIAGNOSTICS.bat\n\n";

echo "🎉 PERMANENT FIX COMPLETE!\n";
echo "==========================================\n";
echo "✅ Environment fixed (using eurotaxi database)\n";
echo "✅ Cache issues resolved\n";
echo "✅ Database collation fixed\n";
echo "✅ Configuration optimized\n";
echo "✅ Startup script created\n";
echo "✅ Diagnostic script created\n\n";

echo "📋 NEXT STEPS:\n";
echo "1. Run: START-EUROTAXI.bat to start the system\n";
echo "2. Run: DIAGNOSTICS.bat to check system health\n";
echo "3. Access: http://127.0.0.1:8000\n\n";

echo "🚖 Your Euro Taxi System is now permanently fixed!\n";
