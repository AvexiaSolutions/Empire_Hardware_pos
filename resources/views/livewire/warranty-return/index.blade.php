<div>
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-1">Warranty & Returns</h3>
            <p class="text-body-secondary mb-0">Manage item returns and warranty claims</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 rounded-4 shadow-sm mb-4">
        <div class="card-body p-4">
            <form wire:submit.prevent="search" class="d-flex gap-2">
                <input type="text" wire:model="searchInvoiceNo" class="form-control form-control-lg rounded-3" placeholder="Enter Bill / Invoice Number (e.g., INV-000001)" required>
                <button type="submit" class="btn btn-primary-custom btn-lg rounded-3 px-4 fw-bold">
                    <span wire:loading.remove wire:target="search">Search</span>
                    <span wire:loading wire:target="search" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </button>
            </form>
        </div>
    </div>

    @if($invoice)
    <div class="card border-0 rounded-4 shadow-sm">
        <div class="card-header bg-body border-bottom-0 pt-4 pb-3 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Invoice Details: <span class="text-primary-custom">{{ $invoice->invoice_no }}</span></h5>
                <span class="badge bg-secondary fs-6">{{ \Carbon\Carbon::parse($invoice->date)->format('M d, Y') }}</span>
            </div>
            @if($invoice->customer_name)
                <div class="mt-2 text-muted">Customer: {{ $invoice->customer_name }}</div>
            @endif
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Item</th>
                            <th>Batch</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                            <th>Warranty Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                            @php
                                $hasWarranty = $item->item->has_warranty;
                                $warrantyValid = false;
                                $warrantyEndDate = null;
                                if ($hasWarranty) {
                                    $warrantyEndDate = \Carbon\Carbon::parse($invoice->date)->addMonths($item->item->warranty_months);
                                    $warrantyValid = $warrantyEndDate->isFuture() || $warrantyEndDate->isToday();
                                }
                                $returnedQty = \App\Models\ReturnLog::where('invoice_item_id', $item->id)->sum('quantity');
                                $isFullyReturned = $returnedQty >= $item->quantity;
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($item->item->image)
                                            <img src="{{ asset('storage/' . $item->item->image) }}" alt="{{ $item->item->name }}" class="rounded" width="40" height="40" style="object-fit: cover;">
                                        @else
                                            <div class="bg-body-secondary rounded d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $item->item->name }}</div>
                                            <div class="text-muted small">{{ $item->item->code }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($item->itemBatch)
                                        <span class="badge bg-light text-dark border">{{ $item->itemBatch->batch_no }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    {{ number_format($item->quantity, 2) }}
                                    @if($returnedQty > 0)
                                        <br><span class="badge bg-danger">Returned: {{ number_format($returnedQty, 2) }}</span>
                                    @endif
                                </td>
                                <td>Rs {{ number_format($item->unit_price, 2) }}</td>
                                <td>Rs {{ number_format($item->total, 2) }}</td>
                                <td>
                                    @if($hasWarranty)
                                        @if($warrantyValid)
                                            <span class="badge bg-success">Valid till {{ $warrantyEndDate->format('Y-m-d') }}</span>
                                        @else
                                            <span class="badge bg-danger">Expired on {{ $warrantyEndDate->format('Y-m-d') }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted small">No Warranty</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if(!$isFullyReturned)
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle rounded-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Options
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                @if($hasWarranty && $warrantyValid)
                                                    <li>
                                                        <button class="dropdown-item text-primary fw-bold" wire:click="processReturn({{ $item->id }}, 'warranty_claim')" onclick="confirm('Process warranty claim for this item?') || event.stopImmediatePropagation()">
                                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" class="me-2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            Claim Warranty
                                                        </button>
                                                    </li>
                                                @endif
                                                <li>
                                                    <button class="dropdown-item" wire:click="processReturn({{ $item->id }}, 'change')" onclick="confirm('Return this item and add back to stock (Change)?') || event.stopImmediatePropagation()">
                                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" class="me-2 text-success" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                                        Return (Change)
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item" wire:click="processReturn({{ $item->id }}, 'damage')" onclick="confirm('Mark this item as damaged? It will NOT be added to regular stock.') || event.stopImmediatePropagation()">
                                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" class="me-2 text-danger" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                        Return (Damage)
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    @else
                                        <span class="badge bg-secondary">Processed</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
