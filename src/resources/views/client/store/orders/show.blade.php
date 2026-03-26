@extends('layouts.app')

@section('title', 'Orden ' . $order->getOrderNumber())

@section('content')
<div class="page-content p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('client.store.orders.index') }}" class="p-2 rounded-lg transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-bold" style="color: var(--text-primary);">{{ $order->getOrderNumber() }}</h2>
                    @php $badge = $order->getStatusBadge(); @endphp
                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-{{ $badge['color'] }}-100 text-{{ $badge['color'] }}-800 dark:bg-{{ $badge['color'] }}-900 dark:text-{{ $badge['color'] }}-200">
                        {{ $badge['label'] }}
                    </span>
                </div>
                <p class="text-sm mt-1" style="color: var(--text-secondary);">Creada el {{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
        
        @if($order->canBeCancelled())
            <form method="POST" action="{{ route('client.store.orders.cancel', $order) }}" onsubmit="return confirm('¿Estás seguro de cancelar esta orden? El stock será devuelto.')">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-red-600 border border-red-200 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancelar Orden
                </button>
            </form>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Items -->
            <div class="app-card rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Productos</h3>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-4 p-4 rounded-lg" style="background-color: var(--bg-secondary);">
                        <div class="w-16 h-16 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: var(--bg-tertiary);">
                            @if($item->product && $item->product->images && count($item->product->images) > 0)
                                <img src="{{ Storage::disk('public')->url($item->product->images[0]) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <svg class="w-6 h-6" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium truncate" style="color: var(--text-primary);">{{ $item->product_name }}</h4>
                            @if($item->variant_name)
                                <p class="text-sm" style="color: var(--text-secondary);">{{ $item->variant_name }}</p>
                            @endif
                            <p class="text-sm mt-1" style="color: var(--text-tertiary);">{{ $item->formatPrice() }} x {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold" style="color: var(--text-primary);">{{ $item->formatSubtotal() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Totals -->
                <div class="mt-6 pt-4 space-y-2" style="border-top: 1px solid var(--border-color);">
                    <div class="flex justify-between text-sm" style="color: var(--text-secondary);">
                        <span>Subtotal</span>
                        <span>{{ $order->formatSubtotal() }}</span>
                    </div>
                    <div class="flex justify-between text-sm" style="color: var(--text-secondary);">
                        <span>Envío</span>
                        <span>{{ $order->formatShipping() }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-2" style="border-top: 1px solid var(--border-color); color: var(--text-primary);">
                        <span>Total</span>
                        <span class="text-primary-600">{{ $order->formatTotal() }}</span>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="app-card rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Información del Cliente</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm" style="color: var(--text-tertiary);">Nombre</p>
                        <p class="font-medium" style="color: var(--text-primary);">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm" style="color: var(--text-tertiary);">Email</p>
                        <p class="font-medium" style="color: var(--text-primary);">{{ $order->customer_email }}</p>
                    </div>
                    <div>
                        <p class="text-sm" style="color: var(--text-tertiary);">Teléfono</p>
                        <p class="font-medium" style="color: var(--text-primary);">{{ $order->customer_phone }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-sm" style="color: var(--text-tertiary);">Dirección de envío</p>
                        <p class="font-medium" style="color: var(--text-primary);">{{ $order->shipping_address }}</p>
                        @if($order->shipping_city || $order->shipping_region)
                            <p class="text-sm mt-1" style="color: var(--text-secondary);">
                                {{ $order->shipping_city }}{{ $order->shipping_city && $order->shipping_region ? ', ' : '' }}{{ $order->shipping_region }}
                                @if($order->shipping_zip) ({{ $order->shipping_zip }}) @endif
                            </p>
                        @endif
                    </div>
                    @if($order->notes)
                        <div class="sm:col-span-2">
                            <p class="text-sm" style="color: var(--text-tertiary);">Notas del cliente</p>
                            <p class="text-sm mt-1" style="color: var(--text-secondary);">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Payment Info -->
            <div class="app-card rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Información de Pago</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm" style="color: var(--text-tertiary);">Método</p>
                        <p class="font-medium" style="color: var(--text-primary);">Transferencia bancaria</p>
                    </div>
                    
                    @if($order->bankAccount)
                        <div>
                            <p class="text-sm" style="color: var(--text-tertiary);">Cuenta seleccionada</p>
                            <div class="p-3 rounded-lg mt-1" style="background-color: var(--bg-secondary);">
                                <p class="font-medium" style="color: var(--text-primary);">{{ $order->bankAccount->bank_name }}</p>
                                <p class="text-sm" style="color: var(--text-secondary);">{{ $order->bankAccount->account_type }}</p>
                                <p class="text-sm font-mono mt-1" style="color: var(--text-tertiary);">{{ $order->bankAccount->account_number }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($order->transfer_receipt_path)
                        <div>
                            <p class="text-sm mb-2" style="color: var(--text-tertiary);">Comprobante</p>
                            <a href="{{ route('client.store.orders.download-receipt', $order) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg transition-colors" style="background-color: var(--bg-tertiary); color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--border-color)'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Descargar comprobante
                            </a>
                            @if($order->transfer_date)
                                <p class="text-xs mt-2" style="color: var(--text-tertiary);">Fecha transferencia: {{ \Carbon\Carbon::parse($order->transfer_date)->format('d/m/Y') }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="app-card rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Acciones</h3>
                
                <div class="space-y-3">
                    @if($order->status === 'pending')
                        <form method="POST" action="{{ route('client.store.orders.confirm-payment', $order) }}">
                            @csrf
                            <button type="submit" class="w-full py-3 px-4 rounded-lg bg-green-600 text-white font-medium transition-colors hover:bg-green-700 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Confirmar Pago Recibido
                            </button>
                            <p class="text-xs text-center mt-2" style="color: var(--text-tertiary);">Marca cuando veas el pago en tu banco</p>
                        </form>
                    @endif
                    
                    @if($order->status === 'paid')
                        <form method="POST" action="{{ route('client.store.orders.mark-processing', $order) }}">
                            @csrf
                            <button type="submit" class="w-full py-3 px-4 rounded-lg bg-blue-600 text-white font-medium transition-colors hover:bg-blue-700 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                Marcar como "En Preparación"
                            </button>
                        </form>
                    @endif
                    
                    @if(in_array($order->status, ['paid', 'processing']))
                        <form method="POST" action="{{ route('client.store.orders.mark-shipped', $order) }}" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-sm mb-1" style="color: var(--text-secondary);">Número de seguimiento</label>
                                <input type="text" name="tracking_number" class="app-input w-full px-3 py-2 rounded-lg text-sm" placeholder="Opcional">
                            </div>
                            <div>
                                <label class="block text-sm mb-1" style="color: var(--text-secondary);">Transporte</label>
                                <input type="text" name="shipping_carrier" class="app-input w-full px-3 py-2 rounded-lg text-sm" placeholder="Ej: Chilexpress, Starken">
                            </div>
                            <button type="submit" class="w-full py-3 px-4 rounded-lg bg-indigo-600 text-white font-medium transition-colors hover:bg-indigo-700 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                </svg>
                                Marcar como "Enviado"
                            </button>
                        </form>
                    @endif
                    
                    @if($order->status === 'shipped')
                        <form method="POST" action="{{ route('client.store.orders.mark-delivered', $order) }}">
                            @csrf
                            <button type="submit" class="w-full py-3 px-4 rounded-lg bg-green-600 text-white font-medium transition-colors hover:bg-green-700 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Marcar como "Entregado"
                            </button>
                        </form>
                    @endif
                    
                    @if($order->tracking_number)
                        <div class="p-3 rounded-lg" style="background-color: var(--bg-secondary);">
                            <p class="text-sm" style="color: var(--text-tertiary);">Tracking</p>
                            <p class="font-mono font-medium" style="color: var(--text-primary);">{{ $order->tracking_number }}</p>
                            @if($order->shipping_carrier)
                                <p class="text-sm" style="color: var(--text-secondary);">{{ $order->shipping_carrier }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
