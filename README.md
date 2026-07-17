# Empire POS System (v1.3)

A modern, fast, and feature-rich Point of Sale (POS) system built with Laravel, Livewire, and Alpine.js.

## Features Included in v1.3
- **AI Assistant**: A powerful, floating AI Assistant powered by Google Gemini (gemini-3.5-flash) capable of analyzing real-time sales and stock data.
- **Real-time Customer Display**: Secondary screen syncing live with the cashier's cart via WebSockets.
- **Advanced Inventory**: Smart handling of bulk/loose items (e.g. converting a Box into Pieces).
- **Cash Register Management**: Full shift tracking, opening/closing drawer logic, and drawer balance records.
- **Maintenance Tools**: Custom `.bat` and `.vbs` scripts for silent startup, easy cache wiping, and dummy data generation.
- **Barcode Generation**: Auto-generated 1D barcodes for specific item stock batches using `milon/barcode`.

---

## Prerequisites
- **PHP 8.1+**
- **Composer**
- **Node.js & NPM**
- **MySQL / SQLite**

---

## Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/AvexiaSolutions/Empire_Hardware_pos.git
   cd pos-system
   ```

2. **Install PHP and Frontend Dependencies:**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Setup Environment:**
   Copy the example environment file and generate a unique app key.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure Database & WebSockets:**
   Update your `.env` file with your database credentials. For real-time updates (WebSockets), ensure `BROADCAST_CONNECTION=reverb` and the `REVERB_*` variables match the `.env.example`.

5. **Setup AI Assistant (Gemini):**
   To enable the AI Assistant, you must provide your Google Gemini API key:
   - Go to [Google AI Studio](https://aistudio.google.com/app/apikey) and generate a free API Key.
   - Open `.env` and add: `GEMINI_API_KEY=your_key_here`

6. **Run Migrations & Seeders:**
   This will create the database tables and insert the default admin user.
   ```bash
   php artisan migrate --seed
   ```
   *Note: If you want to populate the database with hundreds of dummy items for testing, run `php artisan db:seed --class=TestItemsSeeder` after the standard migrations.*

---

## Running the Application (Local Servers)

Instead of manually running multiple terminal windows, use the provided helper scripts:

### Standard Start
Simply double-click the **`run_servers.bat`** file located in the root directory. This will automatically open terminal windows for:
1. Laravel PHP Server (`php artisan serve`)
2. Laravel Reverb WebSocket Server (`php artisan reverb:start`)
3. Laravel Queue Worker (`php artisan queue:work`)

### Silent Background Start (Startup)
To have the POS system automatically run silently in the background when the PC turns on:
1. Locate the **`startup.vbs`** file.
2. Create a shortcut of `startup.vbs`.
3. Press `Win + R`, type `shell:startup`, and press Enter.
4. Paste the shortcut into the Startup folder.
*(The system will now run silently without any annoying terminal windows every time the PC boots).*

---

## Maintenance & Utility Scripts

- **`maintenance.bat`**: Run this if the system feels slow, CSS/images aren't loading, or if you encounter view caching errors. It automatically clears route/view/config caches and resets the storage symbolic link.
- **`reset_data.bat`**: Run this to completely wipe all products, batches, categories, and invoices from the database **without deleting user accounts**. Perfect for cleaning the system before launching to production.

---

## Default Login Credentials
After running the standard migrations (`php artisan migrate --seed`), you can log in using:
- **Username:** `admin` (or `admin@example.com`)
- **Password:** `password`

## License
Open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
