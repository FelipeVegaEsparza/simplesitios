<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index()
    {
        $client = auth()->user()->client;
        
        // Obtener las secciones con endpoint público
        $sections = $client->sections()
            ->where('is_public_endpoint', true)
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->get();
        
        // Construir la información de los endpoints
        $endpoints = $sections->map(function ($section) use ($client) {
            $endpointSlug = $section->endpoint_slug ?? $section->slug;
            
            return [
                'section' => $section,
                'name' => $section->name,
                'description' => $section->description,
                'type' => $section->type,
                'endpoint_url' => url("/api/public/{$client->slug}/{$endpointSlug}"),
                'method' => 'GET',
                'params' => $section->type === 'collection' ? ['page', 'limit', 'search'] : [],
            ];
        });
        
        return view('client.api.index', compact('client', 'endpoints'));
    }
}
