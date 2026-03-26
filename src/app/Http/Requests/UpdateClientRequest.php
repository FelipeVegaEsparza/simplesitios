<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        $clientId = $this->route('client')?->id ?? $this->route('client');
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('clients')->ignore($clientId)],
            'domain' => ['nullable', 'string', 'max:255', Rule::unique('clients')->ignore($clientId)],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended'])],
            'store_enabled' => ['boolean'],
            'settings' => ['nullable', 'array'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'slug' => 'identificador',
            'domain' => 'dominio',
            'description' => 'descripción',
            'logo' => 'logo',
            'status' => 'estado',
            'store_enabled' => 'tienda online',
            'settings' => 'configuración',
        ];
    }
}
