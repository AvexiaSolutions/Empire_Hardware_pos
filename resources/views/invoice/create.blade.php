<x-pos-layout>
    <div class="pb-5">
        
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('invoice.index') }}" class="btn btn-light bg-body border shadow-sm fw-bold d-inline-flex align-items-center gap-2 px-3 rounded-pill py-2">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg> Back
            </a>
            <h2 class="fs-4 fw-bold text-body mb-0 ms-2">Create Invoice</h2>
        </div>

        <div class="card border-0 rounded-4 shadow-sm bg-body p-3 p-lg-5">
            
            <!-- Top Details -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-md-8">
                    <div class="row g-3">
                        <!-- Customer Name -->
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold text-body small">Customer Name</label>
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control rounded-3" placeholder="hasith jayantha">
                                <button class="btn btn-primary-custom rounded-3 d-flex align-items-center justify-content-center px-3 fw-bold fs-5">
                                    +
                                </button>
                            </div>
                        </div>
                        
                        <!-- Invoice No -->
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold text-body small">Invoice No</label>
                            <input type="text" class="form-control rounded-3" value="054">
                        </div>

                        <!-- Customer Phone Number -->
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold text-body small">Customer Phone Number</label>
                            <input type="text" class="form-control rounded-3" placeholder="077*********8">
                        </div>

                        <!-- Date -->
                        <div class="col-12 col-sm-6">
                            <label class="form-label fw-bold text-body small">Date</label>
                            <input type="date" class="form-control rounded-3" value="2024-06-09">
                        </div>
                    </div>
                </div>

                <!-- Balance -->
                <div class="col-12 col-md-4 d-flex flex-column justify-content-start align-items-end pt-2">
                    <div class="fw-bold text-body mb-1">Balance</div>
                    <div class="fs-2 fw-bolder lh-1 text-body">5000.00</div>
                </div>
            </div>

            <!-- Items Table Area -->
            <div class="table-responsive mb-4">
                <table class="table table-sm table-borderless align-middle fw-semibold text-body mb-0" style="min-width: 800px;">
                    <thead>
                        <tr class="small text-body border-bottom">
                            <th class="pb-3 fw-bold" style="width: 100px;">Item code</th>
                            <th class="pb-3 fw-bold">Item / Service</th>
                            <th class="pb-3 fw-bold" style="width: 80px;">Qut</th>
                            <th class="pb-3 fw-bold" style="width: 120px;">Rate(Rs.)</th>
                            <th class="pb-3 fw-bold" style="width: 120px;">Amount(Rs.)</th>
                            <th class="pb-3 fw-bold" style="width: 160px;">Discount(Rs.)</th>
                            <th class="pb-3"></th>
                        </tr>
                    </thead>
                    <tbody class="border-bottom">
                        <tr>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body" value="054"></td>
                            <td class="py-3">
                                <select class="form-select rounded-3 bg-body">
                                    <option>AMW Tire</option>
                                </select>
                            </td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body text-center" value="2"></td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body text-end" value="5,000"></td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body text-end" value="10,000" readonly></td>
                            <td class="py-3">
                                <div class="d-flex gap-2">
                                    <input type="text" class="form-control rounded-3 bg-body text-end" value="2,000">
                                    <select class="form-select rounded-3 bg-body px-2" style="width: 60px;">
                                        <option>%</option>
                                        <option>Rs</option>
                                    </select>
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                <button class="btn btn-link text-danger p-0">
                                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body-tertiary" placeholder="000"></td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body-tertiary" placeholder="Service"></td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body-tertiary text-center" placeholder="0"></td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body-tertiary text-end" placeholder="0"></td>
                            <td class="py-3"><input type="text" class="form-control rounded-3 bg-body-tertiary text-end" placeholder="0" readonly></td>
                            <td class="py-3">
                                <div class="d-flex gap-2">
                                    <input type="text" class="form-control rounded-3 bg-body-tertiary text-end" placeholder="0">
                                    <select class="form-select rounded-3 bg-body-tertiary px-2" style="width: 60px;">
                                        <option>%</option>
                                        <option>Rs</option>
                                    </select>
                                </div>
                            </td>
                            <td class="py-3 text-center"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Add Buttons -->
            <div class="d-flex gap-2 mb-4">
                <button class="btn btn-primary-custom rounded-3 fw-bold small d-flex align-items-center gap-1 py-1 px-3" style="font-size: 0.85rem;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Add Item
                </button>
                <button class="btn btn-primary-custom rounded-3 fw-bold small d-flex align-items-center gap-1 py-1 px-3" style="font-size: 0.85rem;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Add Service
                </button>
            </div>

            <!-- Bottom Section -->
            <div class="row g-5">
                <!-- Note -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold text-body">Note</label>
                    <textarea class="form-control rounded-3 bg-body" rows="6" placeholder="Write some thing ........."></textarea>
                </div>

                <!-- Totals & Payment -->
                <div class="col-12 col-md-6 d-flex flex-column gap-3">
                    <div class="row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-body">Total Amount</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-3 bg-body fw-semibold" value="10,000">
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-body">Discount</label>
                        <div class="col-sm-8 d-flex gap-2">
                            <input type="text" class="form-control rounded-3 bg-body fw-semibold" value="0">
                            <select class="form-select rounded-3 bg-body fw-semibold" style="width: 80px;">
                                <option>%</option>
                                <option>Rs</option>
                            </select>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <label class="col-sm-4 col-form-label fw-bold text-body">Payment Method</label>
                        <div class="col-sm-8">
                            <select class="form-select rounded-3 bg-body fw-semibold" onchange="togglePaymentFields(this.value)">
                                <option value="cash">Cash</option>
                                <option value="credit">Credit</option>
                                <option value="cheque">Cheque</option>
                            </select>
                        </div>
                    </div>

                    <!-- Dynamic Fields based on Payment Method -->
                    <!-- Cash Fields -->
                    <div id="fields_cash" class="row align-items-center payment-fields mt-2">
                        <label class="col-sm-4 col-form-label fw-bold text-body">Payments</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control rounded-3 bg-body fw-semibold" value="15,000">
                        </div>
                    </div>

                    <!-- Credit Fields -->
                    <div id="fields_credit" class="payment-fields mt-2" style="display: none;">
                        <div class="row align-items-center mb-3">
                            <label class="col-sm-4 col-form-label fw-bold text-body">Advanced Amount</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control rounded-3 bg-body fw-semibold" placeholder="0">
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <label class="col-sm-4 col-form-label fw-bold text-body">Due Date</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control rounded-3 bg-body fw-semibold">
                            </div>
                        </div>
                    </div>

                    <!-- Cheque Fields -->
                    <div id="fields_cheque" class="payment-fields mt-2" style="display: none;">
                        <div class="row align-items-center mb-3">
                            <label class="col-sm-4 col-form-label fw-bold text-body">Cheque No.</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control rounded-3 bg-body fw-semibold" placeholder="0000">
                            </div>
                        </div>
                        <div class="row align-items-center mb-3">
                            <label class="col-sm-4 col-form-label fw-bold text-body">Bank Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control rounded-3 bg-body fw-semibold" placeholder="Bank Name">
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <label class="col-sm-4 col-form-label fw-bold text-body">Due Date</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control rounded-3 bg-body fw-semibold">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end align-items-center gap-4 mt-5 pt-4 border-top">
                <a href="{{ route('invoice.index') }}" class="text-primary-custom fw-bold text-decoration-none">Cancel</a>
                <button class="btn btn-outline-primary-custom bg-body fw-bold px-5 py-2 rounded-3 shadow-sm">Print</button>
                <button class="btn btn-primary-custom fw-bold px-5 py-2 rounded-3 shadow-sm">Save</button>
            </div>

        </div>
    </div>

    <!-- Script to toggle payment fields dynamically for UI demonstration -->
    <script>
        function togglePaymentFields(method) {
            document.querySelectorAll('.payment-fields').forEach(el => el.style.display = 'none');
            document.getElementById('fields_' + method).style.display = 'block';
        }
    </script>
</x-pos-layout>
