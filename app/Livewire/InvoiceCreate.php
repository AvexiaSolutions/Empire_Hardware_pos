<?php

namespace App\Livewire;

use Livewire\Component;

class InvoiceCreate extends Component
{
    public $customerName = '';
    public $customerPhone = '';
    public $invoiceNo = '';
    public $invoiceDate = '';
    
    // Cart array: structure => ['batch_id' => [ ...details ] ]
    public $cart = [];
    
    // Total summaries
    public $totalAmount = 0;
    public $globalDiscount = 0;
    public $globalDiscountType = 'Rs';
    public $netAmount = 0;
    
    // Payment
    public $paymentMethod = 'cash';
    public $paymentAmount = 0;
    public $advancedAmount = 0;
    public $dueDate = '';
    public $chequeNo = '';
    public $bankName = '';
    public $note = '';

    // Fast Mode / Barcode Scanning
    public $posFastMode = false;
    public $barcodeInput = '';

    // Selected Item for manual entry
    public $manualBatchId = '';

    public function mount()
    {
        $this->invoiceDate = date('Y-m-d');
        $this->generateInvoiceNo();
        
        $setting = \App\Models\Setting::where('key', 'pos_fast_mode')->first();
        $this->posFastMode = $setting && $setting->value == '1';
    }

    public function generateInvoiceNo()
    {
        $lastInvoice = \App\Models\Invoice::orderBy('id', 'desc')->first();
        $nextId = $lastInvoice ? $lastInvoice->id + 1 : 1;
        $this->invoiceNo = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    }

    public function updatedBarcodeInput()
    {
        if ($this->posFastMode && !empty($this->barcodeInput)) {
            $this->scanBarcode();
        }
    }

    public function scanBarcode()
    {
        $batch = \App\Models\ItemBatch::with('item')->where('barcode', $this->barcodeInput)->where('is_active', true)->first();
        
        if ($batch) {
            $this->addToCart($batch->id);
            $this->barcodeInput = ''; // clear after scan
        } else {
            session()->flash('error', 'Barcode not found or inactive!');
        }
    }

    public function addManualItem()
    {
        if (!empty($this->manualBatchId)) {
            $this->addToCart($this->manualBatchId);
            $this->manualBatchId = '';
        }
    }

    public function addToCart($batchId)
    {
        if (isset($this->cart[$batchId])) {
            $this->cart[$batchId]['quantity']++;
        } else {
            $batch = \App\Models\ItemBatch::with('item')->find($batchId);
            if ($batch) {
                $this->cart[$batchId] = [
                    'batch_id' => $batch->id,
                    'item_id' => $batch->item_id,
                    'barcode' => $batch->barcode,
                    'name' => $batch->item->name,
                    'unit' => $batch->item->base_unit,
                    'rate' => $batch->selling_price,
                    'quantity' => 1,
                    'discount' => 0,
                    'discount_type' => 'Rs', // or '%'
                    
                    // Bulk pricing properties
                    'sale_type' => 'base', // 'base' or 'bulk'
                    'has_bulk' => $batch->item->has_bulk_unit,
                    'base_unit' => $batch->item->base_unit,
                    'bulk_unit' => $batch->item->bulk_unit,
                    'base_rate' => $batch->selling_price,
                    'bulk_rate' => $batch->bulk_selling_price,
                    'conversion_factor' => $batch->item->bulk_conversion_factor,
                ];
            }
        }
        $this->calculateTotals();
    }

    public function updateCart($batchId, $field, $value)
    {
        if (isset($this->cart[$batchId])) {
            $this->cart[$batchId][$field] = $value;
            
            if ($field === 'sale_type') {
                $this->cart[$batchId]['rate'] = $value === 'bulk' 
                    ? $this->cart[$batchId]['bulk_rate'] 
                    : $this->cart[$batchId]['base_rate'];
            }
            
            $this->calculateTotals();
        }
    }

    public function removeFromCart($batchId)
    {
        unset($this->cart[$batchId]);
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->totalAmount = 0;
        
        foreach ($this->cart as $id => $item) {
            $lineTotal = floatval($item['rate']) * floatval($item['quantity']);
            
            // Apply line discount
            $disc = floatval($item['discount']);
            if ($item['discount_type'] == '%') {
                $lineTotal -= ($lineTotal * ($disc / 100));
            } else {
                $lineTotal -= $disc;
            }
            
            $this->cart[$id]['amount'] = max(0, $lineTotal);
            $this->totalAmount += $this->cart[$id]['amount'];
        }

        $this->netAmount = $this->totalAmount;
        
        // Apply global discount
        $gDisc = floatval($this->globalDiscount);
        if ($this->globalDiscountType == '%') {
            $this->netAmount -= ($this->netAmount * ($gDisc / 100));
        } else {
            $this->netAmount -= $gDisc;
        }
        
        $this->netAmount = max(0, $this->netAmount);
        
        if ($this->paymentMethod == 'cash') {
            $this->paymentAmount = $this->netAmount; // auto fill payment amount for convenience
        }
    }

    public function updatedGlobalDiscount() { $this->calculateTotals(); }
    public function updatedGlobalDiscountType() { $this->calculateTotals(); }
    public function updatedPaymentMethod() { $this->calculateTotals(); }

    public function saveInvoice()
    {
        $this->validate([
            'invoiceNo' => 'required',
            'invoiceDate' => 'required|date',
            'paymentMethod' => 'required',
            'cart' => 'required|array|min:1',
        ]);

        // Note: For a real POS, we'd wrap this in a DB transaction
        $invoice = \App\Models\Invoice::create([
            'invoice_no' => $this->invoiceNo,
            'type' => $this->paymentMethod, // Cash, Credit, Cheque
            'customer_name' => $this->customerName ?: 'Walk-in Customer',
            'date' => $this->invoiceDate,
            'sub_total' => $this->totalAmount,
            'discount' => $this->globalDiscountType == '%' ? ($this->totalAmount * ($this->globalDiscount/100)) : $this->globalDiscount,
            'total' => $this->netAmount,
        ]);

        foreach ($this->cart as $item) {
            \App\Models\InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_id' => $item['item_id'],
                'item_batch_id' => $item['batch_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['rate'],
                'total' => $item['amount']
            ]);
            
            // Deduct stock from batch
            $batch = \App\Models\ItemBatch::find($item['batch_id']);
            if($batch) {
                $deductQty = $item['sale_type'] === 'bulk' 
                    ? ($item['quantity'] * $item['conversion_factor']) 
                    : $item['quantity'];
                    
                $batch->quantity -= $deductQty;
                $batch->save();
            }
        }

        // Handle Payment Details
        if ($this->paymentMethod == 'cheque') {
            \App\Models\Cheque::create([
                'invoice_id' => $invoice->id,
                'cheque_no' => $this->chequeNo,
                'bank_name' => $this->bankName,
                'due_date' => $this->dueDate,
                'amount' => $this->netAmount,
                'status' => 'Pending'
            ]);
        } elseif ($this->paymentMethod == 'credit') {
            \App\Models\Credit::create([
                'invoice_id' => $invoice->id,
                'advanced_amount' => $this->advancedAmount,
                'due_date' => $this->dueDate,
                'status' => 'Pending'
            ]);
        }

        session()->flash('success', 'Invoice Saved Successfully!');
        return redirect()->route('invoice.index');
    }

    public function render()
    {
        $availableBatches = \App\Models\ItemBatch::with('item')->where('is_active', true)->where('quantity', '>', 0)->get();
        return view('livewire.invoice-create', [
            'availableBatches' => $availableBatches
        ])->layout('components.pos-layout');
    }
}
