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
                        <input type="number" wire:model="itemDiscount" class="form-control rounded-3" step="0.01" placeholder="Amount or %">
                    </div>

                    <div class="col-md-4">
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

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Quantity (in Base Unit)</label>
                        <input type="number" wire:model="itemQuantity" class="form-control rounded-3" step="0.01">
                        @error('itemQuantity') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Has Bulk Unit?</label>
                        <select wire:model.live="itemHasBulkUnit" class="form-select rounded-3">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>

                    @if($itemHasBulkUnit)
                    <div class="col-md-12 p-3 bg-body-tertiary rounded-3 border">
                        <h6 class="fw-bold text-primary-custom mb-3">Bulk Pricing Setup</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Bulk Unit Name</label>
                                <input type="text" wire:model="itemBulkUnit" class="form-control rounded-3" placeholder="e.g. Roll">
                                @error('itemBulkUnit') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Conversion Factor</label>
                                <input type="number" wire:model="itemBulkConversionFactor" class="form-control rounded-3" step="0.01" placeholder="e.g. 100">
                                <small class="text-muted d-block mt-1" style="font-size:0.75rem;">1 Bulk = X Base Units</small>
                                @error('itemBulkConversionFactor') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Bulk Cost Price</label>
                                <input type="number" wire:model="itemBulkCostPrice" class="form-control rounded-3" step="0.01">
                                @error('itemBulkCostPrice') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Bulk Selling Price</label>
                                <input type="number" wire:model="itemBulkSellingPrice" class="form-control rounded-3" step="0.01">
                                @error('itemBulkSellingPrice') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Warranty?</label>
                        <select wire:model.live="itemHasWarranty" class="form-select rounded-3">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>

                    @if($itemHasWarranty)
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Warranty Period (Months)</label>
                        <input type="number" wire:model="itemWarrantyMonths" class="form-control rounded-3">
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

                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Stock Quantity (in {{ $stockItem->primary_unit }})</label>
                        <input type="number" wire:model="stockQuantity" class="form-control rounded-3" step="0.01">
                        @error('stockQuantity') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Cost Price</label>
                            <input type="number" wire:model="stockCostPrice" class="form-control rounded-3" step="0.01">
                            @error('stockCostPrice') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Selling Price</label>
                            <input type="number" wire:model="stockSellingPrice" class="form-control rounded-3" step="0.01">
                            @error('stockSellingPrice') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>

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

                    <div class="col-md-4">
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

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Has Bulk Unit?</label>
                        <select wire:model.live="itemHasBulkUnit" class="form-select rounded-3">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>

                    @if($itemHasBulkUnit)
                    <div class="col-md-12 p-3 bg-body-tertiary rounded-3 border">
                        <h6 class="fw-bold text-primary-custom mb-3">Bulk Setup</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Bulk Unit Name</label>
                                <input type="text" wire:model="itemBulkUnit" class="form-control rounded-3" placeholder="e.g. Roll">
                                @error('itemBulkUnit') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Conversion Factor</label>
                                <input type="number" wire:model="itemBulkConversionFactor" class="form-control rounded-3" step="0.01" placeholder="e.g. 100">
                                <small class="text-muted d-block mt-1" style="font-size:0.75rem;">1 Bulk = X Base Units</small>
                                @error('itemBulkConversionFactor') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Warranty?</label>
                        <select wire:model.live="itemHasWarranty" class="form-select rounded-3">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>

                    @if($itemHasWarranty)
                    <div class="col-md-6">
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
                            @if($viewingItem->has_bulk_unit)
                            <div class="col-sm-6">
                                <label class="text-muted small mb-1 d-block">Bulk Unit</label>
                                <span class="fw-semibold">{{ $viewingItem->bulk_unit }} (1 = {{ $viewingItem->bulk_conversion_factor }} {{ $viewingItem->base_unit }})</span>
                            </div>
                            @endif
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
                                    <th>Quantity</th>
                                    <th>Cost Price</th>
                                    <th>Selling Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($viewingItem->batches as $batch)
                                    <tr>
                                        <td>{{ $batch->batch_no }}</td>
                                        <td>{{ $batch->barcode }}</td>
                                        <td>{{ $batch->quantity }}</td>
                                        <td>{{ number_format($batch->cost_price, 2) }}</td>
                                        <td>{{ number_format($batch->selling_price, 2) }}</td>
                                        <td>
                                            @if($batch->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-muted">No batches available</td>
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
