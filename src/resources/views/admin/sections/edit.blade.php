@extends('layouts.app')

@section('title', 'Editar Sección')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.sections.index') }}" class="p-2 rounded-lg transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Editar Sección</h1>
            <p class="text-sm" style="color: var(--text-secondary);">{{ $section->name }}</p>
        </div>
    </div>

    <form action="{{ route('admin.sections.update', $section) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="app-card rounded-xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold" style="color: var(--text-primary);">Información de la Sección</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $section->name) }}" required
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Ej: Blog Posts">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Identificador (slug) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $section->slug) }}" required
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Ej: blog-posts">
                    <p class="mt-1 text-xs" style="color: var(--text-tertiary);">Usado en las URLs de la API</p>
                    @error('slug')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Descripción
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none resize-none"
                              placeholder="Descripción de la sección...">{{ old('description', $section->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Selector -->
                <div class="md:col-span-2" 
                     x-data="{ 
                         showMediaSelector: false, 
                         selectedImage: {{ $section->image ? '{id: ' . $section->image->id . ', url: \'' . $section->image->getUrl() . '\', name: \'' . $section->image->original_filename . '\'}' : 'null' }} 
                     }">
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Imagen de la Sección
                    </label>
                    
                    <!-- Preview Area -->
                    <div class="flex items-center gap-4 p-4 rounded-xl" style="background-color: var(--bg-secondary); border: 1px dashed var(--border-color);">
                        <template x-if="selectedImage">
                            <div class="relative">
                                <img :src="selectedImage.url" class="w-24 h-24 object-cover rounded-lg">
                                <button type="button" @click="selectedImage = null; $refs.imageId.value = ''" 
                                        class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-500 text-white flex items-center justify-center text-xs hover:bg-red-600">
                                    ×
                                </button>
                            </div>
                        </template>
                        <template x-if="!selectedImage">
                            <div class="w-24 h-24 rounded-lg flex items-center justify-center" style="background-color: var(--bg-tertiary);">
                                <svg class="w-8 h-8" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </template>
                        
                        <div class="flex-1">
                            <p class="text-sm mb-2" style="color: var(--text-secondary);" x-text="selectedImage ? selectedImage.name : 'Ninguna imagen seleccionada'"></p>
                            <button type="button" @click="showMediaSelector = true"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                    style="background-color: var(--bg-tertiary); color: var(--text-primary);"
                                    onmouseover="this.style.backgroundColor='var(--border-color)'" 
                                    onmouseout="this.style.backgroundColor='var(--bg-tertiary)'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span x-text="selectedImage ? 'Cambiar Imagen' : 'Seleccionar Imagen'"></span>
                            </button>
                        </div>
                    </div>
                    
                    <input type="hidden" name="image_id" x-ref="imageId" value="{{ old('image_id', $section->image_id) }}">
                    @error('image_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <!-- Media Selector Modal -->
                    <div x-show="showMediaSelector" 
                         class="fixed inset-0 z-50 overflow-y-auto" 
                         style="display: none;"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showMediaSelector = false">
                                <div class="absolute inset-0 bg-black/50"></div>
                            </div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            <div class="inline-block align-bottom rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
                                 style="background-color: var(--bg-primary);"
                                 @click.stop>
                                <div class="px-6 py-4 border-b" style="border-color: var(--border-color);">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Seleccionar Imagen</h3>
                                        <button type="button" @click="showMediaSelector = false" class="p-2 rounded-lg" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="p-6 max-h-[60vh] overflow-y-auto">
                                    @php
                                        $mediaImages = \App\Models\Media::where('mime_type', 'like', 'image/%')->latest()->get();
                                    @endphp
                                    @if($mediaImages->count() > 0)
                                        <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3">
                                            @foreach($mediaImages as $image)
                                                <button type="button" 
                                                        @click="selectedImage = {id: {{ $image->id }}, url: '{{ $image->getUrl() }}', name: '{{ $image->original_filename }}'}; $refs.imageId.value = {{ $image->id }}; showMediaSelector = false"
                                                        class="relative aspect-square rounded-lg overflow-hidden hover:ring-2 hover:ring-primary-500 transition-all"
                                                        :class="selectedImage && selectedImage.id == {{ $image->id }} ? 'ring-2 ring-primary-500' : ''">
                                                    <img src="{{ $image->getUrl() }}" alt="{{ $image->original_filename }}" class="w-full h-full object-cover">
                                                </button>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-12">
                                            <svg class="mx-auto w-12 h-12 mb-4" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p style="color: var(--text-secondary);">No hay imágenes en la biblioteca</p>
                                            <a href="{{ route('client.media.create') }}" target="_blank" class="mt-2 inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700">
                                                Subir imágenes
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Endpoint Slug -->
                <div>
                    <label for="endpoint_slug" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Slug del endpoint API
                    </label>
                    <input type="text" name="endpoint_slug" id="endpoint_slug" value="{{ old('endpoint_slug', $section->endpoint_slug) }}"
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Opcional">
                    <p class="mt-1 text-xs" style="color: var(--text-tertiary);">Si se deja vacío, se usará el slug de la sección</p>
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Orden
                    </label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $section->sort_order) }}"
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none">
                </div>

                <!-- Gallery -->
                <div class="md:col-span-2 pt-4" style="border-top: 1px solid var(--border-color);" 
                     x-data="{ 
                         showGallerySelector: false, 
                         galleryImages: {{ $section->gallery->map(fn($img) => ['id' => $img->id, 'url' => $img->getUrl(), 'name' => $img->original_filename])->toJson() }} 
                     }">
                    <label class="block text-sm font-medium mb-3" style="color: var(--text-secondary);">
                        Galería de Imágenes
                    </label>
                    
                    <!-- Gallery Preview -->
                    <div class="flex flex-wrap gap-3 mb-3">
                        <template x-for="(image, index) in galleryImages" :key="image.id">
                            <div class="relative w-20 h-20">
                                <img :src="image.url" class="w-full h-full object-cover rounded-lg">
                                <input type="hidden" name="gallery_images[]" :value="image.id">
                                <button type="button" @click="galleryImages.splice(index, 1)"
                                        class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-red-500 text-white flex items-center justify-center text-xs hover:bg-red-600">
                                    ×
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="showGallerySelector = true"
                                class="w-20 h-20 rounded-lg flex items-center justify-center transition-colors"
                                style="background-color: var(--bg-tertiary); border: 2px dashed var(--border-color); color: var(--text-tertiary);"
                                onmouseover="this.style.borderColor='var(--accent-color)'; this.style.color='var(--accent-color)'"
                                onmouseout="this.style.borderColor='var(--border-color)'; this.style.color='var(--text-tertiary)'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Gallery Selector Modal -->
                    <div x-show="showGallerySelector" 
                         class="fixed inset-0 z-50 overflow-y-auto" 
                         style="display: none;"
                         x-transition>
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showGallerySelector = false">
                                <div class="absolute inset-0 bg-black/50"></div>
                            </div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            <div class="inline-block align-bottom rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
                                 style="background-color: var(--bg-primary);"
                                 @click.stop>
                                <div class="px-6 py-4 border-b" style="border-color: var(--border-color);">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Agregar Imágenes a la Galería</h3>
                                        <button type="button" @click="showGallerySelector = false" class="p-2 rounded-lg" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="p-6 max-h-[60vh] overflow-y-auto">
                                    @php
                                        $galleryImages = \App\Models\Media::where('mime_type', 'like', 'image/%')->latest()->get();
                                    @endphp
                                    @if($galleryImages->count() > 0)
                                        <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3">
                                            @foreach($galleryImages as $image)
                                                <button type="button" 
                                                        @click="if(!galleryImages.find(img => img.id === {{ $image->id }})) { galleryImages.push({id: {{ $image->id }}, url: '{{ $image->getUrl() }}', name: '{{ $image->original_filename }}'}); }"
                                                        class="relative aspect-square rounded-lg overflow-hidden hover:ring-2 hover:ring-primary-500 transition-all"
                                                        :class="galleryImages.find(img => img.id === {{ $image->id }}) ? 'ring-2 ring-primary-500 opacity-50' : ''">
                                                    <img src="{{ $image->getUrl() }}" alt="{{ $image->original_filename }}" class="w-full h-full object-cover">
                                                    <div x-show="galleryImages.find(img => img.id === {{ $image->id }})" class="absolute inset-0 flex items-center justify-center bg-primary-500/50">
                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-12">
                                            <svg class="mx-auto w-12 h-12 mb-4" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p style="color: var(--text-secondary);">No hay imágenes en la biblioteca</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="px-6 py-4 border-t" style="border-color: var(--border-color); background-color: var(--bg-secondary);">
                                    <button type="button" @click="showGallerySelector = false" class="btn-press w-full py-2 px-4 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm transition-all">
                                        Listo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Options -->
                <div class="md:col-span-2 flex flex-wrap gap-6 pt-4" style="border-top: 1px solid var(--border-color);">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_visible" value="1" {{ old('is_visible', $section->is_visible) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm" style="color: var(--text-secondary);">Visible en panel</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_public_endpoint" value="1" {{ old('is_public_endpoint', $section->is_public_endpoint) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm" style="color: var(--text-secondary);">Endpoint público</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.sections.index') }}" class="px-6 py-3 rounded-lg font-medium text-sm transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                Cancelar
            </a>
            <button type="submit" class="btn-press inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm transition-all shadow-lg shadow-primary-600/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
