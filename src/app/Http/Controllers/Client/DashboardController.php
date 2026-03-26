<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ContentEntry;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $client = $user->client;
        
        if (!$client) {
            abort(403, 'No tienes un cliente asignado.');
        }
        
        // Cargar secciones visibles para el cliente
        $sections = $client->sections()
            ->where('is_visible', true)
            ->withCount('contentEntries')
            ->orderBy('sort_order')
            ->get();
        
        // Contenido reciente
        $recentContent = ContentEntry::where('client_id', $client->id)
            ->with('section')
            ->latest()
            ->take(5)
            ->get();
        
        $stats = [
            'sections_count' => $sections->count(),
            'content_count' => ContentEntry::where('client_id', $client->id)->count(),
            'published_count' => ContentEntry::where('client_id', $client->id)
                ->where('status', 'published')
                ->count(),
        ];
        
        return view('client.dashboard', compact('client', 'sections', 'recentContent', 'stats'));
    }
}
