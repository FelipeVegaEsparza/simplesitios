<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isSuperAdmin();
    }

    public function rules(): array
    {
        return [
            // Datos del cliente
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:clients,slug'],
            'domain' => ['nullable', 'string', 'max:255', 'unique:clients,domain'],
            'description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended'])],
            'settings' => ['nullable', 'array'],
            
            // Datos del usuario administrador (opcional)
            'create_user' => ['boolean'],
            'user_name' => ['required_if:create_user,true', 'nullable', 'string', 'max:255'],
            'user_email' => ['required_if:create_user,true', 'nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'user_password' => ['required_if:create_user,true', 'nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre del cliente',
            'slug' => 'identificador',
            'domain' => 'dominio',
            'description' => 'descripción',
            'logo' => 'logo',
            'status' => 'estado',
            'settings' => 'configuración',
            'user_name' => 'nombre del usuario',
            'user_email' => 'correo electrónico del usuario',
            'user_password' => 'contraseña del usuario',
        ];
    }
}
