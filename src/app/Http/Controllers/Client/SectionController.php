<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $client = $user->client;
        
        $sections = $client->sections()
            ->where('is_visible', true)
            ->withCount('contentEntries')
            ->orderBy('sort_order')
            ->get();
        
        return view('client.sections.index', compact('sections'));
    }

    public function show(Section $section)
    {
        $user = auth()->user();
        
        // Verificar que la sección pertenece al cliente del usuario
        if ($section->client_id !== $user->client_id) {
            abort(403);
        }
        
        // Verificar que la sección es visible
        if (!$section->is_visible) {
            abort(404);
        }
        
        if ($section->isSingle()) {
            return redirect()->route('client.sections.single.edit', $section);
        }
        
        return redirect()->route('client.sections.entries.index', $section);
    }
}
