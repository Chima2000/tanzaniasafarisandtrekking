<?php

namespace App\Http\Controllers;

use App\Support\PublishedBlogPosts;
use App\Support\TourCatalog;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(TourCatalog $catalog, PublishedBlogPosts $blogPosts): View
    {
        $packages = $catalog->packages();
        $safariPackages = $catalog->safariPackages();
        $trekPackages = $catalog->trekPackages();
        $homeMedia = $catalog->homeMedia();
        $sectionImages = $homeMedia['sectionImages'];

        return view('home', [
            'packages' => $packages,
            'safariPackages' => $safariPackages,
            'trekPackages' => $trekPackages,
            'featuredPackages' => $packages->take(12),
            'vibes' => $homeMedia['vibes'],
            'destinations' => $homeMedia['destinations'],
            'sectionImages' => $sectionImages,
            'galleryImages' => $sectionImages['gallery'],
            'brandImages' => $sectionImages['gallery'],
            'packageCount' => $packages->count(),
            'posts' => $blogPosts->all()->take(6),
        ]);
    }
}
