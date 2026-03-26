@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold" style="color: var(--text-primary);">Mi Perfil</h1>
            <p class="text-sm" style="color: var(--text-secondary);">Gestiona tu información personal</p>
        </div>
    </div>

    <form action="{{ route('client.profile.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="app-card rounded-xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold" style="color: var(--text-primary);">Información Personal</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Tu nombre">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">
                        Correo electrónico <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="tu@email.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Password Section -->
        <div class="app-card rounded-xl p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold" style="color: var(--text-primary);">Cambiar Contraseña</h2>
                    <p class="text-sm" style="color: var(--text-tertiary);">Dejar en blanco si no deseas cambiar la contraseña</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Contraseña actual</label>
                    <input type="password" name="current_password" id="current_password"
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="••••••••">
                    @error('current_password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div></div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Nueva contraseña</label>
                    <input type="password" name="password" id="password"
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Mínimo 8 caracteres">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Confirmar nueva contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="app-input block w-full px-4 py-3 rounded-lg text-sm focus:outline-none"
                           placeholder="Repite la contraseña">
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
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
