<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard') - {{ \App\Models\Setting::getSiteName() }}</title>
    
    <!-- Favicon -->
    @php
        $faviconUrl = \App\Models\Setting::getFaviconUrl();
    @endphp
    @if($faviconUrl)
        <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
        <link rel="shortcut icon" href="{{ $faviconUrl }}">
    @endif
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                            950: '#082f49',
                        },
                        slate: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(-10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideIn: {
                            '0%': { opacity: '0', transform: 'translateX(-20px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        /* CSS Variables for theming */
        :root {
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-tertiary: #64748b;
            --border-color: #e2e8f0;
            --accent-color: #0284c7;
            --accent-hover: #0369a1;
            --success-bg: #f0fdf4;
            --success-text: #166534;
            --success-border: #bbf7d0;
            --error-bg: #fef2f2;
            --error-text: #991b1b;
            --error-border: #fecaca;
            --card-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --card-shadow-hover: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }
        
        .dark {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-tertiary: #94a3b8;
            --border-color: #334155;
            --accent-color: #38bdf8;
            --accent-hover: #7dd3fc;
            --success-bg: #064e3b;
            --success-text: #86efac;
            --success-border: #059669;
            --error-bg: #450a0a;
            --error-text: #fca5a5;
            --error-border: #dc2626;
            --card-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.3), 0 1px 2px -1px rgb(0 0 0 / 0.3);
            --card-shadow-hover: 0 20px 25px -5px rgb(0 0 0 / 0.4), 0 8px 10px -6px rgb(0 0 0 / 0.4);
        }
        
        body {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .app-card {
            background-color: var(--bg-primary);
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
            transition: all 0.2s ease;
        }
        
        .app-card:hover {
            box-shadow: var(--card-shadow-hover);
        }
        
        .app-input {
            background-color: var(--bg-primary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            transition: all 0.2s ease;
        }
        
        .app-input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
        }
        
        .dark .app-input:focus {
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1);
        }
        
        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--bg-tertiary);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--text-tertiary);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }
        
        /* Table styles */
        .app-table th {
            background-color: var(--bg-tertiary);
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
        
        .app-table td {
            border-bottom: 1px solid var(--border-color);
        }
        
        .app-table tr:hover td {
            background-color: var(--bg-tertiary);
        }
        
        /* Navigation link active state */
        .nav-link {
            position: relative;
            transition: all 0.2s ease;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%) scaleY(0);
            width: 3px;
            height: 24px;
            background-color: var(--accent-color);
            border-radius: 0 4px 4px 0;
            transition: transform 0.2s ease;
        }
        
        .nav-link.active::before,
        .nav-link:hover::before {
            transform: translateY(-50%) scaleY(1);
        }
        
        /* Button press effect */
        .btn-press:active {
            transform: scale(0.98);
        }
        
        /* Smooth page transitions */
        .page-content {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased min-h-screen">
    <div class="min-h-screen flex">
        @auth
            @include('layouts.navigation')
        @endauth
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Header -->
            @auth
            <header class="sticky top-0 z-30 app-card border-b-0 border-l border-r-0 lg:border-l-0">
                <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16">
                    <!-- Mobile menu button -->
                    <button @click="$dispatch('toggle-sidebar')" class="lg:hidden p-2 rounded-lg transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <!-- Page Title -->
                    <h1 class="text-xl font-semibold hidden sm:block" style="color: var(--text-primary);">
                        @yield('title', 'Dashboard')
                    </h1>
                    
                    <!-- Right side -->
                    <div class="flex items-center gap-3">
                        <!-- Dark mode toggle -->
                        <button @click="darkMode = !darkMode" class="p-2 rounded-lg transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'" :title="darkMode ? 'Modo claro' : 'Modo oscuro'">
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </button>
                        
                        <!-- User menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-3 p-2 rounded-lg transition-colors" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                                <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-white font-medium text-sm">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <span class="hidden md:block text-sm font-medium" style="color: var(--text-secondary);">
                                    {{ auth()->user()->name }}
                                </span>
                                <svg class="w-4 h-4" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 rounded-lg app-card py-2 z-50">
                                <div class="px-4 py-3" style="border-bottom: 1px solid var(--border-color);">
                                    <p class="text-sm font-medium" style="color: var(--text-primary);">{{ auth()->user()->name }}</p>
                                    <p class="text-xs truncate" style="color: var(--text-tertiary);">{{ auth()->user()->email }}</p>
                                </div>
                                @if(auth()->user()->isClient())
                                    <a href="{{ route('client.profile.edit') }}" class="block px-4 py-2 text-sm transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                                        Mi Perfil
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm transition-colors" style="color: #dc2626;" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                                        Cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            @endauth
            
            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto">
                <div class="max-w-7xl mx-auto page-content">
                    @if(session('success'))
                        <div class="mb-6 rounded-lg p-4 animate-fade-in flex items-start gap-3" style="background-color: var(--success-bg); border: 1px solid var(--success-border);" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: var(--success-text);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm font-medium" style="color: var(--success-text);">{{ session('success') }}</p>
                            <button @click="show = false" class="ml-auto" style="color: var(--success-text);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-6 rounded-lg p-4 animate-fade-in flex items-start gap-3" style="background-color: var(--error-bg); border: 1px solid var(--error-border);" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: var(--error-text);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm font-medium" style="color: var(--error-text);">{{ session('error') }}</p>
                            <button @click="show = false" class="ml-auto" style="color: var(--error-text);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>
