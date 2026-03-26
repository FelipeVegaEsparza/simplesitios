<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_name',
        'sku',
        'price_override',
        'stock',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'price_override' => 'integer',
        'stock' => 'integer',
        'sort_order' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();
        
        static::saved(function ($variant) {
            $variant->product->updateTotalStock();
        });
        
        static::deleted(function ($variant) {
            $variant->product->updateTotalStock();
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getFinalPrice(): int
    {
        return $this->price_override !== null ? $this->price_override : $this->product->base_price;
    }

    public function formatPrice(): string
    {
        return '$' . number_format($this->getFinalPrice(), 0, ',', '.');
    }

    public function isInStock(): bool
    {
        return !$this->product->track_stock || $this->stock > 0;
    }
}
