<div class="row w-100 h-100 m-0 gap-4" style="animation: slideIn 0.5s ease-out;">
    <!-- Welcome / Advertisement Section -->
    <div class="col h-100 d-flex flex-column justify-content-center align-items-center text-center p-5 position-relative overflow-hidden glass-panel">
        <div class="mb-5 position-relative" style="z-index: 2;">
            <h1 class="display-1 fw-bold outfit-font mb-4 gradient-text">
                {{ config('app.name', 'Empire POS') }}
            </h1>
            <p class="fs-2 text-slate-300 mb-0 opacity-75">
                {{ count($cart) > 0 ? __('Here is your order summary') : __('Welcome! Please scan an item to begin.') }}
            </p>
        </div>
        
        @if(count($cart) == 0)
            <div class="text-center opacity-50" style="animation: pulse 2s infinite;">
                <svg width="120" height="120" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            
            <style>
                @keyframes pulse {
                    0% { transform: scale(1); opacity: 0.3; }
                    50% { transform: scale(1.05); opacity: 0.5; }
                    100% { transform: scale(1); opacity: 0.3; }
                }
            </style>
        @else
            <!-- Display some nice floating items or logo when active -->
            <div class="w-100 mt-auto p-4 rounded-4" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                <h3 class="fs-4 text-white-50 mb-3">Thank you for shopping with us!</h3>
            </div>
        @endif
    </div>

    <!-- Order Summary Panel -->
    <div class="col-5 h-100 d-flex flex-column glass-panel overflow-hidden p-0">
        
        <div class="p-4 border-bottom" style="border-color: rgba(255,255,255,0.1) !important;">
            <h2 class="fs-3 fw-bold outfit-font mb-0">Order Summary</h2>
        </div>

        <div class="flex-grow-1 overflow-auto p-4 d-flex flex-column gap-3" id="cart-items">
            @if(count($cart) > 0)
                @foreach($cart as $item)
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05);">
                        <div class="d-flex flex-column">
                            <span class="fs-4 fw-semibold">{{ $item['name'] }}</span>
                            <span class="fs-6 text-white-50">{{ $item['qty'] }} x Rs. {{ number_format($item['price'], 2) }}</span>
                        </div>
                        <div class="text-end">
                            <span class="fs-4 fw-bold text-info">Rs. {{ number_format($item['item_total'], 2) }}</span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="d-flex h-100 flex-column justify-content-center align-items-center opacity-50">
                    <svg width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mb-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="fs-5">Cart is empty</p>
                </div>
            @endif
        </div>

        <!-- Total Panel -->
        <div class="p-4" style="background: rgba(15, 23, 42, 0.9); border-top: 1px solid rgba(255,255,255,0.1);">
            <div class="d-flex justify-content-between align-items-end mb-2">
                <span class="fs-4 text-white-50">Total Amount</span>
                <span class="fs-1 fw-bold outfit-font gradient-text" style="line-height: 1;">
                    Rs. {{ number_format($grandTotal, 2) }}
                </span>
            </div>
            @if(count($cart) > 0)
            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                <span class="fs-6 text-white-50">Items: {{ count($cart) }}</span>
                <span class="badge bg-info text-dark rounded-pill px-3 py-2 fs-6 fw-bold">Please proceed to checkout</span>
            </div>
            @endif
        </div>
    </div>
</div>
