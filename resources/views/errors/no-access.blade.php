<x-pos-layout>
    <div class="d-flex flex-column align-items-center justify-content-center h-100 py-5">
        <div class="text-center p-5 rounded-4 shadow-sm bg-body" style="max-width: 600px;">
            <svg class="text-danger mb-4 mx-auto" width="80" height="80" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <h2 class="fs-3 fw-bold text-body mb-3">{{ __('Access Denied') }}</h2>
            <p class="fs-5 text-muted mb-0">
                {{ __('You haven\'t access. Please contact admin.') }}
            </p>
        </div>
    </div>
</x-pos-layout>
