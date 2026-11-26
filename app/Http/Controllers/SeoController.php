<?php

namespace App\Http\Controllers;

use App\Services\SitemapGenerator;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $host = request()->getSchemeAndHttpHost();
        $lines = [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin/',
            'Disallow: /search/suggest/',
            '',
            'Sitemap: '.$host.'/sitemap.xml',
        ];

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap(SitemapGenerator $sitemap): Response
    {
        $xml = $sitemap->contents() ?? $sitemap->xml();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
