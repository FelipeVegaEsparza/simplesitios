<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        $clientId = $this->input('client_id');
        
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('sections')->where(function ($query) use ($clientId) {
                    return $query->where('client_id', $clientId);
                }),
            ],
            'description' => ['nullable', 'string'],
            'image_id' => ['nullable', 'exists:media,id'],
            'type' => ['required', Rule::in(['single', 'collection'])],
            'sort_order' => ['integer', 'min:0'],
            'is_visible' => ['boolean'],
            'is_public_endpoint' => ['boolean'],
            'endpoint_slug' => ['nullable', 'string', 'max:255'],
            'config' => ['nullable', 'array'],
        ];
    }

    public function attributes(): array
    {
        return [
            'client_id' => 'cliente',
            'name' => 'nombre',
            'slug' => 'identificador',
            'description' => 'descripción',
            'type' => 'tipo',
            'sort_order' => 'orden',
            'is_visible' => 'visible',
            'is_public_endpoint' => 'endpoint público',
            'endpoint_slug' => 'slug del endpoint',
            'config' => 'configuración',
        ];
    }
}
