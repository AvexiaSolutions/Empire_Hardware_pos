<div>
    <!-- Top Search Bar -->
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="searchQuery" class="form-control form-control rounded-3 shadow-sm" placeholder="{{ __('Search by barcode, item name... (Super Search)') }}">
    </div>

    <!-- Action Buttons -->
    <div class="d-flex gap-3 flex-wrap mb-4">
        @if(auth()->check() && auth()->user()->hasPermission('item.add'))
        <button wire:click="openAddCategoryModal" class="btn btn-outline-primary-custom bg-body px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 small rounded-3" style="font-size: 0.9rem;">
            {{ __('Add Category') }} <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </button>
        <button wire:click="openAddSubCategoryModal" class="btn btn-outline-primary-custom bg-body px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 small rounded-3" style="font-size: 0.9rem;">
            {{ __('Add Sub Category') }} <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </button>
        <button wire:click="openAddItemModal" class="btn btn-primary-custom px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 small rounded-3" style="font-size: 0.9rem;">
            {{ __('Add New Item') }} <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </button>
        @endif
    </div>

    <!-- Middle Section: Categories and Sub Categories Split -->
    <div class="row g-4 mb-4">
        <!-- Categories Column -->
        <div class="col-md-6">
            <h5 class="fw-bold mb-3">{{ __('Categories') }}</h5>
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="list-group list-group-flush" style="max-height: 250px; overflow-y: auto;">
                    @forelse($categories as $category)
                        <div class="list-group-item py-3 px-4 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">{{ $category->name }}</span>
                            <button wire:click="deleteCategory({{ $category->id }})" class="btn btn-sm btn-outline-danger border-0">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    @empty
                        <div class="list-group-item py-3 px-4 text-muted text-center">{{ __('No categories found') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Sub Categories Column -->
        <div class="col-md-6">
            <h5 class="fw-bold mb-3">{{ __('Sub Categories') }}</h5>
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="list-group list-group-flush" style="max-height: 250px; overflow-y: auto;">
                    @forelse($subCategories as $subCat)
                        <div class="list-group-item py-3 px-4 d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-semibold d-block">{{ $subCat->name }}</span>
                                <small class="text-muted">{{ $subCat->category->name ?? __('No Parent') }}</small>
                            </div>
                            <button wire:click="deleteSubCategory({{ $subCat->id }})" class="btn btn-sm btn-outline-danger border-0">
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    @empty
                        <div class="list-group-item py-3 px-4 text-muted text-center">{{ __('No sub-categories found') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Items List Section -->
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h5 class="fw-bold text-body mb-0 fs-5">{{ __('Items List') }}</h5>
        <div class="d-flex gap-2">
            <!-- Basic filters could go here later -->
        </div>
    </div>

    <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                <thead>
                    <tr>
                        <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">{{ __('Item Details') }}</th>
                        <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">{{ __('Category / Sub') }}</th>
                        <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">{{ __('Total Stock') }}</th>
                        <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary text-end">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-body">
                    @forelse($items as $i => $item)
                        <tr class="{{ $i%2 != 0 ? 'bg-body-secondary' : '' }}">
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center gap-3" wire:click="viewItemDetails({{ $item->id }})" style="cursor: pointer;" title="{{ __('Click to view details') }}">
                                    @if($item->image)
                                        <img src="{{ Storage::url($item->image) }}" class="rounded-3" style="width: 48px; height: 48px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center text-secondary" style="width: 48px; height: 48px;">
                                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="d-block text-primary-custom fw-bold">{{ $item->code }}</span>
                                        <span class="fs-6 text-body">{{ $item->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="d-block">{{ $item->category->name ?? '-' }}</span>
                                <small class="text-muted d-block">{{ $item->subCategory->name ?? '-' }}</small>
                                @if($item->rack_number || $item->rack_row)
                                    <div class="mt-1 d-inline-block bg-body-tertiary rounded px-2 py-1 border border-secondary border-opacity-25" style="font-size: 0.75rem;">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-secondary me-1"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        <span class="text-secondary fw-bold">{{ __('Rack:') }}</span> {{ $item->rack_number ?: '-' }} | <span class="text-secondary fw-bold">{{ __('Row:') }}</span> {{ $item->rack_row ?: '-' }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @php 
                                    $totalStock = $item->batches->where('is_active', true)->sum('quantity'); 
                                @endphp
                                <span class="fs-5">{{ number_format($totalStock, 2) }}</span> <small class="text-muted">{{ $item->primary_unit }}</small>
                            </td>
                            <td class="py-3 px-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button wire:click="openAddStockModal({{ $item->id }})" class="btn btn-sm btn-primary-custom d-inline-flex align-items-center gap-1">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> {{ __('Stock') }}
                                    </button>
                                    @if(auth()->check() && auth()->user()->hasPermission('item.edit'))
                                    <button wire:click="editItem({{ $item->id }})" class="btn btn-sm btn-light border">
                                        {{ __('Edit') }}
                                    </button>
                                    @endif
                                    @if(auth()->check() && auth()->user()->hasPermission('item.delete'))
                                    <button wire:click="removeItem({{ $item->id }})" class="btn btn-sm btn-outline-danger">
                                        {{ __('Remove') }}
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-5 text-center text-muted">
                                {{ __('No items found. Click "Add New Item" to create one.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination (if added later) -->
    </div>

    <!-- Modals Placeholder -->
    @include('livewire.item.modals')
</div>
