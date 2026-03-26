@extends('layouts.app')

@section('title', 'Órdenes')

@section('content')
<div class="page-content p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Órdenes</h2>
            <p class="text-sm mt-1" style="color: var(--text-secondary);">Gestiona los pedidos de tu tienda</p>
        </div>
        <a href="{{ route('client.store.bank-accounts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border transition-colors" style="border-color: var(--border-color); color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            Cuentas Bancarias
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="app-card rounded-xl p-4">
            <p class="text-sm" style="color: var(--text-tertiary);">Pendientes</p>
            <p class="text-2xl font-bold mt-1" style="color: var(--text-primary);">{{ $stats['pending'] }}</p>
        </div>
        <div class="app-card rounded-xl p-4">
            <p class="text-sm" style="color: var(--text-tertiary);">En Revisión</p>
            <p class="text-2xl font-bold mt-1 text-orange-600">{{ $stats['payment_review'] }}</p>
        </div>
        <div class="app-card rounded-xl p-4">
            <p class="text-sm" style="color: var(--text-tertiary);">Por Preparar</p>
            <p class="text-2xl font-bold mt-1 text-blue-600">{{ $stats['paid'] }}</p>
        </div>
        <div class="app-card rounded-xl p-4">
            <p class="text-sm" style="color: var(--text-tertiary);">Ventas Totales</p>
            <p class="text-2xl font-bold mt-1 text-green-600">${{ number_format($stats['total_sales'], 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="app-card rounded-xl p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, email o #orden..." class="app-input w-full px-4 py-2 rounded-lg">
            </div>
            <div class="sm:w-48">
                <select name="status" class="app-input w-full px-4 py-2 rounded-lg" onchange="this.form.submit()">
                    <option value="">Todos los estados</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente de pago</option>
                    <option value="payment_review" {{ request('status') === 'payment_review' ? 'selected' : '' }}>En revisión</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Pagado</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>En preparación</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Enviado</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Entregado</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            @if(request('search') || request('status'))
                <a href="{{ route('client.store.orders.index') }}" class="px-4 py-2 rounded-lg text-center transition-colors" style="color: var(--text-secondary); background-color: var(--bg-tertiary);">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    @if($orders->count() > 0)
        <div class="app-card rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full app-table">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left">Orden</th>
                            <th class="px-4 py-3 text-left">Cliente</th>
                            <th class="px-4 py-3 text-left">Fecha</th>
                            <th class="px-4 py-3 text-center">Items</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-slate-800/50">
                            <td class="px-4 py-3">
                                <span class="font-mono font-medium" style="color: var(--text-primary);">{{ $order->getOrderNumber() }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div>
                                    <p class="font-medium" style="color: var(--text-primary);">{{ $order->customer_name }}</p>
                                    <p class="text-sm" style="color: var(--text-tertiary);">{{ $order->customer_email }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm" style="color: var(--text-secondary);">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 text-xs rounded-full" style="background-color: var(--bg-tertiary); color: var(--text-secondary);">{{ $order->items->count() }}</span>
                            </td>
                            <td class="px-4 py-3 text-right font-medium" style="color: var(--text-primary);">{{ $order->formatTotal() }}</td>
                            <td class="px-4 py-3 text-center">
                                @php $badge = $order->getStatusBadge(); @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full" 
                                    style="background-color: var(--{{ $badge['color'] }}-100); color: var(--{{ $badge['color'] }}-800);"
                                    class="bg-{{ $badge['color'] }}-100 text-{{ $badge['color'] }}-800 dark:bg-{{ $badge['color'] }}-900 dark:text-{{ $badge['color'] }}-200">
                                    {{ $badge['label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('client.store.orders.show', $order) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm transition-colors" style="color: var(--accent-color); background-color: var(--bg-tertiary);" onmouseover="this.style.backgroundColor='var(--border-color)'">
                                    Ver
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @else
        <div class="app-card rounded-xl p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center" style="background-color: var(--bg-tertiary);">
                <svg class="w-8 h-8" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium" style="color: var(--text-primary);">No hay órdenes</h3>
            <p class="text-sm mt-1" style="color: var(--text-secondary);">Las órdenes aparecerán aquí cuando los clientes realicen compras</p>
        </div>
    @endif
</div>
@endsection
