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

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
        $this->loadItems();
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
        $query = Item::with('batches')->withSum('invoiceItems', 'quantity');

        if (strlen($this->search) > 0) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhere('search_aliases', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        $this->searchResults = $query->orderByDesc('invoice_items_sum_quantity')
            ->orderByDesc('id') // fallback for new items
            ->take(24)
            ->get();
    }

    public function handleBarcode()
    {
        if (empty($this->barcodeInput)) {
            return;
        }

        $item = Item::where('code', $this->barcodeInput)->with('batches')->first();
        if ($item) {
            $this->addToCart($item->id);
            // Optionally flash a success message or play a beep here
        } else {
            session()->flash('error', 'Item with barcode "' . $this->barcodeInput . '" not found.');
        }

        $this->barcodeInput = ''; // clear input for next scan
    }


    public function addToCart($itemId)
    {
        $item = Item::with('batches')->find($itemId);
        if (!$item) return;

        $batch = $item->batches->first();
        $price = $batch ? $batch->selling_price : 0;
        $batchId = $batch ? $batch->id : null;

        $existingIndex = collect($this->cart)->search(fn($c) => $c['item_id'] == $itemId);
        
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
                'batch_id' => $batchId,
                'name' => $item->name,
                'code' => $item->code,
                'price' => $price,
                'qty' => 1,
                'discount' => 0,
                'discount_type' => 'amount',
                'item_total' => $price,
                'available_stock' => $availableStock
            ];
        }

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
                        if ($batch->quantity >= $item['qty']) {
                            $batch->decrement('quantity', $item['qty']);
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
