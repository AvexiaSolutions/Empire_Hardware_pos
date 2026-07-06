<div>
    <div class="d-flex align-items-center justify-content-center h-100 pb-5">
        
        <div class="card border-0 rounded-4 shadow-sm bg-body" style="width: 100%; max-width: 450px;">
            <div class="card-body p-3 p-md-5">
                
                <div class="d-flex align-items-center gap-3 mb-4 pb-2">
                    <a href="{{ route('employer.index') }}" class="btn btn-light bg-body-tertiary border shadow-sm fw-bold d-inline-flex align-items-center gap-2 px-3 rounded-pill py-1" style="font-size: 0.85rem;">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg> Back
                    </a>
                    <h3 class="fw-bold text-body mb-0 fs-4 ms-2">Pay sheet Select</h3>
                </div>

                <div class="d-grid mb-4 pb-3">
                    <a href="{{ route('employer.paysheet.show') }}" class="btn btn-primary-custom fw-bold py-3 rounded-3 shadow-sm text-center text-decoration-none d-block">This Month Pay Sheet</a>
                </div>

                <div>
                    <label class="form-label fw-bold text-body small mb-2">Previous Months</label>
                    <label class="form-label text-body small mb-1 ms-1 d-block">Month</label>
                    <div class="d-flex gap-2">
                        <div class="input-group flex-grow-1">
                            <input type="text" class="form-control rounded-start-3 fw-bold border-end-0 shadow-sm" value="June/2024">
                            <span class="input-group-text bg-body border-start-0 rounded-end-3 shadow-sm">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            </span>
                        </div>
                        <a href="{{ route('employer.paysheet.show') }}" class="btn btn-primary-custom fw-bold px-4 rounded-3 shadow-sm">Submit</a>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
