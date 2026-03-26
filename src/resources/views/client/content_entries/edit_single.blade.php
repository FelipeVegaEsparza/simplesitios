@extends('layouts.app')

@section('title', $section->name)

@section('content')
<div class="space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 sm:truncate sm:text-3xl sm:tracking-tight" style="color: var(--text-primary);">
                {{ $section->name }}
            </h2>
            @if($section->description)
                <p class="mt-1 text-sm" style="color: var(--text-secondary);">{{ $section->description }}</p>
            @endif
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
            <a href="{{ route('client.dashboard') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg border font-medium text-sm transition-all" style="border-color: var(--border-color); color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                Volver
            </a>
        </div>
    </div>

    <div class="app-card rounded-xl">
        <form action="{{ route('client.sections.single.update', $section) }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-6">
            @csrf
            @method('PUT')
            
            <!-- Dynamic Fields -->
            <div>
                <h3 class="text-base font-semibold leading-6 mb-4" style="color: var(--text-primary);">Contenido</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    @foreach($section->fields as $field)
                        @php
                            $fieldValue = $entry->getFieldValue($field->slug);
                            $oldValue = old('fields.' . $field->slug, $fieldValue);
                        @endphp
                        <div class="{{ $field->column_width === 'full' ? 'sm:col-span-2' : '' }}">
                            <label for="field_{{ $field->slug }}" class="block text-sm font-medium leading-6" style="color: var(--text-primary);">
                                {{ $field->label }}
                                @if($field->is_required)
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>
                            
                            <div class="mt-2">
                                @switch($field->type)
                                    @case('text')
                                        <input type="text" name="fields[{{ $field->slug }}]" id="field_{{ $field->slug }}" value="{{ $oldValue }}" @if($field->is_required) required @endif class="app-input block w-full rounded-lg py-2 px-3 sm:text-sm sm:leading-6">
                                        @break
                                    @case('textarea')
                                        <textarea name="fields[{{ $field->slug }}]" id="field_{{ $field->slug }}" rows="4" @if($field->is_required) required @endif class="app-input block w-full rounded-lg py-2 px-3 sm:text-sm sm:leading-6">{{ $oldValue }}</textarea>
                                        @break
                                    @case('number')
                                        <input type="number" name="fields[{{ $field->slug }}]" id="field_{{ $field->slug }}" value="{{ $oldValue }}" @if($field->is_required) required @endif class="app-input block w-full rounded-lg py-2 px-3 sm:text-sm sm:leading-6">
                                        @break
                                    @case('boolean')
                                        <input type="checkbox" name="fields[{{ $field->slug }}]" id="field_{{ $field->slug }}" value="1" {{ $oldValue ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                        @break
                                    @case('select')
                                        <select name="fields[{{ $field->slug }}]" id="field_{{ $field->slug }}" @if($field->is_required) required @endif class="app-input block w-full rounded-lg py-2 px-3 sm:text-sm sm:leading-6">
                                            <option value="">Seleccionar...</option>
                                            @foreach($field->options['options'] ?? [] as $option)
                                                <option value="{{ $option }}" {{ $oldValue === $option ? 'selected' : '' }}>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                        @break
                                    @case('image')
                                    @case('file')
                                        @if($oldValue)
                                            <div class="mb-2">
                                                <span class="text-sm" style="color: var(--text-secondary);">Archivo actual: {{ $oldValue }}</span>
                                            </div>
                                        @endif
                                        <input type="file" name="fields[{{ $field->slug }}]" id="field_{{ $field->slug }}" @if($field->is_required && !$oldValue) required @endif class="block w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900/30 dark:file:text-primary-400">
                                        @break
                                    @default
                                        <input type="text" name="fields[{{ $field->slug }}]" id="field_{{ $field->slug }}" value="{{ $oldValue }}" class="app-input block w-full rounded-lg py-2 px-3 sm:text-sm sm:leading-6">
                                @endswitch
                            </div>
                            
                            @if($field->help_text)
                                <p class="mt-1 text-xs" style="color: var(--text-tertiary);">{{ $field->help_text }}</p>
                            @endif
                            
                            @error('fields.' . $field->slug)
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t pt-6" style="border-color: var(--border-color);">
                <button type="submit" class="btn-press rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-colors">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
