<?php

namespace App\Models;

use Database\Factories\MediaItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MediaItem extends Model
{
    /** @use HasFactory<MediaItemFactory> */
    use HasFactory;

    public const CATEGORY_IMAGE = 'image';

    public const CATEGORY_AUDIO = 'audio';

    public const CATEGORY_VIDEO = 'video';

    public const CATEGORY_DOCUMENT = 'document';

    public const CATEGORY_ARCHIVE = 'archive';

    public const CATEGORY_OTHER = 'other';

    protected $table = 'media_library';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'disk',
        'path',
        'filename',
        'extension',
        'mime_type',
        'category',
        'folder',
        'file_size',
        'width',
        'height',
        'title',
        'alt_text',
        'caption',
        'description',
        'file_hash',
        'uploaded_by',
        'file_modified_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'file_modified_at' => 'datetime',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function categories(): array
    {
        return [
            self::CATEGORY_IMAGE => 'Images',
            self::CATEGORY_AUDIO => 'Audio',
            self::CATEGORY_VIDEO => 'Video',
            self::CATEGORY_DOCUMENT => 'Documents',
            self::CATEGORY_ARCHIVE => 'Archives',
            self::CATEGORY_OTHER => 'Other',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function url(): string
    {
        return match ($this->disk) {
            'public' => asset('storage/'.$this->path),
            'photos' => asset('photos/'.ltrim($this->path, '/')),
            'assets' => asset($this->path),
            default => asset($this->path),
        };
    }

    public function storagePath(): string
    {
        return $this->path;
    }

    public function humanSize(): string
    {
        $bytes = $this->file_size;

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1).' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1).' KB';
        }

        return $bytes.' B';
    }

    public function isImage(): bool
    {
        return $this->category === self::CATEGORY_IMAGE;
    }

    public function displayTitle(): string
    {
        return $this->title ?: $this->filename;
    }

    public function deleteFile(): bool
    {
        if ($this->disk === 'public' && Storage::disk('public')->exists($this->path)) {
            return Storage::disk('public')->delete($this->path);
        }

        if ($this->disk === 'photos') {
            $full = public_path('photos/'.$this->path);
            if (is_file($full)) {
                return unlink($full);
            }
        }

        if ($this->disk === 'assets') {
            $full = public_path($this->path);
            if (is_file($full)) {
                return unlink($full);
            }
        }

        return false;
    }

    public function purge(): void
    {
        $this->deleteFile();
        $this->delete();
    }

    protected static function newFactory(): MediaItemFactory
    {
        return MediaItemFactory::new();
    }
}
