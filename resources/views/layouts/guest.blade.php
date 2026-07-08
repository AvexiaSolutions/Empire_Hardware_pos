<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ \App\Models\Setting::get('shop_name', 'Avexia POS') }}</title>

        <!-- Favicon -->
        @php $favicon = \App\Models\Setting::get('shop_logo'); @endphp
        <link rel="icon" href="{{ $favicon ? asset($favicon) : asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ $favicon ? asset($favicon) : asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        
        <style>
            body {
                background-image: url('{{ asset('images/login-bg.png') }}');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                position: relative;
                font-family: 'Inter', sans-serif;
            }
            .bg-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(8px);
                z-index: 0;
            }
            .login-card {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                border-radius: 1.5rem;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                border: 1px solid rgba(255, 255, 255, 0.5);
                z-index: 1;
                max-width: 420px;
                width: 100%;
                padding: 3rem 2.5rem;
                transition: all 0.3s ease;
            }
            .login-card .btn-primary-custom {
                transition: all 0.3s ease;
            }
            .login-card .btn-primary-custom:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 15px rgba(0, 124, 239, 0.3) !important;
            }
            
            /* Dark Mode Adjustments */
            @media (prefers-color-scheme: dark) {
                .login-card {
                    background: rgba(24, 24, 27, 0.95); /* Tailwind zinc-900 */
                    border: 1px solid rgba(255, 255, 255, 0.08);
                    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
                }
                .login-card .text-muted, .login-card .text-body-secondary {
                    color: #a1a1aa !important; /* zinc-400 */
                }
                .login-card .form-label {
                    color: #e4e4e7; /* zinc-200 */
                }
                .login-card .form-control {
                    background-color: rgba(0, 0, 0, 0.25);
                    border-color: rgba(255, 255, 255, 0.12);
                    color: #f4f4f5; /* zinc-100 */
                }
                .login-card .form-control:focus {
                    background-color: rgba(0, 0, 0, 0.4);
                    border-color: #007CEF;
                    color: #f4f4f5;
                    box-shadow: 0 0 0 0.25rem rgba(0, 124, 239, 0.25);
                }
                .login-card .form-control::placeholder {
                    color: #71717a; /* zinc-500 */
                }
                .login-card .form-check-label {
                    color: #d4d4d8 !important; /* zinc-300 */
                }
                .login-card .form-check-input {
                    background-color: rgba(0, 0, 0, 0.25);
                    border-color: rgba(255, 255, 255, 0.2);
                }
                .login-card .form-check-input:checked {
                    background-color: #007CEF;
                    border-color: #007CEF;
                }
                .login-card .alert-success {
                    background-color: rgba(25, 135, 84, 0.15);
                    border-color: rgba(25, 135, 84, 0.2);
                    color: #75b798;
                }
            }
        </style>
        <x-theme-script />
    </head>
    <body class="text-body vh-100 overflow-hidden d-flex align-items-center justify-content-center">
        <div class="bg-overlay"></div>
        
        <div class="login-card position-relative">
            <div class="text-center mb-4">
                @php $logo = \App\Models\Setting::get('shop_logo'); @endphp
                @if($logo)
                    <img src="{{ $logo }}" alt="Logo" style="height: 64px;" class="mb-3">
                @endif
                <h2 class="fw-bold text-primary-custom fs-3 mb-1">{{ \App\Models\Setting::get('shop_name', 'Avexia POS') }}</h2>
                <p class="text-muted small">{{ __('Please sign in to continue') }}</p>
            </div>

            {{ $slot }}
        </div>
    </body>
</html>
