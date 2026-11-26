<?php

namespace App\Support;

use App\Models\MediaItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaLibraryScanner
{
    /** @var array<string, string> */
    protected array $mimeMap = [];

    /** @var Collection<string, MediaItem> */
    protected Collection $existing;

    public function __construct()
    {
        $this->mimeMap = array_merge(
            $this->extensionsForCategory(MediaItem::CATEGORY_IMAGE, ['jpg', 'jpeg', 'jfif', 'png', 'gif', 'webp', 'svg', 'ico', 'bmp', 'avif']),
            $this->extensionsForCategory(MediaItem::CATEGORY_AUDIO, ['mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac', 'weba']),
            $this->extensionsForCategory(MediaItem::CATEGORY_VIDEO, ['mp4', 'webm', 'mov', 'avi', 'mkv', 'm4v']),
            $this->extensionsForCategory(MediaItem::CATEGORY_DOCUMENT, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv', 'rtf', 'odt']),
            $this->extensionsForCategory(MediaItem::CATEGORY_ARCHIVE, ['zip', 'rar', '7z', 'tar', 'gz']),
        );
    }

    /**
     * @return array{scanned: int, added: int, updated: int, removed: int}
     */
    public function scan(): array
    {
        $this->existing = MediaItem::query()
            ->get(['id', 'disk', 'path', 'file_hash', 'file_size'])
            ->keyBy(fn (MediaItem $item): string => $item->disk.'|'.$item->path);

        $seen = [];
        $added = 0;
        $updated = 0;
        $insertBuffer = [];

        foreach ($this->iteratePayloads() as $payload) {
            $key = $payload['disk'].'|'.$payload['path'];
            $seen[$key] = true;

            $item = $this->existing->get($key);

            if (! $item) {
                $insertBuffer[] = array_merge($payload, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if (count($insertBuffer) >= 100) {
                    MediaItem::query()->insert($insertBuffer);
                    $added += count($insertBuffer);
                    $insertBuffer = [];
                }

                continue;
            }

            if ($this->payloadChanged($item, $payload)) {
                MediaItem::query()->whereKey($item->id)->update([
                    'filename' => $payload['filename'],
                    'extension' => $payload['extension'],
                    'mime_type' => $payload['mime_type'],
                    'category' => $payload['category'],
                    'folder' => $payload['folder'],
                    'file_size' => $payload['file_size'],
                    'width' => $payload['width'],
                    'height' => $payload['height'],
                    'file_hash' => $payload['file_hash'],
                    'file_modified_at' => $payload['file_modified_at'],
                    'updated_at' => now(),
                ]);
                $updated++;
            }
        }

        if ($insertBuffer !== []) {
            MediaItem::query()->insert($insertBuffer);
            $added += count($insertBuffer);
        }

        return [
            'scanned' => count($seen),
            'added' => $added,
            'updated' => $updated,
            'removed' => $this->removeStale(array_keys($seen)),
        ];
    }

    /**
     * @return \Generator<int, array<string, mixed>>
     */
    protected function iteratePayloads(): \Generator
    {
        yield from $this->scanStoragePublic();
        yield from $this->scanPublicPhotos();
    }

    /**
     * @return \Generator<int, array<string, mixed>>
     */
    protected function scanStoragePublic(): \Generator
    {
        if (! Storage::disk('public')->exists('.')) {
            return;
        }

        foreach (Storage::disk('public')->allFiles() as $path) {
            if (str_starts_with(basename($path), '.')) {
                continue;
            }

            $payload = $this->buildPayload('public', Storage::disk('public')->path($path), $path);
            if ($payload) {
                yield $payload;
            }
        }
    }

    /**
     * @return \Generator<int, array<string, mixed>>
     */
    protected function scanPublicPhotos(): \Generator
    {
        $absolute = public_path('photos');
        if (! is_dir($absolute)) {
            return;
        }

        foreach (File::allFiles($absolute) as $file) {
            $relative = Str::replace('\\', '/', Str::after($file->getPathname(), $absolute.DIRECTORY_SEPARATOR));

            if (str_starts_with(basename($relative), '.')) {
                continue;
            }

            $payload = $this->buildPayload('photos', $file->getPathname(), $relative);
            if ($payload) {
                yield $payload;
            }
        }
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function buildPayload(string $disk, string $absolutePath, string $relativePath): ?array
    {
        if (! is_file($absolutePath)) {
            return null;
        }

        $extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
        if (! isset($this->mimeMap[$extension])) {
            return null;
        }

        $category = $this->mimeMap[$extension];
        $fileSize = filesize($absolutePath) ?: 0;
        $modifiedAt = filemtime($absolutePath) ?: time();
        [$width, $height] = $this->imageDimensions($absolutePath, $category);

        $folder = dirname($relativePath);
        if ($folder === '.') {
            $folder = '';
        }

        return [
            'disk' => $disk,
            'path' => $relativePath,
            'filename' => basename($relativePath),
            'extension' => $extension,
            'mime_type' => $this->guessMime($extension),
            'category' => $category,
            'folder' => $folder,
            'file_size' => $fileSize,
            'width' => $width,
            'height' => $height,
            'file_hash' => $fileSize.'|'.$modifiedAt,
            'file_modified_at' => date('Y-m-d H:i:s', $modifiedAt),
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function payloadChanged(MediaItem $item, array $payload): bool
    {
        return $item->file_hash !== $payload['file_hash']
            || (int) $item->file_size !== (int) $payload['file_size'];
    }

    /**
     * @param  list<string>  $seenKeys
     */
    protected function removeStale(array $seenKeys): int
    {
        if ($seenKeys === []) {
            return 0;
        }

        $seen = array_flip($seenKeys);
        $removed = 0;

        MediaItem::query()->chunkById(200, function ($items) use ($seen, &$removed): void {
            foreach ($items as $item) {
                $key = $item->disk.'|'.$item->path;
                if (! isset($seen[$key])) {
                    $item->purge();
                    $removed++;
                }
            }
        });

        return $removed;
    }

    /**
     * @return array{0: ?int, 1: ?int}
     */
    protected function imageDimensions(string $path, string $category): array
    {
        if ($category !== MediaItem::CATEGORY_IMAGE) {
            return [null, null];
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (in_array($ext, ['svg', 'ico'], true)) {
            return [null, null];
        }

        $size = @getimagesize($path);

        return [$size[0] ?? null, $size[1] ?? null];
    }

    protected function guessMime(string $extension): string
    {
        return match ($extension) {
            'jpg', 'jpeg', 'jfif' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'ogg' => 'audio/ogg',
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'mov' => 'video/quicktime',
            'pdf' => 'application/pdf',
            'zip' => 'application/zip',
            default => 'application/octet-stream',
        };
    }

    /**
     * @param  list<string>  $extensions
     * @return array<string, string>
     */
    protected function extensionsForCategory(string $category, array $extensions): array
    {
        $map = [];
        foreach ($extensions as $ext) {
            $map[$ext] = $category;
        }

        return $map;
    }

    public static function categorizeUploadedFile(string $extension): string
    {
        return (new self)->mimeMap[strtolower($extension)] ?? MediaItem::CATEGORY_OTHER;
    }

    public static function humanBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1).' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1).' KB';
        }

        return $bytes.' B';
    }
}
