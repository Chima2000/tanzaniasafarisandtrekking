<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SitemapGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SitemapController extends Controller
{
    public function __construct(private SitemapGenerator $sitemap) {}

    public function index(): View
    {
        return view('admin.sitemap.index', [
            'sitemapUrl' => route('sitemap'),
            'exists' => $this->sitemap->exists(),
            'generatedAt' => $this->sitemap->generatedAt(),
            'summary' => $this->sitemap->summary(),
        ]);
    }

    public function generate(): RedirectResponse
    {
        if ($this->sitemap->exists()) {
            return back()->with('error', 'Sitemap already exists. Use Regenerate to refresh it.');
        }

        $result = $this->sitemap->write();

        return back()->with('success', 'Sitemap generated with '.$result['url_count'].' URLs.');
    }

    public function regenerate(): RedirectResponse
    {
        if (! $this->sitemap->exists()) {
            return back()->with('error', 'No sitemap has been generated yet. Use Generate first.');
        }

        $result = $this->sitemap->write();

        return back()->with('success', 'Sitemap regenerated with '.$result['url_count'].' URLs.');
    }
}
