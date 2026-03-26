@extends('layouts.app')

@section('title', 'Mis Secciones')

@section('content')
<div class="space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 sm:truncate sm:text-3xl sm:tracking-tight" style="color: var(--text-primary);">
                Mis Secciones
            </h2>
            <p class="mt-1 text-sm" style="color: var(--text-secondary);">
                Secciones disponibles para gestionar contenido
            </p>
        </div>
    </div>

    @if($sections->count() > 0)
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($sections as $section)
                <a href="{{ $section->isSingle() ? route('client.sections.single.edit', $section) : route('client.sections.entries.index', $section) }}" class="app-card relative flex items-center space-x-3 rounded-xl px-6 py-5 hover:scale-[1.02] transition-transform" style="border-color: var(--border-color);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                            <svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                @if($section->type === 'single')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                @endif
                            </svg>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="absolute inset-0" aria-hidden="true"></span>
                        <p class="text-sm font-medium" style="color: var(--text-primary);">{{ $section->name }}</p>
                        <p class="truncate text-sm" style="color: var(--text-tertiary);">{{ $section->content_entries_count }} {{ $section->content_entries_count === 1 ? 'entrada' : 'entradas' }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $section->type === 'single' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400' }}">
                        {{ $section->type === 'single' ? 'Único' : 'Colección' }}
                    </span>
                </a>
            @endforeach
        </div>
    @else
        <div class="app-card rounded-xl p-8 text-center">
            <svg class="mx-auto h-12 w-12 mb-4" style="color: var(--text-tertiary);" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold" style="color: var(--text-primary);">No hay secciones</h3>
            <p class="mt-1 text-sm" style="color: var(--text-secondary);">Aún no tienes secciones configuradas. Contacta al administrador.</p>
        </div>
    @endif
</div>
@endsection
