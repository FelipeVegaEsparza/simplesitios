<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'client_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function createdEntries(): HasMany
    {
        return $this->hasMany(ContentEntry::class, 'created_by');
    }

    public function updatedEntries(): HasMany
    {
        return $this->hasMany(ContentEntry::class, 'updated_by');
    }

    public function uploadedMedia(): HasMany
    {
        return $this->hasMany(Media::class, 'uploaded_by');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function hasAccessToClient(int $clientId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        return $this->client_id === $clientId;
    }

    public function canManageSection(int $sectionId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        $section = Section::find($sectionId);
        
        if (!$section) {
            return false;
        }
        
        return $this->client_id === $section->client_id;
    }

    public function getRedirectRoute(): string
    {
        if ($this->isSuperAdmin()) {
            return route('admin.dashboard');
        }
        
        return route('client.dashboard');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSuperAdmins($query)
    {
        return $query->where('role', 'superadmin');
    }

    public function scopeClients($query)
    {
        return $query->where('role', 'client');
    }

    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }
}
