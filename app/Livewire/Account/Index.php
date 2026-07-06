<?php

namespace App\Livewire\Account;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Paysheet;
use App\Models\Cheque;
use App\Models\Credit;
use Carbon\Carbon;

#[Layout('components.pos-layout')]
class Index extends Component
{
    public $currentMonth;
    
    public $monthlyIncome = 0;
    public $monthlyExpenses = 0;
    public $monthlyPendingIncome = 0;
    
    public $companyProfit = 0;
    public $companyLoss = 0;

    // Data for modals
    public $invoices = [];
    public $expensesList = [];
    public $paysheetsList = [];

    public function mount()
    {
        $this->currentMonth = Carbon::now();
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $startOfMonth = $this->currentMonth->copy()->startOfMonth();
        $endOfMonth = $this->currentMonth->copy()->endOfMonth();

        // 1. Monthly Income (Invoices total)
        $this->invoices = Invoice::whereBetween('date', [$startOfMonth, $endOfMonth])
                                 ->orderBy('date', 'desc')
                                 ->get();
        $this->monthlyIncome = $this->invoices->sum('total');

        // 2. Monthly Expenses (Expenses + Paysheets)
        $this->expensesList = Expense::whereBetween('date', [$startOfMonth, $endOfMonth])
                                     ->orderBy('date', 'desc')
                                     ->get();
                                     
        // Paysheets use string 'month_year' like 'YYYY-MM'
        $monthYearStr = $this->currentMonth->format('Y-m'); 
        $this->paysheetsList = Paysheet::with('employee') // Ensure relation is defined in model or it will fail
                                       ->where('month_year', $monthYearStr)
                                       ->get();
                                       
        $this->monthlyExpenses = $this->expensesList->sum('amount') + $this->paysheetsList->sum('net_salary');

        // 3. Monthly Pending Income (Received Cheques + Received Credits that are Pending)
        $pendingCheques = Cheque::where('type', 'received')
                                ->where('status', 'Pending')
                                ->whereBetween('due_date', [$startOfMonth, $endOfMonth])
                                ->sum('amount');
                                
        $pendingCredits = Credit::where('type', 'received')
                                ->where('status', 'Pending')
                                ->whereBetween('due_date', [$startOfMonth, $endOfMonth])
                                ->sum('amount');
                                
        $this->monthlyPendingIncome = $pendingCheques + $pendingCredits;

        // 4. Profit & Loss
        $net = $this->monthlyIncome - $this->monthlyExpenses;
        if ($net >= 0) {
            $this->companyProfit = $net;
            $this->companyLoss = 0;
        } else {
            $this->companyProfit = 0;
            $this->companyLoss = abs($net);
        }
    }

    public function render()
    {
        return view('livewire.account.index');
    }
}
