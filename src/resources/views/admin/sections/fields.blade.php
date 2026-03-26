@extends('layouts.app')

@section('title', "Campos: {$section->name}")

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, fieldData: {} }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Campos: {{ $section->name }}</h1>
            <p class="mt-1 text-sm" style="color: var(--text-secondary);">Cliente: {{ $section->client->name }}</p>
        </div>
        <button @click="showModal = true; editMode = false; fieldData = { type: 'text', is_required: false, is_visible: true, show_in_list: true, show_in_api: true, column_width: 'full' }" 
                class="btn-press inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nuevo Campo
        </button>
    </div>

    <!-- Fields List -->
    <div class="app-card rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full app-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">Campo</th>
                        <th class="px-6 py-4 text-left">Tipo</th>
                        <th class="px-6 py-4 text-left">Requerido</th>
                        <th class="px-6 py-4 text-left">Visible</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($section->fields as $field)
                        <tr class="transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium" style="color: var(--text-primary);">{{ $field->label }}</p>
                                    <p class="text-sm" style="color: var(--text-tertiary);">{{ $field->slug }} · Orden: {{ $field->sort_order }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: var(--bg-tertiary); color: var(--text-secondary);">
                                    {{ $fieldTypes[$field->type] ?? $field->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($field->is_required)
                                    <span class="inline-flex items-center gap-1 text-sm" style="color: var(--success-text);">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Sí
                                    </span>
                                @else
                                    <span class="text-sm" style="color: var(--text-tertiary);">No</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($field->is_visible)
                                    <span class="inline-flex items-center gap-1 text-sm" style="color: var(--success-text);">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Sí
                                    </span>
                                @else
                                    <span class="text-sm" style="color: var(--text-tertiary);">No</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="showModal = true; editMode = true; fieldData = {{ $field->toJson() }}" 
                                            class="p-2 rounded-lg transition-colors" style="color: var(--text-tertiary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <form action="{{ route('admin.section-fields.destroy', $field) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('¿Estás seguro de eliminar este campo?')" 
                                                class="p-2 rounded-lg transition-colors text-red-600" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'" title="Eliminar">
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
                                <p class="text-sm mb-2" style="color: var(--text-secondary);">No hay campos definidos</p>
                                <button @click="showModal = true" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                                    Crea el primero →
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="showModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
        <div class="fixed inset-0 bg-black/50 transition-opacity backdrop-blur-sm" @click="showModal = false"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-xl app-card text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <form :action="editMode ? '{{ url('admin/section-fields') }}/' + fieldData.id : '{{ route('admin.section-fields.store') }}'" method="POST">
                        @csrf
                        <template x-if="editMode">
                            @method('PUT')
                        </template>
                        <input type="hidden" name="section_id" value="{{ $section->id }}">
                        
                        <div class="px-6 py-5 border-b" style="border-color: var(--border-color);">
                            <h3 class="text-lg font-semibold" style="color: var(--text-primary);" x-text="editMode ? 'Editar Campo' : 'Nuevo Campo'"></h3>
                        </div>
                        
                        <div class="px-6 py-5 space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Nombre interno</label>
                                <input type="text" name="name" x-model="fieldData.name" required 
                                       class="app-input block w-full px-3 py-2 rounded-lg text-sm focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Etiqueta visible</label>
                                <input type="text" name="label" x-model="fieldData.label" required 
                                       class="app-input block w-full px-3 py-2 rounded-lg text-sm focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Identificador (slug)</label>
                                <input type="text" name="slug" x-model="fieldData.slug" required 
                                       class="app-input block w-full px-3 py-2 rounded-lg text-sm focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--text-secondary);">Tipo</label>
                                <select name="type" x-model="fieldData.type" 
                                        class="app-input block w-full px-3 py-2 rounded-lg text-sm focus:outline-none">
                                    @foreach($fieldTypes as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-wrap gap-6 pt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_required" value="1" x-model="fieldData.is_required" 
                                           class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                    <span class="text-sm" style="color: var(--text-secondary);">Requerido</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_visible" value="1" x-model="fieldData.is_visible" 
                                           class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                    <span class="text-sm" style="color: var(--text-secondary);">Visible</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4 flex flex-row-reverse gap-3" style="background-color: var(--bg-secondary); border-top: 1px solid var(--border-color);">
                            <button type="submit" class="btn-press inline-flex justify-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700 transition-colors" x-text="editMode ? 'Guardar' : 'Crear'"></button>
                            <button type="button" @click="showModal = false" class="inline-flex justify-center rounded-lg px-4 py-2 text-sm font-medium transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
