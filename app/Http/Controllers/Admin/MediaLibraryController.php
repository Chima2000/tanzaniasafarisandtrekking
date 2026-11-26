<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaItem;
use App\Support\MediaLibraryScanner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediaLibraryController extends Controller
{
    public function browse(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('q', ''));
        $category = $request->query('category', MediaItem::CATEGORY_IMAGE);

        $query = MediaItem::query()->latest('file_modified_at')->orderByDesc('id');

        if ($category && array_key_exists($category, MediaItem::categories())) {
            $query->where('category', $category);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('filename', 'like', '%'.$search.'%')
                    ->orWhere('title', 'like', '%'.$search.'%')
                    ->orWhere('alt_text', 'like', '%'.$search.'%');
            });
        }

        $items = $query->paginate(24)->withQueryString();

        return response()->json([
            'data' => $items->map(fn (MediaItem $item): array => [
                'id' => $item->id,
                'path' => $item->storagePath(),
                'url' => $item->url(),
                'title' => $item->displayTitle(),
                'thumb' => $item->url(),
                'width' => $item->width,
                'height' => $item->height,
            ]),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    public function index(Request $request, MediaLibraryScanner $scanner): View
    {
        if (MediaItem::query()->count() === 0 && ! app()->runningUnitTests()) {
            $scanner->scan();
        }

        $category = $request->query('category');
        $search = trim((string) $request->query('q', ''));
        $folder = $request->query('folder');
        $viewMode = $request->query('layout', 'grid');

        $query = MediaItem::query()->latest('file_modified_at')->orderByDesc('id');

        if ($category && array_key_exists($category, MediaItem::categories())) {
            $query->where('category', $category);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('filename', 'like', '%'.$search.'%')
                    ->orWhere('title', 'like', '%'.$search.'%')
                    ->orWhere('alt_text', 'like', '%'.$search.'%')
                    ->orWhere('path', 'like', '%'.$search.'%');
            });
        }

        if ($folder) {
            $query->where('folder', $folder);
        }

        $perPage = $viewMode === 'grid' ? 24 : 15;
        $items = $query->paginate($perPage)->withQueryString();

        $stats = array_fill_keys(array_keys(MediaItem::categories()), 0);
        foreach (MediaItem::query()->selectRaw('category, count(*) as aggregate')->groupBy('category')->pluck('aggregate', 'category') as $key => $count) {
            $stats[$key] = (int) $count;
        }

        $folders = MediaItem::query()
            ->whereNotNull('folder')
            ->where('folder', '!=', '')
            ->select('folder')
            ->distinct()
            ->orderBy('folder')
            ->pluck('folder');

        $totalBytes = (int) MediaItem::query()->sum('file_size');

        return view('admin.media.index', [
            'items' => $items,
            'stats' => $stats,
            'total' => MediaItem::query()->count(),
            'totalBytes' => $totalBytes,
            'totalBytesHuman' => MediaLibraryScanner::humanBytes($totalBytes),
            'folders' => $folders,
            'categories' => MediaItem::categories(),
            'filters' => [
                'category' => $category,
                'q' => $search,
                'folder' => $folder,
                'layout' => $viewMode,
            ],
        ]);
    }

    public function scan(Request $request, MediaLibraryScanner $scanner): RedirectResponse
    {
        set_time_limit(120);

        $result = $scanner->scan();

        return redirect()
            ->route('admin.media.index', $request->only(['category', 'q', 'folder', 'layout']))
            ->with('success', "Library synced: {$result['scanned']} files ({$result['added']} new, {$result['updated']} updated, {$result['removed']} removed).");
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'files' => ['required', 'array', 'min:1', 'max:20'],
            'files.*' => ['file', 'max:51200'],
            'folder' => ['nullable', 'string', 'max:120'],
        ], [
            'files.required' => 'Choose at least one file.',
            'files.min' => 'Choose at least one file.',
            'files.max' => 'You can upload up to 20 files at a time.',
            'files.*.file' => 'Each upload must be a file.',
            'files.*.max' => 'Each file must be 50 MB or smaller.',
        ]);

        $subdir = $request->filled('folder')
            ? Str::slug((string) $request->input('folder'))
            : 'uploads/'.date('Y/m');
        $uploaded = [];

        foreach ($request->file('files', []) as $file) {
            $path = $file->store($subdir, 'public');
            $extension = strtolower($file->getClientOriginalExtension());
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
                'folder' => dirname($path) === '.' ? '' : dirname($path),
                'file_size' => $file->getSize(),
                'width' => $width,
                'height' => $height,
                'title' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'file_hash' => md5_file($absolute) ?: null,
                'uploaded_by' => auth()->id(),
                'file_modified_at' => now(),
            ]);

            $uploaded[] = [
                'id' => $item->id,
                'path' => $item->storagePath(),
                'url' => $item->url(),
                'title' => $item->displayTitle(),
                'thumb' => $item->url(),
            ];
        }

        if ($request->expectsJson()) {
            return response()->json(['uploaded' => $uploaded]);
        }

        return redirect()
            ->route('admin.media.index')
            ->with('success', count($uploaded).' file(s) uploaded.');
    }

    public function update(Request $request, MediaItem $mediaItem): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $mediaItem->update($data);

        if ($request->expectsJson()) {
            return response()->json(['item' => $mediaItem->fresh()]);
        }

        return redirect()
            ->route('admin.media.index')
            ->with('success', 'Media details updated.');
    }

    public function destroy(MediaItem $mediaItem): RedirectResponse|JsonResponse
    {
        $mediaItem->purge();

        if (request()->expectsJson()) {
            return response()->json(['deleted' => true]);
        }

        return redirect()
            ->route('admin.media.index')
            ->with('success', 'File deleted from library.');
    }

    public function bulkDestroy(Request $request): RedirectResponse|JsonResponse
    {
        if ($request->isJson()) {
            $request->merge(['ids' => $request->json('ids', [])]);
        }

        $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:media_library,id'],
        ]);

        $count = 0;
        foreach (MediaItem::query()->whereIn('id', $request->ids)->get() as $media) {
            $media->purge();
            $count++;
        }

        if ($request->expectsJson()) {
            return response()->json(['deleted' => $count]);
        }

        return redirect()
            ->route('admin.media.index')
            ->with('success', $count.' file(s) deleted.');
    }
}
