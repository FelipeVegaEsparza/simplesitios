<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    /**
     * Obtener un valor de configuración
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = Cache::remember('setting.' . $key, 3600, function () use ($key) {
            return self::where('key', $key)->first();
        });

        if (!$setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => (bool) $setting->value,
            'image', 'file' => $setting->value ? Storage::url($setting->value) : null,
            default => $setting->value,
        };
    }

    /**
     * Establecer un valor de configuración
     */
    public static function set(string $key, mixed $value, string $type = 'string'): void
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            $setting->update(['value' => $value, 'type' => $type]);
        }
        
        Cache::forget('setting.' . $key);
    }

    /**
     * Obtener el logo del sitio
     */
    public static function getLogoUrl(): ?string
    {
        return self::get('site_logo');
    }

    /**
     * Obtener el favicon del sitio
     */
    public static function getFaviconUrl(): ?string
    {
        return self::get('site_favicon');
    }

    /**
     * Obtener el nombre del sitio
     */
    public static function getSiteName(): string
    {
        return self::get('site_name', config('app.name', 'CMS Headless'));
    }

    /**
     * Limpiar cache cuando se actualiza
     */
    protected static function boot(): void
    {
        parent::boot();

        static::saved(function ($setting) {
            Cache::forget('setting.' . $setting->key);
        });

        static::deleted(function ($setting) {
            Cache::forget('setting.' . $setting->key);
        });
    }
}
