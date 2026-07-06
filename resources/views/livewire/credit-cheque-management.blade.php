<div>
    <div class="pb-5">
        
        <div class="mb-4">
            <h2 class="fs-4 fw-bold text-body">{{ __('Credit & Cheque Management') }}</h2>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-pills mb-4 gap-2">
            <li class="nav-item">
                <button class="nav-link fw-bold px-4 rounded-pill {{ $activeTab === 'received' ? 'active' : 'bg-body-tertiary text-body' }}" 
                        wire:click="switchTab('received')">
                    {{ __('Received (From Customers)') }}
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold px-4 rounded-pill {{ $activeTab === 'issued' ? 'active' : 'bg-body-tertiary text-body' }}" 
                        wire:click="switchTab('issued')">
                    {{ __('Issued (To Suppliers)') }}
                </button>
            </li>
        </ul>

        <!-- Action Buttons & Search -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div style="max-width: 350px; width: 100%;">
                <div class="input-group input-group-sm bg-body-tertiary border rounded-3 overflow-hidden shadow-sm">
                    <span class="input-group-text bg-transparent border-0 text-body-tertiary pe-2">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control border-0 bg-transparent shadow-none" placeholder="{{ __('Search by Name, Invoice No, Cheque No...') }}">
                </div>
            </div>
            
            @if($activeTab === 'issued')
            <div class="d-flex gap-2">
                <button wire:click="openIssueCreditModal" class="btn btn-warning fw-bold d-inline-flex align-items-center gap-2">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/></svg>
                    {{ __('Issue Credit') }}
                </button>
                <button wire:click="openIssueChequeModal" class="btn btn-primary-custom fw-bold d-inline-flex align-items-center gap-2">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/></svg>
                    {{ __('Issue Cheque') }}
                </button>
            </div>
            @endif
        </div>

        @if(session()->has('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 py-2 px-3 fw-bold mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Credit Management Section -->
        <div class="mb-4 pb-4">
            <h4 class="fw-bold fs-5 text-body mb-3">{{ __('Credit Records') }} ({{ ucfirst($activeTab) }})</h4>

            <!-- Credit Table -->
            <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 fw-bold bg-primary bg-opacity-10 text-primary">{{ __('Reference / Invoice') }}</th>
                                <th class="py-3 px-4 fw-bold bg-primary bg-opacity-10 text-primary">{{ __('Name') }}</th>
                                <th class="py-3 px-4 fw-bold bg-primary bg-opacity-10 text-primary">{{ __('Credit Amount') }}</th>
                                <th class="py-3 px-4 fw-bold bg-primary bg-opacity-10 text-primary">{{ __('Due Date') }}</th>
                                <th class="py-3 px-4 fw-bold bg-primary bg-opacity-10 text-primary">{{ __('Status') }}</th>
                                <th class="py-3 px-4 fw-bold bg-primary bg-opacity-10 text-primary text-end">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-body">
                            @forelse($credits as $index => $credit)
                            <tr>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    @if($credit->type === 'received')
                                        IVN {{ $credit->invoice_id }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    @if($credit->type === 'received')
                                        {{ $credit->invoice->customer_name ?? __('Unknown Customer') }}
                                    @else
                                        {{ $credit->supplier->name ?? __('Unknown Supplier') }}
                                    @endif
                                </td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">Rs. {{ number_format($credit->amount, 2) }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    {{ \Carbon\Carbon::parse($credit->due_date)->format('d/m/Y') }}
                                    @if(\Carbon\Carbon::parse($credit->due_date)->isToday() && $credit->status == 'Pending')
                                        <span class="badge bg-danger ms-2">{{ __('Today') }}</span>
                                    @elseif(\Carbon\Carbon::parse($credit->due_date)->isPast() && $credit->status == 'Pending')
                                        <span class="badge bg-danger ms-2">{{ __('Overdue') }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    <span class="badge {{ $credit->status == 'Cleared' ? 'bg-success' : 'bg-warning text-body' }}">{{ $credit->status }}</span>
                                </td>
                                <td class="py-3 px-4 text-end {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    @if($credit->status == 'Pending')
                                        <button wire:click="clearCredit({{ $credit->id }})" class="btn btn-primary-custom btn-sm fw-bold px-3 py-1 rounded-2" style="font-size: 0.75rem;">{{ __('Clear Credit') }}</button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-muted">{{ __('No credit records found.') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Cheque Management Section -->
        <div class="mb-4 pb-4">
            <h4 class="fw-bold fs-5 text-body mb-3">{{ __('Cheque Records') }} ({{ ucfirst($activeTab) }})</h4>

            <!-- Cheque Table -->
            <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden mb-4">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 fw-bold bg-primary bg-opacity-10 text-primary">{{ __('Reference / Invoice') }}</th>
                                <th class="py-3 px-4 fw-bold bg-primary bg-opacity-10 text-primary">{{ __('Name') }}</th>
                                <th class="py-3 px-4 fw-bold bg-primary bg-opacity-10 text-primary">{{ __('Cheque Details') }}</th>
                                <th class="py-3 px-4 fw-bold bg-primary bg-opacity-10 text-primary">{{ __('Amount') }}</th>
                                <th class="py-3 px-4 fw-bold bg-primary bg-opacity-10 text-primary">{{ __('Due Date') }}</th>
                                <th class="py-3 px-4 fw-bold bg-primary bg-opacity-10 text-primary">{{ __('Status') }}</th>
                                <th class="py-3 px-4 fw-bold bg-primary bg-opacity-10 text-primary text-end">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-body">
                            @forelse($cheques->where('status', '!=', 'Return') as $index => $cheque)
                            <tr>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    @if($cheque->type === 'received')
                                        IVN {{ $cheque->invoice_id }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    @if($cheque->type === 'received')
                                        {{ $cheque->invoice->customer_name ?? __('Unknown Customer') }}
                                    @else
                                        {{ $cheque->supplier->name ?? __('Unknown Supplier') }}
                                    @endif
                                </td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    No: {{ $cheque->cheque_no }} <br>
                                    <small class="text-muted">{{ $cheque->bank_name ?? __('Bank Unknown') }}</small>
                                </td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">Rs. {{ number_format($cheque->amount, 2) }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    {{ \Carbon\Carbon::parse($cheque->due_date)->format('d/m/Y') }}
                                    @if(\Carbon\Carbon::parse($cheque->due_date)->isToday() && $cheque->status == 'Pending')
                                        <span class="badge bg-danger ms-2">{{ __('Today') }}</span>
                                    @elseif(\Carbon\Carbon::parse($cheque->due_date)->isPast() && $cheque->status == 'Pending')
                                        <span class="badge bg-danger ms-2">{{ __('Overdue') }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    <span class="badge {{ $cheque->status == 'Cash Done' ? 'bg-success' : 'bg-warning text-body' }}">{{ $cheque->status }}</span>
                                </td>
                                <td class="py-3 px-4 text-end {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    @if($cheque->status == 'Pending')
                                        <div class="d-flex justify-content-end gap-2">
                                            <button wire:click="clearCheque({{ $cheque->id }})" class="btn btn-primary-custom btn-sm fw-bold px-3 py-1 rounded-2" style="font-size: 0.75rem;">{{ __('Clear Cheque') }}</button>
                                            <button wire:click="toggleChequeReject({{ $cheque->id }}, true)" class="btn btn-outline-danger btn-sm fw-bold px-3 py-1 rounded-2" style="font-size: 0.75rem;">{{ __('Reject') }}</button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-muted">{{ __('No cheque records found.') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rejected Cheques Section -->
            @if($cheques->where('status', 'Return')->count() > 0)
            <h6 class="fw-bold text-body mb-3 fs-6">{{ __('Rejected Cheque Table') }}</h6>
            <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden mb-4">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 fw-bold bg-danger bg-opacity-10 text-danger">{{ __('Reference / Invoice') }}</th>
                                <th class="py-3 px-4 fw-bold bg-danger bg-opacity-10 text-danger">{{ __('Name') }}</th>
                                <th class="py-3 px-4 fw-bold bg-danger bg-opacity-10 text-danger">{{ __('Cheque Details') }}</th>
                                <th class="py-3 px-4 fw-bold bg-danger bg-opacity-10 text-danger">{{ __('Amount') }}</th>
                                <th class="py-3 px-4 fw-bold bg-danger bg-opacity-10 text-danger">{{ __('Rejected Date') }}</th>
                                <th class="py-3 px-4 fw-bold bg-danger bg-opacity-10 text-danger text-end">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-body">
                            @foreach($cheques->where('status', 'Return') as $index => $cheque)
                            <tr>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    @if($cheque->type === 'received')
                                        IVN {{ $cheque->invoice_id }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    @if($cheque->type === 'received')
                                        {{ $cheque->invoice->customer_name ?? __('Unknown Customer') }}
                                    @else
                                        {{ $cheque->supplier->name ?? __('Unknown Supplier') }}
                                    @endif
                                </td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    No: {{ $cheque->cheque_no }} <br>
                                    <small class="text-muted">{{ $cheque->bank_name ?? __('Bank Unknown') }}</small>
                                </td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">Rs. {{ number_format($cheque->amount, 2) }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    {{ $cheque->return_date ? \Carbon\Carbon::parse($cheque->return_date)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-end {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    <button wire:click="toggleChequeReject({{ $cheque->id }}, false)" class="btn btn-outline-secondary btn-sm fw-bold px-3 py-1 rounded-2" style="font-size: 0.75rem;">{{ __('Re-Activate') }}</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

    </div>

    <!-- Issue Cheque Modal -->
    <div class="modal fade {{ $showIssueChequeModal ? 'show d-block' : '' }}" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">{{ __('Issue Cheque to Supplier') }}</h5>
                    <button type="button" wire:click="closeModals" class="btn-close"></button>
                </div>
                <div class="modal-body p-3">
                    <form wire:submit.prevent="saveIssueCheque">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-body small mb-1">{{ __('Select Supplier') }}</label>
                            <select wire:model="issueSupplierId" class="form-select rounded-3">
                                <option value="">{{ __('-- Choose Supplier --') }}</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ $supplier->code }})</option>
                                @endforeach
                            </select>
                            @error('issueSupplierId') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-body small mb-1">{{ __('Cheque No') }}</label>
                                <input type="text" wire:model="issueChequeNo" class="form-control rounded-3" placeholder="e.g. 123456">
                                @error('issueChequeNo') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-body small mb-1">{{ __('Bank Name') }}</label>
                                <input type="text" wire:model="issueBankName" class="form-control rounded-3" placeholder="e.g. BOC">
                                @error('issueBankName') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4 pb-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-body small mb-1">{{ __('Amount (Rs)') }}</label>
                                <input type="number" step="0.01" wire:model="issueAmount" class="form-control rounded-3" placeholder="0.00">
                                @error('issueAmount') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-body small mb-1">{{ __('Due Date') }}</label>
                                <input type="date" wire:model="issueDueDate" class="form-control rounded-3">
                                @error('issueDueDate') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="button" wire:click="closeModals" class="btn btn-outline-primary-custom fw-bold py-2 flex-grow-1 rounded-3 bg-body">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary-custom fw-bold py-2 flex-grow-1 rounded-3 shadow-sm">{{ __('Issue Cheque') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Issue Credit Modal -->
    <div class="modal fade {{ $showIssueCreditModal ? 'show d-block' : '' }}" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">{{ __('Issue Credit to Supplier') }}</h5>
                    <button type="button" wire:click="closeModals" class="btn-close"></button>
                </div>
                <div class="modal-body p-3">
                    <form wire:submit.prevent="saveIssueCredit">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-body small mb-1">{{ __('Select Supplier') }}</label>
                            <select wire:model="issueSupplierId" class="form-select rounded-3">
                                <option value="">{{ __('-- Choose Supplier --') }}</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }} ({{ $supplier->code }})</option>
                                @endforeach
                            </select>
                            @error('issueSupplierId') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="row g-3 mb-4 pb-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-body small mb-1">{{ __('Amount (Rs)') }}</label>
                                <input type="number" step="0.01" wire:model="issueAmount" class="form-control rounded-3" placeholder="0.00">
                                @error('issueAmount') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-body small mb-1">{{ __('Due Date') }}</label>
                                <input type="date" wire:model="issueDueDate" class="form-control rounded-3">
                                @error('issueDueDate') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="button" wire:click="closeModals" class="btn btn-outline-primary-custom fw-bold py-2 flex-grow-1 rounded-3 bg-body">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary-custom fw-bold py-2 flex-grow-1 rounded-3 shadow-sm">{{ __('Issue Credit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
