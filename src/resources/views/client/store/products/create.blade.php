@extends('layouts.app')

@section('title', 'Nuevo Producto')

@section('content')
<div class="page-content p-4 sm:p-6 lg:p-8" x-data="productForm()">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('client.store.products.index') }}" class="p-2 rounded-lg transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Nuevo Producto</h2>
            <p class="text-sm" style="color: var(--text-secondary);">Agrega un nuevo producto a tu catálogo</p>
        </div>
    </div>

    <form action="{{ route('client.store.products.store') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info -->
                <div class="app-card rounded-xl p-6">
                    <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Información Básica</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Nombre del producto *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="app-input w-full px-4 py-2 rounded-lg" placeholder="Ej: Polera de Algodón">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Descripción</label>
                            <textarea name="description" rows="4" class="app-input w-full px-4 py-2 rounded-lg" placeholder="Describe tu producto...">{{ old('description') }}</textarea>
                            @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Categoría</label>
                                <select name="category_id" class="app-input w-full px-4 py-2 rounded-lg">
                                    <option value="">Sin categoría</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">SKU (Código)</label>
                                <input type="text" name="sku" value="{{ old('sku') }}" class="app-input w-full px-4 py-2 rounded-lg" placeholder="Ej: POL-001">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="app-card rounded-xl p-6">
                    <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Precio y Stock</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Precio base (CLP) *</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2" style="color: var(--text-tertiary);">$</span>
                                <input type="number" name="base_price" value="{{ old('base_price') }}" required min="0" class="app-input w-full pl-8 pr-4 py-2 rounded-lg" placeholder="15990">
                            </div>
                            @error('base_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        
                        <div x-show="!hasVariants">
                            <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Stock inicial</label>
                            <input type="number" name="base_stock" value="{{ old('base_stock', 0) }}" min="0" class="app-input w-full px-4 py-2 rounded-lg" placeholder="0">
                        </div>
                    </div>
                    
                    <div class="mt-4 flex items-center gap-2">
                        <input type="checkbox" name="track_stock" value="1" checked class="rounded" style="border-color: var(--border-color);">
                        <label class="text-sm" style="color: var(--text-secondary);">Controlar stock (desactivar para productos ilimitados)</label>
                    </div>
                </div>

                <!-- Variants -->
                <div class="app-card rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Variantes</h3>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="has_variants" value="1" x-model="hasVariants" class="rounded" style="border-color: var(--border-color);">
                            <span class="text-sm" style="color: var(--text-secondary);">Este producto tiene variantes (talla, color, etc.)</span>
                        </label>
                    </div>
                    
                    <div x-show="hasVariants" x-transition>
                        <p class="text-sm mb-4" style="color: var(--text-tertiary);">Define las diferentes opciones de tu producto (ej: Talla S, M, L)</p>
                        
                        <div class="space-y-3">
                            <template x-for="(variant, index) in variants" :key="index">
                                <div class="p-4 rounded-lg" style="background-color: var(--bg-secondary);">
                                    <div class="grid grid-cols-12 gap-3 items-end">
                                        <div class="col-span-4">
                                            <label class="block text-xs font-medium mb-1" style="color: var(--text-tertiary);">Nombre (Talla/Color)</label>
                                            <input type="text" :name="`variants[${index}][variant_name]`" x-model="variant.variant_name" required class="app-input w-full px-3 py-2 rounded-lg text-sm" placeholder="M / Azul">
                                        </div>
                                        <div class="col-span-3">
                                            <label class="block text-xs font-medium mb-1" style="color: var(--text-tertiary);">SKU</label>
                                            <input type="text" :name="`variants[${index}][sku]`" x-model="variant.sku" class="app-input w-full px-3 py-2 rounded-lg text-sm" placeholder="Opcional">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-xs font-medium mb-1" style="color: var(--text-tertiary);">Precio</label>
                                            <input type="number" :name="`variants[${index}][price_override]`" x-model="variant.price_override" class="app-input w-full px-3 py-2 rounded-lg text-sm" placeholder="Base">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-xs font-medium mb-1" style="color: var(--text-tertiary);">Stock</label>
                                            <input type="number" :name="`variants[${index}][stock]`" x-model="variant.stock" required min="0" class="app-input w-full px-3 py-2 rounded-lg text-sm" placeholder="0">
                                        </div>
                                        <div class="col-span-1">
                                            <button type="button" @click="removeVariant(index)" class="p-2 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        <button type="button" @click="addVariant" class="mt-3 inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm transition-colors" style="color: var(--accent-color); background-color: var(--bg-tertiary);" onmouseover="this.style.backgroundColor='var(--border-color)'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Agregar variante
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Images -->
                <div class="app-card rounded-xl p-6">
                    <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Imágenes</h3>
                    
                    <div class="space-y-3" id="image-preview-container">
                        <template x-for="(preview, index) in imagePreviews" :key="index">
                            <div class="relative aspect-square rounded-lg overflow-hidden" style="background-color: var(--bg-tertiary);">
                                <img :src="preview" class="w-full h-full object-cover">
                                <button type="button" @click="removeImage(index)" class="absolute top-2 right-2 p-1 rounded-full bg-red-500 text-white hover:bg-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    
                    <div class="mt-3">
                        <label class="flex flex-col items-center justify-center w-full h-32 rounded-lg border-2 border-dashed cursor-pointer transition-colors" style="border-color: var(--border-color); background-color: var(--bg-tertiary);" onmouseover="this.style.borderColor='var(--accent-color)'" onmouseout="this.style.borderColor='var(--border-color)'">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-2" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-xs" style="color: var(--text-tertiary);">Haz clic para subir imágenes</p>
                            </div>
                            <input type="file" name="images[]" multiple accept="image/*" class="hidden" @change="handleImageUpload">
                        </label>
                    </div>
                    @error('images.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Actions -->
                <div class="app-card rounded-xl p-6">
                    <div class="flex flex-col gap-3">
                        <button type="submit" class="w-full py-3 px-4 rounded-lg bg-primary-600 text-white font-medium transition-colors hover:bg-primary-700">
                            Crear Producto
                        </button>
                        <a href="{{ route('client.store.products.index') }}" class="w-full py-3 px-4 rounded-lg text-center transition-colors" style="color: var(--text-secondary); background-color: var(--bg-tertiary);" onmouseover="this.style.backgroundColor='var(--border-color)'">
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function productForm() {
    return {
        hasVariants: {{ old('has_variants') ? 'true' : 'false' }},
        variants: @json(old('variants', [['variant_name' => '', 'sku' => '', 'price_override' => '', 'stock' => '']])),
        imagePreviews: [],
        
        addVariant() {
            this.variants.push({
                variant_name: '',
                sku: '',
                price_override: '',
                stock: ''
            });
        },
        
        removeVariant(index) {
            this.variants.splice(index, 1);
        },
        
        handleImageUpload(event) {
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreviews.push(e.target.result);
                };
                reader.readAsDataURL(files[i]);
            }
        },
        
        removeImage(index) {
            this.imagePreviews.splice(index, 1);
        }
    }
}
</script>
@endsection
