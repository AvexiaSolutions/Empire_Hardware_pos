<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use App\Models\Item;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Credit;
use App\Models\Category;
use App\Events\StockUpdated;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public $search = '';
    public $searchResults = [];
    
    public $selectedCategory = '';
    public $categories = [];
    
    public $cart = []; 
    public $billDiscount = 0;
    public $billDiscountType = 'amount'; 
    public $tenderedAmount = 0;
    public $customerName = '';

    // Print Modal State
    public $showPrintModal = false;
    public $lastInvoiceId = null;

    public $subTotal = 0;
    public $grandTotal = 0;
    public $balance = 0;

    // Cash Register State
    public $activeRegister = null;
    public $showOpenRegisterModal = false;
    public $showCloseRegisterModal = false;
    public $registerOpeningBalance = 0;
    public $registerClosingBalance = 0;
    public $registerNotes = '';

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
        $this->loadItems();

        $this->activeRegister = \App\Models\CashRegister::where('user_id', auth()->id())
            ->where('status', 'Open')
            ->first();

        if (!$this->activeRegister) {
            $this->showOpenRegisterModal = true;
        }
    }

    public function openRegister()
    {
        $this->validate(['registerOpeningBalance' => 'required|numeric|min:0']);
        
        $this->activeRegister = \App\Models\CashRegister::create([
            'user_id' => auth()->id(),
            'opening_time' => now(),
            'opening_balance' => $this->registerOpeningBalance,
            'status' => 'Open'
        ]);

        $this->showOpenRegisterModal = false;
    }

    public function openCloseRegisterModal()
    {
        if ($this->activeRegister) {
            $expected = $this->activeRegister->opening_balance + \App\Models\Invoice::where('cash_register_id', $this->activeRegister->id)->where('type', 'Cash')->sum('tendered_amount') - \App\Models\Invoice::where('cash_register_id', $this->activeRegister->id)->where('type', 'Cash')->where('balance_amount', '<', 0)->sum(DB::raw('abs(balance_amount)'));
            // A safer expected calculation for cash is: Total Cash Collected
            // Let's do a simpler expected balance:
            $totalCashInvoices = \App\Models\Invoice::where('cash_register_id', $this->activeRegister->id)->where('type', 'Cash')->get();
            $cashCollected = 0;
            foreach ($totalCashInvoices as $inv) {
                // Cash collected = tendered - change. If change is negative (balance < 0), we only collected tendered amount, but actually if credit it's not cash.
                // Assuming all Cash invoices, collected cash is the invoice total.
                $cashCollected += $inv->total;
            }
            $this->activeRegister->expected_closing_balance = $this->activeRegister->opening_balance + $cashCollected;
            $this->registerClosingBalance = $this->activeRegister->expected_closing_balance;
            $this->showCloseRegisterModal = true;
        }
    }

    public function closeRegister()
    {
        $this->validate(['registerClosingBalance' => 'required|numeric|min:0']);
        
        $this->activeRegister->update([
            'closing_time' => now(),
            'closing_balance' => $this->registerClosingBalance,
            'status' => 'Closed',
            'notes' => $this->registerNotes
        ]);

        $this->activeRegister = null;
        $this->showCloseRegisterModal = false;
        $this->showOpenRegisterModal = true;
        $this->registerOpeningBalance = 0;
    }

    public function updatedSearch() { $this->loadItems(); }
    public function updatedSelectedCategory() { $this->loadItems(); }

    #[On('echo:pos,StockUpdated')]
    public function refreshItems()
    {
        $this->loadItems();
    }

    public $barcodeInput = '';

    public function loadItems()
    {
        $query = Item::with(['batches' => function($q) {
            $q->where('is_active', true)->where('quantity', '>', 0);
        }])->withSum('invoiceItems', 'quantity');

        if (strlen($this->search) > 0) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhere('search_aliases', 'like', '%' . $this->search . '%')
                  ->orWhereHas('batches', function($q2) {
                      $q2->where('barcode', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        $this->searchResults = $query->orderByDesc('invoice_items_sum_quantity')
            ->orderByDesc('id') // fallback for new items
            ->get();
    }

    public function handleBarcode()
    {
        if (empty($this->barcodeInput)) {
            return;
        }

        // 1. Try to find an exact batch match (loose barcode)
        $batch = \App\Models\ItemBatch::where('barcode', $this->barcodeInput)
            ->where('is_active', true)
            ->with('item.batches')
            ->first();

        if ($batch) {
            $this->addToCart($batch->item_id, $batch->id);
            $this->barcodeInput = '';
            return;
        }

        // 2. Fallback to generic item code match
        $item = Item::where('code', $this->barcodeInput)->first();
        if ($item) {
            $this->selectItem($item->id);
        } else {
            session()->flash('error', 'Item with barcode "' . $this->barcodeInput . '" not found.');
        }

        $this->barcodeInput = ''; // clear input for next scan
    }

    public $showBatchSelectionModal = false;
    public $selectedItemForBatches = null;

    public function selectItem($itemId)
    {
        $item = Item::with(['batches' => function($q) {
            $q->where('is_active', true)->where('quantity', '>', 0);
        }])->find($itemId);

        if (!$item) return;

        if ($item->batches->count() > 1) {
            $this->selectedItemForBatches = $item;
            $this->showBatchSelectionModal = true;
        } else {
            $batch = $item->batches->first();
            $this->addToCart($item->id, $batch ? $batch->id : null);
        }
    }

    public function addToCart($itemId, $batchId = null)
    {
        $item = Item::find($itemId);
        if (!$item) return;

        if ($batchId) {
            $batch = $item->batches->firstWhere('id', $batchId);
        } else {
            $batch = $item->batches->first();
        }

        $actualBatchId = $batch ? $batch->id : null;
        
        $price = $batch ? $batch->selling_price : 0;

        // Ensure we check for duplicate specific batch
        $existingIndex = collect($this->cart)->search(fn($c) => $c['item_id'] == $itemId && $c['batch_id'] == $actualBatchId);
        
        $currentCartQty = $existingIndex !== false ? $this->cart[$existingIndex]['qty'] : 0;
        $availableStock = $batch ? $batch->quantity : 0;

        if ($currentCartQty + 1 > $availableStock) {
            session()->flash('error', 'Not enough stock available!');
            return;
        }

        if ($existingIndex !== false) {
            $this->cart[$existingIndex]['qty']++;
        } else {
            $this->cart[] = [
                'cart_id' => uniqid(),
                'item_id' => $item->id,
                'batch_id' => $actualBatchId,
                'name' => $item->name . ($batch && $batch->batch_no ? ' (' . $batch->batch_no . ')' : ''),
                'code' => $item->code,
                'price' => $price,
                'qty' => 1,
                'discount' => 0,
                'discount_type' => 'amount',
                'item_total' => $price,
                'available_stock' => $availableStock
            ];
        }

        $this->showBatchSelectionModal = false;
        $this->search = '';
        $this->loadItems();
        $this->calculateTotals();
    }

    public function updateCart($cartId, $field, $value)
    {
        foreach ($this->cart as $key => $item) {
            if ($item['cart_id'] == $cartId) {
                // Ensure numeric values
                if (in_array($field, ['qty', 'price', 'discount'])) {
                    $value = is_numeric($value) ? (float)$value : 0;
                }
                
                if ($field === 'qty') {
                    $available = $this->cart[$key]['available_stock'] ?? 0;
                    if ($value > $available) {
                        session()->flash('error', 'Cannot exceed available stock!');
                        $value = $available;
                    }
                }
                
                $this->cart[$key][$field] = $value;
            }
        }
        $this->calculateTotals();
    }

    public function removeFromCart($cartId)
    {
        $this->cart = array_values(array_filter($this->cart, fn($c) => $c['cart_id'] != $cartId));
        $this->calculateTotals();
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->customerName = '';
        $this->billDiscount = 0;
        $this->tenderedAmount = 0;
        $this->calculateTotals();
    }

    public function updatedBillDiscount() { $this->calculateTotals(); }
    public function updatedBillDiscountType() { $this->calculateTotals(); }
    public function updatedTenderedAmount() { $this->calculateTotals(); }

    public function calculateTotals()
    {
        $this->subTotal = 0;

        foreach ($this->cart as $key => $item) {
            $baseTotal = $item['price'] * $item['qty'];
            $discountAmt = 0;
            
            if ($item['discount_type'] == 'percentage') {
                $discountAmt = $baseTotal * ((float)$item['discount'] / 100);
            } else {
                $discountAmt = (float)$item['discount'];
            }
            
            $itemTotal = max(0, $baseTotal - $discountAmt);
            $this->cart[$key]['item_total'] = $itemTotal;
            $this->subTotal += $itemTotal;
        }

        $billDiscountAmt = 0;
        if ($this->billDiscountType == 'percentage') {
            $billDiscountAmt = $this->subTotal * ((float)$this->billDiscount / 100);
        } else {
            $billDiscountAmt = (float)$this->billDiscount;
        }

        $this->grandTotal = max(0, $this->subTotal - $billDiscountAmt);
        $this->balance = (float)$this->tenderedAmount - $this->grandTotal;
        
        broadcast(new \App\Events\CartUpdated($this->cart, $this->grandTotal));
    }

    public function checkout()
    {
        if (empty($this->cart)) {
            session()->flash('error', 'Cart is empty!');
            return;
        }

        DB::transaction(function () {
            // Generate Invoice No
            $lastInvoice = Invoice::orderBy('id', 'desc')->first();
            $nextId = $lastInvoice ? $lastInvoice->id + 1 : 1;
            $invoiceNo = 'INV-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

            $type = $this->balance < 0 ? 'Credit' : 'Cash';

            $invoice = Invoice::create([
                'invoice_no' => $invoiceNo,
                'user_id' => auth()->id(),
                'cash_register_id' => $this->activeRegister ? $this->activeRegister->id : null,
                'type' => $type,
                'customer_name' => $this->customerName,
                'date' => now()->toDateString(),
                'sub_total' => $this->subTotal,
                'bill_discount' => $this->billDiscount ?: 0,
                'bill_discount_type' => $this->billDiscountType,
                'total' => $this->grandTotal,
                'tendered_amount' => $this->tenderedAmount ?: 0,
                'balance_amount' => $this->balance
            ]);

            foreach ($this->cart as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_id' => $item['item_id'],
                    'item_batch_id' => $item['batch_id'],
                    'quantity' => $item['qty'],
                    'unit_price' => $item['price'],
                    'discount' => $item['discount'] ?: 0,
                    'discount_type' => $item['discount_type'],
                    'total' => $item['item_total']
                ]);
                
                // Deduct stock securely with Pessimistic Locking
                if ($item['batch_id']) {
                    $batch = \App\Models\ItemBatch::where('id', $item['batch_id'])->lockForUpdate()->first();
                    if ($batch) {
                        $qtyToDeduct = $item['qty'];
                        if ($batch->quantity >= $qtyToDeduct) {
                            $batch->decrement('quantity', $qtyToDeduct);
                        } else {
                            throw new \Exception("Insufficient stock for item: {$item['name']}");
                        }
                    }
                }
            }

            if ($this->balance < 0) {
                Credit::create([
                    'invoice_id' => $invoice->id,
                    'type' => 'received',
                    'amount' => abs($this->balance),
                    'due_date' => now()->addDays(30)->toDateString(), // Default 30 days
                    'status' => 'Pending'
                ]);
            }

            $this->reset(['cart', 'billDiscount', 'tenderedAmount', 'customerName', 'search', 'searchResults']);
            $this->calculateTotals();
            $this->loadItems(); // Refresh items to reflect new stock counts
            
            $this->lastInvoiceId = $invoice->id;
            $this->showPrintModal = true;
            
            // Dispatch browser event to trigger any JS based auto-print listeners if needed
            $this->dispatch('invoice-created', invoiceId: $invoice->id);
            
            // Broadcast event to other connected POS users to refresh stock
            broadcast(new StockUpdated());
        });
    }

    public function render()
    {
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('livewire.pos.index', [
            'printSettings' => [
                'method' => $settings['print_method'] ?? 'browser',
                'qz_printer' => $settings['qz_printer_name'] ?? '',
                'auto_print' => $settings['auto_print'] ?? '0'
            ]
        ])->layout('components.pos-layout');
    }
}
