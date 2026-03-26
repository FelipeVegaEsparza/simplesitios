@extends('layouts.app')

@section('title', 'Editar Entrada')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.content_entries.by_section', ['client' => $client, 'section' => $section]) }}" class="p-2 rounded-lg transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Editar Entrada</h1>
            <p class="text-sm" style="color: var(--text-secondary);">{{ $section->name }} · {{ $client->name }}</p>
        </div>
    </div>

    <form action="{{ route('admin.content_entries.update', ['client' => $client, 'section' => $section, 'entry' => $entry]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="app-card rounded-xl p-6">
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Título</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $entry->title) }}"
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Título de la entrada">
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $entry->slug) }}"
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="url-amigable">
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Estado</label>
                    <select name="status" id="status"
                            class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none">
                        <option value="draft" {{ old('status', $entry->status) === 'draft' ? 'selected' : '' }}>Borrador</option>
                        <option value="published" {{ old('status', $entry->status) === 'published' ? 'selected' : '' }}>Publicado</option>
                        <option value="archived" {{ old('status', $entry->status) === 'archived' ? 'selected' : '' }}>Archivado</option>
                    </select>
                </div>

                <!-- Published At -->
                <div>
                    <label for="published_at" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Fecha de publicación</label>
                    <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at', $entry->published_at?->format('Y-m-d\TH:i')) }}"
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none">
                </div>
            </div>

            <!-- Dynamic Fields -->
            <div class="pt-6" style="border-top: 1px solid var(--border-color);">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Campos de la sección</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($section->fields as $field)
                        @php
                            $fieldValue = $entry->getFieldValue($field->slug);
                            $oldValue = old('fields.' . $field->slug, $fieldValue);
                        @endphp
                        <div class="{{ $field->column_width === 'full' ? 'md:col-span-2' : '' }}">
                            <label for="field_{{ $field->slug }}" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                                {{ $field->label }}
                                @if($field->is_required)
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>
                            
                            @switch($field->type)
                                @case('text')
                                    <input type="text" name="fields[{{ $field->slug }}]" id="field_{{ $field->slug }}" value="{{ $oldValue }}" 
                                           @if($field->is_required) required @endif 
                                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none">
                                    @break
                                @case('textarea')
                                    <textarea name="fields[{{ $field->slug }}]" id="field_{{ $field->slug }}" rows="4" 
                                              @if($field->is_required) required @endif 
                                              class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none resize-none">{{ $oldValue }}</textarea>
                                    @break
                                @case('number')
                                    <input type="number" name="fields[{{ $field->slug }}]" id="field_{{ $field->slug }}" value="{{ $oldValue }}" 
                                           @if($field->is_required) required @endif 
                                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none">
                                    @break
                                @case('boolean')
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" name="fields[{{ $field->slug }}]" id="field_{{ $field->slug }}" value="1" {{ $oldValue ? 'checked' : '' }}
                                               class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                        <span class="text-sm" style="color: var(--text-secondary);">Sí</span>
                                    </label>
                                    @break
                                @case('select')
                                    <select name="fields[{{ $field->slug }}]" id="field_{{ $field->slug }}" 
                                            @if($field->is_required) required @endif 
                                            class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none">
                                        <option value="">Seleccionar...</option>
                                        @foreach($field->options['options'] ?? [] as $option)
                                            <option value="{{ $option }}" {{ $oldValue === $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                    @break
                                @case('image')
                                @case('file')
                                    @if($oldValue)
                                        <div class="mb-2 p-2 rounded-lg" style="background-color: var(--bg-secondary);">
                                            <span class="text-sm" style="color: var(--text-secondary);">Archivo actual: {{ $oldValue }}</span>
                                        </div>
                                    @endif
                                    <input type="file" name="fields[{{ $field->slug }}]" id="field_{{ $field->slug }}" 
                                           @if($field->is_required && !$oldValue) required @endif 
                                           class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                                    @break
                                @default
                                    <input type="text" name="fields[{{ $field->slug }}]" id="field_{{ $field->slug }}" value="{{ $oldValue }}" 
                                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none">
                            @endswitch
                            
                            @if($field->help_text)
                                <p class="mt-1 text-xs" style="color: var(--text-tertiary);">{{ $field->help_text }}</p>
                            @endif
                            
                            @error('fields.' . $field->slug)
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.content_entries.by_section', ['client' => $client, 'section' => $section]) }}" class="px-6 py-3 rounded-lg font-medium text-sm transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
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
