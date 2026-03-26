<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContentEntryRequest;
use App\Models\Client;
use App\Models\ContentEntry;
use App\Models\Section;
use Illuminate\Http\Request;

class ContentEntryController extends Controller
{
    public function index(Request $request)
    {
        $query = ContentEntry::query()
            ->with(['section', 'client']);
        
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->get('client_id'));
        }
        
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->get('section_id'));
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }
        
        $entries = $query->latest()->paginate(10)->withQueryString();
        
        $clients = Client::all();
        
        // Cargar secciones si hay un cliente seleccionado
        $sections = [];
        if ($request->filled('client_id')) {
            $sections = Section::where('client_id', $request->get('client_id'))->get();
        }
        
        return view('admin.content_entries.index', compact('entries', 'clients', 'sections'));
    }

    public function bySection(Request $request, Client $client, Section $section)
    {
        if ($section->client_id !== $client->id) {
            abort(404);
        }
        
        $query = ContentEntry::where('section_id', $section->id)
            ->with(['creator', 'updater']);
        
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }
        
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }
        
        $entries = $query->orderBy('sort_order')->latest()->paginate(10);
        
        return view('admin.content_entries.by_section', compact('client', 'section', 'entries'));
    }

    public function create(Client $client, Section $section)
    {
        if ($section->client_id !== $client->id) {
            abort(404);
        }
        
        // Si es sección single y ya tiene entrada, redirigir a editar
        if ($section->isSingle() && $section->getSingleEntry()) {
            return redirect()->route('admin.content_entries.edit', [
                'client' => $client,
                'section' => $section,
                'entry' => $section->getSingleEntry(),
            ]);
        }
        
        $section->load('fields');
        
        return view('admin.content_entries.create', compact('client', 'section'));
    }

    public function store(StoreContentEntryRequest $request, Client $client, Section $section)
    {
        if ($section->client_id !== $client->id) {
            abort(404);
        }
        
        $data = $request->validated();
        $data['section_id'] = $section->id;
        $data['client_id'] = $client->id;
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();
        
        $entry = ContentEntry::create($data);
        
        // Guardar valores de campos dinámicos
        $fields = $request->input('fields', []);
        foreach ($fields as $slug => $value) {
            $entry->setFieldValue($slug, $value);
        }
        
        return redirect()
            ->route('admin.content_entries.by_section', ['client' => $client, 'section' => $section])
            ->with('success', 'Contenido creado exitosamente.');
    }

    public function edit(Client $client, Section $section, ContentEntry $entry)
    {
        if ($section->client_id !== $client->id || $entry->section_id !== $section->id) {
            abort(404);
        }
        
        $section->load('fields');
        $entry->load('fieldValues.sectionField');
        
        return view('admin.content_entries.edit', compact('client', 'section', 'entry'));
    }

    public function update(StoreContentEntryRequest $request, Client $client, Section $section, ContentEntry $entry)
    {
        if ($section->client_id !== $client->id || $entry->section_id !== $section->id) {
            abort(404);
        }
        
        $data = $request->validated();
        $data['updated_by'] = auth()->id();
        
        $entry->update($data);
        
        // Actualizar valores de campos dinámicos
        $fields = $request->input('fields', []);
        foreach ($fields as $slug => $value) {
            $entry->setFieldValue($slug, $value);
        }
        
        return redirect()
            ->route('admin.content_entries.by_section', ['client' => $client, 'section' => $section])
            ->with('success', 'Contenido actualizado exitosamente.');
    }

    public function destroy($client, $section, ContentEntry $entry)
    {
        // Si el cliente o sección fueron eliminados, permitir eliminar la entrada igual
        $clientExists = is_object($client) ? $client->exists : Client::where('id', $client)->exists();
        $sectionExists = is_object($section) ? $section->exists : Section::where('id', $section)->exists();
        
        // Si ambos existen, verificar relaciones
        if ($clientExists && $sectionExists) {
            $clientModel = is_object($client) ? $client : Client::find($client);
            $sectionModel = is_object($section) ? $section : Section::find($section);
            
            if ($sectionModel->client_id !== $clientModel->id || $entry->section_id !== $sectionModel->id) {
                abort(404);
            }
        }
        
        $entry->delete();
        
        // Redirigir a lista general si no hay sección válida
        if (!$sectionExists) {
            return redirect()
                ->route('admin.content_entries.index')
                ->with('success', 'Contenido eliminado exitosamente.');
        }
        
        $sectionModel = is_object($section) ? $section : Section::find($section);
        return redirect()
            ->route('admin.content_entries.by_section', ['client' => $clientModel ?? $sectionModel->client_id, 'section' => $sectionModel])
            ->with('success', 'Contenido eliminado exitosamente.');
    }
}
