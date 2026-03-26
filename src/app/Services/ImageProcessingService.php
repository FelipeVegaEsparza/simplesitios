<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageProcessingService
{
    /**
     * Quality para compresión WebP (0-100)
     */
    protected int $webpQuality = 85;

    /**
     * Dimensiones máximas para redimensionar imágenes grandes
     */
    protected int $maxWidth = 1920;
    protected int $maxHeight = 1080;

    /**
     * Verifica si WebP está soportado
     */
    public function supportsWebp(): bool
    {
        return function_exists('imagewebp');
    }

    /**
     * Procesa una imagen y la convierte a WebP si es posible
     *
     * @param UploadedFile $file
     * @param string $directory Directorio donde guardar (ej: 'clients/1/media')
     * @param string $disk
     * @return array Información del archivo procesado
     */
    public function processAndStore(UploadedFile $file, string $directory, string $disk = 'public'): array
    {
        // Si WebP no está disponible, guardar la imagen original
        if (!$this->supportsWebp()) {
            return $this->storeOriginal($file, $directory, $disk);
        }

        try {
            // Generar nombre único con extensión .webp
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = $this->sanitizeFilename($originalName) . '-' . uniqid() . '.webp';
            $path = $directory . '/' . $filename;

            // Crear ImageManager con driver GD
            $manager = new ImageManager(new Driver());
            
            // Leer la imagen
            $image = $manager->read($file);

            // Redimensionar si es necesario
            $image = $this->resizeIfNeeded($image);

            // Codificar a WebP
            $encodedImage = $image->encode(new \Intervention\Image\Encoders\WebpEncoder(quality: $this->webpQuality));

            // Guardar el archivo
            Storage::disk($disk)->put($path, (string) $encodedImage);

            return [
                'path' => $path,
                'filename' => $filename,
                'original_filename' => $file->getClientOriginalName(),
                'mime_type' => 'image/webp',
                'size' => strlen((string) $encodedImage),
                'extension' => 'webp',
            ];
        } catch (\Exception $e) {
            // Si falla la conversión, guardar original
            return $this->storeOriginal($file, $directory, $disk);
        }
    }

    /**
     * Guarda la imagen original sin procesar
     */
    protected function storeOriginal(UploadedFile $file, string $directory, string $disk = 'public'): array
    {
        $path = $file->store($directory, $disk);

        return [
            'path' => $path,
            'filename' => basename($path),
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension(),
        ];
    }

    /**
     * Verifica si el archivo es una imagen procesable
     */
    public function isImage(UploadedFile $file): bool
    {
        $mimeType = $file->getMimeType();
        return str_starts_with($mimeType, 'image/');
    }

    /**
     * Redimensiona la imagen si excede las dimensiones máximas
     */
    protected function resizeIfNeeded($image)
    {
        $width = $image->width();
        $height = $image->height();

        // Si la imagen es más grande que el máximo permitido, redimensionar
        if ($width > $this->maxWidth || $height > $this->maxHeight) {
            $image = $image->scaleDown($this->maxWidth, $this->maxHeight);
        }

        return $image;
    }

    /**
     * Sanitiza el nombre de archivo
     */
    protected function sanitizeFilename(string $filename): string
    {
        // Convertir a minúsculas
        $filename = strtolower($filename);
        
        // Reemplazar espacios y caracteres especiales
        $filename = preg_replace('/[^a-z0-9\-_]/', '-', $filename);
        
        // Eliminar guiones múltiples
        $filename = preg_replace('/-+/', '-', $filename);
        
        // Eliminar guiones al inicio y final
        $filename = trim($filename, '-');
        
        return $filename ?: 'image';
    }

    /**
     * Set custom quality
     */
    public function setQuality(int $quality): self
    {
        $this->webpQuality = max(0, min(100, $quality));
        return $this;
    }

    /**
     * Set custom max dimensions
     */
    public function setMaxDimensions(int $width, int $height): self
    {
        $this->maxWidth = $width;
        $this->maxHeight = $height;
        return $this;
    }
}
