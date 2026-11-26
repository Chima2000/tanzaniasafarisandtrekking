<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Support\CmsMedia;
use App\Support\ManagedPageContent;
use App\Support\SiteSettings;
use App\Support\TourCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;

class PageController extends Controller
{
    public function about(TourCatalog $catalog, ManagedPageContent $pages): View
    {
        $packages = $catalog->packages();
        $managed = $pages->for('about');
        $content = $managed['content'];
        $page = $managed['page'];
        $galleryImages = $catalog->galleryImages(10);

        return view('pages.about', [
            'page' => $page,
            'pageContent' => $content,
            'galleryImages' => [
                CmsMedia::url($content['image_1'] ?? null, $galleryImages[0] ?? null),
                CmsMedia::url($content['image_2'] ?? null, $galleryImages[1] ?? null),
                CmsMedia::url($content['image_3'] ?? null, $galleryImages[2] ?? null),
                CmsMedia::url($content['image_4'] ?? null, $galleryImages[3] ?? null),
                $galleryImages[4] ?? $galleryImages[0] ?? null,
                $galleryImages[5] ?? $galleryImages[1] ?? null,
                $galleryImages[6] ?? $galleryImages[2] ?? null,
                $galleryImages[7] ?? $galleryImages[3] ?? null,
            ],
            'imagePositions' => [
                CmsMedia::position($content['image_1_position'] ?? null),
                CmsMedia::position($content['image_2_position'] ?? null),
                CmsMedia::position($content['image_3_position'] ?? null),
                CmsMedia::position($content['image_4_position'] ?? null),
            ],
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

    public function contact(TourCatalog $catalog, SiteSettings $settings, ManagedPageContent $pages): View
    {
        $managed = $pages->for('contact');
        $page = $managed['page'];

        return view('pages.contact', [
            'page' => $page,
            'pageContent' => $managed['content'],
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

    public function faq(TourCatalog $catalog, SiteSettings $settings, ManagedPageContent $pages): View
    {
        $managed = $pages->for('faq');
        $page = $managed['page'];
        $faqs = Schema::hasTable('faqs')
            ? Faq::query()->published()->ordered()->get()
            : collect();

        return view('pages.faq', [
            'page' => $page,
            'pageContent' => $managed['content'],
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
}
