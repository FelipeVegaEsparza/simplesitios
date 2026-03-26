<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\ImageProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    protected ImageProcessingService $imageService;

    public function __construct(ImageProcessingService $imageService)
    {
        $this->imageService = $imageService;
    }
    public function index(Request $request)
    {
        $user = auth()->user();
        $client = $user->client;
        
        $query = Media::where('client_id', $client->id);
        
        if ($request->has('type')) {
            if ($request->get('type') === 'image') {
                $query->where('mime_type', 'like', 'image/%');
            } elseif ($request->get('type') === 'file') {
                $query->where('mime_type', 'not like', 'image/%');
            }
        }
        
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('original_filename', 'like', "%{$search}%");
        }
        
        $media = $query->latest()->paginate(20);
        
        return view('client.media.index', compact('media'));
    }

    public function create()
    {
        return view('client.media.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $client = $user->client;
        
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'max:10240'], // 10MB max
        ]);
        
        $uploaded = [];
        
        foreach ($request->file('files', []) as $file) {
            // Verificar si es una imagen para convertir a WebP
            if ($this->imageService->isImage($file)) {
                $processed = $this->imageService->processAndStore(
                    $file, 
                    "clients/{$client->id}/media"
                );
                
                $media = Media::create([
                    'client_id' => $client->id,
                    'uploaded_by' => $user->id,
                    'filename' => $processed['filename'],
                    'original_filename' => $processed['original_filename'],
                    'mime_type' => $processed['mime_type'],
                    'size' => $processed['size'],
                    'path' => $processed['path'],
                    'disk' => 'public',
                ]);
            } else {
                // Archivos no-imagen se guardan tal cual
                $path = $file->store("clients/{$client->id}/media", 'public');
                
                $media = Media::create([
                    'client_id' => $client->id,
                    'uploaded_by' => $user->id,
                    'filename' => basename($path),
                    'original_filename' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'path' => $path,
                    'disk' => 'public',
                ]);
            }
            
            $uploaded[] = $media;
        }
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'files' => $uploaded,
            ]);
        }
        
        return redirect()
            ->route('client.media.index')
            ->with('success', count($uploaded) . ' archivo(s) subido(s) exitosamente.');
    }

    public function destroy(Media $media)
    {
        $user = auth()->user();
        
        if ($media->client_id !== $user->client_id) {
            abort(403);
        }
        
        $media->delete();
        
        return redirect()
            ->route('client.media.index')
            ->with('success', 'Archivo eliminado exitosamente.');
    }

    public function selector(Request $request)
    {
        $user = auth()->user();
        $client = $user->client;
        
        $query = Media::where('client_id', $client->id);
        
        if ($request->get('type') === 'image') {
            $query->where('mime_type', 'like', 'image/%');
        }
        
        $media = $query->latest()->paginate(12);
        
        return view('client.media.selector', compact('media'));
    }
}
