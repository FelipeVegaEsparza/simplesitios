@extends('layouts.app')

@section('title', 'Subir Archivos')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('client.media.index') }}" class="p-2 rounded-lg transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Subir Archivos</h1>
            <p class="text-sm" style="color: var(--text-secondary);">Selecciona archivos para subir</p>
        </div>
    </div>

    <div class="app-card rounded-xl p-6">
        <form action="{{ route('client.media.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label for="files" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Seleccionar archivos</label>
                <input type="file" name="files[]" id="files" multiple accept="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" 
                       class="block w-full text-sm file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                <p class="mt-2 text-sm" style="color: var(--text-tertiary);">Puedes seleccionar múltiples archivos. Tamaño máximo: 10MB por archivo.</p>
                @error('files')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('files.*')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-4 pt-6" style="border-top: 1px solid var(--border-color);">
                <a href="{{ route('client.media.index') }}" class="px-6 py-3 rounded-lg font-medium text-sm transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                    Cancelar
                </a>
                <button type="submit" class="btn-press inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm transition-all shadow-lg shadow-primary-600/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Subir Archivos
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
