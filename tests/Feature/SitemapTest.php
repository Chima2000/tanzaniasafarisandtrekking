<?php

use App\Models\BlogPost;
use App\Models\Destination;
use App\Models\Package;
use App\Services\SitemapGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('serves an xml sitemap with core and content urls', function () {
    $package = Package::factory()->create(['slug' => 'seo-safari-package', 'is_published' => true]);
    $destination = Destination::factory()->create(['slug' => 'seo-destination', 'is_published' => true]);
    $post = BlogPost::factory()->create(['slug' => 'seo-blog-post', 'is_published' => true, 'published_at' => now()]);

    $response = $this->get(route('sitemap'));

    $response->assertOk();
    $response->assertHeader('content-type', 'application/xml; charset=UTF-8');
    $response->assertSee('<urlset', false);
    $response->assertSee(route('home'), false);
    $response->assertSee(route('packages.show', $package->slug), false);
    $response->assertSee(route('destinations.show', $destination->slug), false);
    $response->assertSee(route('blog.show', $post->slug), false);
});

it('serves a robots file that points at the sitemap and blocks admin', function () {
    $response = $this->get(route('robots_txt'));

    $response->assertOk();
    $response->assertHeader('content-type', 'text/plain; charset=UTF-8');
    $response->assertSee('Disallow: /admin/', false);
    $response->assertSee('Sitemap: ', false);
    $response->assertSee('/sitemap.xml', false);
});

it('writes a sitemap snapshot to storage', function () {
    Storage::fake('local');

    $result = app(SitemapGenerator::class)->write();

    expect($result['url_count'])->toBeGreaterThan(0)
        ->and(Storage::disk('local')->exists(SitemapGenerator::STORAGE_PATH))->toBeTrue();
});
