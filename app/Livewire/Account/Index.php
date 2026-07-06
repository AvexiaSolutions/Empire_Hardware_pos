<?php

namespace App\Livewire\Account;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Paysheet;
use App\Models\Cheque;
use App\Models\Credit;
use App\Models\ItemBatch;
use App\Models\ReturnLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

#[Layout('components.pos-layout')]
class Index extends Component
{
    public $startDate;
    public $endDate;
    
    public $monthlyIncome = 0;
    public $monthlyExpenses = 0;
    public $monthlyPendingIncome = 0;
    
    public $companyProfit = 0;
    public $companyLoss = 0;

    // Stock details
    public $totalStockCost = 0;
    public $totalStockValue = 0;
    public $expectedProfit = 0;

    // Return & Damage Impact
    public $returnDeductions = 0;
    public $damageExpenses = 0;

    // Data for modals
    public $invoices = [];
    public $expensesList = [];
    public $paysheetsList = [];
    public $warrantyClaims = [];

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->calculateTotals();
    }

    public function updatedStartDate() { $this->calculateTotals(); }
    public function updatedEndDate() { $this->calculateTotals(); }

    public function setToday()
    {
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
        $this->calculateTotals();
    }

    public function setThisMonth()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        if (!$this->startDate || !$this->endDate) return;

        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        // 1. Income (Invoices total) within range
        $this->invoices = Invoice::whereBetween('created_at', [$start, $end])
                                 ->orderBy('created_at', 'desc')
                                 ->get();
        $this->monthlyIncome = $this->invoices->sum('total');

        // 2. Expenses (Expenses + Paysheets) within range
        $this->expensesList = Expense::whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                                     ->orderBy('date', 'desc')
                                     ->get();
                                     
        // Paysheets check if the paysheet's month_year overlaps with the date range
        // Since paysheets are stored as 'Y-m', we'll grab paysheets for the months that fall in the range
        $startMonthStr = $start->format('Y-m'); 
        $endMonthStr = $end->format('Y-m');

        $this->paysheetsList = Paysheet::with('employee')
                                       ->whereBetween('month_year', [$startMonthStr, $endMonthStr])
                                       ->get();
                                       
        $this->monthlyExpenses = $this->expensesList->sum('amount') + $this->paysheetsList->sum('net_salary');

        // 3. Pending Income (Received Cheques + Received Credits that are Pending) due within range
        $pendingCheques = Cheque::where('type', 'received')
                                ->where('status', 'Pending')
                                ->whereBetween('due_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                                ->sum('amount');
                                
        $pendingCredits = Credit::where('type', 'received')
                                ->where('status', 'Pending')
                                ->whereBetween('due_date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                                ->sum('amount');
                                
        // 4. Returns & Damages
        $returnLogs = ReturnLog::with(['invoiceItem', 'itemBatch', 'item'])
            ->whereBetween('date', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->get();

        $this->returnDeductions = 0;
        $this->damageExpenses = 0;
        
        // Filter warranty claims to show in UI
        $this->warrantyClaims = $returnLogs->where('type', 'warranty_claim')->values();

        foreach ($returnLogs as $log) {
            // Deduct refunds from income for both 'change' and 'damage'
            if ($log->type === 'change' || $log->type === 'damage') {
                $refundAmount = $log->quantity * ($log->invoiceItem->unit_price ?? 0);
                $this->returnDeductions += $refundAmount;
            }
            
            // Add cost price of damaged items to expenses
            if ($log->type === 'damage') {
                $costPrice = $log->itemBatch ? $log->itemBatch->getRawOriginal('cost_price') : 0;
                $this->damageExpenses += ($log->quantity * $costPrice);
            }
        }

        // Adjust final income and expenses
        $this->monthlyIncome -= $this->returnDeductions;
        $this->monthlyExpenses += $this->damageExpenses;

        // 5. Profit & Loss
        $net = $this->monthlyIncome - $this->monthlyExpenses;
        if ($net >= 0) {
            $this->companyProfit = $net;
            $this->companyLoss = 0;
        } else {
            $this->companyProfit = 0;
            $this->companyLoss = abs($net);
        }

        // 6. Stock Valuation (Current Active Stock)
        $stockData = ItemBatch::where('is_active', true)
            ->where('quantity', '>', 0)
            ->select(
                DB::raw('SUM(quantity * cost_price) as total_cost'),
                DB::raw('SUM(quantity * selling_price) as total_value')
            )->first();

        $this->totalStockCost = $stockData->total_cost ?? 0;
        $this->totalStockValue = $stockData->total_value ?? 0;
        $this->expectedProfit = $this->totalStockValue - $this->totalStockCost;
    }

    public function render()
    {
        return view('livewire.account.index');
    }
}
