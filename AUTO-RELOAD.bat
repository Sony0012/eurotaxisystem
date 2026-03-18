@echo off
title Euro Taxi Auto Reload
cd /d C:
mpp\htdocs\Eurotaxisystem

echo 🔄 EURO TAXI AUTO RELOAD SYSTEM
echo ==================================
echo.
echo This will automatically reload your browser
echo when you make changes to your code!
echo.
echo Starting server with auto-reload...
echo.

REM Start server in background
start /B php artisan serve --host=127.0.0.1 --port=8000

REM Wait for server to start
timeout /t 3 /nobreak >nul

REM Open browser with auto-reload
start http://127.0.0.1:8000

echo.
echo ✅ Server started with auto-reload!
echo 📝 Make changes to your code and browser will auto-reload
echo 🔄 Press Ctrl+C to stop
echo.

REM Keep running
pause