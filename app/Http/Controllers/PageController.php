<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Page;
use App\Support\SiteSettings;
use App\Support\TourCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;

class PageController extends Controller
{
    public function about(TourCatalog $catalog): View
    {
        $packages = $catalog->packages();
        $page = $this->page('about');
        $content = (array) ($page?->content ?? []);

        return view('pages.about', [
            'page' => $page,
            'pageContent' => $content,
            'galleryImages' => $catalog->galleryImages(6),
            'pageBg' => $page?->hero_image
                ?: ($packages->first()['image'] ?? '/aventour/assets/images/background/page-title.jpg'),
            'awardBg' => $catalog->trekPackages()->first()['image']
                ?? $packages->first()['image']
                ?? '/aventour/assets/images/background/award-bg.jpg',
            'packageCount' => $packages->count(),
            'destinationCount' => $catalog->destinations()->count(),
            'current' => 'about',
        ]);
    }

    public function contact(TourCatalog $catalog, SiteSettings $settings): View
    {
        $page = $this->page('contact');

        return view('pages.contact', [
            'page' => $page,
            'pageContent' => (array) ($page?->content ?? []),
            'siteSettings' => $settings->all(),
            'packages' => $catalog->packages(),
            'pageBg' => $page?->hero_image
                ?: ($catalog->packages()->get(2)['image']
                    ?? $catalog->packages()->first()['image']
                    ?? '/aventour/assets/images/background/page-title.jpg'),
            'awardBg' => $catalog->safariPackages()->first()['image']
                ?? '/aventour/assets/images/background/award-bg.jpg',
            'current' => 'contact',
        ]);
    }

    public function faq(TourCatalog $catalog, SiteSettings $settings): View
    {
        $page = $this->page('faq');
        $faqs = Schema::hasTable('faqs')
            ? Faq::query()->published()->ordered()->get()
            : collect();

        return view('pages.faq', [
            'page' => $page,
            'pageContent' => (array) ($page?->content ?? []),
            'faqs' => $faqs,
            'siteSettings' => $settings->all(),
            'pageBg' => $page?->hero_image
                ?: ($catalog->packages()->get(1)['image']
                    ?? $catalog->packages()->first()['image']
                    ?? '/aventour/assets/images/background/page-title.jpg'),
            'awardBg' => $catalog->trekPackages()->first()['image']
                ?? '/aventour/assets/images/background/award-bg.jpg',
            'current' => 'about',
        ]);
    }

    private function page(string $slug): ?Page
    {
        if (! Schema::hasTable('pages')) {
            return null;
        }

        return Page::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->first();
    }
}
