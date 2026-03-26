@extends('layouts.app')

@section('title', 'Productos')

@section('content')
<div class="page-content p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Productos</h2>
            <p class="text-sm mt-1" style="color: var(--text-secondary);">Gestiona tu catálogo de productos</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('client.store.categories.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border transition-colors" style="border-color: var(--border-color); color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Categorías
            </a>
            <a href="{{ route('client.store.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 text-white transition-colors hover:bg-primary-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Producto
            </a>
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($products as $product)
            <div class="app-card rounded-xl overflow-hidden group">
                <!-- Image -->
                <div class="aspect-square relative overflow-hidden bg-gray-100">
                    @if($product->images && count($product->images) > 0)
                        <img src="{{ Storage::disk('public')->url($product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="background-color: var(--bg-tertiary);">
                            <svg class="w-12 h-12" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute top-2 left-2">
                        @if($product->status === 'active')
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Activo</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Inactivo</span>
                        @endif
                    </div>
                    
                    @if($product->track_stock && $product->total_stock <= 0)
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Sin stock</span>
                        </div>
                    @endif
                </div>
                
                <!-- Info -->
                <div class="p-4">
                    <h3 class="font-semibold truncate" style="color: var(--text-primary);">{{ $product->name }}</h3>
                    <p class="text-sm mt-1" style="color: var(--text-secondary);">{{ $product->category?->name ?? 'Sin categoría' }}</p>
                    
                    <div class="flex items-center justify-between mt-3">
                        <span class="text-lg font-bold text-primary-600">{{ $product->formatBasePrice() }}</span>
                        @if($product->track_stock)
                            <span class="text-sm" style="color: var(--text-tertiary);">{{ $product->total_stock }} disp.</span>
                        @endif
                    </div>
                    
                    @if($product->has_variants)
                        <p class="text-xs mt-2" style="color: var(--text-tertiary);">{{ $product->variants->count() }} variantes</p>
                    @endif
                    
                    <!-- Actions -->
                    <div class="flex gap-2 mt-4 pt-3" style="border-top: 1px solid var(--border-color);">
                        <a href="{{ route('client.store.products.edit', $product) }}" class="flex-1 text-center py-2 text-sm rounded-lg transition-colors" style="color: var(--text-secondary); background-color: var(--bg-tertiary);" onmouseover="this.style.backgroundColor='var(--border-color)'">
                            Editar
                        </a>
                        <form method="POST" action="{{ route('client.store.products.update-status', $product) }}" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $product->status === 'active' ? 'inactive' : 'active' }}">
                            <button type="submit" class="w-full py-2 text-sm rounded-lg transition-colors {{ $product->status === 'active' ? 'text-amber-600' : 'text-green-600' }}" style="background-color: var(--bg-tertiary);" onmouseover="this.style.backgroundColor='var(--border-color)'">
                                {{ $product->status === 'active' ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @else
        <div class="app-card rounded-xl p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center" style="background-color: var(--bg-tertiary);">
                <svg class="w-8 h-8" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium" style="color: var(--text-primary);">No hay productos</h3>
            <p class="text-sm mt-1" style="color: var(--text-secondary);">Comienza agregando tu primer producto</p>
            <a href="{{ route('client.store.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 mt-4 rounded-lg bg-primary-600 text-white transition-colors hover:bg-primary-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Crear Producto
            </a>
        </div>
    @endif
</div>
@endsection
