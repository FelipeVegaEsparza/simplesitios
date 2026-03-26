<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ImageProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    protected ImageProcessingService $imageService;

    public function __construct(ImageProcessingService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $settings = Setting::where('group', 'general')
            ->orderBy('label')
            ->get()
            ->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_logo' => ['nullable', 'image', 'max:2048'],
            'site_favicon' => ['nullable', 'image', 'max:1024', 'mimes:png,ico'],
        ]);

        // Site Name
        Setting::updateOrCreate(
            ['key' => 'site_name'],
            [
                'value' => $validated['site_name'],
                'type' => 'string',
                'group' => 'general',
                'label' => 'Nombre del Sitio',
                'description' => 'Nombre que aparece en el título del sitio y login',
            ]
        );

        // Site Logo
        if ($request->hasFile('site_logo')) {
            // Eliminar logo anterior si existe
            $oldLogo = Setting::where('key', 'site_logo')->first();
            if ($oldLogo && $oldLogo->value) {
                Storage::disk('public')->delete($oldLogo->value);
            }

            // Procesar y guardar nuevo logo
            $processed = $this->imageService->processAndStore(
                $request->file('site_logo'),
                'settings'
            );

            Setting::updateOrCreate(
                ['key' => 'site_logo'],
                [
                    'value' => $processed['path'],
                    'type' => 'image',
                    'group' => 'general',
                    'label' => 'Logo del Sitio',
                    'description' => 'Logo que aparece en el login',
                ]
            );
        }

        // Site Favicon
        if ($request->hasFile('site_favicon')) {
            // Eliminar favicon anterior si existe
            $oldFavicon = Setting::where('key', 'site_favicon')->first();
            if ($oldFavicon && $oldFavicon->value) {
                Storage::disk('public')->delete($oldFavicon->value);
            }

            $favicon = $request->file('site_favicon');
            $filename = 'favicon-' . uniqid() . '.' . $favicon->getClientOriginalExtension();
            $path = $favicon->storeAs('settings', $filename, 'public');

            Setting::updateOrCreate(
                ['key' => 'site_favicon'],
                [
                    'value' => $path,
                    'type' => 'image',
                    'group' => 'general',
                    'label' => 'Favicon',
                    'description' => 'Icono que aparece en la pestaña del navegador',
                ]
            );
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Configuración actualizada exitosamente.');
    }

    public function removeLogo()
    {
        $setting = Setting::where('key', 'site_logo')->first();
        
        if ($setting && $setting->value) {
            Storage::disk('public')->delete($setting->value);
            $setting->update(['value' => null]);
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Logo eliminado exitosamente.');
    }

    public function removeFavicon()
    {
        $setting = Setting::where('key', 'site_favicon')->first();
        
        if ($setting && $setting->value) {
            Storage::disk('public')->delete($setting->value);
            $setting->update(['value' => null]);
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Favicon eliminado exitosamente.');
    }

    /**
     * Inicializar configuraciones por defecto
     */
    public static function initializeDefaults(): void
    {
        $defaults = [
            [
                'key' => 'site_name',
                'value' => 'CMS Headless',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Nombre del Sitio',
                'description' => 'Nombre que aparece en el título del sitio y login',
            ],
        ];

        foreach ($defaults as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
