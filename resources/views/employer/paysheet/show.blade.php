<div>
    <div class="d-flex align-items-center justify-content-center h-100 pb-5 pt-3">
        
        <div class="card border-0 rounded-4 shadow-sm bg-body" style="width: 100%; max-width: 650px;">
            <div class="card-body p-3 p-md-5">
                
                <div class="d-flex align-items-center justify-content-between mb-4 pb-2">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('employer.paysheet.select') }}" class="btn btn-light bg-body-tertiary border shadow-sm fw-bold d-inline-flex align-items-center gap-2 px-3 rounded-pill py-1" style="font-size: 0.85rem;">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg> Back
                        </a>
                        <h3 class="fw-bold text-body mb-0 fs-4 ms-2">Employee Pay Sheet</h3>
                    </div>
                    <button class="btn btn-primary-custom rounded-3 d-flex align-items-center justify-content-center p-2 shadow-sm">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-body small mb-1">Employee ID</label>
                        <div class="form-control rounded-3 fw-bold bg-body">EMP005</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-body small mb-1">Date</label>
                        <div class="form-control rounded-3 fw-bold bg-body d-flex justify-content-between align-items-center">
                            09/06/2024
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-body small mb-1">Employee Name</label>
                    <div class="form-control rounded-3 fw-bold bg-body">Dasun Daluwaththa</div>
                </div>

                <div class="row g-3 mb-4 pb-2">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-body small mb-1">Designation</label>
                        <div class="form-control rounded-3 fw-bold bg-body">Manager</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-body small mb-1">Total Salary</label>
                        <div class="form-control rounded-3 fw-bold bg-body">Rs.60,000</div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Description</th>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Amount(Rs.)</th>
                            </tr>
                        </thead>
                        <tbody class="text-body">
                            <!-- Earning -->
                            <tr>
                                <td colspan="2" class="py-3 px-4 fw-bold text-secondary">Earning</td>
                            </tr>
                            <tr class="fw-semibold">
                                <td class="py-2 px-4 text-body">Basic Salary</td>
                                <td class="py-2 px-4 text-body">59,000</td>
                            </tr>
                            <tr class="fw-semibold">
                                <td class="py-2 px-4 text-body">Performance</td>
                                <td class="py-2 px-4 text-body">1,000</td>
                            </tr>
                            <tr class="fw-bold">
                                <td class="py-3 px-4 text-body">Total Earning</td>
                                <td class="py-3 px-4 text-body">60,000</td>
                            </tr>

                            <!-- Dedications -->
                            <tr>
                                <td colspan="2" class="py-3 px-4 fw-bold text-secondary">Dedications</td>
                            </tr>
                            <tr class="fw-semibold">
                                <td class="py-2 px-4 text-body">Welfare</td>
                                <td class="py-2 px-4 text-body">680</td>
                            </tr>
                            <tr class="fw-semibold">
                                <td class="py-2 px-4 text-body">No Pay</td>
                                <td class="py-2 px-4 text-body">200</td>
                            </tr>
                            <tr class="fw-semibold">
                                <td class="py-2 px-4 text-body">TAX</td>
                                <td class="py-2 px-4 text-body">1,000</td>
                            </tr>
                            <tr class="fw-bold">
                                <td class="py-3 px-4 text-body">Total Dedication</td>
                                <td class="py-3 px-4 text-body">1,880</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-center gap-3 align-items-center">
                    <button onclick="window.print()" class="btn btn-outline-primary-custom bg-body fw-bold py-2 rounded-3 shadow-sm px-4">Download as PDF</button>
                    <button onclick="window.print()" class="btn btn-primary-custom fw-bold py-2 rounded-3 shadow-sm" style="width: 140px;">Print</button>
                </div>

            </div>
        </div>

    </div>
</div>
