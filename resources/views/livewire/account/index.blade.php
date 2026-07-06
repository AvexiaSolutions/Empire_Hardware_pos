<div>
    <div class="d-flex flex-column gap-4 pb-5">
        
        <!-- Header area for the section -->
        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-end mb-4 gap-3">
            <div>
                <h2 class="fs-4 fw-bold text-body mb-1">Account & Reports</h2>
                <h3 class="fs-6 fw-bold text-muted mb-0">Reporting Period</h3>
            </div>
            
            <!-- Date Filters -->
            <div class="d-flex flex-wrap gap-2 align-items-end bg-body p-3 rounded-4 shadow-sm border">
                <div>
                    <label class="form-label mb-1 text-muted small fw-semibold">From Date</label>
                    <input type="date" wire:model.live="startDate" class="form-control form-control-sm border shadow-sm">
                </div>
                <div>
                    <label class="form-label mb-1 text-muted small fw-semibold">To Date</label>
                    <input type="date" wire:model.live="endDate" class="form-control form-control-sm border shadow-sm">
                </div>
                <div class="d-flex gap-2 ms-md-2 mt-2 mt-md-0">
                    <button wire:click="setToday" class="btn btn-sm btn-outline-primary fw-semibold shadow-sm">Today</button>
                    <button wire:click="setThisMonth" class="btn btn-sm btn-outline-primary fw-semibold shadow-sm">This Month</button>
                </div>
                <div class="d-flex gap-2 ms-auto mt-2 mt-md-0">
                    <a href="{{ route('report.pnl', ['start' => $startDate, 'end' => $endDate]) }}" target="_blank" class="btn btn-sm btn-danger shadow-sm fw-bold d-flex align-items-center gap-1">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/><path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z"/></svg>
                        P&L PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Top Row: Income, Expenses, Pending -->
        <div class="row g-4">
            
            <!-- Profit & Loss Summary -->
            <div class="col-12 col-md-12 col-xl-4">
                @php
                    $isLoss = $companyLoss > 0;
                    $gradient = $isLoss ? 'linear-gradient(135deg, #ef4444, #dc2626)' : 'linear-gradient(135deg, #3b82f6, #2563eb)';
                @endphp
                <div class="card border-0 rounded-4 shadow-sm text-white position-relative overflow-hidden h-100" style="background: {{ $gradient }};">
                    <svg class="position-absolute end-0 top-0 h-100 opacity-25 text-white w-75" viewBox="0 0 200 100" preserveAspectRatio="none" fill="none" stroke="currentColor" stroke-width="4">
                        <path d="M0,80 C50,80 100,50 150,20 L180,20 M150,20 L130,20 M150,20 L150,40" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <button data-bs-toggle="modal" data-bs-target="#profitLossModal" class="btn text-start w-100 card-body p-3 p-lg-5 d-flex flex-column justify-content-between position-relative z-1 text-white text-decoration-none border-0 shadow-none" style="min-height: 160px; background: transparent;">
                        <h4 class="fw-bold fs-5 mb-4 text-white">Profit & Loss (Range)</h4>
                        <div class="d-flex align-items-baseline gap-2">
                            <div class="fs-3 fw-bolder lh-1 text-white" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">Rs.{{ number_format(max($companyProfit, $companyLoss), 2) }}</div>
                            <span class="badge bg-white text-dark">{{ $isLoss ? 'Loss' : 'Profit' }}</span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Monthly Income -->
            <div class="col-12 col-md-4">
                <div class="card border-0 rounded-4 shadow-sm text-white position-relative overflow-hidden h-100" style="background: linear-gradient(135deg, #4ade80, #22c55e);">
                    <svg class="position-absolute end-0 top-0 h-100 opacity-25 text-white w-75" viewBox="0 0 200 100" preserveAspectRatio="none" fill="none" stroke="currentColor" stroke-width="4">
                        <path d="M0,100 C50,50 100,80 150,20 L180,50 M150,20 L130,20 M150,20 L150,40" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <button data-bs-toggle="modal" data-bs-target="#incomeModal" class="btn text-start w-100 card-body p-3 p-lg-5 d-flex flex-column justify-content-between position-relative z-1 text-white text-decoration-none border-0 shadow-none" style="min-height: 160px; background: transparent;">
                        <h4 class="fw-bold fs-5 mb-4 text-white">Monthly Income</h4>
                        <div class="fs-3 fw-bolder lh-1 text-white" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">Rs.{{ number_format($monthlyIncome, 2) }}</div>
                    </button>
                </div>
            </div>

            <!-- Monthly Expenses -->
            <div class="col-12 col-md-4">
                <div class="card border-0 rounded-4 shadow-sm text-white position-relative overflow-hidden h-100" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                    <svg class="position-absolute end-0 top-0 h-100 opacity-25 text-white w-75" viewBox="0 0 200 100" preserveAspectRatio="none" fill="none" stroke="currentColor" stroke-width="4">
                        <path d="M0,0 C50,50 100,20 150,80 L180,50 M150,80 L130,80 M150,80 L150,60" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <button data-bs-toggle="modal" data-bs-target="#expenseModal" class="btn text-start w-100 card-body p-3 p-lg-5 d-flex flex-column justify-content-between position-relative z-1 text-white text-decoration-none border-0 shadow-none" style="min-height: 160px; background: transparent;">
                        <h4 class="fw-bold fs-5 mb-4 text-white">Monthly Expenses</h4>
                        <div class="fs-3 fw-bolder lh-1 text-white" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">Rs.{{ number_format($monthlyExpenses, 2) }}</div>
                    </button>
                </div>
            </div>

            <!-- Monthly Pending Income -->
            <div class="col-12 col-md-4">
                <div class="card border-0 rounded-4 shadow-sm text-white position-relative overflow-hidden h-100" style="background: linear-gradient(135deg, #fbbf24, #f59e0b);">
                    <svg class="position-absolute end-0 top-0 h-100 opacity-25 text-white w-75" viewBox="0 0 200 100" preserveAspectRatio="none" fill="none" stroke="currentColor" stroke-width="4">
                        <path d="M0,100 C50,50 100,80 150,20 L180,50 M150,20 L130,20 M150,20 L150,40" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M170,80 L190,80 M180,70 L180,90" stroke-linecap="round" stroke-linejoin="round" stroke-width="6"/>
                    </svg>
                    <div class="card-body p-3 p-lg-5 d-flex flex-column justify-content-between position-relative z-1" style="min-height: 160px;">
                        <h4 class="fw-bold fs-5 mb-4 text-white">Monthly Pending Income</h4>
                        <div class="fs-3 fw-bolder lh-1 text-white" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">Rs.{{ number_format($monthlyPendingIncome, 2) }}</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Second Row: Stock Details -->
        <h3 class="fs-5 fw-bold text-body mt-2 mb-2">Current Stock Valuation</h3>
        <div class="row g-4 mb-4">
            
            <div class="col-12 col-md-4">
                <div class="card border-0 rounded-4 shadow-sm bg-body h-100 p-4">
                    <h5 class="fw-bold text-muted mb-3 fs-6">Total Stock Cost Value</h5>
                    <div class="fs-3 fw-bolder text-body">Rs.{{ number_format($totalStockCost, 2) }}</div>
                    <div class="small text-muted mt-2">Sum of all current active stock cost prices.</div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card border-0 rounded-4 shadow-sm bg-body h-100 p-4">
                    <h5 class="fw-bold text-muted mb-3 fs-6">Total Stock Selling Value</h5>
                    <div class="fs-3 fw-bolder text-body">Rs.{{ number_format($totalStockValue, 2) }}</div>
                    <div class="small text-muted mt-2">Expected revenue from all active stock.</div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card border-0 rounded-4 shadow-sm h-100 p-4 text-white" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="fw-bold text-white mb-0 fs-6">Expected Profit</h5>
                        <a href="{{ route('report.stock') }}" target="_blank" class="btn btn-sm btn-light text-success fw-bold d-flex align-items-center gap-1 shadow-sm px-3">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/><path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z"/></svg>
                            Stock PDF
                        </a>
                    </div>
                    <div class="fs-3 fw-bolder lh-1 mb-2">Rs.{{ number_format($expectedProfit, 2) }}</div>
                    <div class="small text-white" style="opacity: 0.85;">Total future profit margin.</div>
                </div>
            </div>
        </div>

        <!-- Third Row: Warranty Claims -->
        @if(count($warrantyClaims) > 0)
        <h3 class="fs-5 fw-bold text-warning mt-2 mb-2">Pending Warranty Claims</h3>
        <div class="card border-0 rounded-4 shadow-sm bg-body mb-4 p-4">
            <div class="table-responsive">
                <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                    <thead class="border-bottom">
                        <tr>
                            <th class="py-2 px-3 fw-bold text-muted">Date</th>
                            <th class="py-2 px-3 fw-bold text-muted">Item</th>
                            <th class="py-2 px-3 fw-bold text-muted">Invoice No</th>
                            <th class="py-2 px-3 fw-bold text-muted text-center">Qty</th>
                            <th class="py-2 px-3 fw-bold text-muted">Status</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-body">
                        @foreach($warrantyClaims as $claim)
                        <tr>
                            <td class="py-3 px-3">{{ \Carbon\Carbon::parse($claim->date)->format('Y-m-d') }}</td>
                            <td class="py-3 px-3">{{ $claim->item->item_name ?? 'Unknown Item' }}</td>
                            <td class="py-3 px-3">{{ $claim->invoice->invoice_no ?? 'N/A' }}</td>
                            <td class="py-3 px-3 text-center">
                                <span class="badge bg-light text-dark border">{{ $claim->quantity }}</span>
                            </td>
                            <td class="py-3 px-3">
                                <span class="badge bg-warning text-dark">Pending Claim</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-muted small mt-2">
                <i class="bi bi-info-circle"></i> These items have been claimed for warranty and need to be sent back to the respective company. They do not affect the Profit & Loss report.
            </div>
        </div>
        @endif

        <div class="mt-3">
            <button data-bs-toggle="modal" data-bs-target="#profitLossModal" class="btn btn-primary-custom fw-bold py-3 px-4 rounded-3 shadow-sm fs-5 border-0">See Profit & Loss Report</button>
        </div>

    </div>

    <!-- Modals -->

    <!-- Income Modal -->
    <div class="modal fade" id="incomeModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold text-body fs-5 mb-0">Monthly Income Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="card border-0 rounded-4 shadow-sm text-white position-relative overflow-hidden mb-4" style="background: linear-gradient(135deg, #4ade80, #22c55e);">
                        <div class="card-body p-3 position-relative z-1 d-flex flex-column h-100">
                            <div class="d-flex flex-column gap-1 mb-4">
                                <span class="fs-6 fw-semibold text-white-50">Selected Period</span>
                                <h2 class="fs-4 fw-bolder mb-0 lh-1">{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</h2>
                            </div>
                            <div class="mt-2">
                                <div class="fw-bold mb-1" style="opacity: 0.9;">Total Income</div>
                                <div class="fs-2 fw-bolder lh-1">Rs.{{ number_format($monthlyIncome, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mb-4" style="max-height: 300px;">
                        <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                            <thead class="sticky-top bg-body">
                                <tr>
                                    <th class="py-2 px-3 fw-bold bg-body-secondary">Invoice No</th>
                                    <th class="py-2 px-3 fw-bold bg-body-secondary">Date</th>
                                    <th class="py-2 px-3 fw-bold bg-body-secondary">Customer</th>
                                    <th class="py-2 px-3 fw-bold bg-body-secondary">Total(Rs.)</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-body">
                                @forelse($invoices as $index => $invoice)
                                <tr>
                                    <td class="py-2 px-3 border-bottom">{{ $invoice->invoice_no }}</td>
                                    <td class="py-2 px-3 border-bottom">{{ \Carbon\Carbon::parse($invoice->date)->format('Y-m-d') }}</td>
                                    <td class="py-2 px-3 border-bottom">{{ $invoice->customer_name ?? 'Walk-in' }}</td>
                                    <td class="py-2 px-3 border-bottom">{{ number_format($invoice->total, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-3 px-3 text-center text-muted">No income records found for this month.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end gap-3 align-items-center">
                        <button onclick="window.print()" class="btn btn-outline-primary-custom bg-body fw-bold px-4 py-2 rounded-3 shadow-sm">Download as PDF</button>
                        <button onclick="window.print()" class="btn btn-primary-custom fw-bold px-5 py-2 rounded-3 shadow-sm">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expense Modal -->
    <div class="modal fade" id="expenseModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold text-body fs-5 mb-0">Monthly Expenses Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="card border-0 rounded-4 shadow-sm text-white position-relative overflow-hidden mb-4" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                        <div class="card-body p-3 position-relative z-1 d-flex flex-column h-100">
                            <div class="d-flex flex-column gap-1 mb-4">
                                <span class="fs-6 fw-semibold text-white-50">Selected Period</span>
                                <h2 class="fs-4 fw-bolder mb-0 lh-1">{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</h2>
                            </div>
                            <div class="mt-2">
                                <div class="fw-bold mb-1" style="opacity: 0.9;">Total Expenses</div>
                                <div class="fs-2 fw-bolder lh-1">Rs.{{ number_format($monthlyExpenses, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mb-4" style="max-height: 300px;">
                        <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                            <thead class="sticky-top bg-body">
                                <tr>
                                    <th class="py-2 px-3 fw-bold bg-body-secondary">Type</th>
                                    <th class="py-2 px-3 fw-bold bg-body-secondary">Description/Name</th>
                                    <th class="py-2 px-3 fw-bold bg-body-secondary">Date</th>
                                    <th class="py-2 px-3 fw-bold text-end bg-body-secondary">Amount(Rs.)</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-body">
                                @foreach($expensesList as $expense)
                                <tr>
                                    <td class="py-2 px-3 border-bottom"><span class="badge bg-secondary">Expense</span></td>
                                    <td class="py-2 px-3 border-bottom">{{ $expense->description }}</td>
                                    <td class="py-2 px-3 border-bottom">{{ \Carbon\Carbon::parse($expense->date)->format('Y-m-d') }}</td>
                                    <td class="py-2 px-3 border-bottom text-end">{{ number_format($expense->amount, 2) }}</td>
                                </tr>
                                @endforeach
                                @foreach($paysheetsList as $paysheet)
                                <tr>
                                    <td class="py-2 px-3 border-bottom"><span class="badge bg-primary">Salary</span></td>
                                    <td class="py-2 px-3 border-bottom">{{ $paysheet->employee->name ?? 'Unknown Employee' }}</td>
                                    <td class="py-2 px-3 border-bottom">{{ $paysheet->month_year }}</td>
                                    <td class="py-2 px-3 border-bottom text-end">{{ number_format($paysheet->net_salary, 2) }}</td>
                                </tr>
                                @endforeach
                                @if(count($expensesList) == 0 && count($paysheetsList) == 0)
                                <tr>
                                    <td colspan="4" class="py-3 px-3 text-center text-muted">No expense records found for this month.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end gap-3 align-items-center">
                        <button onclick="window.print()" class="btn btn-outline-primary-custom bg-body fw-bold px-4 py-2 rounded-3 shadow-sm">Download as PDF</button>
                        <button class="btn btn-primary-custom fw-bold px-5 py-2 rounded-3 shadow-sm">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profit Loss Modal -->
    <div class="modal fade" id="profitLossModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold text-body fs-5 mb-0">Profit & Loss Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    @php
                        $isLoss = $companyLoss > 0;
                        $gradient = $isLoss ? 'linear-gradient(135deg, #ef4444, #dc2626)' : 'linear-gradient(135deg, #3b82f6, #2563eb)';
                    @endphp
                    <div class="card border-0 rounded-4 shadow-sm text-white position-relative overflow-hidden mb-4" style="background: {{ $gradient }};">
                        <div class="card-body p-3 position-relative z-1 d-flex flex-column h-100">
                            <div class="d-flex flex-column gap-1 mb-4">
                                <span class="fs-6 fw-semibold text-white-50">Selected Period</span>
                                <h2 class="fs-4 fw-bolder mb-0 lh-1">{{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</h2>
                            </div>
                            <div class="mt-2">
                                <div class="fw-bold mb-1" style="opacity: 0.9;">{{ $isLoss ? 'Net Loss' : 'Net Profit' }}</div>
                                <div class="fs-3 fw-bolder lh-1">Rs.{{ number_format(max($companyProfit, $companyLoss), 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-borderless text-start mb-0 text-body">
                            <thead>
                                <tr>
                                    <th class="py-3 px-4 fw-bold bg-body-secondary">Description</th>
                                    <th class="py-3 px-4 fw-bold text-end bg-body-secondary">Amount(Rs.)</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-body">
                                <tr>
                                    <td colspan="2" class="py-3 px-4 fw-bold text-body">Revenue</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-4 ps-4">Invoices Total (Gross)</td>
                                    <td class="py-2 px-4 text-end">{{ number_format($monthlyIncome + $returnDeductions, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-4 ps-4 text-danger">Less: Returns/Damages (Refunds)</td>
                                    <td class="py-2 px-4 text-end text-danger">-{{ number_format($returnDeductions, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-4 ps-4 fw-bold">Net Revenue</td>
                                    <td class="py-2 px-4 text-end fw-bold">{{ number_format($monthlyIncome, 2) }}</td>
                                </tr>
                                
                                <tr>
                                    <td colspan="2" class="py-3 px-4 fw-bold text-body border-top mt-2">Expenses</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-4 ps-4">General Expenses</td>
                                    <td class="py-2 px-4 text-end">{{ number_format($expensesList->sum('amount'), 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-4 ps-4">Employee Salaries</td>
                                    <td class="py-2 px-4 text-end">{{ number_format($paysheetsList->sum('net_salary'), 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-4 ps-4 text-warning" style="color: #d97706 !important;">Damage Item Cost Loss</td>
                                    <td class="py-2 px-4 text-end text-warning" style="color: #d97706 !important;">{{ number_format($damageExpenses, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="py-2 px-4 ps-4 fw-bold">Total Expenses</td>
                                    <td class="py-2 px-4 text-end fw-bold">{{ number_format($monthlyExpenses, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end gap-3 align-items-center">
                        <button onclick="window.print()" class="btn btn-outline-primary-custom bg-body fw-bold px-4 py-2 rounded-3 shadow-sm">Download as PDF</button>
                        <button class="btn btn-primary-custom fw-bold px-5 py-2 rounded-3 shadow-sm">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
