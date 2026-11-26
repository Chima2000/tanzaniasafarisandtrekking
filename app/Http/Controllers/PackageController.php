<?php

namespace App\Http\Controllers;

use App\Support\TourCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PackageController extends Controller
{
    public function index(Request $request, TourCatalog $catalog): View
    {
        $category = $request->string('category')->toString();
        $search = $request->string('q')->toString();

        $packages = $catalog->packagesForCategory($category !== '' ? $category : null);

        if ($search !== '') {
            $packages = $packages
                ->filter(function (array $package) use ($search): bool {
                    return str_contains(strtolower($package['name']), strtolower($search))
                        || str_contains(strtolower($package['location']), strtolower($search))
                        || str_contains(strtolower($package['category']), strtolower($search));
                })
                ->values();
        }

        $pageTitle = match ($category) {
            'Safari' => 'Safari Packages',
            'Trekking' => 'Kilimanjaro Treks',
            'Day Trip' => 'Day Adventures',
            'Cultural' => 'Cultural Experiences',
            default => 'Tour Packages',
        };

        $current = match ($category) {
            'Trekking' => 'kilimanjaro',
            'Day Trip' => 'day-trips',
            'Safari' => 'safari',
            default => 'safari',
        };

        return view('packages.index', [
            'packages' => $packages,
            'categories' => $catalog->experienceCategories(),
            'destinations' => $catalog->destinations(),
            'totalPackages' => $catalog->packages()->count(),
            'activeCategory' => $category !== '' ? $category : null,
            'categoryLabel' => $pageTitle,
            'search' => $search,
            'pageTitle' => $pageTitle,
            'pageBg' => $packages->first()['image']
                ?? $catalog->packages()->first()['image']
                ?? '/aventour/assets/images/background/page-title.jpg',
            'awardBg' => $catalog->trekPackages()->first()['image']
                ?? $catalog->packages()->first()['image']
                ?? '/aventour/assets/images/background/award-bg.jpg',
            'current' => $current,
        ]);
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

        $current = $package['category'] === 'Trekking' ? 'kilimanjaro' : 'safari';

        return view('packages.show', [
            'package' => $package,
            'relatedPackages' => $relatedPackages,
            'awardBg' => $package['images'][1] ?? $package['image'],
            'current' => $current,
        ]);
    }
}
