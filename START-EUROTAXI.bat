@echo off
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
pause