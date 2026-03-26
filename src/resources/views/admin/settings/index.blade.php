@extends('layouts.app')

@section('title', 'Configuración')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Configuración</h1>
            <p class="text-sm" style="color: var(--text-secondary);">Personaliza la apariencia del sitio</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- General Settings -->
        <div class="app-card rounded-xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold" style="color: var(--text-primary);">Configuración General</h2>
            </div>
            
            <div class="space-y-6">
                <!-- Site Name -->
                <div>
                    <label for="site_name" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Nombre del Proyecto <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="site_name" id="site_name" 
                           value="{{ old('site_name', $settings['site_name']->value ?? 'CMS Headless') }}" 
                           required
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Mi Proyecto">
                    <p class="mt-1 text-xs" style="color: var(--text-tertiary);">
                        Este nombre aparecerá en el título del sitio y en la página de login
                    </p>
                    @error('site_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Site Logo -->
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Logo del Sitio
                    </label>
                    
                    @php
                        $logoUrl = $settings['site_logo']->value ?? null;
                        if ($logoUrl && !str_starts_with($logoUrl, 'http')) {
                            $logoUrl = \Illuminate\Support\Facades\Storage::url($logoUrl);
                        }
                    @endphp
                    
                    <div class="flex items-center gap-4 p-4 rounded-xl" style="background-color: var(--bg-secondary); border: 1px dashed var(--border-color);">
                        @if($logoUrl)
                            <div class="relative">
                                <img src="{{ $logoUrl }}" alt="Logo" class="h-16 w-auto object-contain rounded-lg">
                            </div>
                            <div class="flex-1">
                                <p class="text-sm mb-2" style="color: var(--text-secondary);">Logo actual</p>
                                <div class="flex gap-2">
                                    <label class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors" 
                                           style="background-color: var(--bg-tertiary); color: var(--text-primary);"
                                           onmouseover="this.style.backgroundColor='var(--border-color)'" 
                                           onmouseout="this.style.backgroundColor='var(--bg-tertiary)'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Cambiar Logo
                                        <input type="file" name="site_logo" accept="image/*" class="hidden">
                                    </label>
                                    <a href="{{ route('admin.settings.remove-logo') }}" 
                                       onclick="return confirm('¿Estás seguro de eliminar el logo?')"
                                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-red-600 transition-colors"
                                       style="background-color: var(--bg-tertiary);"
                                       onmouseover="this.style.backgroundColor='var(--border-color)'" 
                                       onmouseout="this.style.backgroundColor='var(--bg-tertiary)'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Eliminar
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="h-16 w-16 rounded-lg flex items-center justify-center" style="background-color: var(--bg-tertiary);">
                                <svg class="w-8 h-8" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm mb-2" style="color: var(--text-secondary);">Sin logo configurado</p>
                                <label class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors" 
                                       style="background-color: var(--bg-tertiary); color: var(--text-primary);"
                                       onmouseover="this.style.backgroundColor='var(--border-color)'" 
                                       onmouseout="this.style.backgroundColor='var(--bg-tertiary)'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    Subir Logo
                                    <input type="file" name="site_logo" accept="image/*" class="hidden">
                                </label>
                            </div>
                        @endif
                    </div>
                    <p class="mt-1 text-xs" style="color: var(--text-tertiary);">
                        Se recomienda una imagen de 200x60px o similar. Se convertirá automáticamente a WebP.
                    </p>
                    @error('site_logo')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Favicon -->
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Favicon
                    </label>
                    
                    @php
                        $faviconUrl = $settings['site_favicon']->value ?? null;
                        if ($faviconUrl && !str_starts_with($faviconUrl, 'http')) {
                            $faviconUrl = \Illuminate\Support\Facades\Storage::url($faviconUrl);
                        }
                    @endphp
                    
                    <div class="flex items-center gap-4 p-4 rounded-xl" style="background-color: var(--bg-secondary); border: 1px dashed var(--border-color);">
                        @if($faviconUrl)
                            <div class="h-12 w-12 rounded-lg flex items-center justify-center overflow-hidden" style="background-color: var(--bg-primary); border: 1px solid var(--border-color);">
                                <img src="{{ $faviconUrl }}" alt="Favicon" class="h-8 w-8 object-contain">
                            </div>
                            <div class="flex-1">
                                <p class="text-sm mb-2" style="color: var(--text-secondary);">Favicon actual</p>
                                <div class="flex gap-2">
                                    <label class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors" 
                                           style="background-color: var(--bg-tertiary); color: var(--text-primary);"
                                           onmouseover="this.style.backgroundColor='var(--border-color)'" 
                                           onmouseout="this.style.backgroundColor='var(--bg-tertiary)'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Cambiar Favicon
                                        <input type="file" name="site_favicon" accept=".ico,.png" class="hidden">
                                    </label>
                                    <a href="{{ route('admin.settings.remove-favicon') }}" 
                                       onclick="return confirm('¿Estás seguro de eliminar el favicon?')"
                                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-red-600 transition-colors"
                                       style="background-color: var(--bg-tertiary);"
                                       onmouseover="this.style.backgroundColor='var(--border-color)'" 
                                       onmouseout="this.style.backgroundColor='var(--bg-tertiary)'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Eliminar
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="h-12 w-12 rounded-lg flex items-center justify-center" style="background-color: var(--bg-tertiary);">
                                <svg class="w-6 h-6" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm mb-2" style="color: var(--text-secondary);">Sin favicon configurado</p>
                                <label class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium cursor-pointer transition-colors" 
                                       style="background-color: var(--bg-tertiary); color: var(--text-primary);"
                                       onmouseover="this.style.backgroundColor='var(--border-color)'" 
                                       onmouseout="this.style.backgroundColor='var(--bg-tertiary)'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    Subir Favicon
                                    <input type="file" name="site_favicon" accept=".ico,.png" class="hidden">
                                </label>
                            </div>
                        @endif
                    </div>
                    <p class="mt-1 text-xs" style="color: var(--text-tertiary);">
                        Se recomienda un archivo .ico o .png de 32x32px o 64x64px.
                    </p>
                    @error('site_favicon')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
            <button type="submit" class="btn-press inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm transition-all shadow-lg shadow-primary-600/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Guardar Configuración
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Preview de archivos seleccionados
document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            // Mostrar nombre del archivo seleccionado
            const label = this.closest('label');
            const originalText = label.querySelector('span')?.textContent || 'Subir';
            
            // Crear un elemento para mostrar el nombre
            let fileNameEl = label.nextElementSibling;
            if (!fileNameEl || !fileNameEl.classList.contains('file-name')) {
                fileNameEl = document.createElement('p');
                fileNameEl.className = 'file-name mt-2 text-xs';
                fileNameEl.style.cssText = 'color: var(--text-secondary);';
                label.parentNode.insertBefore(fileNameEl, label.nextSibling);
            }
            fileNameEl.textContent = 'Seleccionado: ' + this.files[0].name;
        }
    });
});
</script>
@endpush
@endsection
