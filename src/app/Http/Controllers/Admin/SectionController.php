<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSectionRequest;
use App\Models\Client;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Section::query()->with('client');
        
        if ($request->has('client_id')) {
            $query->where('client_id', $request->get('client_id'));
        }
        
        if ($request->has('type')) {
            $query->where('type', $request->get('type'));
        }
        
        $sections = $query->latest()->paginate(10);
        $clients = Client::all();
        
        return view('admin.sections.index', compact('sections', 'clients'));
    }

    public function create(Request $request)
    {
        $clients = Client::where('status', 'active')->get();
        $selectedClient = $request->get('client_id');
        
        return view('admin.sections.create', compact('clients', 'selectedClient'));
    }

    public function store(StoreSectionRequest $request)
    {
        $data = $request->validated();
        
        $section = Section::create($data);
        
        // Guardar imágenes de la galería
        if ($request->has('gallery_images')) {
            $galleryImages = collect($request->input('gallery_images'))
                ->unique()
                ->values()
                ->map(fn ($id, $index) => [
                    'media_id' => $id,
                    'sort_order' => $index,
                ]);
            
            $section->gallery()->attach($galleryImages);
        }
        
        return redirect()
            ->route('admin.sections.index')
            ->with('success', "Sección '{$section->name}' creada exitosamente.");
    }

    public function show(Section $section)
    {
        $section->load(['client', 'fields']);
        return view('admin.sections.show', compact('section'));
    }

    public function edit(Section $section)
    {
        $clients = Client::where('status', 'active')->get();
        return view('admin.sections.edit', compact('section', 'clients'));
    }

    public function update(Request $request, Section $section)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image_id' => ['nullable', 'exists:media,id'],
            'type' => ['required', \Illuminate\Validation\Rule::in(['single', 'collection'])],
            'sort_order' => ['integer', 'min:0'],
            'is_visible' => ['boolean'],
            'is_public_endpoint' => ['boolean'],
            'endpoint_slug' => ['nullable', 'string', 'max:255'],
            'config' => ['nullable', 'array'],
        ];
        
        $data = $request->validate($rules);
        
        $section->update($data);
        
        // Sincronizar imágenes de la galería
        if ($request->has('gallery_images')) {
            $galleryImages = collect($request->input('gallery_images'))
                ->unique()
                ->values()
                ->mapWithKeys(fn ($id, $index) => [
                    $id => ['sort_order' => $index]
                ])
                ->toArray();
            
            $section->gallery()->sync($galleryImages);
        } else {
            $section->gallery()->detach();
        }
        
        return redirect()
            ->route('admin.sections.index')
            ->with('success', "Sección '{$section->name}' actualizada exitosamente.");
    }

    public function destroy(Section $section)
    {
        $name = $section->name;
        $section->delete();
        
        return redirect()
            ->route('admin.sections.index')
            ->with('success', "Sección '{$name}' eliminada exitosamente.");
    }

    public function fields(Section $section)
    {
        $section->load('fields');
        $fieldTypes = [
            'text' => 'Texto',
            'textarea' => 'Área de texto',
            'richtext' => 'Editor enriquecido',
            'number' => 'Número',
            'boolean' => 'Sí/No',
            'date' => 'Fecha',
            'datetime' => 'Fecha y hora',
            'email' => 'Correo electrónico',
            'url' => 'URL',
            'image' => 'Imagen',
            'file' => 'Archivo',
            'select' => 'Selección',
            'json' => 'JSON',
            'repeater' => 'Repetidor',
            'gallery' => 'Galería',
        ];
        
        return view('admin.sections.fields', compact('section', 'fieldTypes'));
    }
}
