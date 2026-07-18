@echo off
echo Building Empire POS Premium Web UI Control Panel...
if exist "build" rmdir /s /q "build"
if exist "Empire_POS_Setup.spec" del /q "Empire_POS_Setup.spec"
pyinstaller --noconsole --onefile --uac-admin --add-data "assets;assets" --add-data "ui;ui" --icon="assets\icon.ico" --name "Empire_POS_Setup" main.py
echo Build Complete!
