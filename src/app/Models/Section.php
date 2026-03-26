<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Section extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'name',
        'slug',
        'description',
        'image_id',
        'type',
        'sort_order',
        'is_visible',
        'is_public_endpoint',
        'endpoint_slug',
        'config',
    ];

    protected $casts = [
        'type' => 'string',
        'is_visible' => 'boolean',
        'is_public_endpoint' => 'boolean',
        'config' => 'array',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function gallery(): BelongsToMany
    {
        return $this->belongsToMany(Media::class, 'section_galleries')
            ->withPivot('sort_order')
            ->orderBy('section_galleries.sort_order');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(SectionField::class)->orderBy('sort_order');
    }

    public function contentEntries(): HasMany
    {
        return $this->hasMany(ContentEntry::class);
    }

    public function isSingle(): bool
    {
        return $this->type === 'single';
    }

    public function isCollection(): bool
    {
        return $this->type === 'collection';
    }

    public function getSingleEntry(): ?ContentEntry
    {
        if (!$this->isSingle()) {
            return null;
        }
        
        return $this->contentEntries()->first();
    }

    public function getOrCreateSingleEntry(int $userId): ContentEntry
    {
        $entry = $this->getSingleEntry();
        
        if (!$entry) {
            $entry = $this->contentEntries()->create([
                'client_id' => $this->client_id,
                'status' => 'published',
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        }
        
        return $entry;
    }

    public function getApiSlug(): string
    {
        return $this->endpoint_slug ?? $this->slug;
    }
}
