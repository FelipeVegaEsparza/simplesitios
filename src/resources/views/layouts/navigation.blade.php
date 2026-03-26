<!-- Sidebar Navigation -->
<aside class="fixed inset-y-0 left-0 z-40 w-64 transform transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0"
       :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
       @toggle-sidebar.window="sidebarOpen = !sidebarOpen"
       x-data="{ sidebarOpen: false }"
       style="background-color: var(--bg-primary); border-right: 1px solid var(--border-color);">
    
    <!-- Logo -->
    @php
        $logoUrl = \App\Models\Setting::getLogoUrl();
        $siteName = \App\Models\Setting::getSiteName();
    @endphp
    <div class="flex items-center justify-between h-16 px-6" style="border-bottom: 1px solid var(--border-color);">
        <a href="{{ auth()->user()->isSuperAdmin() ? route('admin.dashboard') : route('client.dashboard') }}" class="flex items-center gap-3">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-9 w-auto object-contain">
            @else
                <div class="w-9 h-9 rounded-lg bg-primary-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <span class="text-lg font-bold" style="color: var(--text-primary);">{{ $siteName }}</span>
            @endif
        </a>
        
        <!-- Close sidebar on mobile -->
        <button @click="sidebarOpen = false" class="lg:hidden p-2 rounded-lg transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    
    <!-- Navigation Links -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        @if(auth()->user()->isSuperAdmin())
            {{-- Superadmin Navigation --}}
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               style="background-color: {{ request()->routeIs('admin.dashboard') ? 'var(--bg-tertiary)' : 'transparent' }}; color: {{ request()->routeIs('admin.dashboard') ? 'var(--accent-color)' : 'var(--text-secondary)' }};"
               onmouseover="if(!this.classList.contains('active')) this.style.backgroundColor='var(--bg-tertiary)'"
               onmouseout="if(!this.classList.contains('active')) this.style.backgroundColor='transparent'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dashboard
            </a>
            
            <a href="{{ route('admin.clients.index') }}" 
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}"
               style="background-color: {{ request()->routeIs('admin.clients.*') ? 'var(--bg-tertiary)' : 'transparent' }}; color: {{ request()->routeIs('admin.clients.*') ? 'var(--accent-color)' : 'var(--text-secondary)' }};"
               onmouseover="if(!this.classList.contains('active')) this.style.backgroundColor='var(--bg-tertiary)'"
               onmouseout="if(!this.classList.contains('active')) this.style.backgroundColor='transparent'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Clientes
            </a>
            
            <a href="{{ route('admin.users.index') }}" 
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
               style="background-color: {{ request()->routeIs('admin.users.*') ? 'var(--bg-tertiary)' : 'transparent' }}; color: {{ request()->routeIs('admin.users.*') ? 'var(--accent-color)' : 'var(--text-secondary)' }};"
               onmouseover="if(!this.classList.contains('active')) this.style.backgroundColor='var(--bg-tertiary)'"
               onmouseout="if(!this.classList.contains('active')) this.style.backgroundColor='transparent'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Usuarios
            </a>
            
            <a href="{{ route('admin.sections.index') }}" 
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.sections.*') ? 'active' : '' }}"
               style="background-color: {{ request()->routeIs('admin.sections.*') ? 'var(--bg-tertiary)' : 'transparent' }}; color: {{ request()->routeIs('admin.sections.*') ? 'var(--accent-color)' : 'var(--text-secondary)' }};"
               onmouseover="if(!this.classList.contains('active')) this.style.backgroundColor='var(--bg-tertiary)'"
               onmouseout="if(!this.classList.contains('active')) this.style.backgroundColor='transparent'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                Secciones
            </a>
            
            <a href="{{ route('admin.content_entries.index') }}" 
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.content_entries.*') ? 'active' : '' }}"
               style="background-color: {{ request()->routeIs('admin.content_entries.*') ? 'var(--bg-tertiary)' : 'transparent' }}; color: {{ request()->routeIs('admin.content_entries.*') ? 'var(--accent-color)' : 'var(--text-secondary)' }};"
               onmouseover="if(!this.classList.contains('active')) this.style.backgroundColor='var(--bg-tertiary)'"
               onmouseout="if(!this.classList.contains('active')) this.style.backgroundColor='transparent'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Contenido
            </a>
            
            <a href="{{ route('admin.settings.index') }}" 
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
               style="background-color: {{ request()->routeIs('admin.settings.*') ? 'var(--bg-tertiary)' : 'transparent' }}; color: {{ request()->routeIs('admin.settings.*') ? 'var(--accent-color)' : 'var(--text-secondary)' }};"
               onmouseover="if(!this.classList.contains('active')) this.style.backgroundColor='var(--bg-tertiary)'"
               onmouseout="if(!this.classList.contains('active')) this.style.backgroundColor='transparent'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Configuración
            </a>
            
        @else
            {{-- Client Navigation --}}
            <a href="{{ route('client.dashboard') }}" 
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('client.dashboard') ? 'active' : '' }}"
               style="background-color: {{ request()->routeIs('client.dashboard') ? 'var(--bg-tertiary)' : 'transparent' }}; color: {{ request()->routeIs('client.dashboard') ? 'var(--accent-color)' : 'var(--text-secondary)' }};"
               onmouseover="if(!this.classList.contains('active')) this.style.backgroundColor='var(--bg-tertiary)'"
               onmouseout="if(!this.classList.contains('active')) this.style.backgroundColor='transparent'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dashboard
            </a>
            
            <a href="{{ route('client.sections.index') }}" 
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('client.sections.*') ? 'active' : '' }}"
               style="background-color: {{ request()->routeIs('client.sections.*') ? 'var(--bg-tertiary)' : 'transparent' }}; color: {{ request()->routeIs('client.sections.*') ? 'var(--accent-color)' : 'var(--text-secondary)' }};"
               onmouseover="if(!this.classList.contains('active')) this.style.backgroundColor='var(--bg-tertiary)'"
               onmouseout="if(!this.classList.contains('active')) this.style.backgroundColor='transparent'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Secciones
            </a>
            
            <a href="{{ route('client.media.index') }}" 
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('client.media.*') ? 'active' : '' }}"
               style="background-color: {{ request()->routeIs('client.media.*') ? 'var(--bg-tertiary)' : 'transparent' }}; color: {{ request()->routeIs('client.media.*') ? 'var(--accent-color)' : 'var(--text-secondary)' }};"
               onmouseover="if(!this.classList.contains('active')) this.style.backgroundColor='var(--bg-tertiary)'"
               onmouseout="if(!this.classList.contains('active')) this.style.backgroundColor='transparent'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Media
            </a>
            
            <a href="{{ route('client.api.index') }}" 
               class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('client.api.*') ? 'active' : '' }}"
               style="background-color: {{ request()->routeIs('client.api.*') ? 'var(--bg-tertiary)' : 'transparent' }}; color: {{ request()->routeIs('client.api.*') ? 'var(--accent-color)' : 'var(--text-secondary)' }};"
               onmouseover="if(!this.classList.contains('active')) this.style.backgroundColor='var(--bg-tertiary)'"
               onmouseout="if(!this.classList.contains('active')) this.style.backgroundColor='transparent'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
                API
            </a>

            @php $client = auth()->user()->client; @endphp
            @if($client && $client->isStoreEnabled())
                <!-- Store Section -->
                <div class="pt-4 mt-4" style="border-top: 1px solid var(--border-color);">
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--text-tertiary);">Tienda Online</p>
                    
                    <a href="{{ route('client.store.products.index') }}" 
                       class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('client.store.products.*') || request()->routeIs('client.store.categories.*') ? 'active' : '' }}"
                       style="background-color: {{ request()->routeIs('client.store.products.*') || request()->routeIs('client.store.categories.*') ? 'var(--bg-tertiary)' : 'transparent' }}; color: {{ request()->routeIs('client.store.products.*') || request()->routeIs('client.store.categories.*') ? 'var(--accent-color)' : 'var(--text-secondary)' }};"
                       onmouseover="if(!this.classList.contains('active')) this.style.backgroundColor='var(--bg-tertiary)'"
                       onmouseout="if(!this.classList.contains('active')) this.style.backgroundColor='transparent'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Productos
                    </a>
                    
                    <a href="{{ route('client.store.orders.index') }}" 
                       class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('client.store.orders.*') ? 'active' : '' }}"
                       style="background-color: {{ request()->routeIs('client.store.orders.*') ? 'var(--bg-tertiary)' : 'transparent' }}; color: {{ request()->routeIs('client.store.orders.*') ? 'var(--accent-color)' : 'var(--text-secondary)' }};"
                       onmouseover="if(!this.classList.contains('active')) this.style.backgroundColor='var(--bg-tertiary)'"
                       onmouseout="if(!this.classList.contains('active')) this.style.backgroundColor='transparent'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Órdenes
                    </a>
                    
                    <a href="{{ route('client.store.bank-accounts.index') }}" 
                       class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('client.store.bank-accounts.*') ? 'active' : '' }}"
                       style="background-color: {{ request()->routeIs('client.store.bank-accounts.*') ? 'var(--bg-tertiary)' : 'transparent' }}; color: {{ request()->routeIs('client.store.bank-accounts.*') ? 'var(--accent-color)' : 'var(--text-secondary)' }};"
                       onmouseover="if(!this.classList.contains('active')) this.style.backgroundColor='var(--bg-tertiary)'"
                       onmouseout="if(!this.classList.contains('active')) this.style.backgroundColor='transparent'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Cuentas Bancarias
                    </a>
                </div>
            @endif
        @endif
    </nav>
    
    <!-- Sidebar Footer -->
    <div class="p-4" style="border-top: 1px solid var(--border-color);">
        <div class="flex items-center gap-3 px-3 py-2 rounded-lg" style="background-color: var(--bg-tertiary);">
            <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-white text-sm font-medium">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium truncate" style="color: var(--text-primary);">{{ auth()->user()->name }}</p>
                <p class="text-xs truncate" style="color: var(--text-tertiary);">
                    {{ auth()->user()->isSuperAdmin() ? 'Superadmin' : 'Cliente' }}
                </p>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar Overlay -->
<div x-data="{ sidebarOpen: false }" 
     @toggle-sidebar.window="sidebarOpen = !sidebarOpen"
     x-show="sidebarOpen" 
     @click="sidebarOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-30 bg-black/50 lg:hidden"
     style="display: none;">
</div>
