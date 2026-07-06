<div>
    <div class="pb-5">
        
        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center gap-3 mb-4">
            <h2 class="fs-4 fw-bold text-body mb-0 fs-2 fs-md-1">{{ __('Invoice Management') }}</h2>
            <div>
                <a href="{{ route('pos.index') }}" class="btn btn-primary-custom px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 w-100 w-md-auto justify-content-center">
                    {{ __('Create New Invoice (POS)') }}
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>

        <!-- Reports Section -->
        <div class="row g-4 mb-4">
            
            <!-- Total Sales Report -->
            <div class="col-12 col-md-6 d-flex flex-column gap-3">
                <h5 class="fw-bold text-body mb-0">{{ __('Total Sales Summary') }}</h5>
                <div class="card border-0 rounded-4 shadow-sm text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                    <svg class="position-absolute start-0 top-0 h-100 opacity-25 text-white" style="width: 150%; transform: translateX(-20%);" viewBox="0 0 200 100" preserveAspectRatio="none" fill="currentColor">
                        <ellipse cx="100" cy="150" rx="120" ry="80" />
                    </svg>
                    <div class="card-body p-3 p-lg-5 position-relative z-1 d-flex flex-column justify-content-between h-100">
                        <div>
                            <div class="fw-bold">{{ __('Total Invoices') }}</div>
                            <div class="display-1 fw-bolder lh-1 mt-1" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">{{ $totalInvoicesCount }}</div>
                        </div>
                        <div class="text-end mt-4">
                            <div class="fw-bold">{{ __('Total Revenue') }}</div>
                            <div class="fs-3 fw-bolder lh-1" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">Rs.{{ number_format($totalSales, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Debtors Report -->
            <div class="col-12 col-md-6 d-flex flex-column gap-3">
                <h5 class="fw-bold text-body mb-0">{{ __('Debtors Report (Credit Sales)') }}</h5>
                <div class="card border-0 rounded-4 shadow-sm text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #f87171, #dc2626);">
                    <svg class="position-absolute end-0 top-0 h-100 opacity-25" style="width: 120%; transform: translateX(20%); color: #7f1d1d;" viewBox="0 0 200 100" preserveAspectRatio="none" fill="currentColor">
                        <ellipse cx="100" cy="-50" rx="120" ry="100" />
                    </svg>
                    <div class="card-body p-3 p-lg-5 position-relative z-1 d-flex flex-column justify-content-between h-100">
                        <div>
                            <div class="fw-bold" style="opacity: 0.9;">{{ __('Total Debtors') }}</div>
                            <div class="display-1 fw-bolder lh-1 mt-1" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">{{ $totalDebtorsCount }}</div>
                        </div>
                        <div class="text-end mt-4">
                            <div class="fw-bold" style="opacity: 0.9;">{{ __('Total Owed Amount') }}</div>
                            <div class="fs-3 fw-bolder lh-1" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">Rs.{{ number_format($totalDebtorsAmount, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        @if(count($debtors) > 0)
        <!-- Debtors List -->
        <div class="mb-4">
            <h5 class="fw-bold text-body mb-3 text-danger"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ __('Customers with Pending Balances') }}</h5>
            <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless text-start align-middle mb-0 text-body text-nowrap">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 fw-bold bg-danger-subtle text-danger">{{ __('Invoice No') }}</th>
                            <th class="py-3 px-4 fw-bold bg-danger-subtle text-danger">{{ __('Cus. Name') }}</th>
                            <th class="py-3 px-4 fw-bold bg-danger-subtle text-danger">{{ __('Total Bill') }}</th>
                            <th class="py-3 px-4 fw-bold bg-danger-subtle text-danger">{{ __('Paid') }}</th>
                            <th class="py-3 px-4 fw-bold bg-danger-subtle text-danger">{{ __('Amount Owed') }}</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-body">
                        @foreach($debtors as $i => $debtor)
                        <tr class="{{ $i%2 != 0 ? 'bg-body-tertiary' : '' }}">
                            <td class="py-3 px-4">{{ $debtor->invoice_no }}</td>
                            <td class="py-3 px-4">{{ $debtor->customer_name ?: __('Unknown Customer') }} <br> <small class="text-muted fw-normal">{{ $debtor->date }}</small></td>
                            <td class="py-3 px-4">Rs.{{ number_format($debtor->total, 2) }}</td>
                            <td class="py-3 px-4">Rs.{{ number_format($debtor->tendered_amount, 2) }}</td>
                            <td class="py-3 px-4 text-danger fw-bolder">Rs.{{ number_format(abs($debtor->balance_amount), 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        @endif

        <!-- All Invoices Section -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-end mb-3 mt-4 gap-3">
            <h5 class="fw-bold text-body mb-0">{{ __('All Invoices History') }}</h5>
            <div class="d-flex align-items-center gap-2">
                <label for="dateFilter" class="form-label mb-0 fw-semibold text-muted small text-nowrap">{{ __('Filter by Date:') }}</label>
                <div class="position-relative">
                    <input type="date" id="dateFilter" wire:model.live="dateFilter" class="form-control form-control-sm border shadow-sm" style="cursor: pointer; padding-right: 30px;">
                    @if($dateFilter)
                        <button wire:click="$set('dateFilter', null)" class="btn btn-link text-danger p-0 position-absolute end-0 top-50 translate-middle-y me-2 text-decoration-none" title="{{ __('Clear Filter') }}">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/></svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-sm table-borderless text-start align-middle mb-0 text-body text-nowrap">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">{{ __('Invoice No') }}</th>
                            <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">{{ __('Date') }}</th>
                            <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">{{ __('Cus. Name') }}</th>
                            <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">{{ __('Total') }}</th>
                            <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">{{ __('Tendered') }}</th>
                            <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">{{ __('Balance') }}</th>
                            <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">{{ __('Status') }}</th>
                            <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-body">
                        @forelse($invoices as $i => $inv)
                            <tr class="{{ $i%2 != 0 ? 'bg-body-tertiary' : '' }}">
                                <td class="py-3 px-4">{{ $inv->invoice_no }}</td>
                                <td class="py-3 px-4">{{ $inv->date }}</td>
                                <td class="py-3 px-4">{{ $inv->customer_name ?: __('Walk-in') }}</td>
                                <td class="py-3 px-4 text-primary">Rs.{{ number_format($inv->total, 2) }}</td>
                                <td class="py-3 px-4">Rs.{{ number_format($inv->tendered_amount, 2) }}</td>
                                <td class="py-3 px-4 {{ $inv->balance_amount < 0 ? 'text-danger fw-bold' : '' }}">
                                    Rs.{{ number_format($inv->balance_amount, 2) }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($inv->balance_amount < 0)
                                        <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-2 border border-danger border-opacity-25">{{ __('Credit / Due') }}</span>
                                    @else
                                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2 border border-success border-opacity-25">{{ __('Paid') }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-end">
                                    <button onclick="window.open('/invoice/{{ $inv->id }}/print', '_blank')" class="btn btn-sm btn-outline-primary" title="{{ __('Print Invoice') }}">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z"/><path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5 text-center text-muted">
                                    <svg class="mb-3 opacity-50 mx-auto" width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <h5>{{ __('No Invoices Found') }}</h5>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
