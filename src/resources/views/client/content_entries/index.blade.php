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
            <a href="{{ route('client.sections.entries.create', $section) }}" class="btn-press inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Entrada
            </a>
        </div>
    </div>

    <div class="app-card rounded-xl overflow-hidden">
        <table class="min-w-full divide-y" style="border-color: var(--border-color);">
            <thead style="background-color: var(--bg-tertiary);">
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold sm:pl-6" style="color: var(--text-primary);">Título</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold" style="color: var(--text-primary);">Estado</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold" style="color: var(--text-primary);">Actualizado</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only" style="color: var(--text-primary);">Acciones</span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y" style="border-color: var(--border-color);">
                @forelse($entries as $entry)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                            <div class="font-medium" style="color: var(--text-primary);">{{ $entry->title ?? 'Sin título' }}</div>
                            <div style="color: var(--text-tertiary);">{{ $entry->slug }}</div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $entry->status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : ($entry->status === 'draft' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400') }}">
                                {{ ucfirst($entry->status) }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm" style="color: var(--text-tertiary);">{{ $entry->updated_at->format('d/m/Y H:i') }}</td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <a href="{{ route('client.sections.entries.edit', ['section' => $section, 'entry' => $entry]) }}" class="text-primary-600 hover:text-primary-700 transition-colors">Editar</a>
                            <form action="{{ route('client.sections.entries.destroy', ['section' => $section, 'entry' => $entry]) }}" method="POST" class="inline ml-4" onsubmit="return confirm('¿Estás seguro de eliminar esta entrada?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 transition-colors">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-3 py-8 text-center text-sm" style="color: var(--text-secondary);">
                            No hay entradas. <a href="{{ route('client.sections.entries.create', $section) }}" class="text-primary-600 hover:text-primary-700 transition-colors">Crear la primera</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($entries->hasPages())
            <div class="border-t px-4 py-3 sm:px-6" style="border-color: var(--border-color);">
                {{ $entries->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
