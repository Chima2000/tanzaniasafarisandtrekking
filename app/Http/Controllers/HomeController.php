<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Support\HomeHeroSlides;
use App\Support\ManagedPageContent;
use App\Support\PublishedBlogPosts;
use App\Support\TourCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function __invoke(
        TourCatalog $catalog,
        PublishedBlogPosts $blogPosts,
        ManagedPageContent $pages,
        HomeHeroSlides $heroSlides,
    ): View {
        $packages = $catalog->packages();
        $safariPackages = $catalog->safariPackages();
        $trekPackages = $catalog->trekPackages();
        $homeMedia = $catalog->homeMedia();
        $sectionImages = $homeMedia['sectionImages'];
        $managed = $pages->for('home');

        $faqs = Schema::hasTable('faqs')
            ? Faq::query()->published()->ordered()->limit(4)->get()
            : collect();

        $fallbackBannerImages = [
            $sectionImages['banner'][0] ?? ($packages[0]['image'] ?? '/aventour/assets/images/banner/banner-1.jpg'),
            $sectionImages['banner'][1] ?? ($packages[1]['image'] ?? '/aventour/assets/images/banner/banner-2.jpg'),
            $sectionImages['banner'][2] ?? ($trekPackages[0]['image'] ?? ($packages[2]['image'] ?? '/aventour/assets/images/banner/banner-3.jpg')),
        ];

        return view('home', [
            'page' => $managed['page'],
            'pageContent' => $managed['content'],
            'heroSlides' => $heroSlides->resolve($managed['content'], $fallbackBannerImages),
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
            'faqs' => $faqs,
        ]);
    }
}
