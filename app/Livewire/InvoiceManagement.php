<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Invoice;

class InvoiceManagement extends Component
{
    public function render()
    {
        $invoices = Invoice::orderBy('created_at', 'desc')->get();
        
        $totalSales = Invoice::sum('total');
        $totalInvoicesCount = Invoice::count();

        // Debtors are customers who owe us (negative balance in pos context means tendered < total)
        // Wait, looking at POS index: balance = tenderedAmount - grandTotal
        // So if tendered < total, balance is negative.
        $debtors = Invoice::where('balance_amount', '<', 0)->get();
        
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
