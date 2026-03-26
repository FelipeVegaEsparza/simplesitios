@extends('layouts.app')

@section('title', $client->name)

@section('content')
<div class="space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                {{ $client->name }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                {{ $client->slug }} · {{ $client->domain ?? 'Sin dominio' }}
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-2">
            <a href="{{ route('admin.clients.edit', $client) }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                Editar
            </a>
            <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" class="inline" id="delete-form-client-show-{{ $client->id }}" data-modal="global-delete-modal">
                @csrf
                @method('DELETE')
                <button type="button" onclick="openDeleteModal('delete-form-client-show-{{ $client->id }}')" class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                    Eliminar
                </button>
            </form>
            <a href="{{ route('admin.clients.index') }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                Volver
            </a>
        </div>
    </div>

    <!-- Info Card -->
    <div class="rounded-lg bg-white shadow">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900">Información del Cliente</h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Slug</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->slug }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Dominio</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->domain ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Estado</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $client->status === 'active' ? 'bg-green-100 text-green-800' : ($client->status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($client->status) }}
                        </span>
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->description ?? 'Sin descripción' }}</dd>
                </div>
                @if($client->logo)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Logo</dt>
                        <dd class="mt-1">
                            <img src="{{ asset('storage/' . $client->logo) }}" alt="Logo" class="h-16 w-auto">
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Sections -->
    <div class="rounded-lg bg-white shadow">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-base font-semibold leading-6 text-gray-900">Secciones</h3>
            <a href="{{ route('admin.sections.create', ['client_id' => $client->id]) }}" class="text-sm text-primary-600 hover:text-primary-900">Nueva sección</a>
        </div>
        <div class="border-t border-gray-200">
            @if($client->sections->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($client->sections as $section)
                        <li class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-primary-600">{{ $section->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $section->type === 'single' ? 'Único' : 'Colección' }} · {{ $section->fields->count() }} campos</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    @if($section->is_public_endpoint)
                                        <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">API</span>
                                    @endif
                                    <a href="{{ route('admin.sections.fields', $section) }}" class="text-sm text-gray-600 hover:text-gray-900">Campos</a>
                                    <a href="{{ route('admin.content_entries.by_section', ['client' => $client, 'section' => $section]) }}" class="text-sm text-primary-600 hover:text-primary-900">Contenido</a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="px-4 py-8 text-center text-sm text-gray-500">
                    No hay secciones configuradas.
                    <a href="{{ route('admin.sections.create', ['client_id' => $client->id]) }}" class="text-primary-600 hover:text-primary-900">Crear primera sección</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Users -->
    <div class="rounded-lg bg-white shadow">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <h3 class="text-base font-semibold leading-6 text-gray-900">Usuarios</h3>
            <a href="{{ route('admin.users.create') }}" class="text-sm text-primary-600 hover:text-primary-900">Nuevo usuario</a>
        </div>
        <div class="border-t border-gray-200">
            @if($client->users->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($client->users as $user)
                        <li class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center">
                                        <span class="text-primary-700 text-sm font-medium">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="px-4 py-8 text-center text-sm text-gray-500">
                    No hay usuarios asignados.
                </div>
            @endif
        </div>
    </div>

    <!-- API Documentation -->
    <div class="rounded-lg bg-white shadow">
        <div class="px-4 py-5 sm:px-6 flex items-center gap-2">
            <svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
            </svg>
            <h3 class="text-base font-semibold leading-6 text-gray-900">API Pública - Documentación</h3>
        </div>
        <div class="border-t border-gray-200">
            <div class="px-4 py-5 sm:px-6">
                <p class="text-sm text-gray-600 mb-4">
                    Base URL: <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ url('/api/public') }}</code>
                </p>
                
                <!-- Endpoint: Client Info -->
                <div class="mb-8 border border-gray-200 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">GET</span>
                            <code class="text-sm font-mono">/{{ $client->slug }}</code>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Obtiene la información general del cliente</p>
                    </div>
                    <div class="p-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Ejemplo de respuesta:</p>
                        <pre class="bg-gray-900 text-gray-100 p-3 rounded text-xs overflow-x-auto"><code>{
  "data": {
    "id": {{ $client->id }},
    "name": "{{ $client->name }}",
    "slug": "{{ $client->slug }}",
    "domain": "{{ $client->domain ?? 'null' }}",
    "description": "{{ $client->description ?? 'null' }}",
    "logo": {{ $client->logo ? '"' . asset('storage/' . $client->logo) . '"' : 'null' }},
    "created_at": "{{ $client->created_at->toIso8601String() }}"
  }
}</code></pre>
                        <div class="mt-3 flex gap-2">
                            <a href="{{ url('/api/public/' . $client->slug) }}" target="_blank" class="inline-flex items-center text-xs text-primary-600 hover:text-primary-900">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Probar endpoint
                            </a>
                            <button onclick="copyToClipboard('curl -X GET &quot;{{ url('/api/public/' . $client->slug) }}&quot;')" class="inline-flex items-center text-xs text-gray-600 hover:text-gray-900">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Copiar cURL
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Endpoint: Sections -->
                <div class="mb-8 border border-gray-200 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">GET</span>
                            <code class="text-sm font-mono">/{{ $client->slug }}/sections</code>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Lista todas las secciones públicas disponibles</p>
                    </div>
                    <div class="p-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Ejemplo de respuesta:</p>
                        <pre class="bg-gray-900 text-gray-100 p-3 rounded text-xs overflow-x-auto"><code>{
  "data": [
@foreach($client->sections->where('is_public_endpoint', true)->take(2) as $section)
    {
      "id": {{ $section->id }},
      "name": "{{ $section->name }}",
      "slug": "{{ $section->slug }}",
      "type": "{{ $section->type }}",
      "description": {{ $section->description ? '"' . $section->description . '"' : 'null' }},
      "endpoint_slug": "{{ $section->getApiSlug() }}"
    }@if(!$loop->last),@endif
@endforeach
  ]
}</code></pre>
                        <div class="mt-3 flex gap-2">
                            <a href="{{ url('/api/public/' . $client->slug . '/sections') }}" target="_blank" class="inline-flex items-center text-xs text-primary-600 hover:text-primary-900">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Probar endpoint
                            </a>
                            <button onclick="copyToClipboard('curl -X GET &quot;{{ url('/api/public/' . $client->slug . '/sections') }}&quot;')" class="inline-flex items-center text-xs text-gray-600 hover:text-gray-900">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Copiar cURL
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Endpoints: Section Content -->
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Endpoints de Contenido por Sección</h4>
                
                @forelse($client->sections->where('is_public_endpoint', true) as $section)
                    <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">GET</span>
                                <code class="text-sm font-mono">/{{ $client->slug }}/{{ $section->getApiSlug() }}</code>
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $section->type === 'single' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $section->type === 'single' ? 'Single' : 'Collection' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ $section->name }} - {{ $section->description ?? 'Sin descripción' }}</p>
                        </div>
                        <div class="p-4">
                            @if($section->type === 'collection')
                                <div class="mb-3">
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Parámetros opcionales:</p>
                                    <ul class="text-xs text-gray-600 space-y-1">
                                        <li><code>page</code> - Número de página (default: 1)</li>
                                        <li><code>per_page</code> - Items por página (default: 20, max: 100)</li>
                                    </ul>
                                </div>
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Ejemplo de respuesta (Collection):</p>
                                <pre class="bg-gray-900 text-gray-100 p-3 rounded text-xs overflow-x-auto"><code>{
  "data": [
    {
      "id": 1,
      "title": "Título de ejemplo",
      "slug": "titulo-ejemplo",
      "status": "published",
      "published_at": "2024-01-15T10:00:00+00:00",
      "created_at": "2024-01-10T08:30:00+00:00",
@foreach($section->fields->where('show_in_api', true)->take(3) as $field)
      "{{ $field->slug }}": {{ $field->type === 'boolean' ? 'true' : ($field->type === 'number' ? '123' : '"' . $field->label . '"') }}{{ !$loop->last ? ',' : '' }}
@endforeach
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 20,
    "total": 100
  }
}</code></pre>
                            @else
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Ejemplo de respuesta (Single):</p>
                                <pre class="bg-gray-900 text-gray-100 p-3 rounded text-xs overflow-x-auto"><code>{
  "data": {
    "id": 1,
    "status": "published",
    "created_at": "2024-01-10T08:30:00+00:00",
@foreach($section->fields->where('show_in_api', true)->take(3) as $field)
    "{{ $field->slug }}": {{ $field->type === 'boolean' ? 'true' : ($field->type === 'number' ? '123' : '"' . $field->label . '"') }}{{ !$loop->last ? ',' : '' }}
@endforeach
  }
}</code></pre>
                            @endif

                            <!-- Campos disponibles -->
                            @if($section->fields->where('show_in_api', true)->count() > 0)
                                <div class="mt-4">
                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Campos disponibles:</p>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                        @foreach($section->fields->where('show_in_api', true) as $field)
                                            <div class="text-xs bg-gray-100 px-2 py-1 rounded flex items-center gap-1">
                                                <span class="font-mono text-gray-700">{{ $field->slug }}</span>
                                                <span class="text-gray-400">({{ $field->type }})</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mt-4 flex flex-wrap gap-2">
                                <a href="{{ url('/api/public/' . $client->slug . '/' . $section->getApiSlug()) }}" target="_blank" class="inline-flex items-center text-xs text-primary-600 hover:text-primary-900">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Probar endpoint
                                </a>
                                <button onclick="copyToClipboard('curl -X GET &quot;{{ url('/api/public/' . $client->slug . '/' . $section->getApiSlug()) }}&quot;')" class="inline-flex items-center text-xs text-gray-600 hover:text-gray-900">
                                    <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    Copiar cURL
                                </button>
                                @if($section->type === 'collection')
                                    <button onclick="copyToClipboard('curl -X GET &quot;{{ url('/api/public/' . $client->slug . '/' . $section->getApiSlug()) }}?page=1&per_page=10&quot;')" class="inline-flex items-center text-xs text-gray-600 hover:text-gray-900">
                                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        cURL con paginación
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500 text-center py-4 bg-gray-50 rounded-lg">
                        No hay secciones con endpoint público configurado.
                    </div>
                @endforelse

                <!-- Settings Endpoint -->
                <div class="mt-8 border border-gray-200 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">GET</span>
                            <code class="text-sm font-mono">/{{ $client->slug }}/settings</code>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Obtiene la configuración pública del cliente</p>
                    </div>
                    <div class="p-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Ejemplo de respuesta:</p>
                        <pre class="bg-gray-900 text-gray-100 p-3 rounded text-xs overflow-x-auto"><code>{
  "data": {
    "theme": "light",
    "language": "es",
    "custom_setting": "value"
  }
}</code></pre>
                        <div class="mt-3 flex gap-2">
                            <a href="{{ url('/api/public/' . $client->slug . '/settings') }}" target="_blank" class="inline-flex items-center text-xs text-primary-600 hover:text-primary-900">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Probar endpoint
                            </a>
                            <button onclick="copyToClipboard('curl -X GET &quot;{{ url('/api/public/' . $client->slug . '/settings') }}&quot;')" class="inline-flex items-center text-xs text-gray-600 hover:text-gray-900">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Copiar cURL
                            </button>
                        </div>
                    </div>
                </div>

                <!-- JavaScript para copiar -->
                <script>
                    function copyToClipboard(text) {
                        navigator.clipboard.writeText(text).then(() => {
                            // Crear toast notification
                            const toast = document.createElement('div');
                            toast.className = 'fixed bottom-4 right-4 bg-gray-900 text-white px-4 py-2 rounded-lg shadow-lg text-sm z-50';
                            toast.textContent = 'Comando copiado al portapapeles';
                            document.body.appendChild(toast);
                            setTimeout(() => toast.remove(), 2000);
                        });
                    }
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
