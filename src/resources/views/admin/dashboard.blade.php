@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="app-card rounded-xl p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold" style="color: var(--text-primary);">
                    ¡Hola, {{ auth()->user()->name }}!
                </h1>
                <p class="mt-2" style="color: var(--text-secondary);">
                    Bienvenido al panel de administración. Aquí tienes un resumen del sistema.
                </p>
            </div>
            <a href="{{ route('admin.clients.create') }}" class="btn-press inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Cliente
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Clientes -->
        <div class="app-card rounded-xl p-5 sm:p-6 group hover:scale-[1.02] transition-transform">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: var(--text-tertiary);">Total Clientes</p>
                    <p class="text-3xl font-bold mt-2" style="color: var(--text-primary);">{{ $stats['clients_count'] }}</p>
                    <p class="text-sm mt-1" style="color: var(--success-text);">
                        <span class="inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $stats['active_clients'] }} activos
                        </span>
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Secciones -->
        <div class="app-card rounded-xl p-5 sm:p-6 group hover:scale-[1.02] transition-transform">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: var(--text-tertiary);">Total Secciones</p>
                    <p class="text-3xl font-bold mt-2" style="color: var(--text-primary);">{{ $stats['sections_count'] }}</p>
                    <p class="text-sm mt-1" style="color: var(--text-tertiary);">Configuradas</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Contenido -->
        <div class="app-card rounded-xl p-5 sm:p-6 group hover:scale-[1.02] transition-transform">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: var(--text-tertiary);">Entradas de Contenido</p>
                    <p class="text-3xl font-bold mt-2" style="color: var(--text-primary);">{{ $stats['content_entries'] }}</p>
                    <p class="text-sm mt-1" style="color: var(--text-tertiary);">Publicadas</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Usuarios -->
        <div class="app-card rounded-xl p-5 sm:p-6 group hover:scale-[1.02] transition-transform">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: var(--text-tertiary);">Total Usuarios</p>
                    <p class="text-3xl font-bold mt-2" style="color: var(--text-primary);">{{ $stats['users_count'] }}</p>
                    <p class="text-sm mt-1" style="color: var(--text-tertiary);">Registrados</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Clients -->
    <div class="app-card rounded-xl overflow-hidden">
        <div class="px-6 py-5 border-b flex items-center justify-between" style="border-color: var(--border-color);">
            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Clientes Recientes</h3>
            <a href="{{ route('admin.clients.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                Ver todos
            </a>
        </div>
        
        <div class="divide-y" style="border-color: var(--border-color);">
            @forelse($recentClients as $client)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-semibold">
                            {{ substr($client->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium" style="color: var(--text-primary);">{{ $client->name }}</p>
                            <p class="text-sm" style="color: var(--text-tertiary);">{{ $client->slug }} · {{ $client->sections->count() }} secciones</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $client->status === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : ($client->status === 'inactive' ? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400') }}">
                            {{ ucfirst($client->status) }}
                        </span>
                        <a href="{{ route('admin.clients.show', $client) }}" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" style="color: var(--text-tertiary);">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto w-12 h-12 mb-4" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="text-sm mb-2" style="color: var(--text-secondary);">No hay clientes registrados</p>
                    <a href="{{ route('admin.clients.create') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                        Crear primer cliente →
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
