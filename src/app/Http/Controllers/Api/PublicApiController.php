<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ContentEntry;
use App\Models\Section;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicApiController extends Controller
{
    // GET /api/public/{client}
    public function clientInfo(string $clientSlug): JsonResponse
    {
        $client = Client::where('slug', $clientSlug)
            ->where('status', 'active')
            ->first();
        
        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }
        
        return response()->json([
            'data' => [
                'id' => $client->id,
                'name' => $client->name,
                'slug' => $client->slug,
                'domain' => $client->domain,
                'description' => $client->description,
                'logo' => $client->logo ? asset('storage/' . $client->logo) : null,
                'created_at' => $client->created_at->toIso8601String(),
            ],
        ]);
    }

    // GET /api/public/{client}/sections
    public function sections(string $clientSlug): JsonResponse
    {
        $client = Client::where('slug', $clientSlug)
            ->where('status', 'active')
            ->first();
        
        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }
        
        $sections = $client->sections()
            ->where('is_public_endpoint', true)
            ->where('is_visible', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($section) {
                return [
                    'id' => $section->id,
                    'name' => $section->name,
                    'slug' => $section->slug,
                    'type' => $section->type,
                    'description' => $section->description,
                    'endpoint_slug' => $section->getApiSlug(),
                ];
            });
        
        return response()->json(['data' => $sections]);
    }

    // GET /api/public/{client}/{section}
    public function sectionContent(string $clientSlug, string $sectionSlug): JsonResponse
    {
        $client = Client::where('slug', $clientSlug)
            ->where('status', 'active')
            ->first();
        
        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }
        
        $section = $client->sections()
            ->where(function ($query) use ($sectionSlug) {
                $query->where('slug', $sectionSlug)
                      ->orWhere('endpoint_slug', $sectionSlug);
            })
            ->where('is_public_endpoint', true)
            ->where('is_visible', true)
            ->first();
        
        if (!$section) {
            return response()->json(['error' => 'Section not found'], 404);
        }
        
        $section->load('fields');
        
        // Si es single, retornar solo la entrada
        if ($section->isSingle()) {
            $entry = $section->getSingleEntry();
            
            if (!$entry) {
                return response()->json(['data' => null]);
            }
            
            return response()->json([
                'data' => $this->formatEntry($entry, $section),
            ]);
        }
        
        // Si es collection, retornar listado
        $query = ContentEntry::where('section_id', $section->id)
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });
        
        $entries = $query->orderBy('sort_order')->latest()->paginate(20);
        
        return response()->json([
            'data' => $entries->map(fn($entry) => $this->formatEntry($entry, $section)),
            'meta' => [
                'current_page' => $entries->currentPage(),
                'last_page' => $entries->lastPage(),
                'per_page' => $entries->perPage(),
                'total' => $entries->total(),
            ],
        ]);
    }

    // GET /api/public/{client}/{section}/{slug}
    public function entryDetail(string $clientSlug, string $sectionSlug, string $entrySlug): JsonResponse
    {
        $client = Client::where('slug', $clientSlug)
            ->where('status', 'active')
            ->first();
        
        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }
        
        $section = $client->sections()
            ->where(function ($query) use ($sectionSlug) {
                $query->where('slug', $sectionSlug)
                      ->orWhere('endpoint_slug', $sectionSlug);
            })
            ->where('is_public_endpoint', true)
            ->where('is_visible', true)
            ->first();
        
        if (!$section) {
            return response()->json(['error' => 'Section not found'], 404);
        }
        
        $section->load('fields');
        
        $entry = ContentEntry::where('section_id', $section->id)
            ->where('slug', $entrySlug)
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            })
            ->first();
        
        if (!$entry) {
            return response()->json(['error' => 'Entry not found'], 404);
        }
        
        return response()->json([
            'data' => $this->formatEntry($entry, $section),
        ]);
    }

    // GET /api/public/{client}/settings
    public function settings(string $clientSlug): JsonResponse
    {
        $client = Client::where('slug', $clientSlug)
            ->where('status', 'active')
            ->first();
        
        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }
        
        $settings = $client->settings()
            ->get()
            ->mapWithKeys(function ($setting) {
                return [$setting->key => $setting->getValue()];
            });
        
        return response()->json(['data' => $settings]);
    }

    private function formatEntry(ContentEntry $entry, Section $section): array
    {
        $data = [
            'id' => $entry->id,
            'title' => $entry->title,
            'slug' => $entry->slug,
            'status' => $entry->status,
            'published_at' => $entry->published_at?->toIso8601String(),
            'created_at' => $entry->created_at->toIso8601String(),
            'updated_at' => $entry->updated_at->toIso8601String(),
        ];
        
        // Agregar campos dinámicos
        foreach ($section->fields as $field) {
            if (!$field->show_in_api) {
                continue;
            }
            
            $value = $entry->getFieldValue($field->slug);
            
            // Transformar valores de archivos
            if ($field->type === 'image' && $value) {
                $value = asset('storage/' . $value);
            } elseif ($field->type === 'file' && $value) {
                $value = asset('storage/' . $value);
            }
            
            $data[$field->slug] = $value;
        }
        
        return $data;
    }
}
