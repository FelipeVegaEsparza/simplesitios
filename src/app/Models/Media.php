<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'uploaded_by',
        'filename',
        'original_filename',
        'mime_type',
        'size',
        'path',
        'disk',
        'metadata',
        'mediable_type',
        'mediable_id',
    ];

    protected $casts = [
        'size' => 'integer',
        'metadata' => 'array',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function mediable()
    {
        return $this->morphTo();
    }

    public function getUrl(): string
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getFullPath(): string
    {
        return Storage::disk($this->disk)->path($this->path);
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function isWebp(): bool
    {
        return $this->mime_type === 'image/webp';
    }

    public function getThumbnailUrl(?string $size = null): ?string
    {
        if (!$this->isImage()) {
            return null;
        }
        
        $metadata = $this->metadata ?? [];
        
        if ($size && isset($metadata['thumbnails'][$size])) {
            return Storage::disk($this->disk)->url($metadata['thumbnails'][$size]);
        }
        
        return $this->getUrl();
    }

    public function getFormattedSize(): string
    {
        $bytes = $this->size;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function deleteFile(): bool
    {
        if (Storage::disk($this->disk)->exists($this->path)) {
            Storage::disk($this->disk)->delete($this->path);
        }
        
        $metadata = $this->metadata ?? [];
        if (isset($metadata['thumbnails'])) {
            foreach ($metadata['thumbnails'] as $thumbnail) {
                if (Storage::disk($this->disk)->exists($thumbnail)) {
                    Storage::disk($this->disk)->delete($thumbnail);
                }
            }
        }
        
        return true;
    }

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($media) {
            if ($media->isForceDeleting()) {
                $media->deleteFile();
            }
        });
    }
}
