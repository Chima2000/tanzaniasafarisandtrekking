<?php

namespace App\Http\Controllers;

use App\Support\PublishedBlogPosts;
use App\Support\TourCatalog;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    public function index(TourCatalog $catalog, PublishedBlogPosts $blogPosts): View
    {
        $packages = $catalog->packages();
        $posts = $blogPosts->all();

        return view('blog.index', [
            'posts' => $posts,
            'pageBg' => $packages->get(3)['image']
                ?? $packages->first()['image']
                ?? '/aventour/assets/images/background/page-title.jpg',
            'awardBg' => $catalog->safariPackages()->first()['image']
                ?? '/aventour/assets/images/background/award-bg.jpg',
            'current' => 'blog',
        ]);
    }

    public function show(string $slug, TourCatalog $catalog, PublishedBlogPosts $blogPosts): View
    {
        $posts = $blogPosts->all();
        $post = $posts->firstWhere('slug', $slug);

        if ($post === null) {
            throw new NotFoundHttpException('Blog post not found.');
        }

        $index = $posts->search(fn (array $item): bool => $item['slug'] === $post['slug']);
        $previous = $index !== false ? $posts->get($index - 1) : null;
        $next = $index !== false ? $posts->get($index + 1) : null;

        $ctaUrl = match (true) {
            ($post['cta_url_name'] ?? null) === 'destinations.show' && isset($post['cta_url_param']) => route('destinations.show', $post['cta_url_param']),
            ($post['cta_url_name'] ?? null) === 'packages.index' => route('packages.index', $post['cta_query'] ?? []),
            default => route('contact'),
        };

        return view('blog.show', [
            'post' => $post,
            'previous' => $previous,
            'next' => $next,
            'relatedPosts' => $posts
                ->reject(fn (array $item): bool => $item['slug'] === $post['slug'])
                ->take(3)
                ->values(),
            'ctaUrl' => $ctaUrl,
            'pageBg' => $post['image'],
            'awardBg' => $catalog->safariPackages()->first()['image']
                ?? $post['image'],
            'current' => 'blog',
        ]);
    }
}
