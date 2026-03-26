<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with('client');
        
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('role')) {
            $query->where('role', $request->get('role'));
        }
        
        $users = $query->latest()->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        return view('admin.users.create', compact('clients'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        
        $user = User::create($data);
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', "Usuario '{$user->name}' creado exitosamente.");
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $clients = Client::where('status', 'active')->get();
        return view('admin.users.edit', compact('user', 'clients'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['superadmin', 'client'])],
            'client_id' => [
                Rule::requiredIf($request->input('role') === 'client'),
                'nullable',
                'exists:clients,id',
            ],
            'is_active' => ['boolean'],
        ];
        
        if ($request->filled('password')) {
            $rules['password'] = ['string', 'min:8', 'confirmed'];
        }
        
        $data = $request->validate($rules);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        
        $user->update($data);
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', "Usuario '{$user->name}' actualizado exitosamente.");
    }

    public function destroy(User $user)
    {
        // No permitir eliminar el último superadmin
        if ($user->isSuperAdmin() && User::where('role', 'superadmin')->count() <= 1) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'No puedes eliminar el último superadministrador.');
        }
        
        $name = $user->name;
        $user->delete();
        
        return redirect()
            ->route('admin.users.index')
            ->with('success', "Usuario '{$name}' eliminado exitosamente.");
    }
}
