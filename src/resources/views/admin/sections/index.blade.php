@extends('layouts.app')

@section('title', 'Secciones')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Secciones</h1>
            <p class="mt-1 text-sm" style="color: var(--text-secondary);">Gestiona las secciones de contenido</p>
        </div>
        <a href="{{ route('admin.sections.create') }}" class="btn-press inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nueva Sección
        </a>
    </div>

    <!-- Table -->
    <div class="app-card rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full app-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">Sección</th>
                        <th class="px-6 py-4 text-left">Cliente</th>
                        <th class="px-6 py-4 text-left">Tipo</th>
                        <th class="px-6 py-4 text-center">API</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sections as $section)
                        <tr class="transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium" style="color: var(--text-primary);">{{ $section->name }}</p>
                                    <code class="text-xs px-2 py-0.5 rounded font-mono" style="background-color: var(--bg-tertiary); color: var(--text-secondary);">{{ $section->slug }}</code>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm" style="color: var(--text-secondary);">{{ $section->client?->name ?? 'Sin cliente' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $section->type === 'single' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' }}">
                                    {{ $section->type === 'single' ? 'Único' : 'Colección' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($section->is_public_endpoint)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        Público
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400">
                                        Privado
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.sections.fields', $section) }}" class="p-2 rounded-lg transition-colors" style="color: var(--text-tertiary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'" title="Campos">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.sections.edit', $section) }}" class="p-2 rounded-lg transition-colors" style="color: var(--text-tertiary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.sections.destroy', $section) }}" class="inline" onsubmit="return confirm('¿Eliminar esta sección? Se eliminarán también todas sus entradas de contenido.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg transition-colors text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30" title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="mx-auto w-12 h-12 mb-4" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                <p class="text-sm mb-2" style="color: var(--text-secondary);">No se encontraron secciones</p>
                                <a href="{{ route('admin.sections.create') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                                    Crear primera sección →
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($sections->hasPages())
            <div class="px-6 py-4 border-t" style="border-color: var(--border-color);">
                {{ $sections->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
