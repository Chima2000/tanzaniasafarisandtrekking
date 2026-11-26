<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaItem;
use App\Support\MediaLibraryScanner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaUploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'max:51200',
                'mimetypes:image/jpeg,image/png,image/webp,image/gif,image/avif,video/mp4,video/webm,video/quicktime',
            ],
        ]);

        $file = $validated['file'];
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = Str::uuid()->toString().'.'.$extension;
        $folder = 'uploads/'.now()->format('Y/m');
        $path = $file->storeAs($folder, $filename, 'public');
        $absolute = Storage::disk('public')->path($path);

        [$width, $height] = [null, null];
        if (@getimagesize($absolute)) {
            $size = getimagesize($absolute);
            $width = $size[0] ?? null;
            $height = $size[1] ?? null;
        }

        $item = MediaItem::query()->create([
            'disk' => 'public',
            'path' => $path,
            'filename' => $file->getClientOriginalName(),
            'extension' => $extension,
            'mime_type' => $file->getMimeType(),
            'category' => MediaLibraryScanner::categorizeUploadedFile($extension),
            'folder' => $folder,
            'file_size' => $file->getSize(),
            'width' => $width,
            'height' => $height,
            'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'file_hash' => md5_file($absolute) ?: null,
            'uploaded_by' => auth()->id(),
            'file_modified_at' => now(),
        ]);

        return response()->json([
            'id' => $item->id,
            'url' => $item->url(),
            'path' => $item->path,
            'title' => $item->displayTitle(),
            'thumb' => $item->url(),
            'category' => $item->category,
        ]);
    }
}
