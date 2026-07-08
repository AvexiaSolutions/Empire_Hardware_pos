@echo off
title POS System Maintenance
color 0A
echo ==========================================
echo       POS System Maintenance Tool
echo ==========================================
echo.
cd /d "%~dp0"

echo [1/2] Clearing System Cache...
call php artisan optimize:clear
echo.

echo [2/2] Re-linking Storage...
call php artisan storage:link
echo.

echo ==========================================
echo Maintenance completed successfully!
echo ==========================================
pause
