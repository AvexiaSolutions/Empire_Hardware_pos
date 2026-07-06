<div>
    <div class="pb-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('invoice.index') }}" class="btn btn-light bg-body border shadow-sm fw-bold d-inline-flex align-items-center gap-2 px-3 rounded-pill py-2">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg> {{ __('Back') }}
                </a>
                <h2 class="fs-4 fw-bold text-body mb-0 ms-2">{{ __('Create Invoice') }}</h2>
            </div>
            @if(session('error'))
                <div class="alert alert-danger mb-0 py-2">{{ session('error') }}</div>
            @endif
        </div>

        <div class="card border-0 rounded-4 shadow-sm bg-body p-3 p-lg-4">
            
            <!-- Top Details -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-md-8">
                    <div class="row g-3">
                        <!-- Customer Name -->
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold text-body small">{{ __('Customer Name') }}</label>
                            <input type="text" wire:model="customerName" class="form-control form-control-sm rounded-3" placeholder="{{ __('Walk-in Customer') }}">
                        </div>
                        
                        <!-- Invoice No -->
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold text-body small">{{ __('Invoice No') }}</label>
                            <input type="text" class="form-control form-control-sm rounded-3" wire:model="invoiceNo" readonly>
                        </div>

                        <!-- Customer Phone Number -->
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold text-body small">{{ __('Customer Phone Number') }}</label>
                            <input type="text" wire:model="customerPhone" class="form-control form-control-sm rounded-3" placeholder="077*********8">
                        </div>

                        <!-- Date -->
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold text-body small">{{ __('Date') }}</label>
                            <input type="date" wire:model="invoiceDate" class="form-control form-control-sm rounded-3">
                        </div>
                    </div>
                </div>

                <!-- Balance -->
                <div class="col-12 col-md-4 d-flex flex-column justify-content-start align-items-end pt-2">
                    <div class="fw-bold text-body mb-1">{{ __('Balance') }}</div>
                    <div class="fs-2 fw-bolder lh-1 text-body">{{ number_format($netAmount, 2) }}</div>
                </div>
            </div>

            <!-- Items Table Area -->
            <div class="table-responsive mb-4">
                <table class="table table-sm table-borderless align-middle fw-semibold text-body mb-0" style="min-width: 600px;">
                    <thead>
                        <tr class="small text-body border-bottom">
                            <th class="pb-3 fw-bold" style="width: 150px;">{{ __('Barcode (Code)') }}</th>
                            <th class="pb-3 fw-bold">{{ __('Item Name') }}</th>
                            <th class="pb-3 fw-bold" style="width: 100px;">{{ __('Qty') }}</th>
                            <th class="pb-3 fw-bold" style="width: 120px;">{{ __('Rate(Rs.)') }}</th>
                            <th class="pb-3 fw-bold" style="width: 160px;">{{ __('Discount') }}</th>
                            <th class="pb-3 fw-bold" style="width: 120px;">{{ __('Amount(Rs.)') }}</th>
                            <th class="pb-3"></th>
                        </tr>
                    </thead>
                    <tbody class="border-bottom">
                        @foreach($cart as $batchId => $item)
                        <tr>
                            <td class="py-3"><input type="text" class="form-control form-control-sm rounded-3 bg-body" value="{{ $item['barcode'] }}" readonly></td>
                            <td class="py-3">
                                <input type="text" class="form-control form-control-sm rounded-3 bg-body" value="{{ $item['name'] }}" readonly>
                                @if($item['has_bulk'])
                                <div class="mt-2">
                                    <select wire:change="updateCart({{ $batchId }}, 'sale_type', $event.target.value)" class="form-select form-select-sm rounded-3 bg-body-tertiary text-primary fw-bold border-primary">
                                        <option value="base" {{ $item['sale_type'] == 'base' ? 'selected' : '' }}>{{ $item['base_unit'] }}</option>
                                        <option value="bulk" {{ $item['sale_type'] == 'bulk' ? 'selected' : '' }}>{{ $item['bulk_unit'] }}</option>
                                    </select>
                                </div>
                                @else
                                <div class="mt-1 small text-muted">{{ $item['base_unit'] }}</div>
                                @endif
                            </td>
                            <td class="py-3">
                                <input type="number" wire:change="updateCart({{ $batchId }}, 'quantity', $event.target.value)" class="form-control rounded-3 bg-body text-center" value="{{ $item['quantity'] }}">
                            </td>
                            <td class="py-3">
                                <input type="number" wire:change="updateCart({{ $batchId }}, 'rate', $event.target.value)" class="form-control rounded-3 bg-body text-end" value="{{ $item['rate'] }}">
                            </td>
                            <td class="py-3">
                                <div class="d-flex gap-2">
                                    <input type="number" wire:change="updateCart({{ $batchId }}, 'discount', $event.target.value)" class="form-control rounded-3 bg-body text-end" value="{{ $item['discount'] }}">
                                    <select wire:change="updateCart({{ $batchId }}, 'discount_type', $event.target.value)" class="form-select form-select-sm rounded-3 bg-body px-2" style="width: 60px;">
                                        <option value="Rs" {{ $item['discount_type'] == 'Rs' ? 'selected' : '' }}>Rs</option>
                                        <option value="%" {{ $item['discount_type'] == '%' ? 'selected' : '' }}>%</option>
                                    </select>
                                </div>
                            </td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body-tertiary text-end" value="{{ number_format($item['amount'], 2) }}" readonly></td>
                            <td class="py-3 text-center">
                                <button wire:click="removeFromCart({{ $batchId }})" class="btn btn-link text-danger p-0">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        
                        <!-- Input Row -->
                        <tr>
                            <td class="py-3">
                                <input type="text" wire:model.live.debounce.300ms="barcodeInput" wire:keydown.enter="scanBarcode" class="form-control rounded-3 {{ $posFastMode ? 'bg-body border-primary shadow-sm' : 'bg-body-tertiary' }}" placeholder="{{ __('Scan Barcode') }}">
                            </td>
                            <td class="py-3">
                                <select wire:model="manualBatchId" wire:change="addManualItem" class="form-select form-select-sm rounded-3 bg-body-tertiary">
                                    <option value="">{{ __('Select Item Manually') }}</option>
                                    @foreach($availableBatches as $batch)
                                        <option value="{{ $batch->id }}">{{ $batch->item->name }} ({{ $batch->barcode }}) - Rs.{{ $batch->selling_price }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td colspan="5" class="py-3">
                                @if($posFastMode)
                                <small class="text-primary-custom fw-bold d-flex align-items-center gap-1 mt-2">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> 
                                    {{ __('Fast Mode ON (Scan barcode to auto-add)') }}
                                </small>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Bottom Section -->
            <div class="row g-5 mt-2">
                <!-- Note -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold text-body">{{ __('Note') }}</label>
                    <textarea wire:model="note" class="form-control form-control-sm rounded-3 bg-body" rows="6" placeholder="{{ __('Write some thing .........') }}"></textarea>
                </div>

                <!-- Totals & Payment -->
                <div class="col-12 col-md-6 d-flex flex-column gap-3">
                    <div class="row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-body">{{ __('Gross Total') }}</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-3 bg-body-tertiary fw-semibold text-end" value="{{ number_format($totalAmount, 2) }}" readonly>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-body">{{ __('Global Discount') }}</label>
                        <div class="col-sm-8 d-flex gap-2">
                            <input type="number" wire:model.live.debounce.500ms="globalDiscount" class="form-control form-control-sm rounded-3 bg-body fw-semibold text-end">
                            <select wire:model.live="globalDiscountType" class="form-select form-select-sm rounded-3 bg-body fw-semibold" style="width: 80px;">
                                <option value="Rs">Rs</option>
                                <option value="%">%</option>
                            </select>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-body fs-5 text-primary-custom">{{ __('Net Amount') }}</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-3 bg-body-tertiary fw-bold text-end text-primary-custom fs-5" value="{{ number_format($netAmount, 2) }}" readonly>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-body">{{ __('Payment Method') }}</label>
                        <div class="col-sm-8">
                            <select wire:model.live="paymentMethod" class="form-select form-select-sm rounded-3 bg-body fw-semibold">
                                <option value="cash">{{ __('Cash') }}</option>
                                <option value="credit">{{ __('Credit') }}</option>
                                <option value="cheque">{{ __('Cheque') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Dynamic Fields based on Payment Method -->
                    @if($paymentMethod == 'cash')
                    <div class="row align-items-center mt-2">
                        <label class="col-sm-4 col-form-label fw-bold text-body">{{ __('Payments') }}</label>
                        <div class="col-sm-8">
                            <input type="number" wire:model="paymentAmount" class="form-control form-control-sm rounded-3 bg-body fw-semibold text-end">
                        </div>
                    </div>
                    @endif

                    @if($paymentMethod == 'credit')
                    <div class="mt-2">
                        <div class="row align-items-center mb-3">
                            <label class="col-sm-4 col-form-label fw-bold text-body">{{ __('Advanced Amount') }}</label>
                            <div class="col-sm-8">
                                <input type="number" wire:model="advancedAmount" class="form-control form-control-sm rounded-3 bg-body fw-semibold text-end">
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <label class="col-sm-4 col-form-label fw-bold text-body">{{ __('Due Date') }}</label>
                            <div class="col-sm-8">
                                <input type="date" wire:model="dueDate" class="form-control form-control-sm rounded-3 bg-body fw-semibold">
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($paymentMethod == 'cheque')
                    <div class="mt-2">
                        <div class="row align-items-center mb-3">
                            <label class="col-sm-4 col-form-label fw-bold text-body">{{ __('Cheque No.') }}</label>
                            <div class="col-sm-8">
                                <input type="text" wire:model="chequeNo" class="form-control form-control-sm rounded-3 bg-body fw-semibold">
                            </div>
                        </div>
                        <div class="row align-items-center mb-3">
                            <label class="col-sm-4 col-form-label fw-bold text-body">{{ __('Bank Name') }}</label>
                            <div class="col-sm-8">
                                <input type="text" wire:model="bankName" class="form-control form-control-sm rounded-3 bg-body fw-semibold">
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <label class="col-sm-4 col-form-label fw-bold text-body">{{ __('Due Date') }}</label>
                            <div class="col-sm-8">
                                <input type="date" wire:model="dueDate" class="form-control form-control-sm rounded-3 bg-body fw-semibold">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end align-items-center gap-4 mt-5 pt-4 border-top">
                <a href="{{ route('invoice.index') }}" class="text-primary-custom fw-bold text-decoration-none">{{ __('Cancel') }}</a>
                <button class="btn btn-outline-primary-custom bg-body fw-bold px-5 py-2 rounded-3 shadow-sm">{{ __('Print') }}</button>
                <button wire:click="saveInvoice" class="btn btn-primary-custom fw-bold px-5 py-2 rounded-3 shadow-sm">{{ __('Save Invoice') }}</button>
            </div>

        </div>
    </div>
</div>
