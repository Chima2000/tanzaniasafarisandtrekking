<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Support\CatalogSync;
use App\Support\TourCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(TourCatalog $catalog): View
    {
        $destinations = Destination::query()->exists()
            ? Destination::query()->ordered()->paginate(12)
            : null;

        return view('admin.destinations.index', [
            'destinations' => $destinations,
            'fallbackDestinations' => $destinations === null ? $catalog->destinationsFromDefinitions() : collect(),
        ]);
    }

    public function create(): View
    {
        return view('admin.destinations.create', [
            'destination' => new Destination([
                'region' => 'Northern Tanzania',
                'is_published' => true,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $destination = Destination::query()->create($this->validated($request));

        return redirect()
            ->route('admin.destinations.edit', $destination)
            ->with('success', 'Destination created successfully.');
    }

    public function show(string $slug, TourCatalog $catalog): View|RedirectResponse
    {
        $destination = Destination::query()->where('slug', $slug)->first();

        if ($destination !== null) {
            return redirect()->route('admin.destinations.edit', $destination);
        }

        $fallback = $catalog->findDestination($slug);
        abort_if($fallback === null, 404);

        return view('admin.destinations.show', [
            'destination' => $fallback,
            'packages' => $catalog->packagesForDestination($fallback['needles'] ?? [$fallback['needle'] ?? $slug]),
        ]);
    }

    public function edit(Destination $destination): View
    {
        return view('admin.destinations.edit', [
            'destination' => $destination,
        ]);
    }

    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $destination->update($this->validated($request, $destination));

        return redirect()
            ->route('admin.destinations.edit', $destination)
            ->with('success', 'Destination updated successfully.');
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        $destination->delete();

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', 'Destination deleted successfully.');
    }

    public function sync(CatalogSync $sync): RedirectResponse
    {
        $count = $sync->syncDestinations();

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', "Synced {$count} destinations from the website catalog.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Destination $destination = null): array
    {
        if ($request->filled('title') && ! $request->filled('slug')) {
            $request->merge(['slug' => Str::slug((string) $request->input('title'))]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('destinations', 'slug')->ignore($destination?->id)],
            'short' => ['nullable', 'string', 'max:10'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'needles_text' => ['nullable', 'string'],
            'starts' => ['nullable', 'string', 'max:120'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
            'headline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'story_intro' => ['nullable', 'string'],
            'highlights_text' => ['nullable', 'string'],
            'wildlife_text' => ['nullable', 'string'],
            'best_time' => ['nullable', 'string'],
            'getting_there' => ['nullable', 'string'],
            'tips_text' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:500'],
            'is_published' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $needles = $this->csvToArray($validated['needles_text'] ?? '');
        if ($needles === []) {
            $needles = [$validated['title']];
        }

        return [
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'short' => $validated['short'] ?? null,
            'full_name' => $validated['full_name'] ?? $validated['title'],
            'region' => $validated['region'] ?? null,
            'needles' => $needles,
            'starts' => $validated['starts'] ?? null,
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
            'seo_keywords' => $validated['seo_keywords'] ?? null,
            'headline' => $validated['headline'] ?? null,
            'description' => $validated['description'] ?? null,
            'story_intro' => $validated['story_intro'] ?? null,
            'highlights' => $this->linesToArray($validated['highlights_text'] ?? ''),
            'wildlife' => $this->csvToArray($validated['wildlife_text'] ?? ''),
            'best_time' => $validated['best_time'] ?? null,
            'getting_there' => $validated['getting_there'] ?? null,
            'tips' => $this->linesToArray($validated['tips_text'] ?? ''),
            'image' => $validated['image'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];
    }

    /**
     * @return list<string>
     */
    private function linesToArray(string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $value) ?: [])
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    private function csvToArray(string $value): array
    {
        return collect(explode(',', $value))
            ->map(fn (string $item): string => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
