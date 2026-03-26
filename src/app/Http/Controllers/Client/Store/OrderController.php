<?php

namespace App\Http\Controllers\Client\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $client = $user->client;
        
        $query = $client->orders()->with('items')->latest();
        
        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }
        
        $orders = $query->paginate(20);
        
        // Estadísticas
        $stats = [
            'pending' => $client->orders()->where('status', 'pending')->count(),
            'payment_review' => $client->orders()->where('status', 'payment_review')->count(),
            'paid' => $client->orders()->whereIn('status', ['paid', 'processing'])->count(),
            'shipped' => $client->orders()->where('status', 'shipped')->count(),
            'total_sales' => $client->orders()
                ->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
                ->sum('total_amount'),
        ];
        
        return view('client.store.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $user = auth()->user();
        
        if ($order->client_id !== $user->client_id) {
            abort(403);
        }
        
        $order->load('items.product', 'items.variant', 'bankAccount');
        
        return view('client.store.orders.show', compact('order'));
    }

    public function confirmPayment(Request $request, Order $order)
    {
        $user = auth()->user();
        
        if ($order->client_id !== $user->client_id) {
            abort(403);
        }
        
        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Esta orden no puede ser confirmada');
        }
        
        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $order->markAsPaid($validated['notes'] ?? null);
        
        return back()->with('success', 'Pago confirmado correctamente');
    }

    public function markAsProcessing(Order $order)
    {
        $user = auth()->user();
        
        if ($order->client_id !== $user->client_id) {
            abort(403);
        }
        
        if ($order->status !== 'paid') {
            return back()->with('error', 'La orden debe estar pagada para marcarla en preparación');
        }
        
        $order->markAsProcessing();
        
        return back()->with('success', 'Orden marcada como "En preparación"');
    }

    public function markAsShipped(Request $request, Order $order)
    {
        $user = auth()->user();
        
        if ($order->client_id !== $user->client_id) {
            abort(403);
        }
        
        if (!in_array($order->status, ['paid', 'processing'])) {
            return back()->with('error', 'La orden debe estar pagada o en preparación para marcarla como enviada');
        }
        
        $validated = $request->validate([
            'tracking_number' => 'nullable|string|max:100',
            'shipping_carrier' => 'nullable|string|max:100',
        ]);
        
        $order->markAsShipped(
            $validated['tracking_number'] ?? null,
            $validated['shipping_carrier'] ?? null
        );
        
        return back()->with('success', 'Orden marcada como "Enviada"');
    }

    public function markAsDelivered(Order $order)
    {
        $user = auth()->user();
        
        if ($order->client_id !== $user->client_id) {
            abort(403);
        }
        
        if ($order->status !== 'shipped') {
            return back()->with('error', 'La orden debe estar enviada para marcarla como entregada');
        }
        
        $order->markAsDelivered();
        
        return back()->with('success', 'Orden marcada como "Entregada"');
    }

    public function cancel(Request $request, Order $order)
    {
        $user = auth()->user();
        
        if ($order->client_id !== $user->client_id) {
            abort(403);
        }
        
        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Esta orden no puede ser cancelada');
        }
        
        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);
        
        $order->cancel($validated['reason'] ?? 'Cancelada por el administrador');
        
        return back()->with('success', 'Orden cancelada correctamente');
    }

    public function downloadReceipt(Order $order)
    {
        $user = auth()->user();
        
        if ($order->client_id !== $user->client_id) {
            abort(403);
        }
        
        if (!$order->transfer_receipt_path || !Storage::disk('public')->exists($order->transfer_receipt_path)) {
            return back()->with('error', 'Comprobante no encontrado');
        }
        
        return Storage::disk('public')->download($order->transfer_receipt_path, 'comprobante-orden-' . $order->id . '.pdf');
    }
}
