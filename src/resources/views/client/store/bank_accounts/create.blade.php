@extends('layouts.app')

@section('title', 'Nueva Cuenta Bancaria')

@section('content')
<div class="page-content p-4 sm:p-6 lg:p-8">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('client.store.bank-accounts.index') }}" class="p-2 rounded-lg transition-colors" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-tertiary)'" onmouseout="this.style.backgroundColor='transparent'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold" style="color: var(--text-primary);">Nueva Cuenta Bancaria</h2>
                <p class="text-sm" style="color: var(--text-secondary);">Agrega una cuenta para recibir pagos</p>
            </div>
        </div>

        <form action="{{ route('client.store.bank-accounts.store') }}" method="POST" class="app-card rounded-xl p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Banco *</label>
                    <input type="text" name="bank_name" required class="app-input w-full px-4 py-2 rounded-lg" placeholder="Ej: Banco Estado">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Tipo de cuenta *</label>
                    <select name="account_type" required class="app-input w-full px-4 py-2 rounded-lg">
                        <option value="">Selecciona...</option>
                        <option value="Cuenta Corriente">Cuenta Corriente</option>
                        <option value="Cuenta Vista">Cuenta Vista</option>
                        <option value="Cuenta de Ahorro">Cuenta de Ahorro</option>
                        <option value="Cuenta RUT">Cuenta RUT</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Número de cuenta *</label>
                    <input type="text" name="account_number" required class="app-input w-full px-4 py-2 rounded-lg" placeholder="Ej: 1234567890">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">RUT del titular</label>
                    <input type="text" name="rut" class="app-input w-full px-4 py-2 rounded-lg" placeholder="12.345.678-9">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Nombre del titular *</label>
                <input type="text" name="account_holder" required class="app-input w-full px-4 py-2 rounded-lg" placeholder="Nombre completo del titular">
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Email (para enviar comprobantes)</label>
                <input type="email" name="email" class="app-input w-full px-4 py-2 rounded-lg" placeholder="email@ejemplo.com">
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--text-secondary);">Instrucciones adicionales</label>
                <textarea name="instructions" rows="3" class="app-input w-full px-4 py-2 rounded-lg" placeholder="Instrucciones que verá el comprador al momento de pagar..."></textarea>
            </div>
            
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_default" value="1" class="rounded" style="border-color: var(--border-color);">
                <label class="text-sm" style="color: var(--text-secondary);">Marcar como cuenta predeterminada</label>
            </div>
            
            <div class="flex items-center gap-3">
                <input type="checkbox" name="active" value="1" checked class="rounded" style="border-color: var(--border-color);">
                <label class="text-sm" style="color: var(--text-secondary);">Cuenta activa</label>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 py-3 px-4 rounded-lg bg-primary-600 text-white font-medium transition-colors hover:bg-primary-700">
                    Guardar Cuenta
                </button>
                <a href="{{ route('client.store.bank-accounts.index') }}" class="flex-1 py-3 px-4 rounded-lg text-center transition-colors" style="color: var(--text-secondary); background-color: var(--bg-tertiary);" onmouseover="this.style.backgroundColor='var(--border-color)'">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
