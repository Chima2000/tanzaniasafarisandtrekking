<?php

namespace App\Models;

use Database\Factories\BlogPostFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    /** @use HasFactory<BlogPostFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'category',
        'author',
        'image',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'intro',
        'body',
        'quote',
        'sections',
        'tips',
        'tags',
        'cta_label',
        'cta_url_name',
        'cta_url_param',
        'cta_query',
        'is_published',
        'published_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quote' => 'array',
            'sections' => 'array',
            'tips' => 'array',
            'tags' => 'array',
            'cta_query' => 'array',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (BlogPost $post): void {
            if ($post->slug === null || $post->slug === '') {
                $post->slug = Str::slug($post->title);
            }

            if ($post->is_published && $post->published_at === null) {
                $post->published_at = now();
            }
        });
    }

    /**
     * @param  Builder<BlogPost>  $query
     * @return Builder<BlogPost>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return array<string, mixed>
     */
    public function toCatalogArray(): array
    {
        $sections = (array) $this->sections;
        $body = trim((string) $this->body);

        if ($sections === [] && $body !== '') {
            $sections[] = [
                'title' => 'Article',
                'body' => $body,
                'points' => [],
            ];
        }

        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'category' => $this->category ?: 'Travel',
            'date' => optional($this->published_at)->format('M d, Y') ?? $this->created_at?->format('M d, Y'),
            'author' => $this->author ?: 'Admin',
            'seo_title' => $this->seo_title ?: $this->title,
            'seo_description' => $this->seo_description ?: (string) $this->intro,
            'seo_keywords' => (string) ($this->seo_keywords ?: ''),
            'intro' => (string) $this->intro,
            'image' => $this->image ?: '/aventour/assets/images/news/news-1.jpg',
            'quote' => $this->quote,
            'sections' => $sections,
            'tips' => (array) $this->tips,
            'tags' => (array) $this->tags,
            'cta_label' => $this->cta_label ?: 'Plan Your Trip',
            'cta_url_name' => $this->cta_url_name,
            'cta_url_param' => $this->cta_url_param,
            'cta_query' => (array) $this->cta_query,
            'managed' => true,
        ];
    }
}
