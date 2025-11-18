@echo off
echo ===========================
echo Pulling latest changes...
echo ===========================
git pull

echo.
echo ===========================
echo Installing Composer dependencies...
echo ===========================
composer install

echo.
echo ===========================
echo Migrating Database...
echo ===========================
php artisan migrate --force

echo.
echo ===========================
echo Starting Dev Build...
echo ===========================
composer run dev

echo.
echo ===========================
echo Done!
echo ===========================
pause
