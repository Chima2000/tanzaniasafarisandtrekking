<?php

namespace App\Http\Controllers;

use App\Support\BookNowOptions;
use App\Support\ManagedPageContent;
use App\Support\PackageCategories;
use App\Support\TourCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PackageController extends Controller
{
    public function index(Request $request, TourCatalog $catalog): View|RedirectResponse
    {
        $category = $request->string('category')->toString();

        if ($category !== '') {
            return redirect()->to(
                PackageCategories::url($category, array_filter([
                    'q' => $request->string('q')->toString() ?: null,
                ])),
                301
            );
        }

        return $this->listing($request, $catalog, null);
    }

    public function safari(Request $request, TourCatalog $catalog): View
    {
        return $this->listing($request, $catalog, 'Safari');
    }

    public function trekking(Request $request, TourCatalog $catalog): View
    {
        return $this->listing($request, $catalog, 'Trekking');
    }

    public function dayTrips(Request $request, TourCatalog $catalog): View
    {
        return $this->listing($request, $catalog, 'Day Trip');
    }

    public function cultural(Request $request, TourCatalog $catalog): View
    {
        return $this->listing($request, $catalog, 'Cultural');
    }

    public function show(string $slug, TourCatalog $catalog): View
    {
        $package = $catalog->findPackage($slug);

        if ($package === null) {
            throw new NotFoundHttpException('Package not found.');
        }

        $relatedPackages = $catalog->packagesForCategory($package['category'])
            ->reject(fn (array $item): bool => $item['slug'] === $package['slug'])
            ->take(3)
            ->values();

        $current = PackageCategories::navKey($package['category']);
        $defaultDuration = 8;
        if (preg_match('/(\d+)/', (string) $package['duration'], $matches) === 1) {
            $defaultDuration = max(1, (int) $matches[1]);
        }

        $matchedDestinations = $catalog->destinations()
            ->filter(function (array $destination) use ($package): bool {
                foreach ($destination['needles'] as $needle) {
                    if (str_contains($package['name'], $needle) || str_contains($package['location'], $needle)) {
                        return true;
                    }
                }

                return false;
            })
            ->pluck('slug')
            ->values()
            ->all();

        return view('packages.show', [
            'package' => $package,
            'relatedPackages' => $relatedPackages,
            'awardBg' => $package['images'][1] ?? $package['image'],
            'current' => $current,
            'plannerDestinations' => $catalog->destinations()
                ->map(fn (array $destination): array => [
                    'slug' => $destination['slug'],
                    'name' => $destination['full_name'] ?? $destination['title'],
                    'region' => $destination['region'] ?? 'Tanzania',
                    'summary' => Str::limit(strip_tags((string) ($destination['description'] ?? '')), 72),
                    'image' => $destination['image'],
                ])
                ->values()
                ->all(),
            'plannerBudgets' => BookNowOptions::budgets(),
            'plannerDurations' => BookNowOptions::durations(),
            'plannerAccommodations' => BookNowOptions::accommodations(),
            'plannerInterests' => BookNowOptions::interests(),
            'plannerOldDestinations' => $matchedDestinations,
            'plannerDefaultBudget' => BookNowOptions::defaultBudget(),
            'plannerDefaultDuration' => $defaultDuration,
            'plannerDefaultAdults' => 2,
            'plannerDefaultChildren' => 0,
            'plannerMinDate' => now()->toDateString(),
            'plannerPackage' => $package,
            'plannerPackageSlug' => $package['slug'],
            'plannerStartStep' => 1,
        ]);
    }

    private function listing(Request $request, TourCatalog $catalog, ?string $category): View
    {
        $search = $request->string('q')->toString();
        $definition = PackageCategories::page($category);
        $managed = app(ManagedPageContent::class)->for('packages');
        $managedContent = $managed['content'];

        $packages = $catalog->packagesForCategory($category);

        if ($search !== '') {
            $packages = $packages
                ->filter(function (array $package) use ($search): bool {
                    return str_contains(strtolower($package['name']), strtolower($search))
                        || str_contains(strtolower($package['location']), strtolower($search))
                        || str_contains(strtolower($package['category']), strtolower($search));
                })
                ->values();
        }

        $pageBg = $managed['page']?->hero_image
            ?: ($packages->first()['image']
                ?? $catalog->packages()->first()['image']
                ?? '/aventour/assets/images/background/page-title.jpg');

        $pageTitle = $category
            ? $definition['label']
            : ($managedContent['page_title'] ?: $definition['label']);

        $intro = $definition;
        if ($category === null) {
            $intro['subtitle'] = $managedContent['subtitle'] ?: ($definition['subtitle'] ?? '');
            $intro['body'] = $managedContent['intro'] ?: ($definition['body'] ?? '');
        }

        return view('packages.index', [
            'packages' => $packages,
            'categories' => $catalog->experienceCategories(),
            'destinations' => $catalog->destinations(),
            'totalPackages' => $catalog->packages()->count(),
            'activeCategory' => $category,
            'categoryLabel' => $definition['label'],
            'search' => $search,
            'pageTitle' => $pageTitle,
            'seoTitle' => $category === null
                ? ($managed['page']?->seo_title ?: $definition['seo_title'])
                : $definition['seo_title'],
            'seoDescription' => $category === null
                ? ($managed['page']?->seo_description ?: $definition['seo_description'])
                : $definition['seo_description'],
            'seoKeywords' => $definition['seo_keywords'],
            'canonicalUrl' => PackageCategories::url($category),
            'intro' => $intro,
            'introImage' => $pageBg,
            'introSecondaryImage' => data_get($packages->get(1), 'image')
                ?: data_get($packages->first(), 'images.1')
                ?: $pageBg,
            'pageBg' => $pageBg,
            'awardBg' => $catalog->trekPackages()->first()['image']
                ?? $catalog->packages()->first()['image']
                ?? '/aventour/assets/images/background/award-bg.jpg',
            'current' => PackageCategories::navKey($category),
            'pageContent' => $managedContent,
        ]);
    }
}
