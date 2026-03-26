@extends('layouts.app')

@section('title', 'Contenido')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Contenido</h1>
            <p class="mt-1 text-sm" style="color: var(--text-secondary);">Todas las entradas de contenido</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="app-card rounded-xl p-4">
        <form method="GET" class="flex flex-col lg:flex-row gap-4" x-data="{ clientId: '{{ request('client_id') }}' }">
            <div class="flex-1 min-w-[200px]">
                <select name="client_id" x-model="clientId" @change="$event.target.form.submit()" 
                        class="app-input block w-full px-3 py-2.5 rounded-lg text-sm focus:outline-none">
                    <option value="">Todos los clientes</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            
            @if(!empty($sections))
            <div class="min-w-[200px]">
                <select name="section_id" 
                        class="app-input block w-full px-3 py-2.5 rounded-lg text-sm focus:outline-none">
                    <option value="">Todas las secciones</option>
                    @foreach($sections as $section)
                        <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            
            <div class="min-w-[150px]">
                <select name="status" 
                        class="app-input block w-full px-3 py-2.5 rounded-lg text-sm focus:outline-none">
                    <option value="">Todos los estados</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publicado</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Borrador</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archivado</option>
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por título o slug..." 
                           class="app-input block w-full pl-10 pr-3 py-2.5 rounded-lg text-sm focus:outline-none">
                </div>
            </div>
            
            <div class="flex gap-2">
                <button type="submit" class="btn-press inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm transition-all">
                    Filtrar
                </button>
                @if(request()->hasAny(['client_id', 'section_id', 'status', 'search']))
                    <a href="{{ route('admin.content_entries.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg font-medium text-sm transition-colors" style="color: var(--text-tertiary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                        Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Results Count -->
    <div class="text-sm" style="color: var(--text-secondary);">
        Mostrando {{ $entries->count() }} de {{ $entries->total() }} entradas
        @if(request()->hasAny(['client_id', 'section_id', 'status', 'search']))
            (filtrado)
        @endif
    </div>

    <!-- Table -->
    <div class="app-card rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full app-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">Título</th>
                        <th class="px-6 py-4 text-left">Sección</th>
                        <th class="px-6 py-4 text-left">Cliente</th>
                        <th class="px-6 py-4 text-left">Estado</th>
                        <th class="px-6 py-4 text-left">Actualizado</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entries as $entry)
                        <tr class="transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium" style="color: var(--text-primary);">{{ $entry->title ?? 'Sin título' }}</p>
                                    <p class="text-sm" style="color: var(--text-tertiary);">{{ $entry->slug }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm" style="color: var(--text-secondary);">{{ $entry->section?->name ?? 'Sin sección' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm" style="color: var(--text-secondary);">{{ $entry->client?->name ?? 'Sin cliente' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $entry->status === 'published' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : ($entry->status === 'draft' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400') }}">
                                    {{ ucfirst($entry->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm" style="color: var(--text-secondary);">{{ $entry->updated_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($entry->client && $entry->section)
                                    <a href="{{ route('admin.content_entries.edit', ['client' => $entry->client, 'section' => $entry->section, 'entry' => $entry]) }}" 
                                       class="p-2 rounded-lg transition-colors" style="color: var(--text-tertiary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.content_entries.destroy', ['client' => $entry->client, 'section' => $entry->section, 'entry' => $entry]) }}" class="inline" id="delete-form-entry-{{ $entry->id }}" data-modal="global-delete-modal">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="openDeleteModal('delete-form-entry-{{ $entry->id }}')" class="p-2 rounded-lg transition-colors text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30" title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-xs" style="color: var(--text-tertiary);">Cliente/Sección eliminados</span>
                                    <form method="POST" action="{{ route('admin.content_entries.destroy', ['client' => $entry->client_id ?? 0, 'section' => $entry->section_id ?? 0, 'entry' => $entry]) }}" class="inline" id="delete-form-entry-orphan-{{ $entry->id }}" data-modal="global-delete-modal">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="openDeleteModal('delete-form-entry-orphan-{{ $entry->id }}')" class="p-2 rounded-lg transition-colors text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30" title="Eliminar entrada huérfana">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto w-12 h-12 mb-4" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-sm" style="color: var(--text-secondary);">No se encontraron entradas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($entries->hasPages())
            <div class="px-6 py-4 border-t" style="border-color: var(--border-color);">
                {{ $entries->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
