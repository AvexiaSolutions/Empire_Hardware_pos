<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $defaultRoute = auth()->user()->role === 'admin' ? route('dashboard', absolute: false) : route('pos.index', absolute: false);
        $this->redirect($defaultRoute, navigate: true);
    }
}; ?>

<div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success fw-bold rounded-3 shadow-sm border-0 mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login">
        <!-- Username or Email Address -->
        <div class="mb-4">
            <label for="username_or_email" class="form-label fw-bold">{{ __('Email or Username') }}</label>
            <input wire:model="form.username_or_email" id="username_or_email" type="text" class="form-control rounded-3 py-2 form-control fs-6 @error('form.username_or_email') is-invalid @enderror" name="username_or_email" required autofocus autocomplete="username" placeholder="name@example.com or username">
            @error('form.username_or_email')
                <div class="invalid-feedback fw-bold">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="password" class="form-label fw-bold mb-0">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-decoration-none small fw-semibold text-primary-custom" wire:navigate>{{ __('Forgot password?') }}</a>
                @endif
            </div>
            <input wire:model="form.password" id="password" type="password" class="form-control rounded-3 py-2 form-control fs-6 @error('form.password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
            @error('form.password')
                <div class="invalid-feedback fw-bold">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-4 form-check">
            <input wire:model="form.remember" id="remember" type="checkbox" class="form-check-input border-secondary" name="remember">
            <label for="remember" class="form-check-label fw-medium text-body-secondary">{{ __('Remember me') }}</label>
        </div>

        <div class="d-grid mt-5">
            <button type="submit" class="btn btn-primary-custom btn-lg fw-bold rounded-3 py-2 shadow-sm d-flex justify-content-center align-items-center gap-2">
                {{ __('Sign In') }}
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>
    </form>
</div>
