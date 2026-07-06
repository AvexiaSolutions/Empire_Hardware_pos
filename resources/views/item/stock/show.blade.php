<x-pos-layout>
    <div class="pb-5">
        
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('item.index') }}" class="btn btn-light bg-body border shadow-sm fw-bold d-inline-flex align-items-center gap-2 px-3 rounded-pill py-2">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg> Back
            </a>
            <h2 class="fs-4 fw-bold text-body mb-0 ms-2">GRN - Good Received Note</h2>
        </div>

        <div class="card border-0 rounded-4 shadow-sm bg-body p-3 p-lg-5">
            
            <!-- Top Details -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-md-8">
                    <div class="row g-4">
                        <!-- Supplier Name -->
                        <div class="col-12 col-sm-6">
                            <div class="fw-bold text-body small mb-2">Supplier Name</div>
                            <div class="border rounded-3 p-2 px-3 bg-body fw-bold">New Line Supplier</div>
                        </div>
                        
                        <!-- GRN No -->
                        <div class="col-12 col-sm-6">
                            <div class="fw-bold text-body small mb-2">GRN No</div>
                            <div class="border rounded-3 p-2 px-3 bg-body fw-bold">GRN054</div>
                        </div>

                        <!-- Order recovered by -->
                        <div class="col-12 col-sm-6">
                            <div class="fw-bold text-body small mb-2">Order recovered by</div>
                            <div class="border rounded-3 p-2 px-3 bg-body fw-bold">Hasith Jayantha</div>
                        </div>

                        <!-- Date -->
                        <div class="col-12 col-sm-6">
                            <div class="fw-bold text-body small mb-2">Date</div>
                            <div class="border rounded-3 p-2 px-3 bg-body fw-bold d-flex justify-content-between">
                                09/06/2024
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Balance -->
                <div class="col-12 col-md-4 d-flex flex-column justify-content-start align-items-end pt-2">
                    <div class="fw-bold text-body mb-1">Balance</div>
                    <div class="fs-2 fw-bolder lh-1 text-body">Rs.18,000</div>
                </div>
            </div>

            <!-- Items Table Area -->
            <div class="table-responsive mb-4">
                <table class="table table-sm table-borderless align-middle fw-bold text-body mb-0" style="min-width: 800px;">
                    <thead>
                        <tr class="text-body border-bottom">
                            <th class="pb-3 fw-bold" style="width: 100px;">Item code</th>
                            <th class="pb-3 fw-bold">Item / Service</th>
                            <th class="pb-3 fw-bold" style="width: 80px;">Qut</th>
                            <th class="pb-3 fw-bold" style="width: 120px;">GRN type</th>
                            <th class="pb-3 fw-bold" style="width: 130px;">Cost Price(Rs.)</th>
                            <th class="pb-3 fw-bold" style="width: 130px;">Amount(Rs.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-3">054</td>
                            <td class="py-3">AMW Tire</td>
                            <td class="py-3">2</td>
                            <td class="py-3">General</td>
                            <td class="py-3">5,000</td>
                            <td class="py-3">10,000</td>
                        </tr>
                        <tr>
                            <td class="py-3">025</td>
                            <td class="py-3">Big lorry Tire</td>
                            <td class="py-3">1</td>
                            <td class="py-3">Styles</td>
                            <td class="py-3">8,000</td>
                            <td class="py-3">8,000</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Bottom Section -->
            <div class="row">
                <!-- Note -->
                <div class="col-12 col-md-8 mb-4 mb-md-0">
                    <div class="fw-bold text-body mb-2">Note</div>
                    <p class="text-body-secondary fw-semibold small pe-5">
                        This invoice valid for 2 weeks and if more information contact us.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="col-12 col-md-4 d-flex flex-column justify-content-end gap-3 align-items-end">
                    <button onclick="window.print()" class="btn btn-primary-custom fw-bold px-4 py-3 rounded-3 shadow-sm w-100">Print</button>
                    <button onclick="window.print()" class="btn btn-outline-primary-custom bg-body fw-bold px-4 py-3 rounded-3 shadow-sm w-75">Download as PDF</button>
                </div>
            </div>

        </div>
    </div>
</x-pos-layout>
