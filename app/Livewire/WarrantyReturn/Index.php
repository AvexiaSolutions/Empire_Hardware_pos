<?php

namespace App\Livewire\WarrantyReturn;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\ReturnLog;
use Illuminate\Support\Carbon;

class Index extends Component
{
    public $searchInvoiceNo = '';
    public $invoice = null;

    public function search()
    {
        $this->validate([
            'searchInvoiceNo' => 'required|string',
        ]);

        $this->invoice = Invoice::with(['items.item', 'items.itemBatch'])
            ->where('invoice_no', $this->searchInvoiceNo)
            ->first();

        if (!$this->invoice) {
            session()->flash('error', 'Invoice not found.');
        }
    }

    public function processReturn($invoiceItemId, $type)
    {
        if (!in_array($type, ['damage', 'change', 'warranty_claim'])) {
            return;
        }

        if (!$this->invoice) return;

        $invoiceItem = $this->invoice->items->where('id', $invoiceItemId)->first();
        if (!$invoiceItem) return;

        // Check if already returned/claimed
        // For simplicity, we assume full quantity is returned. If they want partial, we'd need a quantity input.
        // Assuming 1 quantity per return action for now, or just return the whole invoiceItem quantity.
        $quantityToReturn = $invoiceItem->quantity;

        // Verify if it's already returned to prevent double returns.
        $existingReturn = ReturnLog::where('invoice_item_id', $invoiceItemId)->sum('quantity');
        if ($existingReturn >= $quantityToReturn) {
            session()->flash('error', 'This item has already been fully returned or claimed.');
            return;
        }
        
        $remainingQuantity = $quantityToReturn - $existingReturn;

        $returnLog = ReturnLog::create([
            'invoice_id' => $this->invoice->id,
            'invoice_item_id' => $invoiceItem->id,
            'item_id' => $invoiceItem->item_id,
            'item_batch_id' => $invoiceItem->item_batch_id,
            'type' => $type,
            'quantity' => $remainingQuantity, // Returning all remaining
            'date' => now()->toDateString(),
        ]);

        if ($invoiceItem->itemBatch) {
            if ($type === 'change') {
                $invoiceItem->itemBatch->increment('quantity', $remainingQuantity);
            } elseif ($type === 'damage') {
                $invoiceItem->itemBatch->increment('damaged_quantity', $remainingQuantity);
            }
        }

        session()->flash('success', ucfirst(str_replace('_', ' ', $type)) . ' processed successfully.');
        
        // Refresh invoice
        $this->search();
    }

    public function render()
    {
        return view('livewire.warranty-return.index')
            ->layout('components.pos-layout');
    }
}
