# Changelog

All notable changes to this project will be documented in this file.

## [v1.3.0] - 2026-07-17

### Added
- **AI Assistant Integration**: Added a floating AI Assistant widget to the dashboard powered by the Google Gemini API (`gemini-3.5-flash`), capable of analyzing sales, stock, and generating business insights.
- **Customer Display Screen**: Introduced a dedicated real-time secondary screen (`/customer-display`) using WebSockets for customers to view their current cart, total, and balance live at the checkout counter.
- **Cash Register System**: Added complete cash register management (`app/Models/CashRegister`), enabling shift open/close tracking and drawer balance reporting.
- **Advanced Bulk & Loose Items**: Refactored the core Item architecture. Converted legacy "Bulk Units" setup into a clean "Parent & Loose Item" system. Parent items (e.g., Box) can automatically deduct and generate Loose Items (e.g., Pieces) with their own dedicated barcodes and batch tracking.
- **Maintenance & Deployment Scripts**: 
  - `run_servers.bat`: Starts the Laravel Web server, WebSockets (Reverb), and Queue workers simultaneously.
  - `startup.vbs`: Runs the POS servers silently in the background at Windows startup without visible terminal windows.
  - `maintenance.bat`: Clears system cache and resets storage links.
  - `reset_data.bat`: Wipes dummy data (items, invoices, batches) while keeping admin accounts intact.
- **Expiry Date Tracking**: Added detailed expiry tracking for Stock Batches, dynamically displayed in the POS selection and Invoice UI.
- **Barcode Generator**: Implemented 1D barcode generation (using `milon/barcode`) strictly attached to batch numbers for unique product identification.

### Changed
- Redesigned the "Add New Item" & "Edit Item" modals: Replaced dropdown selects for "Has Warranty" and "Has Expiry" with inline toggle switches in a compact single-row layout.
- Refactored POS UI layout to prevent overflow on long item names, fixing cart boundaries and disabling item limit restrictions.
- Minified Item Management UI action buttons into space-saving icons and added direct barcode image previews.
- Upgraded Google Gemini API configuration to bypass local SSL certificate verification for seamless local development and updated the API to use the newest models.
- Updated `pos-layout.blade.php` and `dashboard/index.blade.php` to correctly teleport floating widgets to the DOM body, fixing CSS overflow cutoff bugs.
- Re-seeded 100 new test items with diverse variations of expiry and warranty values for rigorous QA.

### Removed
- Removed complicated legacy "Bulk Pricing" logic and related unused database tables (`item_bulk_units`, `item_batch_bulk_prices`).
- Reverted the `bulk_discount` feature from item batches as it was superseded by the much cleaner standard batch control.
