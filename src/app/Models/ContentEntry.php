<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ContentEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'section_id',
        'client_id',
        'title',
        'slug',
        'status',
        'published_at',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'status' => 'string',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($entry) {
            if (empty($entry->slug) && !empty($entry->title)) {
                $entry->slug = Str::slug($entry->title);
            }
        });

        static::updating(function ($entry) {
            if ($entry->isDirty('title') && empty($entry->slug)) {
                $entry->slug = Str::slug($entry->title);
            }
        });
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function fieldValues(): HasMany
    {
        return $this->hasMany(ContentEntryValue::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getFieldValue(string $fieldSlug): mixed
    {
        $fieldValue = $this->fieldValues()
            ->whereHas('sectionField', function ($query) use ($fieldSlug) {
                $query->where('slug', $fieldSlug);
            })
            ->first();
        
        if (!$fieldValue) {
            return null;
        }
        
        $field = $fieldValue->sectionField;
        $value = $fieldValue->value;
        
        return match($field->type) {
            'json', 'repeater', 'gallery' => json_decode($value, true),
            'boolean' => (bool) $value,
            'number' => is_numeric($value) ? (float) $value : null,
            default => $value,
        };
    }

    public function setFieldValue(string $fieldSlug, mixed $value): void
    {
        $field = SectionField::where('section_id', $this->section_id)
            ->where('slug', $fieldSlug)
            ->first();
        
        if (!$field) {
            return;
        }
        
        $storedValue = match($field->type) {
            'json', 'repeater', 'gallery' => is_string($value) ? $value : json_encode($value),
            'boolean' => $value ? '1' : '0',
            default => (string) $value,
        };
        
        $this->fieldValues()->updateOrCreate(
            ['section_field_id' => $field->id],
            ['value' => $storedValue]
        );
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && 
               ($this->published_at === null || $this->published_at <= now());
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isArchived(): bool
    {
        return $this->status === 'archived';
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeForSection($query, int $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    public function toArrayWithFields(): array
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => $this->status,
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
        
        foreach ($this->section->fields as $field) {
            $data[$field->slug] = $this->getFieldValue($field->slug);
        }
        
        return $data;
    }
}
