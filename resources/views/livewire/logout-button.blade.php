<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<button wire:click="logout" class="nav-link text-danger w-100 fs-6 fw-semibold d-flex align-items-center p-2 rounded border-0 bg-transparent text-start" style="transition: all 0.2s;">
    <div style="min-width: 32px; display: flex; justify-content: center;">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
    </div>
    <span class="nav-text">{{ __('Log Out') }}</span>
</button>
