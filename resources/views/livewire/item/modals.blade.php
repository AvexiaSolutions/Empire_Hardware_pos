<!-- Add Category Modal -->
<div class="modal fade {{ $showAddCategoryModal ? 'show d-block' : '' }}" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold mb-0">Add Category</h5>
                <button type="button" wire:click="closeModals" class="btn-close"></button>
            </div>
            <div class="modal-body pb-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Category Name</label>
                    <input type="text" wire:model="categoryName" class="form-control rounded-3" placeholder="e.g. Tires">
                    @error('categoryName') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <button wire:click="saveCategory" class="btn btn-primary-custom w-100 fw-bold rounded-3 py-2">Save Category</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Sub Category Modal -->
<div class="modal fade {{ $showAddSubCategoryModal ? 'show d-block' : '' }}" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold mb-0">Add Sub Category</h5>
                <button type="button" wire:click="closeModals" class="btn-close"></button>
            </div>
            <div class="modal-body pb-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Parent Category</label>
                    <select wire:model="subCategoryParentId" class="form-select rounded-3">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('subCategoryParentId') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Sub Category Name</label>
                    <input type="text" wire:model="subCategoryName" class="form-control rounded-3" placeholder="e.g. Car Tires">
                    @error('subCategoryName') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <button wire:click="saveSubCategory" class="btn btn-primary-custom w-100 fw-bold rounded-3 py-2">Save Sub Category</button>
            </div>
        </div>
    </div>
</div>

<!-- Add New Item Modal -->
<div class="modal fade {{ $showAddItemModal ? 'show d-block' : '' }}" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold mb-0">Add New Item</h5>
                <button type="button" wire:click="closeModals" class="btn-close"></button>
            </div>
            <div class="modal-body pb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Item Image (Optional)</label>
                        <input type="file" wire:model="itemImage" class="form-control rounded-3">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category/Sub Category</label>
                        <select wire:model="itemCategoryId" class="form-select rounded-3">
                            <option value="">Select Category/Sub Category</option>
                            @foreach($categories as $category)
                                <option value="cat_{{ $category->id }}" class="fw-bold">{{ $category->name }}</option>
                                @foreach($category->subCategories as $subCat)
                                    <option value="sub_{{ $subCat->id }}">&nbsp;&nbsp;&nbsp;&mdash; {{ $subCat->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('itemCategoryId') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-md-7">
                        <label class="form-label fw-semibold">Item Name</label>
                        <input type="text" wire:model="itemName" class="form-control rounded-3" placeholder="e.g. 10mm Wire">
                        @error('itemName') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Search Aliases</label>
                        <input type="text" wire:model="itemSearchAliases" class="form-control rounded-3" placeholder="e.g. wire, 10mm, cable">
                        <small class="text-muted" style="font-size: 0.7rem;">Optional: Alternative names</small>
                        @error('itemSearchAliases') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Rack Number</label>
                        <input type="text" wire:model="itemRackNumber" class="form-control rounded-3" placeholder="e.g. A1, Rack 5">
                        <small class="text-muted" style="font-size: 0.7rem;">Optional: Item location</small>
                        @error('itemRackNumber') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Rack Row</label>
                        <input type="text" wire:model="itemRackRow" class="form-control rounded-3" placeholder="e.g. Row 2, Top Shelf">
                        <small class="text-muted" style="font-size: 0.7rem;">Optional: Specific position</small>
                        @error('itemRackRow') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Cost Price</label>
                        <input type="number" wire:model="itemCostPrice" class="form-control rounded-3" step="0.01">
                        @error('itemCostPrice') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Selling Price</label>
                        <input type="number" wire:model="itemSellingPrice" class="form-control rounded-3" step="0.01">
                        @error('itemSellingPrice') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Discount (Optional)</label>
                        <input type="text" wire:model="itemDiscount" class="form-control rounded-3" placeholder="Amount or %">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Base Unit (e.g. Meter)</label>
                        <select wire:model="itemBaseUnit" class="form-select rounded-3">
                            <option value="">Select Unit</option>
                            <option value="Pcs">Pcs</option>
                            <option value="Roll">Roll</option>
                            <option value="Box">Box</option>
                            <option value="Kg">Kg</option>
                            <option value="Ltr">Ltr</option>
                            <option value="Meter">Meter</option>
                            <option value="Feet">Feet</option>
                            <option value="Bottle">Bottle</option>
                        </select>
                        @error('itemBaseUnit') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    @if(!$itemHasExpiryDate)
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Quantity (in Base Unit)</label>
                        <input type="number" wire:model="itemQuantity" class="form-control rounded-3" step="0.01">
                        @error('itemQuantity') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <div class="col-12 mt-4 mb-2 p-3 bg-body-secondary rounded-3 border">
                        <div class="row text-center align-items-center g-3">

                            <div class="col-md-4">
                                <div class="form-check form-switch d-inline-block mb-0">
                                    <input class="form-check-input fs-5" type="checkbox" wire:model.live="itemHasWarranty" id="addItemHasWarranty">
                                    <label class="form-check-label fw-bold ms-2 mt-1" for="addItemHasWarranty">Has Warranty?</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch d-inline-block mb-0">
                                    <input class="form-check-input fs-5" type="checkbox" wire:model.live="itemHasExpiryDate" id="addItemHasExpiryDate">
                                    <label class="form-check-label fw-bold ms-2 mt-1" for="addItemHasExpiryDate">Has Expiry Date?</label>
                                </div>
                            </div>
                        </div>
                    </div>



                    @if($itemHasWarranty)
                    <div class="col-md-6 mt-3">
                        <label class="form-label fw-semibold">Warranty Period (Months)</label>
                        <input type="number" wire:model="itemWarrantyMonths" class="form-control rounded-3">
                    </div>
                    @endif

                    @if($itemHasExpiryDate)
                    <div class="col-md-12 p-3 bg-danger-subtle rounded-3 border border-danger-subtle mt-3">
                        <h6 class="fw-bold text-danger mb-3">Expiry Date Tracking</h6>
                        @if(count($expiryDates) === 0)
                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="addExpiryDateRow(false)">
                                <i class="fas fa-plus"></i> Add Expiry Date
                            </button>
                        @endif
                        
                        @foreach($expiryDates as $index => $ed)
                        <div class="row g-3 mb-2 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold small">Expiry Date</label>
                                <input type="date" wire:model="expiryDates.{{ $index }}.date" class="form-control rounded-3">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold small">Quantity</label>
                                <input type="number" wire:model="expiryDates.{{ $index }}.quantity" class="form-control rounded-3" step="0.01">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger w-100 rounded-3" wire:click="removeExpiryDateRow({{ $index }}, false)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                        
                        @if(count($expiryDates) > 0)
                        <div class="mt-3">
                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="addExpiryDateRow(false)">
                                <i class="fas fa-plus"></i> Add Another Expiry Date
                            </button>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <div class="mt-4">
                    <button wire:click="saveItem" class="btn btn-primary-custom w-100 fw-bold rounded-3 py-2">Save Item & Generate Barcode</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Stock Modal (GRN) -->
<div class="modal fade {{ $showAddStockModal ? 'show d-block' : '' }}" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold mb-0">Add Stock (GRN)</h5>
                <button type="button" wire:click="closeModals" class="btn-close"></button>
            </div>
            <div class="modal-body pb-4">
                @if($stockItem)
                    <div class="mb-3">
                        <strong>{{ $stockItem->name }}</strong><br>
                        <small class="text-muted">Current Base Code: {{ $stockItem->code }}</small>
                    </div>

                    @if(!$stockItem->has_expiry_date)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Stock Quantity (in {{ $stockItem->base_unit }})</label>
                        <input type="number" wire:model="stockQuantity" class="form-control rounded-3" step="0.01">
                        @error('stockQuantity') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Cost Price</label>
                            <input type="number" wire:model="stockCostPrice" class="form-control rounded-3" step="0.01">
                            @error('stockCostPrice') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Selling Price</label>
                            <input type="number" wire:model="stockSellingPrice" class="form-control rounded-3" step="0.01">
                            @error('stockSellingPrice') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Discount (Optional)</label>
                            <input type="text" wire:model="stockDiscount" class="form-control rounded-3" placeholder="Amt or %">
                        </div>
                    </div>


                    @if($stockItem->has_expiry_date)
                    <div class="col-md-12 p-3 bg-danger-subtle rounded-3 border border-danger-subtle mb-3">
                        <h6 class="fw-bold text-danger mb-3">Expiry Date Tracking</h6>
                        @if(count($stockExpiryDates) === 0)
                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="addExpiryDateRow(true)">
                                <i class="fas fa-plus"></i> Add Expiry Date
                            </button>
                        @endif
                        
                        @foreach($stockExpiryDates as $index => $ed)
                        <div class="row g-3 mb-2 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold small">Expiry Date</label>
                                <input type="date" wire:model="stockExpiryDates.{{ $index }}.date" class="form-control rounded-3">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold small">Quantity (Base Units)</label>
                                <input type="number" wire:model="stockExpiryDates.{{ $index }}.quantity" class="form-control rounded-3" step="0.01">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger w-100 rounded-3" wire:click="removeExpiryDateRow({{ $index }}, true)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                        
                        @if(count($stockExpiryDates) > 0)
                        <div class="mt-3">
                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="addExpiryDateRow(true)">
                                <i class="fas fa-plus"></i> Add Another Expiry Date
                            </button>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="alert alert-info rounded-3 py-2 small">
                        <svg width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/></svg>
                        <strong>Smart GRN:</strong> If the selling price differs from the current batch, a new Batch ID and Barcode will be generated automatically.
                    </div>

                    <button wire:click="saveStock" class="btn btn-primary-custom w-100 fw-bold rounded-3 py-2">Confirm Add Stock</button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade {{ $showEditItemModal ? 'show d-block' : '' }}" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold mb-0">Edit Item</h5>
                <button type="button" wire:click="closeModals" class="btn-close"></button>
            </div>
            <div class="modal-body pb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Item Image (Optional)</label>
                        <input type="file" wire:model="itemImage" class="form-control rounded-3">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category/Sub Category</label>
                        <select wire:model="itemCategoryId" class="form-select rounded-3">
                            <option value="">Select Category/Sub Category</option>
                            @foreach($categories as $category)
                                <option value="cat_{{ $category->id }}" class="fw-bold">{{ $category->name }}</option>
                                @foreach($category->subCategories as $subCat)
                                    <option value="sub_{{ $subCat->id }}">&nbsp;&nbsp;&nbsp;&mdash; {{ $subCat->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('itemCategoryId') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-md-7">
                        <label class="form-label fw-semibold">Item Name</label>
                        <input type="text" wire:model="itemName" class="form-control rounded-3" placeholder="e.g. 10mm Wire">
                        @error('itemName') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Search Aliases</label>
                        <input type="text" wire:model="itemSearchAliases" class="form-control rounded-3" placeholder="e.g. wire, 10mm, cable">
                        <small class="text-muted" style="font-size: 0.7rem;">Optional: Alternative names</small>
                        @error('itemSearchAliases') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Rack Number</label>
                        <input type="text" wire:model="itemRackNumber" class="form-control rounded-3" placeholder="e.g. A1, Rack 5">
                        <small class="text-muted" style="font-size: 0.7rem;">Optional: Item location</small>
                        @error('itemRackNumber') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Rack Row</label>
                        <input type="text" wire:model="itemRackRow" class="form-control rounded-3" placeholder="e.g. Row 2, Top Shelf">
                        <small class="text-muted" style="font-size: 0.7rem;">Optional: Specific position</small>
                        @error('itemRackRow') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Base Unit (e.g. Meter)</label>
                        <select wire:model="itemBaseUnit" class="form-select rounded-3">
                            <option value="">Select Unit</option>
                            <option value="Pcs">Pcs</option>
                            <option value="Roll">Roll</option>
                            <option value="Box">Box</option>
                            <option value="Kg">Kg</option>
                            <option value="Ltr">Ltr</option>
                            <option value="Meter">Meter</option>
                            <option value="Feet">Feet</option>
                            <option value="Bottle">Bottle</option>
                        </select>
                        @error('itemBaseUnit') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-12 mt-4 mb-2 p-3 bg-body-secondary rounded-3 border">
                        <div class="row text-center align-items-center g-3">
                            <div class="col-md-4">

                                <div class="form-check form-switch d-inline-block mb-0">
                                    <input class="form-check-input fs-5" type="checkbox" wire:model.live="itemHasWarranty" id="editItemHasWarranty">
                                    <label class="form-check-label fw-bold ms-2 mt-1" for="editItemHasWarranty">Has Warranty?</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check form-switch d-inline-block mb-0">
                                    <input class="form-check-input fs-5" type="checkbox" wire:model.live="itemHasExpiryDate" id="editItemHasExpiryDate">
                                    <label class="form-check-label fw-bold ms-2 mt-1" for="editItemHasExpiryDate">Has Expiry Date?</label>
                                </div>
                            </div>
                        </div>
                    </div>



                    @if($itemHasWarranty)
                    <div class="col-md-6 mt-3">
                        <label class="form-label fw-semibold">Warranty Period (Months)</label>
                        <input type="number" wire:model="itemWarrantyMonths" class="form-control rounded-3">
                    </div>
                    @endif
                </div>

                <div class="mt-4">
                    <button wire:click="updateItem" class="btn btn-primary-custom w-100 fw-bold rounded-3 py-2">Update Item</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Item Details Modal -->
<div class="modal fade {{ $showItemDetailsModal ? 'show d-block' : '' }}" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold mb-0">Item Details</h5>
                <button type="button" wire:click="closeModals" class="btn-close"></button>
            </div>
            <div class="modal-body pb-4">
                @if($viewingItem)
                <div class="row">
                    <div class="col-md-4 text-center">
                        @if($viewingItem->image)
                            <img src="{{ Storage::url($viewingItem->image) }}" class="img-fluid rounded-3 mb-3 shadow-sm" style="max-height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-secondary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center text-secondary mx-auto mb-3" style="width: 150px; height: 150px;">
                                <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <h5 class="fw-bold text-primary-custom mb-1">{{ $viewingItem->name }}</h5>
                        <p class="text-muted mb-0">{{ $viewingItem->code }}</p>
                    </div>
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="text-muted small mb-1 d-block">Category / Sub Category</label>
                                <span class="fw-semibold">{{ $viewingItem->category->name ?? '-' }} / {{ $viewingItem->subCategory->name ?? '-' }}</span>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted small mb-1 d-block">Base Unit</label>
                                <span class="fw-semibold">{{ $viewingItem->base_unit }}</span>
                            </div>

                            <div class="col-sm-6">
                                <label class="text-muted small mb-1 d-block">Rack / Row</label>
                                <span class="fw-semibold">{{ $viewingItem->rack_number ?: '-' }} / {{ $viewingItem->rack_row ?: '-' }}</span>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted small mb-1 d-block">Warranty</label>
                                <span class="fw-semibold">{{ $viewingItem->has_warranty ? $viewingItem->warranty_months . ' Months' : 'No Warranty' }}</span>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted small mb-1 d-block">Total Stock</label>
                                @php 
                                    $totalStock = $viewingItem->batches->where('is_active', true)->sum('quantity'); 
                                @endphp
                                <span class="fw-bold fs-5 text-success">{{ number_format($totalStock, 2) }} <small class="text-muted fs-6">{{ $viewingItem->base_unit }}</small></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Stock Batches</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Batch No</th>
                                    <th>Barcode</th>
                                    <th>Exp Date</th>
                                    <th>Quantity</th>
                                    <th>Cost Price</th>
                                    <th>Selling Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($viewingItem->batches as $batch)
                                    <tr>
                                        <td>{{ $batch->batch_no }}</td>
                                        <td>{{ $batch->barcode }}</td>
                                        <td>{!! $batch->expiry_date ? $batch->expiry_date->format('Y-m-d') : '<span class="text-muted">-</span>' !!}</td>
                                        <td>{{ $batch->quantity }}</td>
                                        <td>{{ number_format($batch->cost_price, 2) }}</td>
                                        <td>{{ number_format($batch->selling_price, 2) }}</td>
                                        <td>
                                            @if($batch->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                            @if($batch->is_printed)
                                                <span class="badge bg-info text-dark">Printed</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button wire:click="openPrintModal({{ $batch->id }})" class="btn btn-sm btn-outline-dark" title="Print Barcode">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-muted">No batches available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Print Single Batch Modal -->
<div class="modal fade {{ $showPrintModal ? 'show d-block' : '' }}" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold mb-0">Print Barcode</h5>
                <button type="button" wire:click="closeModals" class="btn-close"></button>
            </div>
            <div class="modal-body pb-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Number of Copies</label>
                    <input type="number" wire:model="printCopies" class="form-control rounded-3" min="1">
                    @error('printCopies') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <button wire:click="printSingleBatch" class="btn btn-primary-custom w-100 fw-bold rounded-3 py-2">
                    Print Now
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Print Modal -->
<div class="modal fade {{ $showBulkPrintModal ? 'show d-block' : '' }}" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold mb-0">Print Pending Barcodes</h5>
                <button type="button" wire:click="closeModals" class="btn-close"></button>
            </div>
            <div class="modal-body pb-4">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-sm align-middle text-center">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Print?</th>
                                <th>Item</th>
                                <th>Barcode</th>
                                <th>Copies (Quantity)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingBatchesToPrint as $index => $batch)
                                <tr>
                                    <td>
                                        <input type="checkbox" wire:model="pendingBatchesToPrint.{{ $index }}.selected" class="form-check-input">
                                    </td>
                                    <td class="text-start">{{ $batch['name'] }}</td>
                                    <td>{{ $batch['barcode'] }}</td>
                                    <td style="width: 120px;">
                                        <input type="number" wire:model="pendingBatchesToPrint.{{ $index }}.copies" class="form-control form-control-sm text-center" min="1">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted py-4">No pending unprinted barcodes.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if(count($pendingBatchesToPrint) > 0)
                <div class="mt-4">
                    <button wire:click="printBulkBatches" class="btn btn-primary-custom w-100 fw-bold rounded-3 py-2">
                        Print Selected Barcodes
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Convert To Loose Modal -->
<div class="modal fade {{ $showConvertToLooseModal ? 'show d-block' : '' }}" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="fw-bold mb-0">Convert to Loose Item</h5>
                <button type="button" wire:click="closeModals" class="btn-close"></button>
            </div>
            <div class="modal-body pb-4">
                <div class="mb-3">
                    <strong>Parent Item: {{ $convertParentItemName }}</strong>
                </div>
                
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Quantity to Open (in parent base units)</label>
                        <input type="number" wire:model="convertQuantityToOpen" class="form-control rounded-3" step="0.01" min="1" placeholder="e.g. 1">
                        <small class="text-muted d-block mt-1" style="font-size:0.75rem;">How many boxes/rolls are you opening?</small>
                        @error('convertQuantityToOpen') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Pieces Per Unit</label>
                        <input type="number" wire:model="convertConversionFactor" class="form-control rounded-3" step="0.01" min="1" placeholder="e.g. 100">
                        <small class="text-muted d-block mt-1" style="font-size:0.75rem;">How many loose items inside 1 parent unit?</small>
                        @error('convertConversionFactor') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">New Selling Price (Loose)</label>
                        <input type="number" wire:model="convertLoosePrice" class="form-control rounded-3" step="0.01">
                        @error('convertLoosePrice') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button wire:click="convertToLoose" class="btn btn-primary-custom w-100 fw-bold rounded-3 py-2">Confirm Conversion</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Print Area -->
<div id="print-area" class="d-none"></div>

<!-- Include JsBarcode -->
@assets
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
@endassets

@script
<script>
    Livewire.on('print-barcodes', (event) => {
        let data = event.data;
        if(!data || data.length === 0) return;
        
        let printArea = document.getElementById('print-area');
        printArea.innerHTML = ''; // Clear previous
        
        let html = '<div class="a4-grid">';
        // Generate HTML for each label
        data.forEach((item, index) => {
            for (let i = 0; i < item.copies; i++) {
                let uniqueId = 'barcode-' + index + '-' + i;
                html += `
                    <div class="barcode-label">
                        <div class="item-name">${item.name}</div>
                        <svg id="${uniqueId}"></svg>
                        <div class="item-price">Rs. ${Number(item.price).toFixed(2)}</div>
                    </div>
                `;
            }
        });
        html += '</div>';
        
        printArea.innerHTML = html;
        
        // Now generate barcodes on the SVGs
        data.forEach((item, index) => {
            for (let i = 0; i < item.copies; i++) {
                let uniqueId = 'barcode-' + index + '-' + i;
                JsBarcode("#" + uniqueId, item.barcode, {
                    format: "CODE128",
                    width: 1, // Thinner bars to prevent overlapping when scaled
                    height: 35,
                    displayValue: true,
                    fontSize: 10,
                    margin: 0
                });
            }
        });
        
        // Open print dialog
        // We need to print just the #print-area. The easiest way without messing up the main window CSS is to copy it to an iframe or just use CSS print media queries.
        
        // Wait for SVG rendering
        setTimeout(() => {
            let printWindow = window.open('', '_blank', 'width=600,height=600');
            printWindow.document.write('<html><head><title>Print Barcodes</title>');
            printWindow.document.write('<style>');
            printWindow.document.write(`
                @page { margin: 10mm; size: A4 portrait; }
                body { margin: 0; padding: 0; font-family: Arial, sans-serif; }
                .a4-grid {
                    display: flex;
                    flex-wrap: wrap;
                    width: 100%;
                }
                .barcode-label {
                    width: calc(25% - 5mm); /* 4 columns */
                    margin-right: 5mm;
                    margin-bottom: 10mm;
                    height: 100px; /* Fixed height per sticker */
                    display: flex; 
                    flex-direction: column; 
                    justify-content: center; 
                    align-items: center; 
                    box-sizing: border-box; 
                    page-break-inside: avoid;
                    border: 1px dashed #ccc; /* Faint cutting guide border */
                    padding: 2mm;
                }
                .barcode-label:nth-child(4n) {
                    margin-right: 0; /* Remove right margin for last item in row */
                }
                .item-name { 
                    font-size: 12px; font-weight: bold; text-align: center; width: 100%; 
                    overflow: hidden; white-space: nowrap; text-overflow: ellipsis; margin-bottom: 2px;
                }
                .item-price {
                    font-size: 14px; font-weight: bold; margin-top: 2px; text-align: center;
                }
                svg { max-width: 100%; height: auto; }
            `);
            printWindow.document.write('</style></head><body>');
            printWindow.document.write(printArea.innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            
            printWindow.focus();
            // Delay for rendering
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        }, 100);
    });
</script>
@endscript
