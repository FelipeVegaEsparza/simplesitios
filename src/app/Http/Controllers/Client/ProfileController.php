<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('client.profile.edit');
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = auth()->user();
        
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ];
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }
        
        $user->update($data);
        
        return redirect()
            ->route('client.profile.edit')
            ->with('success', 'Perfil actualizado exitosamente.');
    }
}
