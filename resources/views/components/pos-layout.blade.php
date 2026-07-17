<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#0D8ABC">
        <link rel="apple-touch-icon" href="https://ui-avatars.com/api/?name=Avexia&background=0D8ABC&color=fff&size=192">

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
            /* Page fade-in animation */
            body {
                animation: fadeIn 0.4s ease-in-out;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            /* Sidebar Hover Expansion */
            .sidebar-hover-expand {
                width: 85px; /* Icon width */
                transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                z-index: 1050;
                overflow-x: hidden;
                overflow-y: auto;
            }
            
            .sidebar-hover-expand:hover, .sidebar-hover-expand.expanded {
                width: 280px;
                box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
            }

            .sidebar-hover-expand .nav-link {
                white-space: nowrap;
                overflow: hidden;
            }

            .sidebar-hover-expand .nav-text {
                opacity: 0;
                transition: opacity 0.3s ease;
                margin-left: 10px;
            }

            .sidebar-hover-expand:hover .nav-text, .sidebar-hover-expand.expanded .nav-text {
                opacity: 1;
            }
            
            .sidebar-hover-expand .brand-text {
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .sidebar-hover-expand:hover .brand-text, .sidebar-hover-expand.expanded .brand-text {
                opacity: 1;
            }

            /* Main Content Shift */
            .main-content-shifted {
                margin-left: 85px;
                transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            @media (max-width: 991.98px) {
                .sidebar-hover-expand {
                    transform: translateX(-100%);
                    width: 280px; /* Always expanded on mobile when shown */
                }
                .sidebar-hover-expand.show {
                    transform: translateX(0);
                }
                .sidebar-hover-expand .nav-text, .sidebar-hover-expand .brand-text {
                    opacity: 1;
                }
                .main-content-shifted {
                    margin-left: 0 !important;
                }
            }

            /* Auto-adjust for smaller monitor heights (100% zoom fit) */
            @media (max-height: 850px) {
                .sidebar-hover-expand .nav-pills { gap: 0.1rem !important; }
                .sidebar-hover-expand .nav-link { padding-top: 0.25rem !important; padding-bottom: 0.25rem !important; }
                .sidebar-hover-expand { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }
            }
        </style>
        <x-theme-script />
    </head>
    <body class="d-flex flex-row vh-100 overflow-hidden text-body bg-body">
        
        <!-- Sidebar -->
        <aside class="sidebar-hover-expand bg-body shadow-sm border-end d-flex flex-column flex-shrink-0 p-3 offcanvas-lg" tabindex="-1" id="sidebarMenu">
            <!-- Sidebar Header -->
            <div class="d-flex align-items-center mb-2 pb-2 border-bottom flex-shrink-0 overflow-hidden">
                @php $logo = \App\Models\Setting::get('shop_logo'); @endphp
                @if($logo)
                    <img src="{{ $logo }}" alt="Logo" class="img-fluid" style="width: 36px; height: 36px; object-fit: contain;">
                @else
                    <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center fw-bold" style="min-width: 36px; height: 36px; font-size: 1.1rem;">
                        {{ substr(\App\Models\Setting::get('shop_name', 'Avexia POS'), 0, 1) }}
                    </div>
                @endif
                <h1 class="fs-5 fw-bold text-primary-custom mb-0 ms-3 brand-text">{{ \App\Models\Setting::get('shop_name', 'Avexia POS') }}</h1>
                <button type="button" class="btn-close d-lg-none ms-auto" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
            </div>

            <!-- Scrollable Nav Items -->
            <div class="flex-grow-1 overflow-y-auto overflow-x-hidden my-2" style="scrollbar-width: none; -ms-overflow-style: none;">
                <style>.sidebar-hover-expand .flex-grow-1::-webkit-scrollbar { display: none; }</style>
                <ul class="nav nav-pills flex-column gap-1">
                    @if(auth()->check() && auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" wire:navigate class="nav-link fs-6 fw-semibold d-flex align-items-center p-2 rounded {{ request()->routeIs('dashboard') ? 'bg-primary-custom text-white' : 'text-body hover-bg-body-tertiary' }}">
                        <div style="min-width: 32px; display: flex; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        <span class="nav-text">{{ __('Dashboard') }}</span>
                    </a>
                </li>
                @endif
                
                @if(auth()->check() && auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a href="{{ route('ai.assistant') }}" wire:navigate class="nav-link fs-6 fw-semibold d-flex align-items-center p-2 rounded {{ request()->routeIs('ai.assistant') ? 'bg-primary-custom text-white' : 'text-body hover-bg-body-tertiary' }}">
                        <div style="min-width: 32px; display: flex; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <span class="nav-text">{{ __('AI Assistant') }}</span>
                    </a>
                </li>
                @endif
                
                @if(auth()->check() && auth()->user()->hasPermission('pos.access'))
                <li class="nav-item">
                    <a href="{{ route('pos.index') }}" wire:navigate class="nav-link fs-6 fw-semibold d-flex align-items-center p-2 rounded {{ request()->routeIs('pos.*') ? 'bg-primary-custom text-white' : 'text-body hover-bg-body-tertiary' }}">
                        <div style="min-width: 32px; display: flex; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span class="nav-text">{{ __('POS') }}</span>
                    </a>
                </li>
                @endif
                
                @if(auth()->check() && auth()->user()->hasPermission('invoice.access'))
                <li class="nav-item">
                    <a href="{{ route('invoice.index') }}" wire:navigate class="nav-link fs-6 fw-semibold d-flex align-items-center p-2 rounded {{ request()->routeIs('invoice.*') ? 'bg-primary-custom text-white' : 'text-body hover-bg-body-tertiary' }}">
                        <div style="min-width: 32px; display: flex; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <span class="nav-text">{{ __('Invoice') }}</span>
                    </a>
                </li>
                @endif
                
                @if(auth()->check() && auth()->user()->hasPermission('returns.access'))
                <li class="nav-item">
                    <a href="{{ route('warranty-returns.index') }}" wire:navigate class="nav-link fs-6 fw-semibold d-flex align-items-center p-2 rounded {{ request()->routeIs('warranty-returns.*') ? 'bg-primary-custom text-white' : 'text-body hover-bg-body-tertiary' }}">
                        <div style="min-width: 32px; display: flex; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path></svg>
                        </div>
                        <span class="nav-text">{{ __('Warranty & Returns') }}</span>
                    </a>
                </li>
                @endif
                
                @if(auth()->check() && auth()->user()->hasPermission('item.access'))
                <li class="nav-item">
                    <a href="{{ route('item.index') }}" wire:navigate class="nav-link fs-6 fw-semibold d-flex align-items-center p-2 rounded {{ request()->routeIs('item.*') ? 'bg-primary-custom text-white' : 'text-body hover-bg-body-tertiary' }}">
                        <div style="min-width: 32px; display: flex; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <span class="nav-text">{{ __('Item') }}</span>
                    </a>
                </li>
                @endif

                @if(auth()->check() && auth()->user()->hasPermission('supplier.access'))
                <li class="nav-item">
                    <a href="{{ route('supplier.index') }}" wire:navigate class="nav-link fs-6 fw-semibold d-flex align-items-center p-2 rounded {{ request()->routeIs('supplier.*') ? 'bg-primary-custom text-white' : 'text-body hover-bg-body-tertiary' }}">
                        <div style="min-width: 32px; display: flex; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span class="nav-text">{{ __('Supplier') }}</span>
                    </a>
                </li>
                @endif
                
                @if(auth()->check() && auth()->user()->hasPermission('account.access'))
                <li class="nav-item">
                    <a href="{{ route('account') }}" wire:navigate class="nav-link fs-6 fw-semibold d-flex align-items-center p-2 rounded {{ request()->routeIs('account') ? 'bg-primary-custom text-white' : 'text-body hover-bg-body-tertiary' }}">
                        <div style="min-width: 32px; display: flex; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="nav-text">{{ __('Account') }}</span>
                    </a>
                </li>
                @endif
                
                @if(auth()->check() && auth()->user()->hasPermission('expenses.access'))
                <li class="nav-item">
                    <a href="{{ route('expenses.index') }}" wire:navigate class="nav-link fs-6 fw-semibold d-flex align-items-center p-2 rounded {{ request()->routeIs('expenses.*') ? 'bg-primary-custom text-white' : 'text-body hover-bg-body-tertiary' }}">
                        <div style="min-width: 32px; display: flex; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="nav-text">{{ __('Expenses') }}</span>
                    </a>
                </li>
                @endif
                
                @if(auth()->check() && auth()->user()->hasPermission('employer.access'))
                <li class="nav-item">
                    <a href="{{ route('employer.index') }}" wire:navigate class="nav-link fs-6 fw-semibold d-flex align-items-center p-2 rounded {{ request()->routeIs('employer.*') ? 'bg-primary-custom text-white' : 'text-body hover-bg-body-tertiary' }}">
                        <div style="min-width: 32px; display: flex; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <span class="nav-text">{{ __('Employer') }}</span>
                    </a>
                </li>
                @endif
                
                @if(auth()->check() && auth()->user()->hasPermission('credit-cheque.access'))
                <li class="nav-item">
                    <a href="{{ route('credit-cheque.index') }}" wire:navigate class="nav-link fs-6 fw-semibold d-flex align-items-center p-2 rounded {{ request()->routeIs('credit-cheque.*') ? 'bg-primary-custom text-white' : 'text-body hover-bg-body-tertiary' }}">
                        <div style="min-width: 32px; display: flex; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="nav-text">{{ __('Credit & Cheque') }}</span>
                    </a>
                </li>
                @endif
                
                @if(auth()->check() && auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a href="{{ route('activity-logs.index') }}" wire:navigate class="nav-link fs-6 fw-semibold d-flex align-items-center p-2 rounded {{ request()->routeIs('activity-logs.*') ? 'bg-primary-custom text-white' : 'text-body hover-bg-body-tertiary' }}">
                        <div style="min-width: 32px; display: flex; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <span class="nav-text">{{ __('Activity Logs') }}</span>
                    </a>
                </li>
                @endif
                
                @if(auth()->check() && auth()->user()->hasPermission('settings.access'))
                <li class="nav-item">
                    <a href="{{ route('settings.index') }}" wire:navigate class="nav-link fs-6 fw-semibold d-flex align-items-center p-2 rounded {{ request()->routeIs('settings.*') ? 'bg-primary-custom text-white' : 'text-body hover-bg-body-tertiary' }}">
                        <div style="min-width: 32px; display: flex; justify-content: center;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <span class="nav-text">{{ __('Settings') }}</span>
                    </a>
                </li>
                @endif
                </ul>
            </div>

            <!-- Sidebar Footer -->
            <hr class="my-2 flex-shrink-0">
            
            <div class="d-flex flex-column gap-3 mt-1 mb-1 overflow-hidden flex-shrink-0">
                <livewire:logout-button />
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content-shifted flex-grow-1 h-100 overflow-auto p-3 p-md-4 bg-body-tertiary w-100">
            <!-- Header -->
            <header class="d-flex justify-content-between align-items-start mb-4">
                <div class="d-flex align-items-start gap-2 gap-md-3">
                    <button class="btn btn-outline-secondary d-lg-none mt-1 me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div>
                        <h2 class="fs-5 fs-md-4 text-body mb-1">{{ __('Hello') }}, <span class="fw-bolder">{{ Auth::user()->name ?? __('User') }}</span></h2>
                        <p class="fs-6 fs-md-5 fw-bold mt-1 mt-md-2 mb-0">
                            @php
                                $routeName = request()->route()->getName();
                                $pageName = __('Dashboard');
                                if (str_contains($routeName, 'pos')) $pageName = __('Point of Sale');
                                elseif (str_contains($routeName, 'invoice')) $pageName = __('Invoices');
                                elseif (str_contains($routeName, 'item')) $pageName = __('Item Management');
                                elseif (str_contains($routeName, 'warranty-returns')) $pageName = __('Warranty & Returns');
                                elseif (str_contains($routeName, 'supplier')) $pageName = __('Suppliers');
                                elseif (str_contains($routeName, 'account')) $pageName = __('Accounting');
                                elseif (str_contains($routeName, 'employer')) $pageName = __('Employer');
                                elseif (str_contains($routeName, 'credit-cheque')) $pageName = __('Credit & Cheque');
                                elseif (str_contains($routeName, 'settings')) $pageName = __('Settings');
                                elseif (str_contains($routeName, 'ai.assistant')) $pageName = __('AI Assistant');
                            @endphp
                            {{ $pageName }}
                        </p>
                    </div>
                </div>
                    <div class="d-flex align-items-center gap-3">
                        <!-- Theme Toggle -->
                        <button id="theme-toggle" onclick="toggleTheme()" class="btn btn-outline-secondary rounded-circle d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                            <svg id="theme-icon-sun" class="d-none" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <svg id="theme-icon-moon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        </button>
                        
                        <!-- Language Switcher -->
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                  <path d="M4.545 6.714 4.11 8H3l1.862-5h1.284L8 8H6.833l-.435-1.286H4.545zm1.634-.736L5.5 3.956h-.049l-.679 2.022h1.408z"/>
                                  <path d="M0 2a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v3h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-3H2a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H2zm7.138 9.995c.193.301.402.583.63.846-.748.575-1.673 1.001-2.768 1.292.178.217.451.635.555.867 1.125-.359 2.08-.844 2.886-1.494.777.665 1.739 1.165 2.93 1.472.133-.254.414-.673.629-.89-1.125-.253-2.057-.694-2.82-1.284.681-.747 1.222-1.651 1.621-2.757H14V8h-3v1.047h.765c-.318.844-.74 1.546-1.272 2.13a6.066 6.066 0 0 1-.415-.492 1.988 1.988 0 0 1-.309-.441l-.825.492a5.531 5.531 0 0 0 .546.852c-.443.346-.902.639-1.372.875z"/>
                                </svg>
                                {{ session()->get('applocale') == 'si' ? 'සිංහල' : (session()->get('applocale') == 'ta' ? 'தமிழ்' : 'English') }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="languageDropdown">
                                <li><a class="dropdown-item {{ session()->get('applocale') == 'en' || !session()->has('applocale') ? 'active bg-primary-custom text-white' : '' }}" href="{{ route('lang.switch', 'en') }}">English</a></li>
                                <li><a class="dropdown-item {{ session()->get('applocale') == 'si' ? 'active bg-primary-custom text-white' : '' }}" href="{{ route('lang.switch', 'si') }}">සිංහල</a></li>
                                <li><a class="dropdown-item {{ session()->get('applocale') == 'ta' ? 'active bg-primary-custom text-white' : '' }}" href="{{ route('lang.switch', 'ta') }}">தமிழ்</a></li>
                            </ul>
                        </div>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=0D8ABC&color=fff" class="rounded-circle shadow-sm" width="40" height="40" alt="Profile">
                    <div class="d-none d-md-block">
                        <p class="fw-bold fs-6 fs-md-5 mb-0 lh-1">{{ \App\Models\Setting::get('shop_name', 'Shop Name') }}</p>
                        <p class="text-body-secondary fst-italic small mb-0 mt-1">#{{ Auth::user()->name ?? 'Username' }}</p>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            {{ $slot }}
        </main>
        
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/serviceworker.js').then(registration => {
                        console.log('ServiceWorker registration successful');
                    }).catch(err => {
                        console.log('ServiceWorker registration failed: ', err);
                    });
                });
            }

            // Ensure offcanvas works nicely with our custom hover classes
            document.addEventListener('DOMContentLoaded', function() {
                var sidebar = document.getElementById('sidebarMenu');
                sidebar.addEventListener('show.bs.offcanvas', function () {
                    sidebar.classList.add('offcanvas-start');
                });
                sidebar.addEventListener('hide.bs.offcanvas', function () {
                    sidebar.classList.remove('offcanvas-start');
                });
            });
        </script>
    </body>
</html>
