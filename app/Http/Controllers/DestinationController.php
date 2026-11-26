<?php

namespace App\Http\Controllers;

use App\Support\ManagedPageContent;
use App\Support\TourCatalog;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DestinationController extends Controller
{
    public function index(TourCatalog $catalog, ManagedPageContent $pages): View
    {
        $destinations = $catalog->destinations();
        $managed = $pages->for('destinations');

        return view('destinations.index', [
            'destinations' => $destinations,
            'page' => $managed['page'],
            'pageContent' => $managed['content'],
            'pageBg' => $managed['page']?->hero_image
                ?: ($destinations->first()['image']
                    ?? '/aventour/assets/images/background/page-title.jpg'),
            'awardBg' => $destinations->get(1)['image']
                ?? $destinations->first()['image']
                ?? '/aventour/assets/images/background/award-bg.jpg',
            'current' => 'destinations',
        ]);
    }

    public function show(string $slug, TourCatalog $catalog): View
    {
        $destination = $catalog->findDestination($slug);

        if ($destination === null) {
            throw new NotFoundHttpException('Destination not found.');
        }

        $packages = $catalog->packagesForDestination($destination['needles'] ?? $destination['needle']);

        $galleryImages = $packages
            ->flatMap(fn (array $package): array => $package['images'])
            ->unique()
            ->take(8)
            ->values()
            ->all();

        if ($galleryImages === []) {
            $galleryImages = [$destination['image']];
        }

        return view('destinations.show', [
            'destination' => $destination,
            'packages' => $packages,
            'galleryImages' => $galleryImages,
            'relatedDestinations' => $catalog->destinations()
                ->reject(fn (array $item): bool => $item['slug'] === $destination['slug'])
                ->values(),
            'awardBg' => $packages->get(1)['image']
                ?? $destination['image'],
            'current' => 'destinations',
        ]);
    }
}
