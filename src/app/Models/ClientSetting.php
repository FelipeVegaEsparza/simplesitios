<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'key',
        'value',
        'type',
        'group',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function getValue(): mixed
    {
        return match($this->type) {
            'json' => json_decode($this->value, true),
            'boolean' => (bool) $this->value,
            'number' => is_numeric($this->value) ? (float) $this->value : 0,
            default => $this->value,
        };
    }

    public function setValue(mixed $value): void
    {
        $this->value = match($this->type) {
            'json' => is_string($value) ? $value : json_encode($value),
            'boolean' => $value ? '1' : '0',
            'number' => (string) $value,
            default => (string) $value,
        };
    }
}
