<?php

namespace App\Models;

use Database\Factories\PackageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Package extends Model
{
    /** @use HasFactory<PackageFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'location',
        'duration',
        'category',
        'rating',
        'image',
        'images',
        'photo_count',
        'overview',
        'itinerary',
        'includes',
        'excludes',
        'is_published',
        'is_featured',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'images' => 'array',
            'itinerary' => 'array',
            'includes' => 'array',
            'excludes' => 'array',
            'photo_count' => 'integer',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Package $package): void {
            if ($package->slug === null || $package->slug === '') {
                $package->slug = Str::slug($package->name);
            }

            $images = array_values(array_filter((array) $package->images));
            $package->images = $images;
            $package->photo_count = count($images);

            if (($package->image === null || $package->image === '') && $images !== []) {
                $package->image = $images[0];
            }
        });
    }

    /**
     * @param  Builder<Package>  $query
     * @return Builder<Package>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * @param  Builder<Package>  $query
     * @return Builder<Package>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
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
        $images = array_values(array_filter((array) $this->images));
        if ($images === [] && $this->image) {
            $images = [$this->image];
        }

        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'location' => (string) $this->location,
            'duration' => (string) $this->duration,
            'category' => (string) $this->category,
            'rating' => (string) ($this->rating ?: '5.0'),
            'image' => (string) ($this->image ?: ($images[0] ?? '')),
            'images' => $images,
            'photo_count' => $this->photo_count ?: count($images),
            'overview' => (string) $this->overview,
            'itinerary' => (array) $this->itinerary,
            'gallery' => $images,
            'includes' => (array) $this->includes,
            'excludes' => (array) $this->excludes,
        ];
    }
}
