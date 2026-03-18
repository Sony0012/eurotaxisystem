@echo off
REM Set PHP path for this session only
set PATH=C:\xampp\php;%PATH%
echo PHP Path Updated: %PATH%
echo Now you can run: php artisan serve
php artisan serve --host=127.0.0.1 --port=8000
pause
