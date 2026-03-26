@extends('layouts.app')

@section('title', 'Biblioteca de Medios')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Biblioteca de Medios</h1>
            <p class="mt-1 text-sm" style="color: var(--text-secondary);">Gestiona tus imágenes y archivos</p>
        </div>
        <a href="{{ route('client.media.create') }}" class="btn-press inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Subir Archivos
        </a>
    </div>

    <!-- Filters -->
    <div class="app-card rounded-xl p-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <select name="type" class="app-input block w-40 py-2.5 px-3 rounded-lg text-sm focus:outline-none">
                <option value="">Todos los tipos</option>
                <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Imágenes</option>
                <option value="file" {{ request('type') === 'file' ? 'selected' : '' }}>Archivos</option>
            </select>
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..." 
                           class="app-input block w-full pl-10 pr-3 py-2.5 rounded-lg text-sm focus:outline-none">
                </div>
            </div>
            <button type="submit" class="btn-press inline-flex items-center gap-2 px-4 py-2.5 rounded-lg transition-all font-medium text-sm" style="background-color: var(--bg-tertiary); color: var(--text-secondary);">
                Filtrar
            </button>
        </form>
    </div>

    <!-- Media Grid -->
    @if($media->count() > 0)
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
            @foreach($media as $item)
                <div class="relative group rounded-xl app-card overflow-hidden">
                    @if($item->isImage())
                        <div class="aspect-square flex items-center justify-center" style="background-color: var(--bg-tertiary);">
                            <img src="{{ $item->getUrl() }}" alt="{{ $item->original_filename }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="aspect-square flex items-center justify-center" style="background-color: var(--bg-tertiary);">
                            <svg class="h-12 w-12" style="color: var(--text-tertiary);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                    @endif
                    
                    <div class="p-3">
                        <p class="text-sm font-medium truncate" style="color: var(--text-primary);" title="{{ $item->original_filename }}">{{ $item->original_filename }}</p>
                        <div class="flex items-center gap-2">
                            <p class="text-xs" style="color: var(--text-tertiary);">{{ $item->getFormattedSize() }}</p>
                            @if($item->isWebp())
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                    WEBP
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                        <a href="{{ $item->getUrl() }}" target="_blank" class="rounded-lg px-3 py-1.5 text-sm font-medium transition-colors" style="background-color: var(--bg-primary); color: var(--text-primary);" onmouseover="this.style.backgroundColor='var(--bg-secondary)'" onmouseout="this.style.backgroundColor='var(--bg-primary)'">Ver</a>
                        <form action="{{ route('client.media.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este archivo?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg px-3 py-1.5 text-sm font-medium bg-red-600 text-white hover:bg-red-700 transition-colors">Eliminar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($media->hasPages())
            <div class="mt-6">
                {{ $media->links() }}
            </div>
        @endif
    @else
        <div class="app-card rounded-xl p-12 text-center">
            <svg class="mx-auto h-12 w-12 mb-4" style="color: var(--text-tertiary);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H3.75A2.25 2.25 0 001.5 6v12a2.25 2.25 0 002.25 2.25z" />
            </svg>
            <h3 class="text-sm font-semibold" style="color: var(--text-primary);">No hay archivos</h3>
            <p class="mt-1 text-sm" style="color: var(--text-secondary);">Sube tu primer archivo para comenzar.</p>
            <div class="mt-6">
                <a href="{{ route('client.media.create') }}" class="btn-press inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm transition-all">
                    Subir Archivos
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
