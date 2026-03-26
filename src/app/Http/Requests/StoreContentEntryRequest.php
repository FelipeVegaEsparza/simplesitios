<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Section;
use App\Models\SectionField;

class StoreContentEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        $section = $this->route('section');
        if (!$section) {
            return false;
        }
        return $this->user()->canManageSection($section->id);
    }

    public function rules(): array
    {
        $section = $this->route('section');
        $rules = [
            'title' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['integer', 'min:0'],
        ];
        
        // Agregar reglas para campos dinámicos
        foreach ($section->fields as $field) {
            $fieldRules = $field->getValidationRulesArray();
            $rules["fields.{$field->slug}"] = $fieldRules;
        }
        
        return $rules;
    }

    public function attributes(): array
    {
        $section = $this->route('section');
        $attributes = [
            'title' => 'título',
            'slug' => 'identificador',
            'status' => 'estado',
            'published_at' => 'fecha de publicación',
            'sort_order' => 'orden',
        ];
        
        foreach ($section->fields as $field) {
            $attributes["fields.{$field->slug}"] = $field->label;
        }
        
        return $attributes;
    }

    protected function prepareForValidation(): void
    {
        // Generar slug automáticamente si no se proporciona
        if (!$this->has('slug') && $this->has('title')) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->input('title')),
            ]);
        }
    }
}
