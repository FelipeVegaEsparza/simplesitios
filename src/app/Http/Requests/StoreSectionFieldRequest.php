<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSectionFieldRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            'section_id' => ['required', 'exists:sections,id'],
            'name' => ['required', 'string', 'max:255'],
            'label' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in([
                'text', 'textarea', 'richtext', 'number', 'boolean',
                'date', 'datetime', 'email', 'url', 'image', 'file',
                'select', 'json', 'repeater', 'gallery'
            ])],
            'is_required' => ['boolean'],
            'default_value' => ['nullable', 'string'],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'help_text' => ['nullable', 'string'],
            'sort_order' => ['integer', 'min:0'],
            'is_visible' => ['boolean'],
            'show_in_list' => ['boolean'],
            'show_in_api' => ['boolean'],
            'column_width' => ['required', Rule::in(['full', 'half', 'third'])],
            'options' => ['nullable', 'array'],
            'validation_rules' => ['nullable', 'array'],
        ];
    }

    public function attributes(): array
    {
        return [
            'section_id' => 'sección',
            'name' => 'nombre interno',
            'label' => 'etiqueta visible',
            'slug' => 'identificador',
            'type' => 'tipo',
            'is_required' => 'requerido',
            'default_value' => 'valor por defecto',
            'placeholder' => 'placeholder',
            'help_text' => 'texto de ayuda',
            'sort_order' => 'orden',
            'is_visible' => 'visible',
            'show_in_list' => 'mostrar en listado',
            'show_in_api' => 'mostrar en API',
            'column_width' => 'ancho de columna',
            'options' => 'opciones',
            'validation_rules' => 'reglas de validación',
        ];
    }
}
