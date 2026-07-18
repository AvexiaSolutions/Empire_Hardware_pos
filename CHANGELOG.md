# Changelog

All notable changes to this project will be documented in this file.

## [v1.5.0] - Compiled Executable & Setup Wizard Optimization

### Added
- Compiled the Python-based Setup Wizard into a standalone `Empire_POS_Setup.exe` executable for easy launching.
- Background multithreading implemented for environment validation (PHP/MySQL checks) to prevent UI freezing on startup.
- Dynamic Start/Stop server button toggle logic based on real-time server health.

### Changed
- Expanded Setup Wizard window size to 1150x750 to provide ample space for UI components.
- Adjusted the UI layout in the Database Config tab to prevent overlapping buttons and text.
- Reduced the socket connection timeout to 0.05 seconds, completely eliminating UI lag during background server status polling.

### Fixed
- Fixed phpMyAdmin authentication error by correcting the MySQL port configuration and updating the blowfish secret.
- Fixed the "Checking..." UI freeze bug by offloading heavy PATH resolution operations to background threads.

## [v1.4] - Setup Wizard Major Rebuild

### Added
- Auto-detection and automatic background downloading of portable PHP and MySQL (MariaDB).
- Native Python functionality to manage database configuration, automatically creating MySQL users, databases, assigning privileges, and updating the `.env` simultaneously.
- Native Python process management for Laravel, Reverb, and MySQL, ensuring duplicate processes are prevented and graceful shutdown occurs on exit.
- 'Open phpMyAdmin' button in Database Config tab to launch a local PHP webserver serving phpMyAdmin on port 8081.

### Changed
- Refactored `main.py` entirely to avoid reliance on external batch scripts for complex logic.
- UI Layout fixed: Server Dashboard tab isolated correctly and Server Logs fixed to the right side of the window persistently.
- System maintenance tasks (Cache clearing) now executed directly via python `subprocess` calling artisan commands instead of `.bat` files.

### Removed
- `maintenance.bat`, `reset_data.bat`, and `run_servers.bat` removed as their functionalities were integrated natively into the Python backend.
