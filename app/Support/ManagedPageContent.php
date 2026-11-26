<?php

namespace App\Support;

use App\Models\Page;
use Illuminate\Support\Facades\Schema;

class ManagedPageContent
{
    public function __construct(private PageBlueprint $blueprint) {}

    /**
     * @return array{page: ?Page, content: array<string, mixed>}
     */
    public function for(string $slug): array
    {
        $defaults = $this->blueprint->defaultContent($slug);
        $page = $this->find($slug);
        $pageContent = (array) ($page?->content ?? []);
        $content = array_merge($defaults, $pageContent);

        if ($slug === 'home' && ! array_key_exists('hero_slides', $pageContent)) {
            unset($content['hero_slides']);
        }

        return [
            'page' => $page,
            'content' => $content,
        ];
    }

    public function find(string $slug): ?Page
    {
        if (! Schema::hasTable('pages')) {
            return null;
        }

        $query = Page::query()->where('slug', $slug);

        if (! (request()->boolean('cms_preview') && auth()->check() && auth()->user()?->isAdmin())) {
            $query->where('is_published', true);
        }

        return $query->first();
    }

    public function isPreviewMode(): bool
    {
        return request()->boolean('cms_preview')
            && auth()->check()
            && auth()->user()?->isAdmin();
    }
}
