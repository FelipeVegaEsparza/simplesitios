<?php

namespace App\Http\Controllers\Client\Store;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $client = $user->client;
        
        $accounts = $client->bankAccounts()->orderBy('is_default', 'desc')->orderBy('bank_name')->get();
        
        return view('client.store.bank_accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('client.store.bank_accounts.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $client = $user->client;
        
        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_type' => 'required|string|max:50',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
            'rut' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'is_default' => 'boolean',
            'active' => 'boolean',
            'instructions' => 'nullable|string|max:1000',
        ]);

        $validated['client_id'] = $client->id;
        $validated['is_default'] = $request->boolean('is_default', false);
        $validated['active'] = $request->boolean('active', true);
        
        // Si es la primera cuenta, marcarla como default
        if ($client->bankAccounts()->count() === 0) {
            $validated['is_default'] = true;
        }

        BankAccount::create($validated);

        return redirect()->route('client.store.bank-accounts.index')
            ->with('success', 'Cuenta bancaria creada correctamente');
    }

    public function edit(BankAccount $bankAccount)
    {
        $user = auth()->user();
        
        if ($bankAccount->client_id !== $user->client_id) {
            abort(403);
        }
        
        return view('client.store.bank_accounts.edit', compact('bankAccount'));
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $user = auth()->user();
        
        if ($bankAccount->client_id !== $user->client_id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_type' => 'required|string|max:50',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
            'rut' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'is_default' => 'boolean',
            'active' => 'boolean',
            'instructions' => 'nullable|string|max:1000',
        ]);

        $validated['is_default'] = $request->boolean('is_default', false);
        $validated['active'] = $request->boolean('active', true);

        $bankAccount->update($validated);

        return redirect()->route('client.store.bank-accounts.index')
            ->with('success', 'Cuenta bancaria actualizada correctamente');
    }

    public function destroy(BankAccount $bankAccount)
    {
        $user = auth()->user();
        
        if ($bankAccount->client_id !== $user->client_id) {
            abort(403);
        }
        
        // Verificar si tiene órdenes asociadas
        if ($bankAccount->orders()->count() > 0) {
            // Desactivar en lugar de eliminar
            $bankAccount->update(['active' => false]);
            return redirect()->route('client.store.bank-accounts.index')
                ->with('success', 'Cuenta desactivada (tiene órdenes asociadas)');
        }
        
        $bankAccount->delete();

        return redirect()->route('client.store.bank-accounts.index')
            ->with('success', 'Cuenta bancaria eliminada correctamente');
    }

    public function setDefault(BankAccount $bankAccount)
    {
        $user = auth()->user();
        
        if ($bankAccount->client_id !== $user->client_id) {
            abort(403);
        }
        
        $bankAccount->update(['is_default' => true]);

        return back()->with('success', 'Cuenta marcada como predeterminada');
    }
}
