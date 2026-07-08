@echo off
title POS System - Background Services
cd /d "%~dp0"

:: Start Laravel Web Server on port 8000
start /b php artisan serve --host=127.0.0.1 --port=8000

:: Start Reverb WebSocket Server
start /b php artisan reverb:start

:: Start Queue Worker
start /b php artisan queue:work

exit
