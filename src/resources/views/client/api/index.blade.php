@extends('layouts.app')

@section('title', 'API Endpoints')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">API Endpoints</h1>
            <p class="mt-1 text-sm" style="color: var(--text-secondary);">Endpoints públicos disponibles para tu cliente</p>
        </div>
    </div>

    <!-- Info Card -->
    <div class="app-card rounded-xl p-6" style="background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-2" style="color: var(--text-primary);">¿Cómo usar la API?</h3>
                <p class="text-sm mb-3" style="color: var(--text-secondary);">
                    Los siguientes endpoints están disponibles para consumir tu contenido desde aplicaciones externas. 
                    Todos los endpoints son de solo lectura (GET) y devuelven datos en formato JSON.
                </p>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                        Base URL: {{ url('/api/public') }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                        Cliente: {{ $client->slug }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Endpoints List -->
    @if($endpoints->count() > 0)
        <div class="space-y-4">
            @foreach($endpoints as $endpoint)
                <div class="app-card rounded-xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold" style="color: var(--text-primary);">{{ $endpoint['name'] }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $endpoint['type'] === 'single' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' }}">
                                        {{ $endpoint['type'] === 'single' ? 'Único' : 'Colección' }}
                                    </span>
                                </div>
                                @if($endpoint['description'])
                                    <p class="text-sm mb-3" style="color: var(--text-secondary);">{{ $endpoint['description'] }}</p>
                                @endif
                                
                                <!-- URL del endpoint -->
                                <div class="flex items-center gap-2 p-3 rounded-lg" style="background-color: var(--bg-secondary);">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        GET
                                    </span>
                                    <code class="text-sm font-mono flex-1 break-all" style="color: var(--accent-color);">
                                        {{ $endpoint['endpoint_url'] }}
                                    </code>
                                    <button onclick="copyToClipboard('{{ $endpoint['endpoint_url'] }}')" 
                                            class="p-2 rounded-lg transition-colors" style="color: var(--text-tertiary);"
                                            onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" 
                                            onmouseout="this.style.backgroundColor='transparent'"
                                            title="Copiar URL">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Parámetros (solo para colecciones) -->
                        @if($endpoint['type'] === 'collection' && count($endpoint['params']) > 0)
                            <div class="mt-4 pt-4" style="border-top: 1px solid var(--border-color);">
                                <h4 class="text-sm font-medium mb-2" style="color: var(--text-secondary);">Parámetros opcionales:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($endpoint['params'] as $param)
                                        <code class="px-2 py-1 rounded text-xs font-mono" style="background-color: var(--bg-tertiary); color: var(--text-secondary);">
                                            {{ $param }}
                                        </code>
                                    @endforeach
                                </div>
                                <p class="text-xs mt-2" style="color: var(--text-tertiary);">
                                    Ejemplo: <code class="font-mono">{{ $endpoint['endpoint_url'] }}?page=1&limit=10</code>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="app-card rounded-xl p-12 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold mb-2" style="color: var(--text-primary);">No hay endpoints disponibles</h3>
            <p class="text-sm" style="color: var(--text-secondary);">
                No tienes secciones con endpoints públicos configurados. 
                Contacta al administrador para habilitar el acceso API a tus secciones.
            </p>
        </div>
    @endif

    <!-- API de Tienda (solo si está habilitada) -->
    @if(auth()->user()->client->isStoreEnabled())
    <div class="mt-8 pt-8" style="border-top: 2px solid var(--border-color);">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold" style="color: var(--text-primary);">API de Tienda Online</h2>
                <p class="text-sm" style="color: var(--text-secondary);">Endpoints para integrar tu catálogo de productos y órdenes</p>
            </div>
        </div>

        <!-- Info Card -->
        <div class="app-card rounded-xl p-6 mb-6" style="background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold mb-2" style="color: var(--text-primary);">Base URL para Tienda</h3>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                            {{ url('/api/store/' . $client->slug) }}
                        </span>
                    </div>
                    <p class="text-xs mt-2" style="color: var(--text-tertiary);">
                        El carrito se maneja en el frontend. Solo envías los items al crear la orden.
                    </p>
                </div>
            </div>
        </div>

        <!-- Endpoints de Tienda -->
        <div class="space-y-4">
            
            <!-- Catálogo -->
            <div class="app-card rounded-xl overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <h3 class="text-base font-semibold" style="color: var(--text-primary);">Listar Productos</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-700">GET</span>
                    </div>
                    <p class="text-sm mb-3" style="color: var(--text-secondary);">Obtiene el catálogo de productos con sus variantes e imágenes.</p>
                    
                    <div class="flex items-center gap-2 p-3 rounded-lg" style="background-color: var(--bg-secondary);">
                        <code class="text-sm font-mono flex-1 break-all" style="color: var(--accent-color);">
                            {{ url('/api/store/' . $client->slug . '/catalog') }}
                        </code>
                        <button onclick="copyToClipboard('{{ url('/api/store/' . $client->slug . '/catalog') }}')" 
                                class="p-2 rounded-lg transition-colors" style="color: var(--text-tertiary);"
                                onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" 
                                onmouseout="this.style.backgroundColor='transparent'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="mt-4 pt-4" style="border-top: 1px solid var(--border-color);">
                        <p class="text-xs mb-2" style="color: var(--text-tertiary);">Query params opcionales:</p>
                        <code class="text-xs font-mono" style="color: var(--text-secondary);">?category={slug}&search={query}&page=1&per_page=12</code>
                    </div>
                </div>
            </div>

            <!-- Categorías -->
            <div class="app-card rounded-xl overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <h3 class="text-base font-semibold" style="color: var(--text-primary);">Listar Categorías</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-700">GET</span>
                    </div>
                    <p class="text-sm mb-3" style="color: var(--text-secondary);">Obtiene todas las categorías de productos.</p>
                    
                    <div class="flex items-center gap-2 p-3 rounded-lg" style="background-color: var(--bg-secondary);">
                        <code class="text-sm font-mono flex-1 break-all" style="color: var(--accent-color);">
                            {{ url('/api/store/' . $client->slug . '/categories') }}
                        </code>
                        <button onclick="copyToClipboard('{{ url('/api/store/' . $client->slug . '/categories') }}')" 
                                class="p-2 rounded-lg transition-colors" style="color: var(--text-tertiary);"
                                onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" 
                                onmouseout="this.style.backgroundColor='transparent'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Producto Detalle -->
            <div class="app-card rounded-xl overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <h3 class="text-base font-semibold" style="color: var(--text-primary);">Detalle de Producto</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-700">GET</span>
                    </div>
                    <p class="text-sm mb-3" style="color: var(--text-secondary);">Obtiene el detalle completo de un producto incluyendo productos relacionados.</p>
                    
                    <div class="flex items-center gap-2 p-3 rounded-lg" style="background-color: var(--bg-secondary);">
                        <code class="text-sm font-mono flex-1 break-all" style="color: var(--accent-color);">
                            {{ url('/api/store/' . $client->slug . '/product/{slug}') }}
                        </code>
                        <button onclick="copyToClipboard('{{ url('/api/store/' . $client->slug . '/product/{slug}') }}')" 
                                class="p-2 rounded-lg transition-colors" style="color: var(--text-tertiary);"
                                onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" 
                                onmouseout="this.style.backgroundColor='transparent'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Crear Orden -->
            <div class="app-card rounded-xl overflow-hidden border-2 border-orange-200 dark:border-orange-900/50">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <h3 class="text-base font-semibold" style="color: var(--text-primary);">Crear Orden (Checkout)</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-blue-100 text-blue-700">POST</span>
                    </div>
                    <p class="text-sm mb-3" style="color: var(--text-secondary);">Crea una nueva orden de compra. El stock se reduce inmediatamente.</p>
                    
                    <div class="flex items-center gap-2 p-3 rounded-lg mb-4" style="background-color: var(--bg-secondary);">
                        <code class="text-sm font-mono flex-1 break-all" style="color: var(--accent-color);">
                            {{ url('/api/store/' . $client->slug . '/orders') }}
                        </code>
                        <button onclick="copyToClipboard('{{ url('/api/store/' . $client->slug . '/orders') }}')" 
                                class="p-2 rounded-lg transition-colors" style="color: var(--text-tertiary);"
                                onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" 
                                onmouseout="this.style.backgroundColor='transparent'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <pre class="text-xs p-4 rounded-lg" style="background-color: var(--bg-secondary); color: var(--text-primary);"><code>// Body JSON de ejemplo
{
  "customer_name": "Juan Pérez",
  "customer_email": "juan@email.com",
  "customer_phone": "+56912345678",
  "shipping_address": "Av. Principal 123, Depto 45",
  "shipping_city": "Santiago",
  "shipping_region": "Metropolitana",
  "bank_account_id": 1,
  "notes": "Entregar en la tarde",
  "items": [
    {
      "product_id": 1,
      "variant_id": 3,  // opcional si no tiene variantes
      "quantity": 2
    }
  ]
}</code></pre>
                    </div>
                </div>
            </div>

            <!-- Ver Orden -->
            <div class="app-card rounded-xl overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <h3 class="text-base font-semibold" style="color: var(--text-primary);">Consultar Orden</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-700">GET</span>
                    </div>
                    <p class="text-sm mb-3" style="color: var(--text-secondary);">Obtiene el estado y detalles de una orden. Requiere email por seguridad.</p>
                    
                    <div class="flex items-center gap-2 p-3 rounded-lg mb-4" style="background-color: var(--bg-secondary);">
                        <code class="text-sm font-mono flex-1 break-all" style="color: var(--accent-color);">
                            {{ url('/api/store/' . $client->slug . '/orders/{orderId}?email=cliente@email.com') }}
                        </code>
                        <button onclick="copyToClipboard('{{ url('/api/store/' . $client->slug . '/orders/{orderId}') }}')" 
                                class="p-2 rounded-lg transition-colors" style="color: var(--text-tertiary);"
                                onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" 
                                onmouseout="this.style.backgroundColor='transparent'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>

                    <p class="text-xs" style="color: var(--text-tertiary);">Retorna: información del pedido, items, dirección de envío y tracking.</p>
                </div>
            </div>

            <!-- Subir Comprobante -->
            <div class="app-card rounded-xl overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <h3 class="text-base font-semibold" style="color: var(--text-primary);">Subir Comprobante de Pago</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-blue-100 text-blue-700">POST</span>
                    </div>
                    <p class="text-sm mb-3" style="color: var(--text-secondary);">Permite al cliente subir el comprobante de transferencia.</p>
                    
                    <div class="flex items-center gap-2 p-3 rounded-lg mb-4" style="background-color: var(--bg-secondary);">
                        <code class="text-sm font-mono flex-1 break-all" style="color: var(--accent-color);">
                            {{ url('/api/store/' . $client->slug . '/orders/{orderId}/receipt') }}
                        </code>
                        <button onclick="copyToClipboard('{{ url('/api/store/' . $client->slug . '/orders/{orderId}/receipt') }}')" 
                                class="p-2 rounded-lg transition-colors" style="color: var(--text-tertiary);"
                                onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" 
                                onmouseout="this.style.backgroundColor='transparent'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <pre class="text-xs p-4 rounded-lg" style="background-color: var(--bg-secondary); color: var(--text-primary);"><code>// FormData multipart/form-data
{
  "email": "juan@email.com",           // required
  "receipt": [archivo],              // required (jpg, png, pdf)
  "transfer_date": "2026-03-25"      // opcional
}</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ejemplo de Flujo -->
        <div class="app-card rounded-xl p-6 mt-6" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); dark: linear-gradient(135deg, #451a03 0%, #78350f 100%);">
            <h3 class="text-lg font-semibold mb-4 text-amber-900 dark:text-amber-100">Flujo de compra recomendado</h3>
            <div class="overflow-x-auto">
                <pre class="text-sm p-4 rounded-lg bg-white/80 dark:bg-black/30 text-amber-900 dark:text-amber-100"><code>// 1. Mostrar catálogo
GET /api/store/{client}/catalog

// 2. Ver detalle del producto  
GET /api/store/{client}/product/{slug}

// 3. El carrito lo manejas en tu frontend (localStorage, Context, etc.)

// 4. Al finalizar, envías los items al backend
POST /api/store/{client}/orders
// → Recibes datos bancarios para transferencia

// 5. El cliente realiza la transferencia y sube comprobante (opcional)
POST /api/store/{client}/orders/{id}/receipt

// 6. Puedes consultar el estado de la orden
GET /api/store/{client}/orders/{id}?email=cliente@email.com</code></pre>
            </div>
        </div>
    </div>
    @endif

    <!-- Ejemplo de uso -->
    <div class="app-card rounded-xl p-6 mt-8">
        <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Ejemplo de uso con JavaScript</h3>
        <div class="overflow-x-auto">
            <pre class="text-sm p-4 rounded-lg" style="background-color: var(--bg-secondary); color: var(--text-primary);"><code>// Ejemplo de fetch para obtener datos
const fetchData = async () => {
  try {
    const response = await fetch('{{ $endpoints->first()['endpoint_url'] ?? url('/api/public/' . $client->slug) }}');
    const data = await response.json();
    console.log(data);
  } catch (error) {
    console.error('Error:', error);
  }
};

fetchData();</code></pre>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Mostrar notificación temporal
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 px-4 py-2 rounded-lg text-sm font-medium z-50';
        toast.style.cssText = 'background-color: var(--success-bg); color: var(--success-text); border: 1px solid var(--success-border);';
        toast.textContent = 'URL copiada al portapapeles';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    });
}
</script>
@endsection
