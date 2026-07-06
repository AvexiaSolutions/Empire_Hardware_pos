<div>
    <div class="pb-5">
        
        <!-- Supplier Management Section -->
        <div class="d-flex flex-column gap-3 mb-4">
            <h2 class="fs-4 fw-bold text-body">{{ __('Supplier Management') }}</h2>
            <div>
                <button wire:click="openAddModal" class="btn btn-primary-custom px-4 py-2 fw-bold d-inline-flex align-items-center gap-2">
                    {{ __('New Supplier') }} 
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-end mb-3">
            <h5 class="fw-bold text-body mb-0 fs-6">{{ __('Supplier Table') }}</h5>
            <div class="d-flex gap-2">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm rounded-3 shadow-sm border-0" placeholder="{{ __('Search suppliers...') }}" style="width: 250px;">
            </div>
        </div>

        <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                        <tr>
                            <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">{{ __('Supplier Name') }}</th>
                            <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">{{ __('Code') }}</th>
                            <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary">{{ __('Phone') }}</th>
                            <th class="py-3 px-4 fw-bold bg-primary-subtle text-primary"></th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-body">
                        @forelse($suppliers as $index => $supplier)
                        <tr>
                            <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-secondary' }}">{{ $supplier->name }}</td>
                            <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-secondary' }}">{{ $supplier->code }}</td>
                            <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-secondary' }}">{{ $supplier->phone ?? '-' }}</td>
                            <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-secondary' }} text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <button wire:click="openViewModal({{ $supplier->id }})" class="btn btn-primary-custom btn-sm fw-bold px-3 py-1 rounded-2">{{ __('See details') }}</button>
                                    <button wire:click="openEditModal({{ $supplier->id }})" class="btn btn-outline-primary-custom bg-body btn-sm fw-bold px-3 py-1 rounded-2">{{ __('Edit Details') }}</button>
                                    <button wire:click="deleteSupplier({{ $supplier->id }})" wire:confirm="{{ __('Are you sure you want to delete this supplier?') }}" class="btn btn-outline-danger btn-sm fw-bold px-3 py-1 rounded-2 border-0 bg-body shadow-sm">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-muted">{{ __('No suppliers found.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Add/Edit Supplier Modal -->
    <div class="modal fade {{ $showAddSupplierModal ? 'show d-block' : '' }}" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">{{ $supplierId ? __('Edit Supplier') : __('Add New Supplier') }}</h5>
                    <button type="button" wire:click="closeModals" class="btn-close"></button>
                </div>
                <div class="modal-body p-3">
                    <form wire:submit.prevent="saveSupplier">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-body small mb-1">{{ __('Supplier Name') }}</label>
                            <input type="text" wire:model="supplierName" class="form-control rounded-3" placeholder="e.g. ABC Suppliers">
                            @error('supplierName') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-body small mb-1">{{ __('Supplier Phone No') }}</label>
                                <input type="text" wire:model="supplierPhone" class="form-control rounded-3" placeholder="077*********7">
                                @error('supplierPhone') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-body small mb-1">{{ __('Supplier Email') }}</label>
                                <input type="email" wire:model="supplierEmail" class="form-control rounded-3" placeholder="email@example.com">
                                @error('supplierEmail') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4 pb-2">
                            <label class="form-label fw-bold text-body small mb-1">{{ __('Supplier Address') }}</label>
                            <input type="text" wire:model="supplierAddress" class="form-control rounded-3" placeholder="e.g. 23/k, Negombo, Gampaha">
                            @error('supplierAddress') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="row g-3 mb-4 pb-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-body small mb-1">{{ __('Ref. Name') }}</label>
                                <input type="text" wire:model="supplierRefName" class="form-control rounded-3" placeholder="e.g. John Doe">
                                @error('supplierRefName') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-body small mb-1">{{ __('Ref. Phone No') }}</label>
                                <input type="text" wire:model="supplierRefPhone" class="form-control rounded-3" placeholder="077*********7">
                                @error('supplierRefPhone') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-3">
                            <button type="button" wire:click="closeModals" class="btn btn-outline-primary-custom fw-bold py-2 flex-grow-1 rounded-3 bg-body">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary-custom fw-bold py-2 flex-grow-1 rounded-3 shadow-sm">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View Supplier Modal -->
    <div class="modal fade {{ $showViewSupplierModal ? 'show d-block' : '' }}" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">{{ __('Supplier Details') }}</h5>
                    <button type="button" wire:click="closeModals" class="btn-close"></button>
                </div>
                <div class="modal-body p-3">
                    @if($viewSupplier)
                    <div class="row g-4">
                        <div class="col-6">
                            <label class="form-label fw-bold text-body small mb-1">{{ __('Supplier Code') }}</label>
                            <div class="border rounded-3 p-2 bg-body-tertiary fw-semibold text-muted">{{ $viewSupplier->code }}</div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold text-body small mb-1">{{ __('Supplier Name') }}</label>
                            <div class="border rounded-3 p-2 bg-body-tertiary fw-semibold text-muted">{{ $viewSupplier->name }}</div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold text-body small mb-1">{{ __('Supplier Phone') }}</label>
                            <div class="border rounded-3 p-2 bg-body-tertiary fw-semibold text-muted">{{ $viewSupplier->phone ?: 'N/A' }}</div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold text-body small mb-1">{{ __('Supplier Email') }}</label>
                            <div class="border rounded-3 p-2 bg-body-tertiary fw-semibold text-muted">{{ $viewSupplier->email ?: 'N/A' }}</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold text-body small mb-1">{{ __('Supplier Address') }}</label>
                            <div class="border rounded-3 p-2 bg-body-tertiary fw-semibold text-muted">{{ $viewSupplier->address ?: 'N/A' }}</div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold text-body small mb-1">{{ __('Ref Name') }}</label>
                            <div class="border rounded-3 p-2 bg-body-tertiary fw-semibold text-muted">{{ $viewSupplier->ref_name ?: 'N/A' }}</div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold text-body small mb-1">{{ __('Ref Phone') }}</label>
                            <div class="border rounded-3 p-2 bg-body-tertiary fw-semibold text-muted">{{ $viewSupplier->ref_phone ?: 'N/A' }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
