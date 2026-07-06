<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Invoice;

class InvoiceManagement extends Component
{
    public $dateFilter;

    public function render()
    {
        $query = Invoice::query();
        $debtorQuery = Invoice::where('balance_amount', '<', 0);

        if ($this->dateFilter) {
            $query->whereDate('created_at', $this->dateFilter);
            $debtorQuery->whereDate('created_at', $this->dateFilter);
        }

        $invoices = (clone $query)->orderBy('created_at', 'desc')->get();
        
        $totalSales = (clone $query)->sum('total');
        $totalInvoicesCount = (clone $query)->count();

        // Debtors are customers who owe us (negative balance in pos context means tendered < total)
        $debtors = $debtorQuery->get();
        
        $totalDebtorsCount = $debtors->count();
        // The owed amount is the absolute value of the negative balance
        $totalDebtorsAmount = $debtors->sum('balance_amount') * -1;

        return view('livewire.invoice-management', [
            'invoices' => $invoices,
            'totalSales' => $totalSales,
            'totalInvoicesCount' => $totalInvoicesCount,
            'debtors' => $debtors,
            'totalDebtorsCount' => $totalDebtorsCount,
            'totalDebtorsAmount' => $totalDebtorsAmount,
        ])->layout('components.pos-layout');
    }
}
