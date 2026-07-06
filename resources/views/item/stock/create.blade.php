<x-pos-layout>
    <div class="pb-5">
        
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('item.index') }}" class="btn btn-light bg-body border shadow-sm fw-bold d-inline-flex align-items-center gap-2 px-3 rounded-pill py-2">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg> Back
            </a>
            <h2 class="fs-4 fw-bold text-body mb-0 ms-2">Enter New Stock</h2>
        </div>

        <div class="card border-0 rounded-4 shadow-sm bg-body p-3 p-lg-5">
            
            <!-- Top Details -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-md-8">
                    <div class="row g-3">
                        <!-- Supplier Name -->
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold text-body small">Supplier Name</label>
                            <select class="form-select rounded-3 fw-bold bg-body">
                                <option>New Line Supplier</option>
                            </select>
                        </div>
                        
                        <!-- GRN No -->
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold text-body small">GRN No</label>
                            <input type="text" class="form-control rounded-3 fw-bold bg-body" value="GRN054">
                        </div>

                        <!-- Order recovered by -->
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold text-body small">Order recovered by</label>
                            <input type="text" class="form-control rounded-3 bg-body" placeholder="Enter your name">
                        </div>

                        <!-- Date -->
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold text-body small">Date</label>
                            <input type="date" class="form-control rounded-3 fw-bold bg-body" value="2024-06-09">
                        </div>
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="col-12 col-md-4 d-flex flex-column justify-content-start align-items-end pt-2">
                    <div class="fw-bold text-body mb-1">Total Amount</div>
                    <div class="fs-2 fw-bolder lh-1 text-body">Rs.10,000</div>
                </div>
            </div>

            <!-- Items Table Area -->
            <div class="table-responsive mb-4">
                <table class="table table-sm table-borderless align-middle fw-semibold text-body mb-0" style="min-width: 900px;">
                    <thead>
                        <tr class="small text-body border-bottom">
                            <th class="pb-3 fw-bold" style="width: 100px;">Item code</th>
                            <th class="pb-3 fw-bold">Item</th>
                            <th class="pb-3 fw-bold" style="width: 80px;">Qut</th>
                            <th class="pb-3 fw-bold" style="width: 120px;">GRN type</th>
                            <th class="pb-3 fw-bold" style="width: 130px;">Cost Price(Rs.)</th>
                            <th class="pb-3 fw-bold" style="width: 130px;">Amount(Rs.)</th>
                            <th class="pb-3 fw-bold" style="width: 140px;">Selling Price(Rs.)</th>
                            <th class="pb-3"></th>
                        </tr>
                    </thead>
                    <tbody class="border-bottom">
                        <tr>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body fw-bold" value="054"></td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body fw-bold" value="AMW Tire"></td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body fw-bold text-center" value="2"></td>
                            <td class="py-3">
                                <select class="form-select rounded-3 bg-body fw-bold">
                                    <option>General</option>
                                    <option>Styles</option>
                                </select>
                            </td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body fw-bold text-end" value="5,000"></td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body fw-bold text-end" value="10,000"></td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body fw-bold text-end" value="10,000"></td>
                            <td class="py-3 text-center">
                                <button class="btn btn-link text-danger p-0">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body-tertiary" placeholder="000"></td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body-tertiary" placeholder="Item Name"></td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body-tertiary text-center" placeholder="0"></td>
                            <td class="py-3">
                                <select class="form-select rounded-3 bg-body-tertiary fw-bold text-secondary">
                                    <option>Styles</option>
                                    <option>General</option>
                                </select>
                            </td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body-tertiary text-end" placeholder="0"></td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body-tertiary text-end" placeholder="0"></td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body-tertiary text-end" placeholder="0"></td>
                            <td class="py-3 text-center"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Add Buttons -->
            <div class="mb-4">
                <button class="btn btn-primary-custom rounded-3 fw-bold small d-inline-flex align-items-center gap-1 py-1 px-3" style="font-size: 0.85rem;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Add New
                </button>
            </div>

            <!-- Bottom Section -->
            <div class="row">
                <!-- Note -->
                <div class="col-12 col-md-8 mb-4 mb-md-0">
                    <label class="form-label fw-bold text-body">Note</label>
                    <textarea class="form-control rounded-3 bg-body" rows="6" placeholder="Write some thing ........."></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="col-12 col-md-4 d-flex flex-column justify-content-end gap-3 align-items-end pt-2">
                    <button class="btn btn-primary-custom fw-bold px-4 py-3 rounded-3 shadow-sm w-75">Save</button>
                    <button class="btn btn-outline-primary-custom bg-body fw-bold px-4 py-3 rounded-3 shadow-sm w-75">Print as GRN</button>
                    <div class="w-75 text-center mt-2">
                        <a href="{{ route('item.index') }}" class="text-primary-custom fw-bold text-decoration-underline">Cancel</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-pos-layout>
