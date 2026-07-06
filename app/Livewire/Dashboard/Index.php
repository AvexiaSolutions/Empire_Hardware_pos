<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Item;
use App\Models\Invoice;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Cheque;
use App\Models\Credit;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('components.pos-layout')]
class Index extends Component
{
    public $totalProducts = 0;
    public $currentStockItemsCount = 0;
    public $outOfStockItemsCount = 0;
    
    public $currentStockPercentage = 0;
    public $outOfStockPercentage = 0;
    
    public $outOfStockAlerts = [];
    
    public $totalInvoices = 0;
    
    public $attendanceCount = 0;
    public $totalEmployees = 0;
    
    public $pendingReminders = [];
    
    public $fastMovingItems = [];
    public $slowMovingItems = [];
    
    public $monthlySalesData = [];
    public $monthlySalesLabels = [];
    
    public $forecastData = [];
    public $forecastLabels = [];

    public function mount()
    {
        $this->calculateStocks();
        $this->calculateInvoices();
        $this->calculateAttendance();
        $this->calculateReminders();
        $this->calculateMovingItems();
        $this->calculateMonthlySales();
        $this->calculateSalesForecast();
    }

    private function calculateStocks()
    {
        $items = Item::withSum('batches', 'quantity')->get();
        $this->totalProducts = $items->count();
        
        $this->currentStockItemsCount = $items->where('batches_sum_quantity', '>', 0)->count();
        $this->outOfStockItemsCount = $items->where('batches_sum_quantity', '<=', 0)->count();
        
        if ($this->totalProducts > 0) {
            $this->currentStockPercentage = round(($this->currentStockItemsCount / $this->totalProducts) * 100);
            $this->outOfStockPercentage = round(($this->outOfStockItemsCount / $this->totalProducts) * 100);
        }
        
        $this->outOfStockAlerts = $items->where('batches_sum_quantity', '<=', 50)->take(5);
    }
    
    private function calculateInvoices()
    {
        $this->totalInvoices = Invoice::whereMonth('date', Carbon::now()->month)
                                      ->whereYear('date', Carbon::now()->year)
                                      ->count();
    }
    
    private function calculateAttendance()
    {
        $this->totalEmployees = Employee::count();
        $this->attendanceCount = Attendance::whereDate('date', Carbon::today())->count();
    }
    
    private function calculateReminders()
    {
        $cheques = Cheque::with('invoice')
            ->where('status', 'Pending')
            ->whereDate('due_date', '<=', Carbon::today())
            ->get()
            ->map(function($cheque) {
                return (object)[
                    'type' => 'Cheque',
                    'invoice_no' => $cheque->invoice ? $cheque->invoice->invoice_no : 'N/A',
                    'name' => $cheque->invoice ? $cheque->invoice->customer_name : 'Unknown',
                    'amount' => $cheque->amount
                ];
            });
            
        $credits = Credit::with('invoice')
            ->where('status', 'Pending')
            ->whereDate('due_date', '<=', Carbon::today())
            ->get()
            ->map(function($credit) {
                return (object)[
                    'type' => 'Credit',
                    'invoice_no' => $credit->invoice ? $credit->invoice->invoice_no : 'N/A',
                    'name' => $credit->invoice ? $credit->invoice->customer_name : 'Unknown',
                    'amount' => $credit->amount
                ];
            });
            
        $this->pendingReminders = $cheques->merge($credits)->take(5);
    }
    
    private function calculateMovingItems()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $itemSales = InvoiceItem::join('invoices', 'invoice_items.invoice_id', '=', 'invoices.id')
            ->join('items', 'invoice_items.item_id', '=', 'items.id')
            ->whereMonth('invoices.date', $currentMonth)
            ->whereYear('invoices.date', $currentYear)
            ->select('items.name', 'items.id', DB::raw('SUM(invoice_items.quantity) as total_sold'))
            ->groupBy('items.id', 'items.name')
            ->orderBy('total_sold', 'desc')
            ->get();
            
        $this->fastMovingItems = $itemSales->take(5);
        $this->slowMovingItems = $itemSales->reverse()->take(5);
    }

    private function calculateMonthlySales()
    {
        $salesData = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M Y');
            
            $totalSales = Invoice::whereMonth('date', $month->month)
                                 ->whereYear('date', $month->year)
                                 ->sum('total');
                                 
            $salesData[] = $totalSales;
        }
        
        $this->monthlySalesLabels = $labels;
        $this->monthlySalesData = $salesData;
    }
    
    private function calculateSalesForecast()
    {
        // Simple Linear Regression over the last 30 days
        $days = 30;
        $startDate = Carbon::now()->subDays($days);
        
        $dailySales = Invoice::where('date', '>=', $startDate->format('Y-m-d'))
            ->select(DB::raw('DATE(date) as sale_date'), DB::raw('SUM(total) as daily_total'))
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get()
            ->keyBy('sale_date');
            
        $sumX = 0; $sumY = 0; $sumXY = 0; $sumXX = 0;
        $n = $days;
        
        for ($i = 0; $i < $n; $i++) {
            $dateStr = $startDate->copy()->addDays($i)->format('Y-m-d');
            $x = $i + 1;
            $y = isset($dailySales[$dateStr]) ? $dailySales[$dateStr]->daily_total : 0;
            
            $sumX += $x;
            $sumY += $y;
            $sumXY += ($x * $y);
            $sumXX += ($x * $x);
        }
        
        // Slope (m) and Intercept (b) for y = mx + b
        $denominator = ($n * $sumXX) - ($sumX * $sumX);
        $m = $denominator == 0 ? 0 : (($n * $sumXY) - ($sumX * $sumY)) / $denominator;
        $b = ($sumY - ($m * $sumX)) / $n;
        
        $forecastLabels = [];
        $forecastData = [];
        
        // Forecast next 7 days
        for ($i = 1; $i <= 7; $i++) {
            $targetX = $n + $i;
            $predictedY = ($m * $targetX) + $b;
            
            $forecastLabels[] = Carbon::now()->addDays($i)->format('D, M d');
            $forecastData[] = round(max(0, $predictedY), 2); // Prevent negative forecast
        }
        
        $this->forecastLabels = $forecastLabels;
        $this->forecastData = $forecastData;
    }

    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
