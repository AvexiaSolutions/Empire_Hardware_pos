<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Credit;
use App\Models\Cheque;
use App\Models\Supplier;
use App\Models\Invoice;
use Carbon\Carbon;

class CreditChequeManagement extends Component
{
    public $activeTab = 'received'; // 'received' or 'issued'
    public $search = '';

    // Issue Modals
    public $showIssueChequeModal = false;
    public $showIssueCreditModal = false;

    // Issue Forms
    public $issueSupplierId = '';
    public $issueAmount = '';
    public $issueDueDate = '';
    public $issueChequeNo = '';
    public $issueBankName = '';

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetFields();
    }

    public function openIssueChequeModal()
    {
        $this->resetFields();
        $this->showIssueChequeModal = true;
    }

    public function openIssueCreditModal()
    {
        $this->resetFields();
        $this->showIssueCreditModal = true;
    }

    public function closeModals()
    {
        $this->showIssueChequeModal = false;
        $this->showIssueCreditModal = false;
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->issueSupplierId = '';
        $this->issueAmount = '';
        $this->issueDueDate = '';
        $this->issueChequeNo = '';
        $this->issueBankName = '';
        $this->resetValidation();
    }

    public function saveIssueCheque()
    {
        $this->validate([
            'issueSupplierId' => 'required|exists:suppliers,id',
            'issueChequeNo' => 'required|string|unique:cheques,cheque_no',
            'issueBankName' => 'required|string|max:255',
            'issueAmount' => 'required|numeric|min:0',
            'issueDueDate' => 'required|date',
        ]);

        Cheque::create([
            'type' => 'issued',
            'supplier_id' => $this->issueSupplierId,
            'cheque_no' => $this->issueChequeNo,
            'bank_name' => $this->issueBankName,
            'amount' => $this->issueAmount,
            'due_date' => $this->issueDueDate,
            'status' => 'Pending',
        ]);

        session()->flash('success', 'Cheque issued successfully.');
        $this->closeModals();
    }

    public function saveIssueCredit()
    {
        $this->validate([
            'issueSupplierId' => 'required|exists:suppliers,id',
            'issueAmount' => 'required|numeric|min:0',
            'issueDueDate' => 'required|date',
        ]);

        Credit::create([
            'type' => 'issued',
            'supplier_id' => $this->issueSupplierId,
            'amount' => $this->issueAmount,
            'due_date' => $this->issueDueDate,
            'status' => 'Pending',
        ]);

        session()->flash('success', 'Credit issued successfully.');
        $this->closeModals();
    }

    public function clearCredit($id)
    {
        $credit = Credit::find($id);
        if ($credit) {
            $credit->update(['status' => 'Cleared']);
            
            if ($credit->type === 'received' && $credit->invoice_id) {
                $invoice = Invoice::find($credit->invoice_id);
                if ($invoice) {
                    $invoice->tendered_amount += $credit->amount;
                    $invoice->balance_amount += $credit->amount;
                    $invoice->save();
                }
            }

            session()->flash('success', 'Credit marked as cleared and Invoice updated.');
        }
    }

    public function clearCheque($id)
    {
        $cheque = Cheque::find($id);
        if ($cheque) {
            $cheque->update(['status' => 'Cash Done']);
            
            if ($cheque->type === 'received' && $cheque->invoice_id) {
                $invoice = Invoice::find($cheque->invoice_id);
                if ($invoice) {
                    $invoice->tendered_amount += $cheque->amount;
                    $invoice->balance_amount += $cheque->amount;
                    $invoice->save();
                }
            }

            session()->flash('success', 'Cheque marked as cashed and Invoice updated.');
        }
    }
    
    public function toggleChequeReject($id, $isRejected)
    {
        $cheque = Cheque::find($id);
        if ($cheque) {
            $cheque->update([
                'status' => $isRejected ? 'Return' : 'Pending',
                'return_date' => $isRejected ? Carbon::now() : null,
            ]);
            session()->flash('success', 'Cheque status updated.');
        }
    }

    public function render()
    {
        // Get Credits
        $creditsQuery = Credit::with(['supplier', 'invoice'])->where('type', $this->activeTab);
        if ($this->search) {
            $creditsQuery->where(function($q) {
                // If it's issued, search supplier name
                if ($this->activeTab == 'issued') {
                    $q->whereHas('supplier', function($sq) {
                        $sq->where('name', 'like', '%' . $this->search . '%');
                    });
                } else {
                    // If it's received, search invoice->customer name or invoice_id
                    $q->where('invoice_id', 'like', '%' . $this->search . '%')
                      ->orWhereHas('invoice', function($iq) {
                          $iq->where('customer_name', 'like', '%' . $this->search . '%');
                      });
                }
            });
        }
        $credits = $creditsQuery->orderBy('due_date', 'asc')->get();

        // Get Cheques
        $chequesQuery = Cheque::with(['supplier', 'invoice'])->where('type', $this->activeTab);
        if ($this->search) {
            $chequesQuery->where(function($q) {
                if ($this->activeTab == 'issued') {
                    $q->whereHas('supplier', function($sq) {
                        $sq->where('name', 'like', '%' . $this->search . '%');
                    })->orWhere('cheque_no', 'like', '%' . $this->search . '%');
                } else {
                    $q->where('invoice_id', 'like', '%' . $this->search . '%')
                      ->orWhere('cheque_no', 'like', '%' . $this->search . '%')
                      ->orWhereHas('invoice', function($iq) {
                          $iq->where('customer_name', 'like', '%' . $this->search . '%');
                      });
                }
            });
        }
        $cheques = $chequesQuery->orderBy('due_date', 'asc')->get();

        $suppliers = Supplier::orderBy('name')->get();

        return view('livewire.credit-cheque-management', [
            'credits' => $credits,
            'cheques' => $cheques,
            'suppliers' => $suppliers,
        ])->layout('components.pos-layout');
    }
}
