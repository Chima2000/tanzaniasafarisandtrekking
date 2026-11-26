<?php

namespace App\Models;

use App\Support\PackagePricing;
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
        'seo_title',
        'seo_description',
        'seo_keywords',
        'rating',
        'image',
        'images',
        'photo_count',
        'overview',
        'itinerary',
        'includes',
        'excludes',
        'cost_per_person',
        'profit_per_person',
        'blocked_dates',
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
            'blocked_dates' => 'array',
            'cost_per_person' => 'float',
            'profit_per_person' => 'float',
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

            if ($package->cost_per_person === null || $package->profit_per_person === null) {
                $defaults = PackagePricing::defaultsFor([
                    'category' => (string) $package->category,
                    'duration' => (string) $package->duration,
                    'name' => (string) $package->name,
                ]);

                $package->cost_per_person ??= $defaults['cost_per_person'];
                $package->profit_per_person ??= $defaults['profit_per_person'];
            }

            $blocked = array_values(array_filter(
                (array) $package->blocked_dates,
                fn (mixed $date): bool => is_string($date) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) === 1
            ));
            $package->blocked_dates = $blocked;
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

    public function pricePerPerson(): float
    {
        return PackagePricing::sellingPrice($this->cost_per_person, $this->profit_per_person);
    }

    public function formattedPricePerPerson(): string
    {
        return PackagePricing::formatUsd($this->pricePerPerson());
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

        $pricePerPerson = $this->pricePerPerson();

        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'location' => (string) $this->location,
            'duration' => (string) $this->duration,
            'category' => (string) $this->category,
            'seo_title' => (string) ($this->seo_title ?: \site_title($this->name)),
            'seo_description' => (string) ($this->seo_description ?: \truncate_meta((string) $this->overview)),
            'seo_keywords' => (string) ($this->seo_keywords ?: ''),
            'rating' => (string) ($this->rating ?: '5.0'),
            'image' => (string) ($this->image ?: ($images[0] ?? '')),
            'images' => $images,
            'photo_count' => $this->photo_count ?: count($images),
            'overview' => (string) $this->overview,
            'itinerary' => (array) $this->itinerary,
            'gallery' => $images,
            'includes' => (array) $this->includes,
            'excludes' => (array) $this->excludes,
            'cost_per_person' => (float) $this->cost_per_person,
            'profit_per_person' => (float) $this->profit_per_person,
            'price_per_person' => $pricePerPerson,
            'price_label' => PackagePricing::formatUsd($pricePerPerson),
            'blocked_dates' => array_values(array_filter((array) $this->blocked_dates)),
        ];
    }
}
