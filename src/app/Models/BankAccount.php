<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'bank_name',
        'account_type',
        'account_number',
        'account_holder',
        'rut',
        'email',
        'is_default',
        'active',
        'instructions',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'active' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();
        
        static::saved(function ($account) {
            if ($account->is_default) {
                // Asegurar que solo haya una cuenta por defecto por cliente
                static::where('client_id', $account->client_id)
                    ->where('id', '!=', $account->id)
                    ->update(['is_default' => false]);
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function getFormattedDetails(): string
    {
        return sprintf(
            "%s\n%s: %s\nTitular: %s%s%s",
            $this->bank_name,
            $this->account_type,
            $this->account_number,
            $this->account_holder,
            $this->rut ? "\nRUT: {$this->rut}" : "",
            $this->email ? "\nEmail: {$this->email}" : ""
        );
    }
}
