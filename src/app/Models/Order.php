<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'bank_account_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_region',
        'shipping_zip',
        'notes',
        'subtotal',
        'shipping_cost',
        'total_amount',
        'status',
        'payment_method',
        'payment_status',
        'transfer_receipt_path',
        'transfer_date',
        'payment_notes',
        'tracking_number',
        'shipping_carrier',
        'shipped_at',
        'delivered_at',
        'expires_at',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'shipping_cost' => 'integer',
        'total_amount' => 'integer',
        'transfer_date' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeExpiring($query)
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->where('status', 'pending');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return in_array($this->status, ['paid', 'processing', 'shipped', 'delivered']);
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'payment_review']);
    }

    public function formatTotal(): string
    {
        return '$' . number_format($this->total_amount, 0, ',', '.');
    }

    public function formatSubtotal(): string
    {
        return '$' . number_format($this->subtotal, 0, ',', '.');
    }

    public function formatShipping(): string
    {
        return '$' . number_format($this->shipping_cost, 0, ',', '.');
    }

    public function getOrderNumber(): string
    {
        return '#' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function cancel(?string $reason = null): void
    {
        if (!$this->canBeCancelled()) {
            return;
        }

        // Devolver stock
        foreach ($this->items as $item) {
            if ($item->variant_id) {
                $variant = ProductVariant::find($item->variant_id);
                if ($variant) {
                    $variant->stock += $item->quantity;
                    $variant->save();
                }
            } else {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->total_stock += $item->quantity;
                    $product->save();
                    $product->updateStatusBasedOnStock();
                }
            }
        }

        $this->status = 'cancelled';
        $this->payment_notes = $reason ?? 'Orden cancelada automáticamente por expiración';
        $this->save();
    }

    public function markAsPaid(?string $notes = null): void
    {
        $this->payment_status = 'confirmed';
        $this->status = 'paid';
        $this->payment_notes = $notes;
        $this->save();
    }

    public function markAsProcessing(): void
    {
        $this->status = 'processing';
        $this->save();
    }

    public function markAsShipped(?string $trackingNumber = null, ?string $carrier = null): void
    {
        $this->status = 'shipped';
        $this->tracking_number = $trackingNumber;
        $this->shipping_carrier = $carrier;
        $this->shipped_at = now();
        $this->save();
    }

    public function markAsDelivered(): void
    {
        $this->status = 'delivered';
        $this->delivered_at = now();
        $this->save();
    }

    public function getStatusBadge(): array
    {
        return match($this->status) {
            'pending' => ['label' => 'Pendiente de pago', 'color' => 'yellow'],
            'payment_review' => ['label' => 'En revisión', 'color' => 'orange'],
            'paid' => ['label' => 'Pagado', 'color' => 'green'],
            'processing' => ['label' => 'En preparación', 'color' => 'blue'],
            'shipped' => ['label' => 'Enviado', 'color' => 'indigo'],
            'delivered' => ['label' => 'Entregado', 'color' => 'green'],
            'cancelled' => ['label' => 'Cancelado', 'color' => 'red'],
            'refunded' => ['label' => 'Reembolsado', 'color' => 'gray'],
            default => ['label' => $this->status, 'color' => 'gray'],
        };
    }
}
