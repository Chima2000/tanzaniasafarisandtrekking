<?php

namespace App\Models;

use Database\Factories\DestinationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Destination extends Model
{
    /** @use HasFactory<DestinationFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'slug',
        'short',
        'title',
        'full_name',
        'region',
        'needles',
        'starts',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'headline',
        'description',
        'story_intro',
        'story_chapters',
        'highlights',
        'wildlife',
        'best_time',
        'getting_there',
        'tips',
        'facts',
        'faqs',
        'image',
        'is_published',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'needles' => 'array',
            'story_chapters' => 'array',
            'highlights' => 'array',
            'wildlife' => 'array',
            'tips' => 'array',
            'facts' => 'array',
            'faqs' => 'array',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Destination $destination): void {
            if ($destination->slug === null || $destination->slug === '') {
                $destination->slug = Str::slug($destination->title);
            }
        });
    }

    /**
     * @param  Builder<Destination>  $query
     * @return Builder<Destination>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * @param  Builder<Destination>  $query
     * @return Builder<Destination>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('title');
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
        $needles = array_values(array_filter((array) $this->needles));

        return [
            'short' => (string) $this->short,
            'title' => $this->title,
            'full_name' => (string) ($this->full_name ?: $this->title),
            'region' => (string) ($this->region ?: 'Tanzania'),
            'slug' => $this->slug,
            'needles' => $needles !== [] ? $needles : [$this->title],
            'needle' => $needles[0] ?? $this->title,
            'starts' => (string) $this->starts,
            'seo_title' => (string) ($this->seo_title ?: $this->title),
            'seo_description' => (string) $this->seo_description,
            'seo_keywords' => (string) $this->seo_keywords,
            'headline' => (string) $this->headline,
            'description' => (string) $this->description,
            'story_intro' => (string) $this->story_intro,
            'story_chapters' => (array) $this->story_chapters,
            'highlights' => (array) $this->highlights,
            'wildlife' => (array) $this->wildlife,
            'best_time' => (string) $this->best_time,
            'getting_there' => (string) $this->getting_there,
            'tips' => (array) $this->tips,
            'facts' => (array) $this->facts,
            'faqs' => (array) $this->faqs,
            'image' => (string) $this->image,
            'places' => $this->starts ?: 'Tours',
            'package_count' => 0,
        ];
    }
}
