# POS System (v1.2)

A Point of Sale (POS) system built with Laravel.

## Prerequisites
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL / SQLite

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/AvexiaSolutions/Empire_Hardware_pos.git
   cd pos-system
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install frontend dependencies:**
   ```bash
   npm install
   npm run build
   ```

4. **Setup Environment:**
   Copy the example environment file and generate an app key.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure Database:**
   Update your `.env` file with your database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

6. **Run Migrations:**
   ```bash
   php artisan migrate --seed
   ```

7. **Start the server:**
   ```bash
   php artisan serve
   ```

## Default Login Credentials
After running the migrations and seeders (`php artisan migrate --seed`), you can log in using the following default administrator account:
- **Email/Username:** `admin@example.com` or `admin`
- **Password:** `password`

## License
Open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
