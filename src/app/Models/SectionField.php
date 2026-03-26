<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SectionField extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'name',
        'label',
        'slug',
        'type',
        'is_required',
        'default_value',
        'placeholder',
        'help_text',
        'sort_order',
        'is_visible',
        'show_in_list',
        'show_in_api',
        'column_width',
        'options',
        'validation_rules',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_visible' => 'boolean',
        'show_in_list' => 'boolean',
        'show_in_api' => 'boolean',
        'options' => 'array',
        'validation_rules' => 'array',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(ContentEntryValue::class);
    }

    public function isFileType(): bool
    {
        return in_array($this->type, ['image', 'file']);
    }

    public function isSelectableType(): bool
    {
        return $this->type === 'select';
    }

    public function getValidationRulesArray(): array
    {
        $rules = [];
        
        if ($this->is_required) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }
        
        $rules[] = match($this->type) {
            'text', 'textarea', 'richtext' => 'string',
            'number' => 'numeric',
            'boolean' => 'boolean',
            'date' => 'date',
            'datetime' => 'date',
            'email' => 'email',
            'url' => 'url',
            'image', 'file' => 'file',
            'select' => 'string',
            'json' => 'json',
            default => 'string',
        };
        
        if ($this->validation_rules) {
            $rules = array_merge($rules, $this->validation_rules);
        }
        
        return $rules;
    }

    public function getInputType(): string
    {
        return match($this->type) {
            'text' => 'text',
            'textarea' => 'textarea',
            'richtext' => 'richtext',
            'number' => 'number',
            'boolean' => 'checkbox',
            'date' => 'date',
            'datetime' => 'datetime-local',
            'email' => 'email',
            'url' => 'url',
            'image' => 'file',
            'file' => 'file',
            'select' => 'select',
            'json' => 'textarea',
            'repeater' => 'repeater',
            'gallery' => 'gallery',
            default => 'text',
        };
    }
}
