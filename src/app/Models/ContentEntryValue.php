<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentEntryValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_entry_id',
        'section_field_id',
        'value',
    ];

    public function contentEntry(): BelongsTo
    {
        return $this->belongsTo(ContentEntry::class);
    }

    public function sectionField(): BelongsTo
    {
        return $this->belongsTo(SectionField::class);
    }

    public function getTypedValue(): mixed
    {
        $field = $this->sectionField;
        $value = $this->value;
        
        if ($value === null) {
            return null;
        }
        
        return match($field->type) {
            'json', 'repeater', 'gallery' => json_decode($value, true),
            'boolean' => (bool) $value,
            'number' => is_numeric($value) ? (float) $value : null,
            default => $value,
        };
    }
}
