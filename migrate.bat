@echo off
echo Migrating database...
php artisan migrate --force
pause
