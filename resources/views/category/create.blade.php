<x-pos-layout>
    <div class="d-flex align-items-center justify-content-center h-100 pb-5">
        
        <div class="card border-0 rounded-4 shadow-sm bg-body" style="width: 100%; max-width: 500px;">
            <div class="card-body p-3 p-md-5">
                
                <div class="d-flex align-items-center gap-3 mb-4 pb-2">
                    <a href="{{ route('item.index') }}" class="btn btn-light bg-body-tertiary border shadow-sm fw-bold d-inline-flex align-items-center gap-2 px-3 rounded-pill py-1" style="font-size: 0.85rem;">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg> Back
                    </a>
                    <h3 class="fw-bold text-body mb-0 fs-4 ms-2">Add New Category</h3>
                </div>

                <form>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-body small mb-1">Category Name</label>
                        <input type="text" class="form-control rounded-3" placeholder="car tire">
                    </div>
                    
                    <div class="mb-3 form-check d-flex align-items-center gap-2 ps-0">
                        <input type="checkbox" class="form-check-input mt-0 ms-0 border-primary bg-primary" id="hasSubCategory" checked style="width: 1.25rem; height: 1.25rem;">
                        <label class="form-check-label fw-bold text-body small mt-1" for="hasSubCategory">If have sub category</label>
                    </div>

                    <div class="mb-3 d-flex flex-wrap gap-2">
                        <span class="badge bg-secondary text-body bg-opacity-25 border rounded-1 px-2 py-1 d-flex align-items-center gap-1 text-body fw-bold">car tire <span style="cursor:pointer">&times;</span></span>
                        <span class="badge bg-secondary text-body bg-opacity-25 border rounded-1 px-2 py-1 d-flex align-items-center gap-1 text-body fw-bold">car tire <span style="cursor:pointer">&times;</span></span>
                    </div>

                    <div class="mb-3 position-relative d-flex align-items-center gap-2">
                        <div class="flex-grow-1">
                            <label class="form-label fw-bold text-body small mb-1">Sub Category Name</label>
                            <input type="text" class="form-control rounded-3" placeholder="car tire">
                        </div>
                        <button type="button" class="btn btn-link text-danger p-0 mt-4">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-body small mb-1">Sub Category Name</label>
                        <input type="text" class="form-control rounded-3" placeholder="car tire">
                    </div>

                    <div class="mb-4 pb-2">
                        <button type="button" class="btn btn-primary-custom rounded-3 fw-bold small d-inline-flex align-items-center gap-1 py-1 px-3" style="font-size: 0.85rem;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Add New
                        </button>
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
