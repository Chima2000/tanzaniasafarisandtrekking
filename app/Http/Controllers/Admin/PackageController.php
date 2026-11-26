<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Support\CatalogSync;
use App\Support\RichText;
use App\Support\TourCatalog;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(Request $request, TourCatalog $catalog): View
    {
        $packages = Package::query()->exists()
            ? Package::query()->ordered()->paginate(12)
            : null;

        return view('admin.packages.index', [
            'packages' => $packages,
            'fallbackPackages' => $packages === null ? $catalog->packagesFromFilesystem() : collect(),
            'selectedCategory' => $request->string('category')->toString() ?: 'all',
            'categories' => $catalog->experienceCategories(),
        ]);
    }

    public function create(): View
    {
        return view('admin.packages.create', [
            'package' => new Package([
                'rating' => '5.0',
                'is_published' => true,
                'is_featured' => false,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $package = Package::query()->create($this->validated($request));

        return redirect()
            ->route('admin.packages.edit', $package)
            ->with('success', 'Package created successfully.');
    }

    public function show(string $slug, TourCatalog $catalog): View|RedirectResponse
    {
        $package = Package::query()->where('slug', $slug)->first();

        if ($package !== null) {
            return redirect()->route('admin.packages.edit', $package);
        }

        $fallback = $catalog->findPackage($slug);
        abort_if($fallback === null, 404);

        return view('admin.packages.show', [
            'package' => $fallback,
        ]);
    }

    public function edit(Package $package): View
    {
        return view('admin.packages.edit', [
            'package' => $package,
        ]);
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        $package->update($this->validated($request, $package));

        return redirect()
            ->route('admin.packages.edit', $package)
            ->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package): RedirectResponse
    {
        $package->delete();

        return redirect()
            ->route('admin.packages.index')
            ->with('success', 'Package deleted successfully.');
    }

    public function sync(CatalogSync $sync): RedirectResponse
    {
        $count = $sync->syncPackages();

        return redirect()
            ->route('admin.packages.index')
            ->with('success', "Synced {$count} packages from the website catalog.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Package $package = null): array
    {
        if ($request->filled('name') && ! $request->filled('slug')) {
            $request->merge(['slug' => Str::slug((string) $request->input('name'))]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('packages', 'slug')->ignore($package?->id)],
            'location' => ['nullable', 'string', 'max:255'],
            'duration' => ['nullable', 'string', 'max:120'],
            'category' => ['nullable', 'string', 'max:120'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
            'rating' => ['nullable', 'string', 'max:20'],
            'image' => ['nullable', 'string', 'max:500'],
            'images_text' => ['nullable', 'string'],
            'overview' => ['nullable', 'string'],
            'itinerary' => ['nullable', 'array'],
            'itinerary.*.day' => ['nullable', 'string', 'max:120'],
            'itinerary.*.title' => ['nullable', 'string', 'max:255'],
            'itinerary.*.description' => ['nullable', 'string'],
            'itinerary.*.meals' => ['nullable', 'string', 'max:255'],
            'itinerary.*.destinations' => ['nullable', 'string', 'max:255'],
            'includes_text' => ['nullable', 'string'],
            'excludes_text' => ['nullable', 'string'],
            'cost_per_person' => ['nullable', 'numeric', 'min:0'],
            'profit_per_person' => ['nullable', 'numeric', 'min:0'],
            'blocked_dates_text' => ['nullable', 'string'],
            'is_published' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $images = $this->linesToArray($validated['images_text'] ?? '');
        if ($images === [] && ! empty($validated['image'])) {
            $images = [$validated['image']];
        }

        $blockedDates = collect($this->linesToArray($validated['blocked_dates_text'] ?? ''))
            ->map(function (string $line): ?string {
                $line = trim($line);
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $line) === 1) {
                    return $line;
                }

                try {
                    return Carbon::parse($line)->toDateString();
                } catch (\Throwable) {
                    return null;
                }
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        return [
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'location' => $validated['location'] ?? null,
            'duration' => $validated['duration'] ?? null,
            'category' => $validated['category'] ?? null,
            'seo_title' => $validated['seo_title'] ?? null,
            'seo_description' => $validated['seo_description'] ?? null,
            'seo_keywords' => $validated['seo_keywords'] ?? null,
            'rating' => $validated['rating'] ?? null,
            'image' => $validated['image'] ?? ($images[0] ?? null),
            'images' => $images,
            'overview' => RichText::sanitize($validated['overview'] ?? null),
            'itinerary' => $this->sanitizeItinerary($validated['itinerary'] ?? []),
            'includes' => $this->linesToArray($validated['includes_text'] ?? ''),
            'excludes' => $this->linesToArray($validated['excludes_text'] ?? ''),
            'cost_per_person' => $validated['cost_per_person'] ?? null,
            'profit_per_person' => $validated['profit_per_person'] ?? null,
            'blocked_dates' => $blockedDates,
            'is_published' => $request->boolean('is_published'),
            'is_featured' => $request->boolean('is_featured'),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];
    }

    /**
     * @param  array<int, mixed>  $days
     * @return list<array{day: string, title: string, description: string, meals: ?string, destinations: ?string}>
     */
    private function sanitizeItinerary(array $days): array
    {
        $itinerary = [];

        foreach (array_values($days) as $index => $day) {
            if (! is_array($day)) {
                continue;
            }

            $title = trim((string) ($day['title'] ?? ''));
            $description = RichText::sanitize($day['description'] ?? null);
            $label = trim((string) ($day['day'] ?? ''));

            if ($title === '' && $description === '' && $label === '') {
                continue;
            }

            $itinerary[] = [
                'day' => $label !== '' ? $label : 'Day '.($index + 1),
                'title' => $title,
                'description' => $description,
                'meals' => ($meals = trim((string) ($day['meals'] ?? ''))) !== '' ? $meals : null,
                'destinations' => ($destinations = trim((string) ($day['destinations'] ?? ''))) !== '' ? $destinations : null,
            ];
        }

        return $itinerary;
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
}
