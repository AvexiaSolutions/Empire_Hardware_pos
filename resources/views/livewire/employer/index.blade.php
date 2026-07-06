    <div class="pb-5">
        
        <div class="d-flex flex-column gap-3 mb-4">
            <h2 class="fs-4 fw-bold text-body">Employer Management</h2>
        </div>

        <!-- Attendance Mark Section -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <h5 class="fw-bold text-body mb-0 fs-6">Attendant Mark</h5>
            </div>
            
            <div class="d-flex justify-content-between align-items-end mb-3">
                <button data-bs-toggle="modal" data-bs-target="#attendanceModal" class="btn btn-primary-custom px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 border-0">
                    Attendence Mark 
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
                <button class="btn btn-primary-custom px-3 py-1 fw-bold rounded-2 small d-inline-flex align-items-center gap-2" style="font-size: 0.85rem;">
                    Edit Sheet <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </button>
            </div>

            <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">Date</th>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">Emp. ID</th>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">Name</th>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">Designation</th>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">Present</th>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">Absent</th>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">Start Time</th>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">End Time</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-body">
                            @forelse($employees as $index => $emp)
                            <tr>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ now()->format('Y.m.d') }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $emp->emp_id }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $emp->name }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $emp->designation }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    <div class="bg-secondary text-white d-inline-flex align-items-center justify-content-center rounded" style="width: 24px; height: 24px;">-</div>
                                </td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    <div class="bg-secondary text-white d-inline-flex align-items-center justify-content-center rounded" style="width: 24px; height: 24px;">-</div>
                                </td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">-</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">-</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No employees found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Salary Management Section -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <h5 class="fw-bold text-body mb-0 fs-6">Salary Management</h5>
            </div>

            <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">Date</th>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">Emp. ID</th>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">Name</th>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">Designation</th>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">Salary</th>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary"></th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-body">
                            @forelse($employees as $index => $emp)
                            <tr>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ now()->format('Y.m.d') }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $emp->emp_id }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $emp->name }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $emp->designation }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">LKR {{ number_format($emp->basic_salary, 2) }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    <button data-bs-toggle="modal" data-bs-target="#paysheetModal" class="btn btn-primary-custom btn-sm fw-bold px-3 py-1 rounded-2 border-0" style="font-size: 0.75rem;">Get Pay Sheet</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No employees found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Employer Details Section -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <h5 class="fw-bold text-body mb-0 fs-6">Employer Details</h5>
            </div>

            <!-- Banner -->
            <div class="card border-0 rounded-4 shadow-sm mb-4 bg-primary-subtle text-primary">
                <div class="card-body p-3 p-md-5 d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold text-body mb-1">Welcome For</h4>
                        <h2 class="fs-4 fw-bolder text-primary-custom mb-3">New Employer</h2>
                        <p class="text-secondary small fw-semibold mb-4">Enter Details for add New employer of your company</p>
                        <button data-bs-toggle="modal" data-bs-target="#createEmployerModal" class="btn btn-primary-custom fw-bold px-4 py-2 rounded-3 d-inline-flex align-items-center gap-2 border-0">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            Add New Employer
                        </button>
                    </div>
                    <!-- Placeholder for illustration -->
                    <div class="d-none d-md-block bg-primary bg-opacity-10" style="width: 300px; height: 150px; border-radius: 20px;">
                        <!-- You can place an SVG illustration here -->
                    </div>
                </div>
            </div>

            <!-- Employer List Table -->
            <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden" style="max-width: 600px;">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">Date</th>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">Emp. ID</th>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">Name</th>
                                <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">Designation</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-body">
                            @forelse($employees as $index => $emp)
                            <tr>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $emp->created_at ? $emp->created_at->format('Y.m.d') : now()->format('Y.m.d') }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $emp->emp_id }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $emp->name }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $emp->designation }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No employees found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Modals -->
        
        <!-- Create Employer Modal -->
        <div class="modal fade" id="createEmployerModal" tabindex="-1" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="fw-bold text-body fs-5 mb-0">Add New Employer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <form wire:submit.prevent="save">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-body small mb-1">Date</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control rounded-start-3 fw-bold border-end-0 bg-body-tertiary" value="{{ now()->format('d/m/Y') }}" readonly>
                                        <span class="input-group-text bg-body border-start-0 rounded-end-3">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-body small mb-1">EMP ID</label>
                                    <input type="text" wire:model="emp_id" class="form-control rounded-3 fw-bold bg-body-tertiary" readonly>
                                    @error('emp_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-body small mb-1">Employer Name</label>
                                <input type="text" wire:model="name" class="form-control rounded-3" placeholder="Jhon">
                                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-body small mb-1">Phone Number</label>
                                <input type="text" wire:model="phone" class="form-control rounded-3 text-body-secondary" placeholder="077*********7">
                                @error('phone') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-body small mb-1">Designation</label>
                                <select wire:model="designation" class="form-select rounded-3 fw-bold">
                                    <option value="">Select Designation</option>
                                    <option value="Accounter">Accounter</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Cashier">Cashier</option>
                                </select>
                                @error('designation') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-4 pb-2">
                                <label class="form-label fw-bold text-body small mb-1">Salary</label>
                                <input type="number" step="0.01" wire:model="basic_salary" class="form-control rounded-3 text-body-secondary fw-semibold" placeholder="10000.00">
                                @error('basic_salary') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="d-flex gap-3 mt-2">
                                <button type="button" class="btn btn-outline-primary-custom fw-bold py-2 flex-grow-1 rounded-3 bg-body" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary-custom fw-bold py-2 flex-grow-1 rounded-3 shadow-sm">
                                    <span wire:loading.remove wire:target="save">Save</span>
                                    <span wire:loading wire:target="save">Saving...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Modal -->
        <div class="modal fade" id="attendanceModal" tabindex="-1" wire:ignore.self>
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="fw-bold text-body fs-5 mb-0">Attendance Mark</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="mb-4 pb-2" style="width: 200px;">
                            <label class="form-label fw-bold text-body small mb-1">Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control rounded-start-3 fw-bold border-end-0 bg-body-tertiary" value="{{ now()->format('d/m/Y') }}" readonly>
                                <span class="input-group-text bg-body border-start-0 rounded-end-3">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                </span>
                            </div>
                        </div>

                        <div class="table-responsive mb-4 pb-2">
                            <table class="table table-sm table-borderless align-middle mb-0 text-body">
                                <thead>
                                    <tr class="border-bottom">
                                        <th class="py-3 px-2 fw-bold text-body">Emp. ID</th>
                                        <th class="py-3 px-2 fw-bold text-body">Name</th>
                                        <th class="py-3 px-2 fw-bold text-body">Designation</th>
                                        <th class="py-3 px-2 fw-bold text-body text-center">Present</th>
                                        <th class="py-3 px-2 fw-bold text-body text-center">Absent</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-body">
                                    @foreach($employees as $emp)
                                    <tr>
                                        <td class="py-3 px-2 text-secondary">{{ $emp->emp_id }}</td>
                                        <td class="py-3 px-2 text-body">{{ $emp->name }}</td>
                                        <td class="py-3 px-2" style="width: 200px;">
                                            <input type="text" class="form-control rounded-3 fw-bold border-secondary-subtle shadow-sm bg-body text-body w-100" value="{{ $emp->designation }}" readonly>
                                        </td>
                                        <td class="py-3 px-2 text-center">
                                            <input type="checkbox" class="form-check-input border-2 border-secondary-subtle bg-transparent shadow-sm" style="width: 28px; height: 28px;">
                                        </td>
                                        <td class="py-3 px-2 text-center">
                                            <input type="checkbox" class="form-check-input border-2 border-secondary-subtle bg-transparent shadow-sm" style="width: 28px; height: 28px;">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-2">
                            <button type="button" class="btn btn-outline-primary-custom fw-bold py-2 px-4 rounded-3 bg-body" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary-custom fw-bold py-2 px-4 rounded-3 shadow-sm" data-bs-dismiss="modal">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pay Sheet Modal -->
        <div class="modal fade" id="paysheetModal" tabindex="-1" wire:ignore.self>
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="fw-bold text-body fs-5 mb-0">Pay sheet Select</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="d-grid mb-4 pb-3">
                            <button type="button" class="btn btn-primary-custom fw-bold py-3 rounded-3 shadow-sm text-center border-0" data-bs-dismiss="modal">This Month Pay Sheet</button>
                        </div>

                        <div>
                            <label class="form-label fw-bold text-body small mb-2">Previous Months</label>
                            <label class="form-label text-body small mb-1 ms-1 d-block">Month</label>
                            <div class="d-flex gap-2">
                                <div class="input-group flex-grow-1">
                                    <input type="text" class="form-control rounded-start-3 fw-bold border-end-0 shadow-sm" value="{{ now()->subMonth()->format('F/Y') }}">
                                    <span class="input-group-text bg-body border-start-0 rounded-end-3 shadow-sm">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    </span>
                                </div>
                                <button type="button" class="btn btn-primary-custom fw-bold px-4 rounded-3 shadow-sm border-0" data-bs-dismiss="modal">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('livewire:initialized', () => {
                @this.on('close-modal', (event) => {
                    const modalElement = document.getElementById(event.id);
                    if (modalElement) {
                        const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                        if (modal) {
                            modal.hide();
                            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                            document.body.classList.remove('modal-open');
                        }
                    }
                });
                
                @this.on('open-modal', (event) => {
                    const modalElement = document.getElementById(event.id);
                    if (modalElement) {
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                    }
                });
            });
        </script>
    </div>
