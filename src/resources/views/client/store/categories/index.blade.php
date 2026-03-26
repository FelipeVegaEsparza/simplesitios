@extends('layouts.app')

@section('title', 'Categorías')

@section('content')
<div class="page-content p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Categorías</h2>
            <p class="text-sm mt-1" style="color: var(--text-secondary);">Organiza tus productos por categorías</p>
        </div>
        <a href="{{ route('client.store.products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border transition-colors" style="border-color: var(--border-color); color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Volver a Productos
        </a>
    </div>

    <!-- New Category Form -->
    <div class="app-card rounded-xl p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Nueva Categoría</h3>
        <form method="POST" action="{{ route('client.store.categories.store') }}" class="flex gap-4">
            @csrf
            <input type="text" name="name" required placeholder="Nombre de la categoría" class="app-input flex-1 px-4 py-2 rounded-lg">
            <input type="text" name="description" placeholder="Descripción (opcional)" class="app-input flex-1 px-4 py-2 rounded-lg">
            <button type="submit" class="px-6 py-2 rounded-lg bg-primary-600 text-white font-medium transition-colors hover:bg-primary-700">
                Crear
            </button>
        </form>
    </div>

    <!-- Categories List -->
    @if($categories->count() > 0)
        <div class="app-card rounded-xl overflow-hidden">
            <table class="w-full app-table">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-left">Nombre</th>
                        <th class="px-4 py-3 text-left">Descripción</th>
                        <th class="px-4 py-3 text-center">Productos</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td class="px-4 py-3 font-medium" style="color: var(--text-primary);">{{ $category->name }}</td>
                        <td class="px-4 py-3" style="color: var(--text-secondary);">{{ Str::limit($category->description, 50) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 text-xs rounded-full" style="background-color: var(--bg-tertiary); color: var(--text-secondary);">{{ $category->products_count }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($category->active)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Activa</span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Inactiva</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <form method="POST" action="{{ route('client.store.categories.update', $category) }}" class="inline-flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="name" value="{{ $category->name }}">
                                    <input type="hidden" name="description" value="{{ $category->description }}">
                                    <input type="hidden" name="active" value="{{ $category->active ? '0' : '1' }}">
                                    <button type="submit" class="text-sm {{ $category->active ? 'text-amber-600' : 'text-green-600' }} hover:underline">
                                        {{ $category->active ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>
                                <span style="color: var(--border-color);">|</span>
                                <form method="POST" action="{{ route('client.store.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('¿Eliminar esta categoría?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:underline">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    @else
        <div class="app-card rounded-xl p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center" style="background-color: var(--bg-tertiary);">
                <svg class="w-8 h-8" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium" style="color: var(--text-primary);">No hay categorías</h3>
            <p class="text-sm mt-1" style="color: var(--text-secondary);">Crea tu primera categoría para organizar los productos</p>
        </div>
    @endif
</div>
@endsection
