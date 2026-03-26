<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'category_id',
        'name',
        'slug',
        'description',
        'base_price',
        'sku',
        'images',
        'has_variants',
        'total_stock',
        'track_stock',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'base_price' => 'integer',
        'has_variants' => 'boolean',
        'track_stock' => 'boolean',
        'total_stock' => 'integer',
        'sort_order' => 'integer',
        'images' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();
        
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
        
        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
        
        static::saved(function ($product) {
            if ($product->has_variants) {
                $product->updateTotalStock();
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function updateTotalStock(): void
    {
        if ($this->has_variants) {
            $this->total_stock = $this->variants()->sum('stock');
            $this->saveQuietly();
        }
        
        $this->updateStatusBasedOnStock();
    }

    public function updateStatusBasedOnStock(): void
    {
        if ($this->track_stock && $this->total_stock <= 0) {
            $this->status = 'out_of_stock';
            $this->saveQuietly();
        } elseif ($this->status === 'out_of_stock' && $this->total_stock > 0) {
            $this->status = 'active';
            $this->saveQuietly();
        }
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeInStock($query)
    {
        return $query->where(function ($q) {
            $q->where('track_stock', false)
              ->orWhere('total_stock', '>', 0);
        });
    }

    public function getFinalPrice(?int $variantId = null): int
    {
        if ($variantId && $this->has_variants) {
            $variant = $this->variants()->find($variantId);
            return $variant && $variant->price_override !== null 
                ? $variant->price_override 
                : $this->base_price;
        }
        return $this->base_price;
    }

    public function getStock(?int $variantId = null): int
    {
        if ($this->has_variants && $variantId) {
            $variant = $this->variants()->find($variantId);
            return $variant ? $variant->stock : 0;
        }
        return $this->total_stock;
    }

    public function formatPrice(?int $variantId = null): string
    {
        return '$' . number_format($this->getFinalPrice($variantId), 0, ',', '.');
    }

    public function formatBasePrice(): string
    {
        return '$' . number_format($this->base_price, 0, ',', '.');
    }
}
