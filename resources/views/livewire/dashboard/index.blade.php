<div>
        <h3 class="text-uppercase tracking-wider fw-bold text-body-secondary" style="font-size: 0.85rem; letter-spacing: 0.05em;">{{ __('Dashboard') }}</h3>

    <!-- Main Grid Container -->
    <div class="row g-4 pb-5">
        
        <!-- Row 1 -->
        <!-- Current Stocks Widget -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body">
                <div class="card-body p-3 p-lg-4 d-flex flex-column flex-sm-row justify-content-between align-items-center text-center text-sm-start position-relative overflow-hidden gap-3 gap-sm-0">
                    <div>
                        <h3 class="fs-5 fw-bold text-body">{{ __('Current Stocks') }}</h3>
                        <div class="display-2 fw-bolder lh-1 mt-3 text-body">{{ $currentStockItemsCount }}</div>
                        <p class="fs-5 fw-semibold mt-2 text-body-secondary mb-0">{{ __('Of') }} {{ $totalProducts }} {{ __('Product') }}</p>
                    </div>
                    <div class="position-relative" style="width: 140px; height: 140px;">
                        <svg class="w-100 h-100" viewBox="0 0 100 100" style="transform: rotate(-90deg);">
                            <circle cx="50" cy="50" r="40" stroke="#E6FFE1" stroke-width="12" fill="none" class="opacity-50" />
                            <circle cx="50" cy="50" r="40" stroke="#00D200" stroke-width="12" fill="none" stroke-dasharray="251.2" stroke-dashoffset="{{ 251.2 - (251.2 * $currentStockPercentage / 100) }}" stroke-linecap="round" />
                        </svg>
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                            <span class="fs-3 fw-bolder" style="color: #00D200;">{{ $currentStockPercentage }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Out of Stock Widget -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body">
                <div class="card-body p-3 p-lg-4 d-flex flex-column flex-sm-row justify-content-between align-items-center text-center text-sm-start position-relative overflow-hidden gap-3 gap-sm-0">
                    <div>
                        <h3 class="fs-5 fw-bold text-body">{{ __('Out of Stock') }}</h3>
                        <div class="display-2 fw-bolder lh-1 mt-3 text-body">{{ $outOfStockItemsCount }}</div>
                        <p class="fs-5 fw-semibold mt-2 text-body-secondary mb-0">{{ __('Of') }} {{ $totalProducts }} {{ __('Product') }}</p>
                    </div>
                    <div class="position-relative" style="width: 140px; height: 140px;">
                        <svg class="w-100 h-100" viewBox="0 0 100 100" style="transform: rotate(-90deg);">
                            <circle cx="50" cy="50" r="40" stroke="#FFE6E6" stroke-width="12" fill="none" class="opacity-50" />
                            <circle cx="50" cy="50" r="40" stroke="#FF3333" stroke-width="12" fill="none" stroke-dasharray="251.2" stroke-dashoffset="{{ 251.2 - (251.2 * $outOfStockPercentage / 100) }}" stroke-linecap="round" />
                        </svg>
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                            <span class="fs-3 fw-bolder" style="color: #FF3333;">{{ $outOfStockPercentage }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2 -->
        <!-- Out of Stock Alert Table -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body">
                <div class="card-body p-3 p-lg-5">
                    <h3 class="fs-5 fw-bold mb-4 text-body">{{ __('Out of Stock Alert') }}</h3>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless text-start align-middle fw-semibold text-body mb-0">
                            <thead>
                                <tr class="text-body small border-bottom">
                                    <th class="pb-3 fw-bold ps-0">{{ __('Product Name') }}</th>
                                    <th class="pb-3 fw-bold">{{ __('Code') }}</th>
                                    <th class="pb-3 fw-bold">{{ __('Qut of Now') }}</th>
                                    <th class="pb-3 pe-0"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($outOfStockAlerts as $alert)
                                <tr>
                                    <td class="py-3 ps-0 border-bottom">{{ $alert->name }}</td>
                                    <td class="border-bottom">{{ $alert->code }}</td>
                                    <td class="border-bottom">{{ $alert->batches_sum_quantity ?? 0 }}</td>
                                    <td class="text-end pe-0 border-bottom"><a href="{{ route('supplier.index') }}" class="btn btn-primary-custom px-4 py-1 rounded-3 small fw-bold shadow-sm">{{ __('Order') }}</a></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-3 ps-0 text-muted">{{ __('No out of stock alerts.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Section -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body">
                <div class="card-body p-3 p-lg-5 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <h3 class="fs-5 fw-bold text-body">{{ __('Invoice') }}</h3>
                        <div class="text-end">
                            <div class="small fw-semibold text-body-secondary">{{ __('Invoice Number (This Month)') }}</div>
                            <div class="display-2 fw-bolder lh-1 text-body mt-n2">{{ $totalInvoices }}</div>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3 mt-auto">
                        <a href="{{ route('invoice.create') }}" class="btn btn-primary-custom fw-bold py-3 rounded-4 shadow-sm fs-5 w-100">{{ __('Create Invoice') }}</a>
                        <a href="{{ route('invoice.index') }}" class="btn btn-outline-primary-custom fw-bold py-3 rounded-4 fs-5 w-100 transition">{{ __('Preview Invoice Table') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3 -->
        <!-- Attendance Mark -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body">
                <div class="card-body p-3 p-lg-5 d-flex flex-column justify-content-between">
                    <div>
                        <h3 class="fs-5 fw-bold text-body mb-1">{{ __('Attendance Mark') }}</h3>
                        <p class="small text-body-secondary fw-semibold mb-4">{{ __('today Attendance of Emp.') }}</p>
                        <div class="d-flex align-items-end gap-2 mb-4">
                            <span class="display-2 fw-bolder lh-1 text-body">{{ str_pad($attendanceCount, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="fs-4 fw-bold mb-1 text-body">{{ __('Of') }} {{ str_pad($totalEmployees, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3 mt-auto">
                        <a href="{{ route('employer.paysheet.select') }}" class="btn btn-primary-custom fw-bold py-3 rounded-4 shadow-sm fs-5 w-100">Attendance Mark</a>
                        <a href="{{ route('employer.index', ['add' => 'true']) }}" class="btn btn-outline-primary-custom fw-bold py-3 rounded-4 fs-5 w-100 transition">{{ __('Add New Employer') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cheque & Credit Reminder -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(to right, #FFE700, #FFC700);">
                <div class="position-absolute opacity-75" style="right: -30px; bottom: -50px; filter: drop-shadow(0 25px 25px rgba(0,0,0,0.25));">
                    <svg width="250" height="250" viewBox="0 0 24 24" fill="none" stroke="#402D00" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" fill="#402D00"></path>
                        <line x1="12" y1="9" x2="12" y2="13" stroke="#FFE700" stroke-width="3"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17" stroke="#FFE700" stroke-width="3"></line>
                    </svg>
                </div>

                <div class="card-body p-3 p-lg-5 position-relative z-1 w-100" style="max-width: 100%; z-index: 2;">
                    <h3 class="small fw-bold text-uppercase mb-2" style="color: #624100;">{{ __('Cheque & Credit Reminder') }}</h3>
                    <h4 class="fs-3 fw-bolder mb-4" style="color: #624100;">{{ __('Due Date') }} - {{ \Carbon\Carbon::today()->format('d/m/Y') }}{{ __('(Today)') }}</h4>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless fw-semibold text-start mb-0" style="color: #624100;">
                            <thead>
                                <tr>
                                    <th class="pb-2 small ps-0" style="color: inherit;">{{ __('Invoice No') }}</th>
                                    <th class="pb-2 small" style="color: inherit;">{{ __('Name') }}</th>
                                    <th class="pb-2 small" style="color: inherit;">{{ __('Type') }}</th>
                                    <th class="pb-2 small" style="color: inherit;">{{ __('Amount') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingReminders as $reminder)
                                <tr>
                                    <td class="py-2 ps-0 border-bottom border-warning" style="color: inherit;">{{ $reminder->invoice_no }}</td>
                                    <td class="border-bottom border-warning" style="color: inherit;">{{ $reminder->name }}</td>
                                    <td class="border-bottom border-warning" style="color: inherit;">{{ $reminder->type }}</td>
                                    <td class="border-bottom border-warning" style="color: inherit;">Rs.{{ number_format($reminder->amount, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-2 ps-0" style="color: inherit;">{{ __('No pending reminders for today.') }}</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('credit-cheque.index') }}" class="btn mt-4 px-4 py-2 rounded-3 fw-bold shadow-sm" style="background-color: #624100; color: #EBA00F;">{{ __('Get Action') }}</a>
                </div>
            </div>
        </div>

        <!-- Row 4 -->
        <!-- Fast Moving Item -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body position-relative overflow-hidden">
                <div class="position-absolute bottom-0 start-0 w-100 h-50 opacity-50 z-0" style="background: linear-gradient(to top, rgba(25,135,84,0.15), transparent);"></div>
                
                <div class="card-body p-3 p-lg-5 position-relative z-1 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start">
                        <h3 class="fs-5 fw-bold text-body d-flex align-items-center gap-2">
                            {{ __('Fast Moving Item (This Month)') }}
                            <svg width="16" height="16" fill="#198754" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/></svg>
                        </h3>
                    </div>
                    
                    <div class="d-flex mt-4" style="height: 160px;">
                        <div class="w-75 d-flex align-items-end justify-content-around border-bottom border-start pb-2 ps-2 text-body-tertiary" style="font-size: 0.75rem;">
                            @php
                                $maxQty = $fastMovingItems->max('total_sold') ?: 1;
                                $colors = ['#86EFAC', '#4ADE80', '#22C55E', '#16A34A', '#15803D'];
                            @endphp
                            @foreach($fastMovingItems as $index => $item)
                                @php $height = max(10, ($item->total_sold / $maxQty) * 100); @endphp
                                <div class="rounded-top shadow-sm position-relative" style="width: 32px; height: {{ $height }}%; background-color: {{ $colors[$index % 5] }};" title="{{ $item->total_sold }} sold"></div>
                            @endforeach
                        </div>
                        <div class="w-25 d-flex flex-column justify-content-start ps-4 py-2 fw-bold text-body small gap-2">
                            @foreach($fastMovingItems as $index => $item)
                            <div class="d-flex align-items-center gap-2" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $item->name }}">
                                <span class="rounded flex-shrink-0" style="width: 16px; height: 16px; background-color: {{ $colors[$index % 5] }};"></span> 
                                <span class="text-truncate">{{ $item->name }}</span>
                            </div>
                            @endforeach
                            @if(count($fastMovingItems) == 0)
                                <div class="text-muted">{{ __('No data') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slow Moving Item -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm h-100 bg-body position-relative overflow-hidden">
                <div class="position-absolute bottom-0 start-0 w-100 h-50 opacity-50 z-0" style="background: linear-gradient(to top, rgba(220,53,69,0.15), transparent);"></div>
                
                <div class="card-body p-3 p-lg-5 position-relative z-1 d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-start">
                        <h3 class="fs-5 fw-bold text-body d-flex align-items-center gap-2">
                            {{ __('Slow Moving Item (This Month)') }}
                            <svg width="16" height="16" fill="#dc3545" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </h3>
                    </div>
                    
                    <div class="d-flex mt-4" style="height: 160px;">
                        <div class="w-75 d-flex align-items-end justify-content-around border-bottom border-start pb-2 ps-2 text-body-tertiary" style="font-size: 0.75rem;">
                            @php
                                $maxSlowQty = $slowMovingItems->max('total_sold') ?: 1;
                                $slowColors = ['#FCA5A5', '#F87171', '#EF4444', '#DC2626', '#B91C1C'];
                            @endphp
                            @foreach($slowMovingItems as $index => $item)
                                @php $height = max(10, ($item->total_sold / $maxSlowQty) * 100); @endphp
                                <div class="rounded-top shadow-sm" style="width: 32px; height: {{ $height }}%; background-color: {{ $slowColors[$index % 5] }};" title="{{ $item->total_sold }} sold"></div>
                            @endforeach
                        </div>
                        <div class="w-25 d-flex flex-column justify-content-start ps-4 py-2 fw-bold text-body small gap-2">
                            @foreach($slowMovingItems as $index => $item)
                            <div class="d-flex align-items-center gap-2" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $item->name }}">
                                <span class="rounded flex-shrink-0" style="width: 16px; height: 16px; background-color: {{ $slowColors[$index % 5] }};"></span> 
                                <span class="text-truncate">{{ $item->name }}</span>
                            </div>
                            @endforeach
                            @if(count($slowMovingItems) == 0)
                                <div class="text-muted">{{ __('No data') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Analytics Chart -->
        <div class="col-12 col-xl-8">
            <div class="card border-0 rounded-4 shadow-sm bg-body h-100">
                <div class="card-body p-3 p-lg-5">
                    <h3 class="fs-5 fw-bold text-body mb-4">{{ __('6-Month Sales Analytics') }}</h3>
                    <div style="position: relative; height:40vh; width:100%">
                        <canvas id="monthlySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- 7-Day Sales Forecast (AI) -->
        <div class="col-12 col-xl-4">
            <div class="card border-0 rounded-4 shadow-sm bg-body h-100 position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 p-3 opacity-25">
                    <svg width="64" height="64" fill="currentColor" class="text-primary" viewBox="0 0 16 16">
                        <path d="M12.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-1 0v-9a.5.5 0 0 1 .5-.5zm-4 3a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0v-6a.5.5 0 0 1 .5-.5zm-4 2a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 1 .5-.5z"/>
                        <path fill-rule="evenodd" d="M14.5 13.5a.5.5 0 0 1 .5.5H1a.5.5 0 0 1 0-1h13a.5.5 0 0 1 .5.5z"/>
                        <path fill-rule="evenodd" d="M11.854 4.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5-2.646 2.647a.5.5 0 0 1-.708-.708l3-3a.5.5 0 0 1 .708 0l1.5 1.5 2.646-2.647a.5.5 0 0 1 .708 0z"/>
                    </svg>
                </div>
                <div class="card-body p-3 p-lg-5 position-relative z-1 d-flex flex-column">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-primary text-white rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/></svg>
                            {{ __('AI Powered') }}
                        </span>
                    </div>
                    <h3 class="fs-5 fw-bold text-body mb-4">{{ __('7-Day Sales Forecast') }}</h3>
                    <p class="text-muted small mb-4">{{ __('Based on linear regression of the past 30 days of sales data, here is the projected revenue trend for the coming week.') }}</p>
                    
                    <div class="flex-grow-1" style="position: relative; min-height: 25vh; width: 100%;">
                        <canvas id="forecastChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        // Monthly Sales Chart
        const ctx = document.getElementById('monthlySalesChart');
        if(ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($monthlySalesLabels),
                    datasets: [{
                        label: '{!! __('Total Sales (Rs.)') !!}',
                        data: @json($monthlySalesData),
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#0d6efd',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#0d6efd',
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [5, 5],
                                color: 'rgba(128, 128, 128, 0.2)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Forecast Chart
        const forecastCtx = document.getElementById('forecastChart');
        if(forecastCtx) {
            new Chart(forecastCtx, {
                type: 'bar',
                data: {
                    labels: @json($forecastLabels),
                    datasets: [{
                        label: '{!! __('Predicted Sales (Rs.)') !!}',
                        data: @json($forecastData),
                        backgroundColor: 'rgba(111, 66, 193, 0.8)',
                        borderRadius: 4,
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [5, 5],
                                color: 'rgba(128, 128, 128, 0.2)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    });
</script>
