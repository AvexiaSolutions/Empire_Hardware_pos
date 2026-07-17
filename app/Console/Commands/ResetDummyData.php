<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetDummyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-dummy-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all dummy data except users, roles, and settings.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (app()->environment('production') && !$this->confirm('You are in production! Do you wish to continue?')) {
            return Command::FAILURE;
        }

        $this->info('Starting database reset...');

        Schema::disableForeignKeyConstraints();

        // Truncate Tables
        $tables = [
            'items',
            'item_batches',
            'item_bulk_units',
            'item_batch_bulk_prices',
            'categories',
            'sub_categories',
            'invoices',
            'invoice_items',
            'credits',
        ];

        foreach ($tables as $table) {
            $this->info("Truncating {$table}...");
            DB::table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();

        $this->info('Tables truncated successfully. Running seeders...');

        // Run seeders
        $this->call('db:seed', ['--class' => 'TestItemsSeeder', '--force' => true]);
        $this->call('db:seed', ['--class' => 'ExtraTestItemsSeeder', '--force' => true]);

        $this->info('Dummy data reset successfully!');
        return Command::SUCCESS;
    }
}
