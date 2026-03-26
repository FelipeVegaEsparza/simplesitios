<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'product_name',
        'variant_name',
        'sku',
        'price',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'price' => 'integer',
        'quantity' => 'integer',
        'subtotal' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function formatPrice(): string
    {
        return '$' . number_format($this->price, 0, ',', '.');
    }

    public function formatSubtotal(): string
    {
        return '$' . number_format($this->subtotal, 0, ',', '.');
    }
}
