<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\User;
use App\Services\ImageProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    protected ImageProcessingService $imageService;

    public function __construct(ImageProcessingService $imageService)
    {
        $this->imageService = $imageService;
    }
    public function index(Request $request)
    {
        $query = Client::query();
        
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('domain', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }
        
        $clients = $query->latest()->paginate(10);
        
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(StoreClientRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('logo')) {
            $processed = $this->imageService->processAndStore(
                $request->file('logo'),
                'clients/logos'
            );
            $data['logo'] = $processed['path'];
        }
        
        $client = Client::create($data);
        
        // Crear usuario administrador del cliente si se indicó
        $message = "Cliente '{$client->name}' creado exitosamente.";
        
        if ($request->boolean('create_user')) {
            $user = User::create([
                'name' => $request->input('user_name'),
                'email' => $request->input('user_email'),
                'password' => Hash::make($request->input('user_password')),
                'role' => 'client',
                'client_id' => $client->id,
                'is_active' => true,
            ]);
            
            $message .= " Usuario '{$user->email}' creado.";
        }
        
        return redirect()
            ->route('admin.clients.index')
            ->with('success', $message);
    }

    public function show(Client $client)
    {
        $client->load(['sections', 'users']);
        return view('admin.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $data = $request->validated();
        
        if ($request->hasFile('logo')) {
            // Eliminar logo anterior
            if ($client->logo) {
                Storage::disk('public')->delete($client->logo);
            }
            $processed = $this->imageService->processAndStore(
                $request->file('logo'),
                'clients/logos'
            );
            $data['logo'] = $processed['path'];
        }
        
        $client->update($data);
        
        return redirect()
            ->route('admin.clients.index')
            ->with('success', "Cliente '{$client->name}' actualizado exitosamente.");
    }

    public function destroy(Client $client)
    {
        $name = $client->name;
        
        if ($client->logo) {
            Storage::disk('public')->delete($client->logo);
        }
        
        $client->delete();
        
        return redirect()
            ->route('admin.clients.index')
            ->with('success', "Cliente '{$name}' eliminado exitosamente.");
    }

    public function sections(Client $client)
    {
        $client->load('sections');
        return view('admin.clients.sections', compact('client'));
    }
}
