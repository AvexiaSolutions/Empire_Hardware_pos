<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:check-low-stock')]
#[Description('Command description')]
class CheckLowStock extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threshold = 50; // Items with total quantity <= 50 are considered low stock

        $lowStockItems = \App\Models\Item::withSum('batches', 'quantity')
            ->having('batches_sum_quantity', '<=', $threshold)
            ->get();

        if ($lowStockItems->isNotEmpty()) {
            \Illuminate\Support\Facades\Mail::to('admin@example.com')->send(new \App\Mail\LowStockAlertMail($lowStockItems));
            $this->info('Low stock alert email sent for ' . $lowStockItems->count() . ' items.');
        } else {
            $this->info('No items are low on stock.');
        }
    }
}
