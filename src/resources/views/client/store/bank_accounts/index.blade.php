@extends('layouts.app')

@section('title', 'Cuentas Bancarias')

@section('content')
<div class="page-content p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Cuentas Bancarias</h2>
            <p class="text-sm mt-1" style="color: var(--text-secondary);">Configura las cuentas donde recibirás los pagos por transferencia</p>
        </div>
        <a href="{{ route('client.store.bank-accounts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 text-white transition-colors hover:bg-primary-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva Cuenta
        </a>
    </div>

    <!-- Accounts List -->
    @if($accounts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($accounts as $account)
            <div class="app-card rounded-xl p-6 {{ $account->is_default ? 'ring-2 ring-primary-500' : '' }}">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-primary-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold" style="color: var(--text-primary);">{{ $account->bank_name }}</h3>
                            <p class="text-sm" style="color: var(--text-secondary);">{{ $account->account_type }}</p>
                            @if($account->is_default)
                                <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded-full bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200">Predeterminada</span>
                            @endif
                            @if(!$account->active)
                                <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Inactiva</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-1">
                        @if(!$account->is_default && $account->active)
                            <form method="POST" action="{{ route('client.store.bank-accounts.set-default', $account) }}" class="inline">
                                @csrf
                                <button type="submit" class="p-2 rounded-lg transition-colors" style="color: var(--text-tertiary);" title="Marcar como predeterminada" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('client.store.bank-accounts.edit', $account) }}" class="p-2 rounded-lg transition-colors" style="color: var(--text-tertiary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('client.store.bank-accounts.destroy', $account) }}" class="inline" onsubmit="return confirm('¿Estás seguro?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 rounded-lg transition-colors text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 space-y-2" style="border-top: 1px solid var(--border-color);">
                    <div class="flex justify-between">
                        <span class="text-sm" style="color: var(--text-tertiary);">Número</span>
                        <span class="text-sm font-mono font-medium" style="color: var(--text-primary);">{{ $account->account_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm" style="color: var(--text-tertiary);">Titular</span>
                        <span class="text-sm font-medium" style="color: var(--text-primary);">{{ $account->account_holder }}</span>
                    </div>
                    @if($account->rut)
                        <div class="flex justify-between">
                            <span class="text-sm" style="color: var(--text-tertiary);">RUT</span>
                            <span class="text-sm font-mono" style="color: var(--text-secondary);">{{ $account->rut }}</span>
                        </div>
                    @endif
                    @if($account->email)
                        <div class="flex justify-between">
                            <span class="text-sm" style="color: var(--text-tertiary);">Email</span>
                            <span class="text-sm" style="color: var(--text-secondary);">{{ $account->email }}</span>
                        </div>
                    @endif
                </div>
                
                @if($account->instructions)
                    <div class="mt-4 p-3 rounded-lg text-sm" style="background-color: var(--bg-secondary); color: var(--text-secondary);">
                        {{ $account->instructions }}
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    @else
        <div class="app-card rounded-xl p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center" style="background-color: var(--bg-tertiary);">
                <svg class="w-8 h-8" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium" style="color: var(--text-primary);">No hay cuentas bancarias</h3>
            <p class="text-sm mt-1" style="color: var(--text-secondary);">Agrega al menos una cuenta para recibir pagos por transferencia</p>
            <a href="{{ route('client.store.bank-accounts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 mt-4 rounded-lg bg-primary-600 text-white transition-colors hover:bg-primary-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Agregar Cuenta
            </a>
        </div>
    @endif
</div>
@endsection
