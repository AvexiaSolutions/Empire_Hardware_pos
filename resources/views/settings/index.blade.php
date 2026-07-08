<x-pos-layout>
    <div class="mb-4">
        <h3 class="text-uppercase tracking-wider fw-bold text-body-secondary" style="font-size: 0.85rem; letter-spacing: 0.05em;">System Settings</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success fw-bold rounded-4 shadow-sm border-0 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden">
        <div class="card-header bg-body border-bottom-0 pt-4 pb-0 px-4 px-lg-5">
            <ul class="nav nav-tabs border-0" id="settingsTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold fs-5 border-0 pb-3" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" style="color: inherit;">General</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold fs-5 border-0 pb-3" id="bill-tab" data-bs-toggle="tab" data-bs-target="#bill" type="button" role="tab" style="color: inherit;">Bill & Printer</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold fs-5 border-0 pb-3" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab" style="color: inherit;">User Management</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold fs-5 border-0 pb-3" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab" style="color: inherit;">Automated Reports</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold fs-5 border-0 pb-3" id="update-tab" data-bs-toggle="tab" data-bs-target="#update" type="button" role="tab" style="color: inherit;">System Update</button>
                </li>
            </ul>
        </div>
        <div class="card-body p-3 p-lg-5">
            <div class="tab-content" id="settingsTabContent">
                
                <!-- General Settings Tab -->
                <div class="tab-pane fade show active" id="general" role="tabpanel" tabindex="0">
                    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex flex-column align-items-center mb-4">
                            <label class="form-label fw-bold mb-3">System Logo</label>
                            <div class="position-relative" style="width: 120px; height: 120px; cursor: pointer;" onclick="document.getElementById('logoInput').click()">
                                <div class="rounded-circle border border-2 border-primary d-flex align-items-center justify-content-center overflow-hidden bg-body-tertiary shadow-sm" style="width: 100%; height: 100%;">
                                    <img id="logoPreview" src="{{ $settings['shop_logo'] ?? 'https://ui-avatars.com/api/?name=Logo&background=f8f9fa&color=adb5bd' }}" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div class="position-absolute bottom-0 end-0 bg-primary-custom text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 32px; height: 32px;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <input type="file" id="logoInput" name="logo" accept="image/*" class="d-none" onchange="previewLogo(event)">
                            </div>
                            <small class="text-muted mt-2">Click the circle to upload a new logo</small>
                        </div>

                        <div class="row g-4">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Shop Name</label>
                                <input type="text" class="form-control rounded-3 py-2" name="shop_name" value="{{ $settings['shop_name'] ?? '' }}" placeholder="Avexia POS">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Phone Number</label>
                                <input type="text" class="form-control rounded-3 py-2" name="shop_phone" value="{{ $settings['shop_phone'] ?? '' }}" placeholder="07x xxxxxxx">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" class="form-control rounded-3 py-2" name="shop_email" value="{{ $settings['shop_email'] ?? '' }}" placeholder="shop@example.com">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Shop Address</label>
                                <textarea class="form-control rounded-3" name="shop_address" rows="3" placeholder="123 Main St, City">{{ $settings['shop_address'] ?? '' }}</textarea>
                            </div>
                            
                            <!-- Cost Price Modifier Section -->
                            <div class="col-12 mt-4">
                                <div class="card border border-warning rounded-4 shadow-sm">
                                    <div class="card-header bg-warning bg-opacity-10 border-bottom-0 pt-3 pb-2 px-4">
                                        <h5 class="fw-bold text-warning-emphasis mb-0">Cost Price Display Modifier (Visual Only)</h5>
                                        <small class="text-body-secondary">Temporarily inflate the cost price shown across the system (useful for client presentations).</small>
                                    </div>
                                    <div class="card-body px-4 pb-4 pt-2 row g-3">
                                        <div class="col-12 col-md-4">
                                            <label class="form-label fw-bold">Enable Fake Cost Price</label>
                                            <select class="form-select rounded-3 py-2" name="fake_cost_markup_active">
                                                <option value="0" {{ ($settings['fake_cost_markup_active'] ?? '0') == '0' ? 'selected' : '' }}>Disabled (Show Real Cost)</option>
                                                <option value="1" {{ ($settings['fake_cost_markup_active'] ?? '0') == '1' ? 'selected' : '' }}>Enabled (Show Fake Cost)</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label fw-bold">Markup Type</label>
                                            <select class="form-select rounded-3 py-2" name="fake_cost_markup_type">
                                                <option value="percentage" {{ ($settings['fake_cost_markup_type'] ?? 'percentage') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                                <option value="fixed" {{ ($settings['fake_cost_markup_type'] ?? 'percentage') == 'fixed' ? 'selected' : '' }}>Fixed Amount (Rs)</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label fw-bold">Markup Value</label>
                                            <input type="number" step="0.01" min="0" class="form-control rounded-3 py-2" name="fake_cost_markup_value" value="{{ $settings['fake_cost_markup_value'] ?? '0' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4 text-end">
                                <button type="submit" class="btn btn-primary-custom fw-bold px-4 py-2 rounded-3 shadow-sm">Save General Settings</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Bill & Printer Tab -->
                <div class="tab-pane fade" id="bill" role="tabpanel" tabindex="0">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <!-- Left: Form -->
                            <div class="col-12 col-lg-6">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Print Format (Layout)</label>
                                        <select class="form-select rounded-3 py-2" name="printer_type" id="printerTypeSelect">
                                            <option value="80mm" {{ ($settings['printer_type'] ?? '') == '80mm' ? 'selected' : '' }}>80mm Thermal</option>
                                            <option value="58mm" {{ ($settings['printer_type'] ?? '') == '58mm' ? 'selected' : '' }}>58mm Thermal</option>
                                            <option value="A4" {{ ($settings['printer_type'] ?? '') == 'A4' ? 'selected' : '' }}>A4 Standard</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Connection Method <small class="text-muted">(How to connect to printer)</small></label>
                                        <select class="form-select rounded-3 py-2" name="print_method" id="printMethodSelect" onchange="toggleQzSettings()">
                                            <option value="browser" {{ ($settings['print_method'] ?? 'browser') == 'browser' ? 'selected' : '' }}>Browser Default / Save as PDF (Best for Any Device)</option>
                                            <option value="rawbt" {{ ($settings['print_method'] ?? '') == 'rawbt' ? 'selected' : '' }}>RawBT (Best for Android Bluetooth/USB/WiFi)</option>
                                            <option value="qz" {{ ($settings['print_method'] ?? '') == 'qz' ? 'selected' : '' }}>QZ Tray (Best for PC/Laptop USB/WiFi)</option>
                                        </select>
                                    </div>
                                    <div class="col-12 qz-settings-group" style="display: {{ ($settings['print_method'] ?? 'browser') == 'qz' ? 'block' : 'none' }};">
                                        <label class="form-label fw-bold">QZ Printer Name / IP <span class="badge bg-success ms-2 d-none" id="qz-online-badge">Online</span><span class="badge bg-danger ms-2 d-none" id="qz-offline-badge">Offline</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control rounded-start-3 py-2" name="qz_printer_name" id="qzPrinterName" value="{{ $settings['qz_printer_name'] ?? '' }}" placeholder="e.g. EPSON TM-T88V or 192.168.1.50">
                                            <button type="button" class="btn btn-outline-secondary" onclick="checkQzStatus()">Check Status</button>
                                        </div>
                                        <small class="text-muted mt-1 d-block">Make sure QZ Tray is running on this PC.</small>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" role="switch" id="autoPrintSwitch" name="auto_print" value="1" {{ ($settings['auto_print'] ?? '0') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="autoPrintSwitch">Auto-Print on Checkout</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Tax / Registration No</label>
                                        <input type="text" class="form-control rounded-3 py-2" name="tax_no" value="{{ $settings['tax_no'] ?? '' }}" placeholder="VAT/BR Number">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Bill Footer Message</label>
                                        <textarea class="form-control rounded-3" name="bill_footer" rows="3" placeholder="Thank you for shopping with us!">{{ $settings['bill_footer'] ?? '' }}</textarea>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary-custom fw-bold px-4 py-2 rounded-3 shadow-sm w-100">Save Printer Settings</button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right: Live Preview -->
                            <div class="col-12 col-lg-6">
                                <div class="card border border-primary border-opacity-25 rounded-4 shadow-sm bg-body-tertiary">
                                    <div class="card-header bg-primary bg-opacity-10 border-bottom-0 pt-3 pb-2 px-4">
                                        <h5 class="fw-bold text-primary mb-0">Bill Live Preview</h5>
                                    </div>
                                    <div class="card-body p-4 d-flex justify-content-center align-items-center" style="min-height: 400px;">
                                        
                                        <!-- Mock Receipt -->
                                        <div id="receiptPreviewBox" class="bg-white shadow p-3 mx-auto" style="width: 300px; font-family: 'Courier New', Courier, monospace; font-size: 11px; color: #000;">
                                            <div class="text-center mb-2">
                                                <div class="fw-bold" style="font-size: 1.3em;">{{ $settings['shop_name'] ?? 'Avexia POS' }}</div>
                                                <div>{{ $settings['shop_address'] ?? '123 Main St, City' }}</div>
                                                <div>Tel: {{ $settings['shop_phone'] ?? '07X XXXXXXX' }}</div>
                                                <div id="previewTaxNo">{{ !empty($settings['tax_no']) ? 'VAT/BR: ' . $settings['tax_no'] : '' }}</div>
                                            </div>
                                            
                                            <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>
                                            
                                            <div class="d-flex justify-content-between">
                                                <span>No: INV-000001</span>
                                                <span>{{ date('Y-m-d') }}</span>
                                            </div>
                                            
                                            <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>
                                            
                                            <table class="w-100 mb-2">
                                                <tr>
                                                    <td class="text-start">Item A</td>
                                                    <td class="text-center">1</td>
                                                    <td class="text-end">150.00</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start">Item B</td>
                                                    <td class="text-center">2</td>
                                                    <td class="text-end">400.00</td>
                                                </tr>
                                            </table>
                                            
                                            <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>
                                            
                                            <div class="d-flex justify-content-between fw-bold" style="font-size: 1.2em;">
                                                <span>NET TOTAL:</span>
                                                <span>550.00</span>
                                            </div>
                                            
                                            <div style="border-bottom: 1px dashed #000; margin: 8px 0;"></div>
                                            
                                            <div class="text-center mt-3" id="previewFooter">
                                                {!! nl2br(e($settings['bill_footer'] ?? 'Thank you for shopping with us!')) !!}
                                            </div>
                                            <div class="text-center mt-2" style="font-size: 0.8em;">Software by Avexia</div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- User Management Tab -->
                <div class="tab-pane fade" id="users" role="tabpanel" tabindex="0">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">System Users</h4>
                        <button class="btn btn-primary-custom fw-bold px-3 py-2 rounded-3 shadow-sm small" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            + Add New User
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-borderless align-middle fw-semibold text-body mb-0">
                            <thead>
                                <tr class="border-bottom">
                                    <th class="pb-3 fw-bold ps-0">Name</th>
                                    <th class="pb-3 fw-bold">Email</th>
                                    <th class="pb-3 fw-bold">Role</th>
                                    <th class="pb-3 fw-bold pe-0 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr class="border-bottom">
                                    <td class="py-3 ps-0">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-secondary rounded-pill px-3">{{ ucfirst($user->role ?? 'User') }}</span>
                                    </td>
                                    <td class="text-end pe-0">
                                        <button class="btn btn-sm btn-outline-info fw-bold rounded-3 me-2" data-bs-toggle="modal" data-bs-target="#permissionsModal{{ $user->id }}">Permissions</button>
                                        <button class="btn btn-sm btn-outline-primary-custom fw-bold rounded-3" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">Edit</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Automated Reports Tab -->
                <div class="tab-pane fade" id="reports" role="tabpanel" tabindex="0">
                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Report & Backup Email Address</label>
                                <input type="email" class="form-control rounded-3 py-2" name="report_email" value="{{ $settings['report_email'] ?? '' }}" placeholder="admin@example.com">
                                <small class="text-muted">Daily end-of-day reports and database backups will be sent here.</small>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">Daily Scheduled Time</label>
                                <input type="time" class="form-control rounded-3 py-2" name="report_time" value="{{ $settings['report_time'] ?? '18:59' }}">
                                <small class="text-muted">Time to run the scheduled report & backup job.</small>
                            </div>
                            <div class="col-12 mt-4 text-end d-flex justify-content-between align-items-center">
                                <a href="{{ route('settings.manual-backup') }}" class="btn btn-outline-secondary fw-bold px-4 py-2 rounded-3 shadow-sm">Trigger Manual Backup</a>
                                <button type="submit" class="btn btn-primary-custom fw-bold px-4 py-2 rounded-3 shadow-sm">Save Report Settings</button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- System Update Tab -->
                <div class="tab-pane fade" id="update" role="tabpanel" tabindex="0">
                    <div class="row g-4 justify-content-center">
                        <div class="col-12 col-lg-8">
                            <div class="card border border-primary border-opacity-25 rounded-4 shadow-sm bg-body">
                                <div class="card-header bg-primary bg-opacity-10 border-bottom-0 pt-4 pb-3 px-4 text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3 shadow-sm" style="width: 60px; height: 60px;">
                                        <svg width="30" height="30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    </div>
                                    <h4 class="fw-bold text-primary mb-1">Update System via GitHub</h4>
                                    <p class="text-body-secondary small mb-0">Sync your local system with the latest source code from the central repository.</p>
                                </div>
                                <div class="card-body p-4 p-md-5">
                                    
                                    <div class="alert alert-warning border-0 rounded-3 shadow-sm d-flex gap-3 align-items-start mb-4">
                                        <svg width="24" height="24" class="text-warning flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        <div>
                                            <h6 class="fw-bold mb-1">Warning: Overwrite Risk</h6>
                                            <p class="small mb-0">Running this update will perform a <code>git reset --hard</code>. Any manual code changes made directly on this server will be permanently deleted and replaced with the official GitHub code. Only proceed if you have not made manual edits outside of GitHub.</p>
                                        </div>
                                    </div>

                                    <div class="mb-4 bg-body-tertiary p-3 rounded-3 border d-flex justify-content-between align-items-center">
                                        <div>
                                            <label class="fw-bold small text-muted d-block">Target Repository URL</label>
                                            <div class="d-flex align-items-center gap-2">
                                                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                                <code class="text-body fw-bold">https://github.com/AvexiaSolutions/Empire_Hardware_pos.git</code>
                                            </div>
                                        </div>
                                    </div>

                                    <form action="{{ route('settings.system-update') }}" method="POST" id="updateForm">
                                        @csrf
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary-custom fw-bold px-5 py-3 rounded-pill shadow fs-5" id="updateBtn">
                                                <span id="updateBtnText">Pull Latest Updates Now</span>
                                                <span id="updateBtnLoader" class="spinner-border spinner-border-sm d-none ms-2" role="status" aria-hidden="true"></span>
                                            </button>
                                            <p class="text-muted small mt-3" id="updateStatusText">This process may take a minute or two. Please do not close the window.</p>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.getElementById('updateForm').addEventListener('submit', function() {
                        const btn = document.getElementById('updateBtn');
                        const text = document.getElementById('updateBtnText');
                        const loader = document.getElementById('updateBtnLoader');
                        const status = document.getElementById('updateStatusText');
                        
                        btn.classList.add('disabled');
                        text.innerText = 'Updating System...';
                        loader.classList.remove('d-none');
                        status.classList.add('text-primary');
                        status.classList.remove('text-muted');
                        status.innerText = 'Downloading files, running migrations, and optimizing caches. Please wait...';
                    });
                </script>

            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('settings.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="name" class="form-control rounded-3 py-2" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username <small class="text-muted">(Optional)</small></label>
                            <input type="text" name="username" class="form-control rounded-3 py-2" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control rounded-3 py-2" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Role</label>
                            <select name="role" class="form-select rounded-3 py-2" required>
                                <option value="cashier">Cashier</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control rounded-3 py-2" required autocomplete="new-password">
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="submit" class="btn btn-primary-custom w-100 fw-bold py-2 rounded-3">Create User</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>

    @foreach($users as $user)
    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit User: {{ $user->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('settings.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-3">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="name" class="form-control rounded-3 py-2" value="{{ $user->name }}" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Username <small class="text-muted">(Optional)</small></label>
                            <input type="text" name="username" class="form-control rounded-3 py-2" value="{{ $user->username }}" autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control rounded-3 py-2" value="{{ $user->email }}" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Role</label>
                            <select name="role" class="form-select rounded-3 py-2" required>
                                <option value="cashier" {{ $user->role === 'cashier' ? 'selected' : '' }}>Cashier</option>
                                <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password <small class="text-muted">(Leave blank to keep current)</small></label>
                            <input type="password" name="password" class="form-control rounded-3 py-2" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="submit" class="btn btn-primary-custom w-100 fw-bold py-2 rounded-3">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Permissions Modal for User {{ $user->id }} -->
    <div class="modal fade" id="permissionsModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Manage Permissions: {{ $user->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('settings.users.permissions.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-3">
                        <div class="row g-3">
                            @php
                                $availablePermissions = [
                                    'pos.access' => 'Access POS',
                                    'invoice.access' => 'Access Invoices',
                                    'item.access' => 'Access Items',
                                    'item.add' => 'Add Items',
                                    'item.edit' => 'Edit Items',
                                    'item.delete' => 'Delete Items',
                                    'supplier.access' => 'Access Suppliers',
                                    'account.access' => 'Access Accounting',
                                    'expenses.access' => 'Access Expenses',
                                    'employer.access' => 'Access Employer',
                                    'credit-cheque.access' => 'Access Credit & Cheque',
                                    'returns.access' => 'Access Warranty & Returns',
                                    'settings.access' => 'Access Settings'
                                ];
                                $userPermissions = is_array($user->permissions) ? $user->permissions : [];
                            @endphp
                            
                            @foreach($availablePermissions as $key => $label)
                            <div class="col-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $key }}" id="perm_{{ $user->id }}_{{ str_replace('.', '_', $key) }}" {{ in_array($key, $userPermissions) ? 'checked' : '' }} {{ $user->role === 'admin' ? 'disabled checked' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $user->id }}_{{ str_replace('.', '_', $key) }}">{{ $label }}</label>
                                </div>
                            </div>
                            @endforeach
                            
                            @if($user->role === 'admin')
                                <div class="col-12 mt-2">
                                    <div class="alert alert-info py-2 small mb-0">Admins have all permissions by default.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="submit" class="btn btn-primary-custom w-100 fw-bold py-2 rounded-3" {{ $user->role === 'admin' ? 'disabled' : '' }}>Save Permissions</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <script>
        function previewLogo(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logoPreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function toggleQzSettings() {
            const method = document.getElementById('printMethodSelect').value;
            const qzGroup = document.querySelector('.qz-settings-group');
            if (method === 'qz') {
                qzGroup.style.display = 'block';
            } else {
                qzGroup.style.display = 'none';
            }
        }

        function checkQzStatus() {
            alert('QZ Tray integration is being setup. It will connect to localhost via Websockets.');
        }
    </script>
</x-pos-layout>
