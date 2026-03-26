@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.users.index') }}" class="p-2 rounded-lg transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Editar Usuario</h1>
            <p class="text-sm" style="color: var(--text-secondary);">{{ $user->name }}</p>
        </div>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="app-card rounded-xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold" style="color: var(--text-primary);">Información del Usuario</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Nombre completo">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Correo electrónico <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="usuario@ejemplo.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Nueva contraseña
                        <span class="text-xs font-normal" style="color: var(--text-tertiary);">(dejar en blanco para no cambiar)</span>
                    </label>
                    <input type="password" name="password" id="password"
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Mínimo 8 caracteres">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Confirmar nueva contraseña
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Repite la contraseña">
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Rol <span class="text-red-500">*</span>
                    </label>
                    <select name="role" id="role" required onchange="document.getElementById('client_field').classList.toggle('hidden', this.value === 'superadmin')"
                            class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none">
                        <option value="client" {{ old('role', $user->role) === 'client' ? 'selected' : '' }}>Cliente</option>
                        <option value="superadmin" {{ old('role', $user->role) === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                    </select>
                    @error('role')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Client -->
                <div id="client_field" class="{{ old('role', $user->role) === 'superadmin' ? 'hidden' : '' }}">
                    <label for="client_id" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Cliente asignado
                    </label>
                    <select name="client_id" id="client_id"
                            class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none">
                        <option value="">Seleccionar cliente...</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $user->client_id) == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active -->
                <div class="md:col-span-2 flex items-center gap-3 pt-4" style="border-top: 1px solid var(--border-color);">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                    <label for="is_active" class="text-sm" style="color: var(--text-secondary);">Usuario activo</label>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-lg font-medium text-sm transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                Cancelar
            </a>
            <button type="submit" class="btn-press inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm transition-all shadow-lg shadow-primary-600/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
