<div class="d-flex flex-column w-100" style="height: calc(100vh - 150px);">
<div class="row flex-grow-1 overflow-hidden flex-lg-row m-0 position-relative">
    <style>
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
            background-color: var(--bs-tertiary-bg) !important;
        }
        .product-grid {
            animation: slideIn 0.4s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .cart-panel {
            animation: slideInLeft 0.4s ease-out;
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
    
    <!-- Left Pane: Search & Products -->
    <div class="col-12 col-lg-8 h-100 d-flex flex-column pe-lg-4 product-grid border-end">
        <!-- Barcode Scanner Input -->
        <div class="mb-3">
            <form wire:submit.prevent="handleBarcode">
                <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden">
                    <span class="input-group-text bg-body-secondary border-0 ps-4 text-primary">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4h4v16H3zM8 4h2v16H8zM12 4h1v16h-1zM15 4h2v16h-2zM19 4h2v16h-2z"></path></svg>
                    </span>
                    <input type="text" id="barcode-input" class="form-control border-0 px-3 fw-bold" placeholder="{{ __('Scan Barcode here... [F3]') }}" wire:model="barcodeInput" autofocus>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">{{ __('Add to Cart') }}</button>
                </div>
            </form>
        </div>

        <!-- Search Bar & Filters -->
        <div class="mb-4 d-flex flex-column flex-md-row gap-3 position-relative">
            <select class="form-select form-select-lg shadow-sm border-0 rounded-pill w-100" style="max-width: 100%;" wire:model.live="selectedCategory">
                <option value="">{{ __('All Categories') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            
            <div class="flex-grow-1 position-relative w-100">
                <input type="text" id="search-input" class="form-control form-control shadow-sm border-0 rounded-pill px-4 w-100" 
                       placeholder="{{ __('Search items by name or code... [F2]') }}" 
                       wire:model.live.debounce.300ms="search">
                
                <div wire:loading wire:target="search" class="position-absolute top-50 end-0 translate-middle-y me-4">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <div class="flex-grow-1 overflow-auto pe-2 pb-5">
            @if(count($searchResults) > 0)
                <div class="row g-3">
                    @foreach($searchResults as $item)
                        <div class="col-6 col-md-4 col-xl-3">
                            <div wire:click="selectItem({{ $item->id }})" class="card border-0 shadow-sm h-100 cursor-pointer text-body hover-lift" style="cursor: pointer;">
                                <div class="card-body p-3 d-flex flex-column">
                                    <div class="mb-2">
                                        <span class="badge bg-secondary-subtle text-secondary">{{ $item->code }}</span>
                                    </div>
                                    <div class="d-flex align-items-start gap-2 mb-2">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" class="rounded shadow-sm flex-shrink-0" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-body-secondary rounded d-flex align-items-center justify-content-center text-muted shadow-sm flex-shrink-0" style="width: 40px; height: 40px;">
                                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                        <div class="d-flex flex-column align-items-start">
                                            <h5 class="card-title fs-6 fw-bold mb-1 lh-sm">{{ $item->name }}</h5>
                                            <span class="badge {{ $item->getTotalStock() > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill" style="font-size: 0.7rem;">Stock: {{ $item->getTotalStock() }}</span>
                                        </div>
                                    </div>
                                    @if($item->rack_number || $item->rack_row)
                                        <div class="small text-muted mb-2 d-flex flex-column gap-0" style="font-size: 0.75rem;">
                                            <div><span class="text-secondary opacity-75">{{ __('Rack:') }}</span> <span class="fw-bold">{{ $item->rack_number ?: '-' }}</span></div>
                                            <div><span class="text-secondary opacity-75">{{ __('Row:') }}</span> <span class="fw-bold">{{ $item->rack_row ?: '-' }}</span></div>
                                        </div>
                                    @endif
                                    <p class="card-text text-primary fw-bolder mt-auto mb-0 fs-5">
                                        @php $batch = $item->batches->first(); @endphp
                                        Rs. {{ number_format($batch ? $batch->selling_price : 0, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 text-muted d-flex flex-column justify-content-center h-100">
                    <svg class="mb-3 opacity-50 mx-auto" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <h5>{{ __('No items found') }}</h5>
                    <p class="small">{{ __('Try searching with a different keyword or category.') }}</p>
                </div>
            @endif
        </div>
        </div>
        
        <!-- Mobile Floating Cart Button -->
        <div class="d-lg-none position-fixed bottom-0 start-50 translate-middle-x mb-3 z-3 w-100 px-3 pb-2" style="max-width: 400px;">
            <button class="btn btn-primary btn-lg shadow-lg fw-bold w-100 d-flex justify-content-between align-items-center rounded-pill px-4 py-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">
                <span class="d-flex align-items-center gap-2">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="badge bg-white text-primary rounded-pill">{{ count($cart) }}</span> {{ __('Items') }}
                </span>
                <span class="fs-5">Rs. {{ number_format($grandTotal, 2) }}</span>
            </button>
        </div>
    <!-- Right Pane: Cart & Checkout -->
    <div class="col-12 col-lg-4 h-100 d-flex flex-column ps-lg-4 cart-panel offcanvas-lg offcanvas-end bg-body" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
        
        <!-- Offcanvas Header for Mobile -->
        <div class="offcanvas-header d-lg-none border-bottom px-3 py-3">
            <h5 class="offcanvas-title fw-bold fs-4" id="cartOffcanvasLabel">{{ __('Current Order') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#cartOffcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body flex-column h-100 p-3 p-lg-0 overflow-hidden">
            <!-- Header (Desktop) -->
            <div class="d-none d-lg-flex justify-content-between align-items-center mb-3">
                <h3 class="fs-4 fw-bold text-body mb-0">{{ __('Current Order') }}</h3>
                <div>
                    <button wire:click="openCloseRegisterModal" class="btn btn-sm btn-outline-warning shadow-sm hover-lift me-2" title="End Shift">{{ __('End Shift') }}</button>
                    <button wire:click="clearCart" class="btn btn-sm btn-outline-danger shadow-sm hover-lift" title="Shortcut: F4">{{ __('Clear Cart [F4]') }}</button>
                </div>
            </div>
            
            <!-- Mobile clear cart button -->
            <div class="d-lg-none position-fixed bottom-0 start-50 translate-middle-x mb-3 z-3 w-100 px-3 pb-2" style="max-width: 400px;">
                <button wire:click="clearCart" class="btn btn-sm btn-outline-danger shadow-sm">{{ __('Clear Cart') }}</button>
            </div>

        @if(session()->has('error'))
            <div class="alert alert-danger py-2">{{ session('error') }}</div>
        @endif
        @if(session()->has('success'))
            <div class="alert alert-success py-2">{{ session('success') }}</div>
        @endif

        <!-- Cart Table (Scrollable) -->
        <div class="flex-grow-1 bg-body-secondary rounded-4 shadow-sm mb-3 d-flex flex-column overflow-hidden">
            <div class="table-responsive flex-grow-1 h-100 overflow-auto">
                @if(count($cart) > 0)
                <table class="table table-hover align-middle mb-0 text-nowrap" style="font-size: 0.9rem;">
                    <thead class="sticky-top shadow-sm" style="background-color: var(--bs-secondary-bg); z-index: 1;">
                        <tr>
                            <th>{{ __('Item') }}</th>
                            <th width="20%">{{ __('Qty') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                <tbody>
                    @foreach($cart as $item)
                        <tr>
                            <td class="fw-semibold">
                                {{ $item['name'] }} <br> 
                                <small class="text-primary fw-bold">Rs. {{ number_format($item['price'], 2) }}</small>
                                
                                <!-- Discount Toggle per item inside a popover or just tiny inputs -->
                                <div class="input-group input-group-sm mt-1" style="max-width: 120px;">
                                    <span class="input-group-text p-1" style="font-size: 0.7rem;">{{ __('Disc') }}</span>
                                    <input type="number" step="0.01" min="0" class="form-control p-1" style="font-size: 0.7rem;" value="{{ $item['discount'] }}" wire:change="updateCart('{{ $item['cart_id'] }}', 'discount', $event.target.value)">
                                    <select class="form-select p-1" wire:change="updateCart('{{ $item['cart_id'] }}', 'discount_type', $event.target.value)" style="font-size: 0.7rem; max-width: 40px;">
                                        <option value="amount" {{ $item['discount_type'] == 'amount' ? 'selected' : '' }}>$</option>
                                        <option value="percentage" {{ $item['discount_type'] == 'percentage' ? 'selected' : '' }}>%</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <input type="number" min="1" class="form-control form-control-sm text-center" value="{{ $item['qty'] }}" wire:change="updateCart('{{ $item['cart_id'] }}', 'qty', $event.target.value)">
                            </td>
                            <td class="fw-bold text-end">Rs. {{ number_format($item['item_total'], 2) }}</td>
                            <td class="text-end">
                                <button wire:click="removeFromCart('{{ $item['cart_id'] }}')" class="btn btn-sm btn-light text-danger hover-lift">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted w-100">
                <svg class="mb-3 opacity-50" width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <h5 class="fw-bold">{{ __('Cart is empty.') }}</h5>
                <span class="small">{{ __('Scan a barcode or select items.') }}</span>
            </div>
            @endif
            </div>
        </div>

        <!-- Small Footer Summary -->
        <div class="bg-body-secondary p-3 rounded-4 shadow-sm hover-lift d-flex justify-content-between align-items-center" style="transition-duration: 0.5s;">
            <div>
                <span class="text-muted small fw-bold d-block">{{ __('Net Total') }}</span>
                <span class="text-primary fw-bolder fs-4">Rs. {{ number_format($grandTotal, 2) }}</span>
            </div>
            <button id="pay-now-btn" class="btn btn-primary btn-lg fw-bold shadow hover-lift px-4" data-bs-toggle="modal" data-bs-target="#checkoutModal" {{ empty($cart) ? 'disabled' : '' }}>
                {{ __('Pay Now [F1]') }}
            </button>
        </div>
    </div>
    </div>
    </div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold fs-4" id="checkoutModalLabel">{{ __('Complete Checkout') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold text-muted mb-1">{{ __('Customer Name (Optional)') }}</label>
                        <input type="text" class="form-control bg-body-tertiary" wire:model="customerName" placeholder="{{ __('Enter customer name') }}">
                    </div>
                    
                    <div class="col-12 d-flex justify-content-between align-items-center mt-4">
                        <span class="text-muted fw-bold">{{ __('Sub Total') }}</span>
                        <span class="fw-bold fs-5">Rs. {{ number_format($subTotal, 2) }}</span>
                    </div>
                    
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <span class="text-muted fw-bold">{{ __('Discount') }}</span>
                        <div class="input-group" style="max-width: 150px;">
                            <input type="number" step="0.01" min="0" class="form-control text-end" wire:model.live.debounce.500ms="billDiscount">
                            <select class="form-select" wire:model.live="billDiscountType" style="max-width: 60px;">
                                <option value="amount">$</option>
                                <option value="percentage">%</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12">
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-body fw-bolder fs-5">{{ __('Net Total') }}</span>
                            <span class="text-primary fw-bolder fs-3">Rs. {{ number_format($grandTotal, 2) }}</span>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <label class="form-label fw-bold text-success mb-1">{{ __('Cash Given (Tendered)') }}</label>
                        <input type="number" step="0.01" class="form-control form-control border-success border-2 text-end fw-bold fs-4" wire:model.live.debounce.300ms="tenderedAmount" placeholder="0.00">
                    </div>

                    <div class="col-12 mt-3">
                        <div class="p-3 rounded-3 text-center {{ $balance < 0 ? 'bg-danger text-white shadow' : 'bg-success-subtle text-success' }}" style="transition: all 0.3s;">
                            <span class="d-block fw-bold mb-1">{{ $balance < 0 ? __('Credit Balance (Due)') : __('Change to Return') }}</span>
                            <span class="fs-1 fw-bolder lh-1">Rs. {{ number_format(abs($balance), 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pb-4 px-4">
                <button type="button" class="btn btn-light bg-body fw-bold px-4" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button wire:click="checkout" class="btn btn-primary btn-lg fw-bold shadow flex-grow-1" data-bs-dismiss="modal" {{ empty($cart) ? 'disabled' : '' }}>
                    {{ __('Confirm Order') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Print Preview Modal -->
<div class="modal fade" id="printPreviewModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-success"><i class="fas fa-check-circle me-2"></i>Order Complete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="$set('showPrintModal', false)"></button>
            </div>
            <div class="modal-body text-center p-4">
                <p class="mb-3 text-muted">Invoice generated successfully.</p>
                <div id="printPreviewFrameContainer" class="bg-body-tertiary rounded-3 p-2 mb-3" style="height: 300px; overflow: hidden; position: relative;">
                    <!-- Iframe will be injected here by JS -->
                    <div class="d-flex justify-content-center align-items-center h-100" id="printLoader">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex flex-column gap-2">
                    <button type="button" id="btnManualPrint" class="btn btn-primary btn-lg fw-bold rounded-pill">
                        <i class="fas fa-print me-2"></i> Print Bill
                    </button>
                    <button type="button" class="btn btn-light fw-bold rounded-pill" data-bs-dismiss="modal" wire:click="$set('showPrintModal', false)">
                        New Order (Next)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Batch Selection Modal -->
<div class="modal fade {{ $showBatchSelectionModal ? 'show d-block' : '' }}" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold fs-4">
                    {{ __('Select Batch for') }} <span class="text-primary">{{ $selectedItemForBatches ? $selectedItemForBatches->name : '' }}</span>
                </h5>
                <button type="button" class="btn-close" wire:click="$set('showBatchSelectionModal', false)"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('Batch ID') }} / {{ __('Barcode') }}</th>
                                <th>{{ __('Stock') }}</th>
                                <th>{{ __('Expiry') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th class="text-end">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($selectedItemForBatches)
                                @foreach($selectedItemForBatches->batches as $batch)
                                    @if($batch->is_active && $batch->quantity > 0)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">{{ $batch->batch_no ?: '-' }} <span class="badge bg-secondary ms-1">Loose</span></div>
                                            <small class="text-muted">{{ $batch->barcode }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success rounded-pill">{{ $batch->quantity }}</span>
                                        </td>
                                        <td>
                                            @if($batch->expiry_date)
                                                <span class="badge {{ \Carbon\Carbon::parse($batch->expiry_date)->isPast() ? 'bg-danger' : (\Carbon\Carbon::parse($batch->expiry_date)->diffInDays(now()) < 30 ? 'bg-warning text-dark' : 'bg-info') }}">
                                                    {{ \Carbon\Carbon::parse($batch->expiry_date)->format('Y-m-d') }}
                                                </span>
                                            @else
                                                <span class="text-muted small">{{ __('No Expiry') }}</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold">Rs. {{ number_format($batch->selling_price, 2) }}</td>
                                        <td class="text-end">
                                            <button class="btn btn-primary btn-sm px-3 fw-bold" wire:click="addToCart({{ $selectedItemForBatches->id }}, {{ $batch->id }})">
                                                {{ __('Select') }}
                                            </button>
                                        </td>
                                    </tr>



                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 pb-4 px-4">
                <button type="button" class="btn btn-light bg-body fw-bold px-4" wire:click="$set('showBatchSelectionModal', false)">{{ __('Cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('keydown', function(e) {
        // F1: Pay Now
        if (e.key === 'F1') {
            e.preventDefault();
            const payBtn = document.getElementById('pay-now-btn');
            if (payBtn && !payBtn.disabled) {
                payBtn.click();
            }
        }
        // F2: Focus Search
        if (e.key === 'F2') {
            e.preventDefault();
            document.getElementById('search-input')?.focus();
        }
        // F3: Focus Barcode
        if (e.key === 'F3') {
            e.preventDefault();
            document.getElementById('barcode-input')?.focus();
        }
        // F4: Clear Cart (Triggers Livewire action)
        if (e.key === 'F4') {
            e.preventDefault();
            if(confirm("Are you sure you want to clear the cart?")) {
                @this.call('clearCart');
            }
        }
    });

    // --- PRINT ENGINE ---
    let printSettings = @json($printSettings ?? ['method' => 'browser', 'auto_print' => '0']);
    let currentInvoiceUrl = '';

    window.addEventListener('invoice-created', event => {
        let invoiceId = event.detail.invoiceId;
        currentInvoiceUrl = `/invoice/${invoiceId}/print`;
        
        // Show Modal
        let printModal = new bootstrap.Modal(document.getElementById('printPreviewModal'));
        printModal.show();
        
        // Load iframe
        let container = document.getElementById('printPreviewFrameContainer');
        document.getElementById('printLoader').classList.remove('d-none');
        
        // Remove old iframe if any
        let oldIframe = document.getElementById('receiptIframe');
        if (oldIframe) oldIframe.remove();
        
        let iframe = document.createElement('iframe');
        iframe.id = 'receiptIframe';
        iframe.src = currentInvoiceUrl;
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = 'none';
        iframe.onload = function() {
            document.getElementById('printLoader').classList.add('d-none');
            
            if (printSettings.auto_print === '1') {
                executePrint();
            }
        };
        container.appendChild(iframe);
    });

    document.getElementById('btnManualPrint').addEventListener('click', function() {
        executePrint();
    });

    function executePrint() {
        let method = printSettings.method;
        
        if (method === 'rawbt') {
            // Android RawBT Intent
            // Note: The print page should ideally return raw text for rawbt, but HTML works as fallback for RawBT if configured
            let intentUrl = "intent:" + window.location.origin + currentInvoiceUrl + "#Intent;scheme=http;package=ru.a402d.rawbtprinter;end;";
            window.location.href = intentUrl;
        } 
        else if (method === 'qz') {
            // QZ Tray integration (Placeholder)
            alert('QZ Tray print requested. (Integration requires QZ-Tray running and setup). Falling back to browser print.');
            printViaIframe();
        } 
        else {
            // Default Browser Print
            printViaIframe();
        }
    }

    function printViaIframe() {
        let iframe = document.getElementById('receiptIframe');
        if (iframe && iframe.contentWindow) {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        }
    }

</script>

    <!-- Cash Register Modals -->
    @if($showOpenRegisterModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">Open Register (Start Shift)</h5>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted">Please enter the opening cash balance in the drawer to start your shift.</p>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-2">Opening Balance (Rs.)</label>
                        <input type="number" step="0.01" wire:model="registerOpeningBalance" class="form-control form-control-lg text-center fw-bold">
                        @error('registerOpeningBalance') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="openRegister" class="btn btn-primary w-100 btn-lg fw-bold">Open Register</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($showCloseRegisterModal)
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5); z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title fw-bold text-dark">Close Register (End Shift)</h5>
                    <button type="button" class="btn-close" wire:click="$set('showCloseRegisterModal', false)"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4 p-3 bg-light rounded text-center border">
                        <small class="text-muted d-block fw-bold text-uppercase tracking-wider">Expected Cash in Drawer</small>
                        <strong class="fs-1 text-primary">Rs. {{ number_format($activeRegister->expected_closing_balance ?? 0, 2) }}</strong>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-2">Actual Closing Balance (Rs.)</label>
                        <input type="number" step="0.01" wire:model="registerClosingBalance" class="form-control form-control-lg text-center fw-bold">
                        @error('registerClosingBalance') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-2">Notes (Optional)</label>
                        <textarea wire:model="registerNotes" class="form-control" rows="2" placeholder="e.g. Cash discrepancy reason..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="closeRegister" class="btn btn-danger w-100 btn-lg fw-bold">Confirm & Close Register</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
