@extends('layouts.app')

@section('title', "Contenido: {$section->name}")

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">{{ $section->name }}</h1>
            <p class="mt-1 text-sm" style="color: var(--text-secondary);">
                Cliente: {{ $client->name }} · Tipo: {{ $section->type === 'single' ? 'Único' : 'Colección' }}
            </p>
        </div>
        @if($section->isCollection())
            <a href="{{ route('admin.content_entries.create', ['client' => $client, 'section' => $section]) }}" class="btn-press inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Entrada
            </a>
        @endif
    </div>

    @if($section->isSingle())
        @php $entry = $section->getSingleEntry(); @endphp
        @if($entry)
            <div class="app-card rounded-xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Contenido</h3>
                    <a href="{{ route('admin.content_entries.edit', ['client' => $client, 'section' => $section, 'entry' => $entry]) }}" 
                       class="inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </a>
                </div>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach($section->fields as $field)
                        <div>
                            <dt class="text-sm font-medium" style="color: var(--text-secondary);">{{ $field->label }}</dt>
                            <dd class="mt-1 text-sm" style="color: var(--text-primary);">
                                @php $value = $entry->getFieldValue($field->slug); @endphp
                                @if(is_array($value))
                                    <pre class="text-xs p-2 rounded-lg overflow-x-auto" style="background-color: var(--bg-secondary);">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                @else
                                    {{ $value ?? '-' }}
                                @endif
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        @else
            <div class="app-card rounded-xl p-12 text-center">
                <svg class="mx-auto h-12 w-12 mb-4" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mb-4" style="color: var(--text-secondary);">No hay contenido creado aún</p>
                <a href="{{ route('admin.content_entries.create', ['client' => $client, 'section' => $section]) }}" class="btn-press inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Crear Contenido
                </a>
            </div>
        @endif
    @else
        <!-- Collection Table -->
        <div class="app-card rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full app-table">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left">Título</th>
                            <th class="px-6 py-4 text-left">Estado</th>
                            <th class="px-6 py-4 text-left">Actualizado</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entries as $entry)
                            <tr class="transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium" style="color: var(--text-primary);">{{ $entry->title ?? 'Sin título' }}</p>
                                        <p class="text-sm" style="color: var(--text-tertiary);">{{ $entry->slug }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $entry->status === 'published' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : ($entry->status === 'draft' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400') }}">
                                        {{ ucfirst($entry->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm" style="color: var(--text-secondary);">{{ $entry->updated_at->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.content_entries.edit', ['client' => $client, 'section' => $section, 'entry' => $entry]) }}" 
                                           class="p-2 rounded-lg transition-colors" style="color: var(--text-tertiary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'" title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.content_entries.destroy', ['client' => $client, 'section' => $section, 'entry' => $entry]) }}" class="inline" id="delete-form-entry-section-{{ $entry->id }}" data-modal="global-delete-modal">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="openDeleteModal('delete-form-entry-section-{{ $entry->id }}')" class="p-2 rounded-lg transition-colors text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30" title="Eliminar">
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
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <svg class="mx-auto w-12 h-12 mb-4" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-sm mb-2" style="color: var(--text-secondary);">No hay entradas</p>
                                    <a href="{{ route('admin.content_entries.create', ['client' => $client, 'section' => $section]) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                                        Crear la primera →
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($entries->hasPages())
            <div>
                {{ $entries->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
