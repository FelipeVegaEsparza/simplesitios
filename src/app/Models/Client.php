<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'logo',
        'description',
        'status',
        'store_enabled',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'store_enabled' => 'boolean',
    ];



    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }

    public function contentEntries(): HasMany
    {
        return $this->hasMany(ContentEntry::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(ClientSetting::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getSetting(string $key, mixed $default = null): mixed
    {
        $setting = $this->settings()->where('key', $key)->first();
        return $setting ? $setting->getValue() : $default;
    }

    public function setSetting(string $key, mixed $value, string $type = 'string', string $group = 'general'): void
    {
        $this->settings()->updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'group' => $group]
        );
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function productCategories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class);
    }

    public function isStoreEnabled(): bool
    {
        return $this->store_enabled === true;
    }
}
