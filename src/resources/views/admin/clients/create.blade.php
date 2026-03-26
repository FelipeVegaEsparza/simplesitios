@extends('layouts.app')

@section('title', 'Nuevo Cliente')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.clients.index') }}" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" style="color: var(--text-secondary);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Nuevo Cliente</h1>
            <p class="text-sm" style="color: var(--text-secondary);">Crea un nuevo cliente y su usuario administrador</p>
        </div>
    </div>

    <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Información del Cliente -->
        <div class="app-card rounded-xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold" style="color: var(--text-primary);">Información del Cliente</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Ej: Tech Solutions">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Identificador (slug) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Ej: tech-solutions">
                    <p class="mt-1 text-xs" style="color: var(--text-tertiary);">Usado en las URLs de la API</p>
                    @error('slug')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Domain -->
                <div>
                    <label for="domain" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Dominio
                    </label>
                    <input type="text" name="domain" id="domain" value="{{ old('domain') }}"
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Ej: techsolutions.com">
                    @error('domain')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Estado <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                            class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none">
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspendido</option>
                    </select>
                    @error('status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Descripción
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none resize-none"
                              placeholder="Descripción del cliente...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Logo -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Logo
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-lg" style="border-color: var(--border-color);">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12" style="color: var(--text-tertiary);" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm justify-center" style="color: var(--text-secondary);">
                                <label for="logo" class="relative cursor-pointer rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none">
                                    <span>Subir archivo</span>
                                    <input id="logo" name="logo" type="file" accept="image/*" class="sr-only">
                                </label>
                                <p class="pl-1">o arrastra y suelta</p>
                            </div>
                            <p class="text-xs" style="color: var(--text-tertiary);">PNG, JPG, GIF hasta 2MB</p>
                        </div>
                    </div>
                    @error('logo')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Usuario Administrador -->
        <div class="app-card rounded-xl p-6" x-data="{ createUser: {{ old('create_user') ? 'true' : 'false' }} }">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-semibold" style="color: var(--text-primary);">Usuario Administrador</h2>
                    <p class="text-sm" style="color: var(--text-tertiary);">Crea un usuario para que el cliente acceda al panel</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="create_user" value="1" x-model="createUser" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                </label>
            </div>

            <div x-show="createUser" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Name -->
                <div>
                    <label for="user_name" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="user_name" id="user_name" value="{{ old('user_name') }}"
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Nombre del administrador">
                    @error('user_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- User Email -->
                <div>
                    <label for="user_email" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Correo electrónico <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="user_email" id="user_email" value="{{ old('user_email') }}"
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="admin@ejemplo.com">
                    @error('user_email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- User Password -->
                <div>
                    <label for="user_password" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Contraseña <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="user_password" id="user_password"
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Mínimo 8 caracteres">
                    @error('user_password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- User Password Confirmation -->
                <div>
                    <label for="user_password_confirmation" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Confirmar contraseña <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="user_password_confirmation" id="user_password_confirmation"
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Repite la contraseña">
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.clients.index') }}" class="px-6 py-3 rounded-lg font-medium text-sm transition-colors" style="color: var(--text-secondary);">
                Cancelar
            </a>
            <button type="submit" class="btn-press inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm transition-all shadow-lg shadow-primary-600/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Crear Cliente
            </button>
        </div>
    </form>
</div>
@endsection
