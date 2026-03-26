<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContentEntryRequest;
use App\Models\ContentEntry;
use App\Models\Section;
use Illuminate\Http\Request;

class ContentEntryController extends Controller
{
    public function index(Section $section)
    {
        $user = auth()->user();
        
        if ($section->client_id !== $user->client_id || !$section->is_visible) {
            abort(403);
        }
        
        if ($section->isSingle()) {
            return redirect()->route('client.sections.single.edit', $section);
        }
        
        $entries = ContentEntry::where('section_id', $section->id)
            ->orderBy('sort_order')
            ->latest()
            ->paginate(10);
        
        return view('client.content_entries.index', compact('section', 'entries'));
    }

    public function create(Section $section)
    {
        $user = auth()->user();
        
        if ($section->client_id !== $user->client_id || !$section->is_visible) {
            abort(403);
        }
        
        if ($section->isSingle()) {
            abort(404);
        }
        
        $section->load('fields');
        
        return view('client.content_entries.create', compact('section'));
    }

    public function store(StoreContentEntryRequest $request, Section $section)
    {
        $user = auth()->user();
        
        if ($section->client_id !== $user->client_id || !$section->is_visible) {
            abort(403);
        }
        
        $data = $request->validated();
        $data['section_id'] = $section->id;
        $data['client_id'] = $user->client_id;
        $data['created_by'] = $user->id;
        $data['updated_by'] = $user->id;
        
        $entry = ContentEntry::create($data);
        
        // Guardar valores de campos dinámicos
        $fields = $request->input('fields', []);
        foreach ($fields as $slug => $value) {
            $entry->setFieldValue($slug, $value);
        }
        
        return redirect()
            ->route('client.sections.entries.index', $section)
            ->with('success', 'Contenido creado exitosamente.');
    }

    public function edit(Section $section, ContentEntry $entry)
    {
        $user = auth()->user();
        
        if ($section->client_id !== $user->client_id || 
            $entry->section_id !== $section->id ||
            $entry->client_id !== $user->client_id) {
            abort(403);
        }
        
        $section->load('fields');
        $entry->load('fieldValues.sectionField');
        
        return view('client.content_entries.edit', compact('section', 'entry'));
    }

    public function update(StoreContentEntryRequest $request, Section $section, ContentEntry $entry)
    {
        $user = auth()->user();
        
        if ($section->client_id !== $user->client_id || 
            $entry->section_id !== $section->id ||
            $entry->client_id !== $user->client_id) {
            abort(403);
        }
        
        $data = $request->validated();
        $data['updated_by'] = $user->id;
        
        $entry->update($data);
        
        // Actualizar valores de campos dinámicos
        $fields = $request->input('fields', []);
        foreach ($fields as $slug => $value) {
            $entry->setFieldValue($slug, $value);
        }
        
        return redirect()
            ->route('client.sections.entries.index', $section)
            ->with('success', 'Contenido actualizado exitosamente.');
    }

    public function destroy(Section $section, ContentEntry $entry)
    {
        $user = auth()->user();
        
        if ($section->client_id !== $user->client_id || 
            $entry->section_id !== $section->id ||
            $entry->client_id !== $user->client_id) {
            abort(403);
        }
        
        $entry->delete();
        
        return redirect()
            ->route('client.sections.entries.index', $section)
            ->with('success', 'Contenido eliminado exitosamente.');
    }

    // Para secciones tipo single
    public function editSingle(Section $section)
    {
        $user = auth()->user();
        
        if ($section->client_id !== $user->client_id || !$section->is_visible) {
            abort(403);
        }
        
        if (!$section->isSingle()) {
            abort(404);
        }
        
        $section->load('fields');
        $entry = $section->getOrCreateSingleEntry($user->id);
        $entry->load('fieldValues.sectionField');
        
        return view('client.content_entries.edit_single', compact('section', 'entry'));
    }

    public function updateSingle(StoreContentEntryRequest $request, Section $section)
    {
        $user = auth()->user();
        
        if ($section->client_id !== $user->client_id || !$section->is_visible) {
            abort(403);
        }
        
        if (!$section->isSingle()) {
            abort(404);
        }
        
        $entry = $section->getOrCreateSingleEntry($user->id);
        
        $data = $request->validated();
        $data['updated_by'] = $user->id;
        
        $entry->update($data);
        
        // Actualizar valores de campos dinámicos
        $fields = $request->input('fields', []);
        foreach ($fields as $slug => $value) {
            $entry->setFieldValue($slug, $value);
        }
        
        return redirect()
            ->route('client.sections.single.edit', $section)
            ->with('success', 'Contenido actualizado exitosamente.');
    }
}
