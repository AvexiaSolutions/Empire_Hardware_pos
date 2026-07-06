<div>
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="text-uppercase tracking-wider fw-bold text-body-secondary" style="font-size: 0.85rem; letter-spacing: 0.05em;">{{ __('Expense Management') }}</h3>
            <h2 class="fs-3 fw-bold text-body mt-1">{{ __('Manage Expenses') }}</h2>
        </div>
        <button class="btn btn-primary-custom fw-bold px-4 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#expenseModal" wire:click="resetForm">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
            {{ __('Add New Expense') }}
        </button>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 rounded-4 shadow-sm bg-body">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="position-relative w-25">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control rounded-pill ps-4 bg-body-tertiary border-0" placeholder="{{ __('Search expenses...') }}">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-sm table-borderless align-middle fw-semibold text-body mb-0">
                    <thead class="border-bottom">
                        <tr>
                            <th class="pb-3 pt-0">{{ __('Date') }}</th>
                            <th class="pb-3 pt-0">{{ __('Description') }}</th>
                            <th class="pb-3 pt-0 text-end">{{ __('Amount (Rs)') }}</th>
                            <th class="pb-3 pt-0 text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td class="py-3 border-bottom">{{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}</td>
                                <td class="py-3 border-bottom">{{ $expense->description }}</td>
                                <td class="py-3 border-bottom text-end">{{ number_format($expense->amount, 2) }}</td>
                                <td class="py-3 border-bottom text-end">
                                    <button class="btn btn-sm btn-light text-primary me-2 rounded-3" data-bs-toggle="modal" data-bs-target="#expenseModal" wire:click="edit({{ $expense->id }})">{{ __('Edit') }}</button>
                                    <button class="btn btn-sm btn-light text-danger rounded-3" wire:click="delete({{ $expense->id }})" wire:confirm="{{ __('Are you sure you want to delete this expense?') }}">{{ __('Delete') }}</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted border-bottom">{{ __('No expenses found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>

    <!-- Expense Modal -->
    <div class="modal fade" id="expenseModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow">
                <div class="modal-header border-bottom-0 pb-0 px-4 pt-4">
                    <h5 class="modal-title fw-bold fs-4">{{ $isEditMode ? __('Edit Expense') : __('Add New Expense') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 py-4">
                    <form wire:submit="save">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">{{ __('Date') }}</label>
                            <input type="date" wire:model="date" class="form-control rounded-3 py-2 bg-body-tertiary border-0">
                            @error('date') <span class="text-danger small mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">{{ __('Description') }}</label>
                            <input type="text" wire:model="description" class="form-control rounded-3 py-2 bg-body-tertiary border-0" placeholder="e.g. Electricity Bill">
                            @error('description') <span class="text-danger small mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary small">{{ __('Amount (Rs)') }}</label>
                            <input type="number" step="0.01" wire:model="amount" class="form-control rounded-3 py-2 bg-body-tertiary border-0" placeholder="0.00">
                            @error('amount') <span class="text-danger small mt-1">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary-custom w-100 fw-bold py-3 rounded-3 shadow-sm">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            {{ $isEditMode ? __('Update Expense') : __('Save Expense') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('close-modal', () => {
                var expenseModal = bootstrap.Modal.getInstance(document.getElementById('expenseModal'));
                if(expenseModal) {
                    expenseModal.hide();
                }
            });
        });
    </script>
</div>
