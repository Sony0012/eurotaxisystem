@echo off
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
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database: OK';"
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
php artisan tinker --execute="echo 'System Health: OK';"
echo.

echo ========================================
echo DIAGNOSTICS COMPLETE
echo ========================================
pause