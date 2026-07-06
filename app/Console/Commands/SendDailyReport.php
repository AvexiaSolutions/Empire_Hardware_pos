<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Item;
use App\Models\InvoiceItem;
use App\Models\Setting;
use App\Mail\DailyReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SendDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-daily-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily end-of-day analytics report via email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reportEmail = Setting::get('report_email');
        if (empty($reportEmail)) {
            $this->error('Report email not configured in settings. Skipping daily report.');
            return;
        }

        $today = Carbon::today();

        // 1. Sales & Invoices
        $invoices = Invoice::whereDate('date', $today)->get();
        $totalSales = $invoices->sum('total');
        $totalDiscounts = $invoices->sum('discount');
        $invoiceCount = $invoices->count();

        // 2. Net Profit (Sales - Expenses)
        $expenses = Expense::whereDate('date', $today)->sum('amount');
        $netProfit = $totalSales - $expenses;

        // 3. Best Selling Items Today
        $bestSellers = InvoiceItem::join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->join('items', 'invoice_items.item_id', '=', 'items.id')
            ->whereDate('invoices.date', $today)
            ->select('items.name', DB::raw('SUM(invoice_items.quantity) as total_sold'))
            ->groupBy('items.id', 'items.name')
            ->orderBy('total_sold', 'desc')
            ->take(10)
            ->get();

        // 4. Low Stock Alerts
        $lowStockItems = Item::withSum('batches', 'quantity')
            ->having('batches_sum_quantity', '<=', 50)
            ->take(15) // limit to top 15 for email brevity
            ->get();

        $reportData = [
            'total_sales' => $totalSales,
            'total_discounts' => $totalDiscounts,
            'invoice_count' => $invoiceCount,
            'expenses' => $expenses,
            'net_profit' => $netProfit,
            'best_sellers' => $bestSellers,
            'low_stock_items' => $lowStockItems
        ];

        Mail::to($reportEmail)->send(new DailyReportMail($reportData));

        $this->info("Daily report queued for sending to {$reportEmail}");
    }
}
