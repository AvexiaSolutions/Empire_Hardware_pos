<x-pos-layout>
    <div class="d-flex align-items-center justify-content-center h-100 pb-5">
        
        <div class="card border-0 rounded-4 shadow-sm bg-body" style="width: 100%; max-width: 500px;">
            <div class="card-body p-3 p-md-5">
                
                <div class="d-flex align-items-center gap-3 mb-4 pb-2">
                    <a href="{{ route('item.index') }}" class="btn btn-light bg-body-tertiary border shadow-sm fw-bold d-inline-flex align-items-center gap-2 px-3 rounded-pill py-1" style="font-size: 0.85rem;">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg> Back
                    </a>
                    <h3 class="fw-bold text-body mb-0 fs-4 ms-2">Add New Item</h3>
                </div>

                <form>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-body small mb-1">Item Name</label>
                        <input type="text" class="form-control rounded-3" placeholder="car tire">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-body small mb-1">Item Code</label>
                        <input type="text" class="form-control rounded-3 fw-bold" value="054">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-body small mb-1">Category</label>
                        <select class="form-select rounded-3 fw-bold">
                            <option>Tire</option>
                            <option>Oil</option>
                            <option>Battery</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-body small mb-1">Sub Category</label>
                        <select class="form-select rounded-3 fw-bold">
                            <option>Car Tire</option>
                            <option>Van Tire</option>
                            <option>Lorry Tire</option>
                        </select>
                    </div>

                    <div class="mb-4 pb-3">
                        <label class="form-label fw-bold text-body small mb-1">Min. Margin Amount</label>
                        <input type="text" class="form-control rounded-3" placeholder="20">
                    </div>

                    <div class="d-flex gap-3 mt-2">
                        <a href="{{ route('item.index') }}" class="btn btn-outline-primary-custom fw-bold py-2 flex-grow-1 rounded-3 bg-body">Cancel</a>
                        <button type="button" class="btn btn-primary-custom fw-bold py-2 flex-grow-1 rounded-3 shadow-sm">Save</button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</x-pos-layout>
