<?php

namespace App\Support;

use App\Models\BlogPost;
use App\Models\Destination;
use App\Models\Package;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TourCatalog
{
    /**
     * @return Collection<int, array{name: string, slug: string, location: string, duration: string, category: string, rating: string, image: string, images: array<int, string>, photo_count: int}>
     */
    public function packages(): Collection
    {
        if ($this->hasManagedPackages()) {
            return Package::query()
                ->published()
                ->ordered()
                ->get()
                ->map(fn (Package $package): array => $package->toCatalogArray())
                ->values();
        }

        return $this->packagesFromFilesystem();
    }

    /**
     * @return Collection<int, array{name: string, slug: string, location: string, duration: string, category: string, rating: string, image: string, images: array<int, string>, photo_count: int}>
     */
    public function packagesFromFilesystem(): Collection
    {
        $photosPath = public_path('photos');

        if (! File::isDirectory($photosPath)) {
            return collect();
        }

        /** @var array<string, list<string>> $grouped */
        $grouped = [];

        foreach (File::files($photosPath) as $file) {
            if (! in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp'], true)) {
                continue;
            }

            $basename = strtolower($file->getFilenameWithoutExtension());

            if (str_starts_with($basename, 'bg-')) {
                continue;
            }

            $packageName = $this->packageNameFromFilename($file->getFilenameWithoutExtension());
            $grouped[$packageName][] = $file->getFilename();
        }

        return collect($grouped)
            ->map(function (array $images, string $name): array {
                usort($images, function (string $a, string $b): int {
                    return $this->imageSortKey($a) <=> $this->imageSortKey($b);
                });

                $category = $this->categoryFor($name);

                $uniqueImages = array_values(array_unique($images));
                $featuredImages = $this->rotateFeaturedImages($uniqueImages, $name);

                return [
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'location' => $this->locationFor($name, $category),
                    'duration' => $this->durationFor($name),
                    'category' => $category,
                    'rating' => $this->ratingFor($name),
                    'image' => $this->photoUrl($featuredImages[0]),
                    'images' => array_map(
                        fn (string $image): string => $this->photoUrl($image),
                        $featuredImages
                    ),
                    'photo_count' => count($featuredImages),
                ];
            })
            ->sortBy(function (array $package): array {
                return [
                    $this->categorySortOrder($package['category']),
                    $this->durationSortKey($package['name']),
                    $package['name'],
                ];
            })
            ->values();
    }

    /**
     * @return Collection<int, array{name: string, slug: string, location: string, duration: string, category: string, rating: string, image: string, images: array<int, string>, photo_count: int}>
     */
    public function safariPackages(): Collection
    {
        return $this->packages()
            ->filter(fn (array $package): bool => in_array($package['category'], ['Safari', 'Day Trip'], true))
            ->values();
    }

    /**
     * @return Collection<int, array{name: string, slug: string, location: string, duration: string, category: string, rating: string, image: string, images: array<int, string>, photo_count: int}>
     */
    public function trekPackages(): Collection
    {
        return $this->packages()
            ->filter(fn (array $package): bool => $package['category'] === 'Trekking')
            ->values();
    }

    private function photoUrl(string $filename): string
    {
        return asset('photos/'.implode('/', array_map('rawurlencode', explode('/', $filename))));
    }

    /**
     * @return Collection<int, array{title: string, image: string, count: string}>
     */
    public function vibes(): Collection
    {
        $used = [];

        return $this->vibesUsing($this->packages(), $used);
    }

    /**
     * @param  Collection<int, array{name: string, slug: string, location: string, duration: string, category: string, rating: string, image: string, images: array<int, string>, photo_count: int}>  $packages
     * @param  array<string, bool>  $used
     * @return Collection<int, array{title: string, image: string, count: string}>
     */
    private function vibesUsing(Collection $packages, array &$used): Collection
    {
        $definitions = [
            [
                'title' => 'Wildlife Safaris',
                'category' => 'Safari',
                'fallback' => 'Serengeti',
            ],
            [
                'title' => 'Kilimanjaro Treks',
                'category' => 'Trekking',
                'fallback' => 'Kilimanjaro',
            ],
            [
                'title' => 'Day Adventures',
                'category' => 'Day Trip',
                'fallback' => 'Day',
            ],
            [
                'title' => 'Cultural Experiences',
                'category' => 'Cultural',
                'fallback' => 'Materuni',
            ],
        ];

        return collect($definitions)->map(function (array $definition) use ($packages, &$used): array {
            $matched = $packages->filter(
                fn (array $package): bool => $package['category'] === $definition['category']
                    || str_contains($package['name'], $definition['fallback'])
            );

            $image = $this->claimDistinctImage($matched, $used, preferSecondary: true)
                ?? $this->claimDistinctImage($matched, $used)
                ?? $this->claimDistinctImage($packages, $used, preferSecondary: true)
                ?? $this->claimDistinctImage($packages, $used)
                ?? asset('aventour/assets/images/resource/vibes-1.jpg');

            $count = max($matched->count(), 1);

            return [
                'title' => $definition['title'],
                'image' => $image,
                'count' => sprintf('%d+ Tours', $count),
            ];
        });
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function destinations(): Collection
    {
        $used = [];

        return $this->destinationsUsing($this->packages(), $used);
    }

    /**
     * @param  Collection<int, array{name: string, slug: string, location: string, duration: string, category: string, rating: string, image: string, images: array<int, string>, photo_count: int}>  $packages
     * @param  array<string, bool>  $used
     * @return Collection<int, array<string, mixed>>
     */
    private function destinationsUsing(Collection $packages, array &$used): Collection
    {
        if ($this->hasManagedDestinations()) {
            return Destination::query()
                ->published()
                ->ordered()
                ->get()
                ->map(function (Destination $destination) use ($packages, &$used): array {
                    $definition = $destination->toCatalogArray();
                    $matched = $this->filterPackagesByNeedles($packages, $definition['needles']);
                    $storedImage = $definition['image'] !== '' ? $definition['image'] : null;

                    return [
                        ...$definition,
                        'places' => sprintf('%02d+ Tours', max($matched->count(), 1)),
                        'image' => $this->claimDistinctImage($matched, $used, preferSecondary: true)
                            ?? $this->claimUnusedImage($storedImage, $used)
                            ?? $this->claimDistinctImage($packages, $used, preferSecondary: true)
                            ?? $this->claimDistinctImage($packages, $used)
                            ?? $storedImage
                            ?? asset('aventour/assets/images/resource/popular-1.jpg'),
                        'package_count' => $matched->count(),
                    ];
                })
                ->values();
        }

        return $this->destinationsFromDefinitionsUsing($packages, $used);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function destinationsFromDefinitions(): Collection
    {
        $used = [];

        return $this->destinationsFromDefinitionsUsing($this->packagesFromFilesystem(), $used);
    }

    /**
     * @param  Collection<int, array{name: string, slug: string, location: string, duration: string, category: string, rating: string, image: string, images: array<int, string>, photo_count: int}>  $packages
     * @param  array<string, bool>  $used
     * @return Collection<int, array<string, mixed>>
     */
    private function destinationsFromDefinitionsUsing(Collection $packages, array &$used): Collection
    {
        return collect($this->destinationDefinitions())->map(function (array $definition) use ($packages, &$used): array {
            $matched = $this->filterPackagesByNeedles($packages, $definition['needles']);

            return [
                ...$definition,
                'needle' => $definition['needles'][0],
                'places' => sprintf('%02d+ Tours', max($matched->count(), 1)),
                'image' => $this->claimDistinctImage($matched, $used, preferSecondary: true)
                    ?? $this->claimDistinctImage($matched, $used)
                    ?? $this->claimDistinctImage($packages, $used, preferSecondary: true)
                    ?? $this->claimDistinctImage($packages, $used)
                    ?? asset('aventour/assets/images/resource/popular-1.jpg'),
                'package_count' => $matched->count(),
            ];
        });
    }

    /**
     * @param  Collection<int, array{name: string, slug: string, location: string, duration: string, category: string, rating: string, image: string, images: array<int, string>, photo_count: int}>  $packages
     * @param  array<string, bool>  $used
     * @return array{
     *     banner: list<string>,
     *     about: list<string>,
     *     journeys: list<string>,
     *     categories_bg: string,
     *     chooseus_bg: string,
     *     testimonial_bg: string,
     *     testimonials: list<string>,
     *     award_bg: string,
     *     gallery: list<string>
     * }
     */
    private function sectionImagesUsing(Collection $packages, array &$used): array
    {
        $fallback = static fn (string $path): string => asset($path);

        $takeMany = function (int $count) use ($packages, &$used): array {
            return $this->takeDistinctImages($packages, $used, $count);
        };

        $takeOne = function (string $fallbackPath) use ($packages, &$used, $fallback): string {
            return $this->claimDistinctImage($packages, $used, preferSecondary: true)
                ?? $this->claimDistinctImage($packages, $used)
                ?? $fallback($fallbackPath);
        };

        return [
            'banner' => $takeMany(3),
            'about' => $takeMany(4),
            'journeys' => $takeMany(5),
            'categories_bg' => $takeOne('aventour/assets/images/background/categories-bg.jpg'),
            'chooseus_bg' => $takeOne('aventour/assets/images/background/chooseus-bg.png'),
            'testimonial_bg' => $takeOne('aventour/assets/images/resource/testimonial-4.png'),
            'testimonials' => $takeMany(3),
            'award_bg' => $takeOne('aventour/assets/images/background/award-bg.jpg'),
            'gallery' => $takeMany(12),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findDestination(string $slug): ?array
    {
        return $this->destinations()->firstWhere('slug', $slug);
    }

    /**
     * @return array{name: string, slug: string, location: string, duration: string, category: string, rating: string, image: string, images: array<int, string>, photo_count: int}|null
     */
    public function findPackage(string $slug): ?array
    {
        $package = $this->packages()->firstWhere('slug', $slug);

        return $package === null ? null : $this->enrichPackage($package);
    }

    /**
     * @param  array{name: string, slug: string, location: string, duration: string, category: string, rating: string, image: string, images: array<int, string>, photo_count: int}  $package
     * @return array{name: string, slug: string, location: string, duration: string, category: string, rating: string, image: string, images: array<int, string>, photo_count: int, overview: string, itinerary: list<array{day: string, title: string, image: ?string, points: list<string>}>, gallery: list<string>, includes: list<string>, excludes: list<string>}
     */
    public function enrichPackage(array $package): array
    {
        $images = array_values(array_unique(array_filter(
            ($package['images'] ?? []) !== [] ? $package['images'] : [$package['image'] ?? null]
        )));

        if (($package['overview'] ?? '') !== '' && array_key_exists('itinerary', $package)) {
            return [
                ...$package,
                'gallery' => $package['gallery'] ?? $images,
                'includes' => $package['includes'] ?? $this->includesFor($package),
                'excludes' => $package['excludes'] ?? [
                    'International airfare',
                    'Visa fees',
                    'Travel insurance',
                    'Personal expenses and tips',
                ],
            ];
        }

        $days = $this->dayCountFor($package['name']);
        $itinerary = [];

        // Use a unique photo per day without wrapping/repeating earlier shots.
        for ($day = 1; $day <= max($days, 1); $day++) {
            $itinerary[] = [
                'day' => sprintf('Day %d', $day),
                'title' => $this->itineraryTitleFor($package, $day, $days),
                'image' => $images[$day - 1] ?? null,
                'points' => $this->itineraryPointsFor($package, $day, $days),
            ];
        }

        return [
            ...$package,
            'overview' => $this->overviewFor($package),
            'itinerary' => $itinerary,
            'gallery' => $images,
            'includes' => $this->includesFor($package),
            'excludes' => [
                'International airfare',
                'Visa fees',
                'Travel insurance',
                'Personal expenses and tips',
            ],
        ];
    }

    /**
     * @return Collection<int, array{name: string, slug: string, location: string, duration: string, category: string, rating: string, image: string, images: array<int, string>, photo_count: int}>
     */
    public function packagesForCategory(?string $category = null): Collection
    {
        $packages = $this->packages();

        if ($category === null || $category === '' || $category === 'all') {
            return $packages;
        }

        return $packages
            ->filter(fn (array $package): bool => strcasecmp($package['category'], $category) === 0)
            ->values();
    }

    /**
     * @param  string|list<string>  $needles
     * @return Collection<int, array{name: string, slug: string, location: string, duration: string, category: string, rating: string, image: string, images: array<int, string>, photo_count: int}>
     */
    public function packagesForDestination(string|array $needles): Collection
    {
        return $this->filterPackagesByNeedles($this->packages(), (array) $needles)->values();
    }

    /**
     * @return Collection<int, array{label: string, slug: string, count: int}>
     */
    public function experienceCategories(): Collection
    {
        return $this->packages()
            ->groupBy('category')
            ->map(fn (Collection $items, string $category): array => [
                'label' => $category,
                'slug' => Str::slug($category),
                'count' => $items->count(),
            ])
            ->sortBy('label')
            ->values();
    }

    /**
     * @return list<string>
     */
    public function galleryImages(int $limit = 12): array
    {
        $packages = $this->packages();
        $used = [];

        foreach ($packages as $package) {
            if (($package['image'] ?? '') !== '') {
                $used[$package['image']] = true;
            }
        }

        return $this->takeDistinctImages($packages, $used, $limit);
    }

    /**
     * Build a homepage media plan that avoids repeating the same photo across
     * decorative sections, destinations, vibes, and gallery tiles.
     *
     * @return array{
     *     vibes: Collection<int, array{title: string, image: string, count: string}>,
     *     destinations: Collection<int, array<string, mixed>>,
     *     sectionImages: array{
     *         banner: list<string>,
     *         about: list<string>,
     *         journeys: list<string>,
     *         categories_bg: string,
     *         chooseus_bg: string,
     *         testimonial_bg: string,
     *         testimonials: list<string>,
     *         award_bg: string,
     *         gallery: list<string>
     *     }
     * }
     */
    public function homeMedia(): array
    {
        $packages = $this->packages();
        $used = [];

        // Keep package-card covers reserved so section art feels different from tour cards.
        foreach ($packages as $package) {
            if (($package['image'] ?? '') !== '') {
                $used[$package['image']] = true;
            }
        }

        $vibes = $this->vibesUsing($packages, $used);
        $destinations = $this->destinationsUsing($packages, $used);
        $sectionImages = $this->sectionImagesUsing($packages, $used);

        return [
            'vibes' => $vibes,
            'destinations' => $destinations,
            'sectionImages' => $sectionImages,
        ];
    }

    /**
     * @return array{
     *     banner: list<string>,
     *     about: list<string>,
     *     journeys: list<string>,
     *     categories_bg: string,
     *     chooseus_bg: string,
     *     testimonial_bg: string,
     *     testimonials: list<string>,
     *     award_bg: string,
     *     gallery: list<string>
     * }
     */
    public function homeSectionImages(): array
    {
        return $this->homeMedia()['sectionImages'];
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function posts(): Collection
    {
        if ($this->hasManagedBlogPosts()) {
            return BlogPost::query()
                ->published()
                ->latest('published_at')
                ->get()
                ->map(fn (BlogPost $post): array => $post->toCatalogArray())
                ->values();
        }

        return $this->postsFromDefinitions();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function postsFromDefinitions(): Collection
    {
        $images = $this->galleryImages(6);
        $fallback = $this->packagesFromFilesystem()->first()['image']
            ?? asset('aventour/assets/images/news/news-1.jpg');

        return collect($this->postDefinitions())->map(function (array $definition, int $index) use ($images, $fallback): array {
            return [
                ...$definition,
                'image' => $images[$index] ?? $fallback,
            ];
        })->values();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findPost(string $slug): ?array
    {
        return $this->posts()->firstWhere('slug', $slug);
    }

    /**
     * @return list<string>
     */
    public function brandImages(int $limit = 16): array
    {
        return $this->galleryImages($limit);
    }

    /**
     * @param  Collection<int, array{image?: string, images?: list<string>}>  $packages
     * @param  array<string, bool>  $used
     * @return list<string>
     */
    private function takeDistinctImages(Collection $packages, array &$used, int $count): array
    {
        $images = [];

        for ($i = 0; $i < $count; $i++) {
            $image = $this->claimDistinctImage($packages, $used, preferSecondary: true)
                ?? $this->claimDistinctImage($packages, $used);

            if ($image === null) {
                break;
            }

            $images[] = $image;
        }

        if ($images === [] && $packages->isNotEmpty()) {
            $images[] = $packages->first()['image']
                ?? asset('aventour/assets/images/resource/popular-1.jpg');
        }

        return array_values(array_unique($images));
    }

    /**
     * Claim an unused photo from the given packages, preferring alternate gallery
     * shots over package cover images so homepage sections stay visually distinct.
     *
     * @param  Collection<int, array{image?: string, images?: list<string>}>  $packages
     * @param  array<string, bool>  $used
     */
    private function claimDistinctImage(Collection $packages, array &$used, bool $preferSecondary = false): ?string
    {
        $candidates = [];

        foreach ($packages as $package) {
            $images = array_values(array_filter((array) ($package['images'] ?? [])));

            if ($images === [] && ($package['image'] ?? '') !== '') {
                $images = [$package['image']];
            }

            if ($preferSecondary && count($images) > 1) {
                $images = array_values(array_slice($images, 1));
            }

            foreach ($images as $image) {
                $candidates[] = $image;
            }
        }

        foreach ($candidates as $image) {
            $claimed = $this->claimUnusedImage($image, $used);

            if ($claimed !== null) {
                return $claimed;
            }
        }

        return null;
    }

    /**
     * @param  array<string, bool>  $used
     */
    private function claimUnusedImage(?string $image, array &$used): ?string
    {
        if ($image === null || $image === '' || isset($used[$image])) {
            return null;
        }

        $used[$image] = true;

        return $image;
    }

    private function packageNameFromFilename(string $basename): string
    {
        if (preg_match('/^(.*?)(?:\s*\d+)?$/u', $basename, $matches) === 1) {
            return trim($matches[1]);
        }

        return trim($basename);
    }

    private function imageSortKey(string $filename): int
    {
        $basename = pathinfo($filename, PATHINFO_FILENAME);

        if (preg_match('/(\d+)$/', $basename, $matches) === 1) {
            return (int) $matches[1];
        }

        return 0;
    }

    /**
     * Rotate which gallery shot leads as the package card cover so neighboring
     * tours feel visually distinct instead of always opening on photo #1.
     *
     * @param  list<string>  $images
     * @return list<string>
     */
    private function rotateFeaturedImages(array $images, string $name): array
    {
        if (count($images) <= 1) {
            return $images;
        }

        $offset = abs(crc32($name)) % count($images);

        if ($offset === 0) {
            return $images;
        }

        return array_values([
            ...array_slice($images, $offset),
            ...array_slice($images, 0, $offset),
        ]);
    }

    private function categoryFor(string $name): string
    {
        if (str_contains($name, 'Kilimanjaro') || str_contains($name, 'Route') || str_contains($name, 'Northern Circuit')) {
            return 'Trekking';
        }

        if (str_contains($name, 'Materuni') || str_contains($name, 'Hot Springs') || str_contains($name, 'Coffee')) {
            return 'Cultural';
        }

        if (preg_match('/\bDays?\b/i', $name) === 1 && preg_match('/^(\d+)\s+Days?\b/i', $name) !== 1) {
            return 'Day Trip';
        }

        if (str_contains($name, '1-Day') || str_ends_with($name, 'Day Trip') || str_ends_with($name, 'Day Safari')) {
            return 'Day Trip';
        }

        return 'Safari';
    }

    private function locationFor(string $name, string $category): string
    {
        return match (true) {
            str_contains($name, 'Kilimanjaro') => 'Mount Kilimanjaro, Tanzania',
            str_contains($name, 'Serengeti') => 'Northern Circuit, Tanzania',
            str_contains($name, 'Ngorongoro') => 'Ngorongoro, Tanzania',
            str_contains($name, 'Tarangire') => 'Tarangire, Tanzania',
            str_contains($name, 'Arusha') => 'Arusha, Tanzania',
            str_contains($name, 'Materuni'), str_contains($name, 'Moshi') => 'Moshi, Tanzania',
            str_contains($name, 'Kikuletwa'), str_contains($name, 'Chemka') => 'Kilimanjaro Region, Tanzania',
            $category === 'Safari' => 'Northern Tanzania',
            default => 'Tanzania',
        };
    }

    private function durationFor(string $name): string
    {
        if (preg_match('/^(\d+)\s+Days?/i', $name, $matches) === 1) {
            $days = (int) $matches[1];
            $nights = max($days - 1, 0);

            if ($nights === 0) {
                return sprintf('%d Day', $days);
            }

            return sprintf(
                '%d Days - %d %s',
                $days,
                $nights,
                $nights === 1 ? 'Night' : 'Nights'
            );
        }

        if (str_contains($name, 'Day')) {
            return '1 Day';
        }

        return 'Custom';
    }

    private function ratingFor(string $name): string
    {
        $seed = crc32($name);
        $rating = 4.6 + (($seed % 5) / 10);

        return number_format(min($rating, 5.0), 1);
    }

    private function categorySortOrder(string $category): int
    {
        return match ($category) {
            'Safari' => 1,
            'Trekking' => 2,
            'Day Trip' => 3,
            'Cultural' => 4,
            default => 5,
        };
    }

    private function durationSortKey(string $name): int
    {
        if (preg_match('/^(\d+)\s+Days?/i', $name, $matches) === 1) {
            return (int) $matches[1];
        }

        return 1;
    }

    private function dayCountFor(string $name): int
    {
        if (preg_match('/^(\d+)\s+Days?/i', $name, $matches) === 1) {
            return max((int) $matches[1], 1);
        }

        return 1;
    }

    /**
     * @param  array{name: string, category: string, location: string, duration: string}  $package
     */
    private function overviewFor(array $package): string
    {
        return match ($package['category']) {
            'Trekking' => sprintf(
                'Join a guided Kilimanjaro ascent on the %s experience. This %s journey is paced for acclimatization, safety, and summit success with expert mountain crews supporting you from gate to Uhuru Peak.',
                $package['name'],
                $package['duration']
            ),
            'Day Trip' => sprintf(
                'Make the most of a focused day in %s with the %s experience. Ideal for travelers short on time who still want authentic wildlife, culture, or scenic highlights.',
                $package['location'],
                $package['name']
            ),
            'Cultural' => sprintf(
                'Connect with local life on the %s experience. This %s itinerary blends community visits, landscapes, and hands-on moments around %s.',
                $package['name'],
                $package['duration'],
                $package['location']
            ),
            default => sprintf(
                'Explore Tanzania’s Northern Circuit on the %s safari. Over %s you will travel through classic wildlife habitats around %s with guided game drives and carefully chosen lodge or camping stays.',
                $package['name'],
                $package['duration'],
                $package['location']
            ),
        };
    }

    /**
     * @param  array{name: string, category: string, location: string}  $package
     */
    private function itineraryTitleFor(array $package, int $day, int $totalDays): string
    {
        if ($package['category'] === 'Trekking') {
            return match (true) {
                $day === 1 => 'Arrive & Begin the Ascent',
                $day === $totalDays => 'Summit Push & Descent',
                default => sprintf('Acclimatization Trek — Day %d', $day),
            };
        }

        if ($totalDays === 1) {
            return sprintf('Full Day in %s', $package['location']);
        }

        return match (true) {
            $day === 1 => 'Arrival & First Game Drive',
            $day === $totalDays => 'Final Safari Highlights & Departure',
            default => sprintf('Wildlife Exploration — Day %d', $day),
        };
    }

    /**
     * @param  array{name: string, category: string, location: string}  $package
     * @return list<string>
     */
    private function itineraryPointsFor(array $package, int $day, int $totalDays): array
    {
        if ($package['category'] === 'Trekking') {
            return match (true) {
                $day === 1 => ['Meet your mountain team', 'Transfer to the trail gate', 'Trek to the first camp'],
                $day === $totalDays => ['Early summit attempt', 'Descend to lower camp', 'Celebration and debrief'],
                default => ['Steady paced hiking', 'Acclimatization walk', 'Hot meals and overnight camp'],
            };
        }

        if ($totalDays === 1) {
            return ['Morning pickup from your hotel', 'Guided excursion with picnic or lodge lunch', 'Return transfer in the afternoon'];
        }

        return match (true) {
            $day === 1 => ['Meet and greet in Arusha area', 'Drive into the park or conservation area', 'Afternoon game drive and overnight'],
            $day === $totalDays => ['Morning wildlife viewing', 'Scenic transfer back toward Arusha', 'Drop-off at hotel or airport'],
            default => ['Sunrise or morning game drive', 'Picnic or lodge lunch', 'Afternoon wildlife viewing'],
        };
    }

    /**
     * @param  array{category: string}  $package
     * @return list<string>
     */
    private function includesFor(array $package): array
    {
        return match ($package['category']) {
            'Trekking' => [
                'Park fees and rescue fees',
                'Mountain guide, cook, and porters',
                'Camping or hut accommodation as per route',
                'Meals on the mountain',
            ],
            'Day Trip' => [
                'Park or attraction fees where applicable',
                'Professional guide',
                'Private transport',
                'Bottled drinking water',
            ],
            default => [
                'Park entry fees',
                'Safari guide and 4x4 game-drive vehicle',
                'Accommodation as per itinerary',
                'Meals as outlined for the tour',
            ],
        };
    }

    /**
     * @param  Collection<int, array{name: string, image: string}>  $packages
     * @param  list<string>  $needles
     * @return Collection<int, array{name: string, image: string}>
     */
    private function filterPackagesByNeedles(Collection $packages, array $needles): Collection
    {
        return $packages->filter(function (array $package) use ($needles): bool {
            foreach ($needles as $needle) {
                if (str_contains($package['name'], $needle)) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function hasManagedPackages(): bool
    {
        return Schema::hasTable('packages') && Package::query()->exists();
    }

    private function hasManagedDestinations(): bool
    {
        return Schema::hasTable('destinations') && Destination::query()->exists();
    }

    private function hasManagedBlogPosts(): bool
    {
        return Schema::hasTable('blog_posts') && BlogPost::query()->published()->exists();
    }

    private function destinationDefinitions(): array
    {
        return [
            [
                'short' => 'Se',
                'title' => 'Serengeti',
                'full_name' => 'Serengeti National Park',
                'region' => 'Northern Tanzania',
                'slug' => 'serengeti',
                'needles' => ['Serengeti'],
                'starts' => 'From 4 Days',
                'seo_title' => 'Serengeti National Park Safari Tours | Tanzania Safaris and Trekking',
                'seo_description' => 'Explore Serengeti National Park safaris with Great Migration wildebeest crossings, Big Five game drives, and private Tanzania itineraries led by local guides.',
                'seo_keywords' => 'Serengeti safari, Serengeti National Park, Great Migration, Big Five Tanzania, wildebeest migration safari',
                'headline' => 'Where the Plains Tell Their Own Story',
                'description' => 'Endless plains, big cat drama, and the legendary Great Migration across Tanzania’s most iconic national park.',
                'story_intro' => 'In the Serengeti, every sunrise opens a new chapter. Lions patrol golden grass, elephants move like quiet mountains, and millions of wildebeest rewrite the landscape as seasons turn. This is Northern Tanzania’s signature wildlife stage—vast, cinematic, and unforgettable.',
                'story_chapters' => [
                    [
                        'label' => 'Chapter 01',
                        'title' => 'Arrival on the Endless Plains',
                        'body' => 'Your Serengeti story begins as the bush road opens into open grassland. Acacias stand like punctuation marks across the horizon while your guide reads tracks, bird calls, and weather for the day’s first sightings.',
                        'points' => ['Scenic transfer into the park', 'Afternoon game drive orientation', 'Sunset light over the savannah'],
                    ],
                    [
                        'label' => 'Chapter 02',
                        'title' => 'The Great Migration Unfolds',
                        'body' => 'Depending on the month, herds gather, calve, move, or brave river crossings. Following the migration is less about a single viewpoint and more about timing, patience, and being present when the plains come alive.',
                        'points' => ['Seasonal herd movements', 'Predator-prey drama', 'Best viewing windows with local insight'],
                    ],
                    [
                        'label' => 'Chapter 03',
                        'title' => 'Big Cat Country After Dark Stories',
                        'body' => 'Serengeti is famous for lions, cheetahs on kopjes, and leopard sightings in riverine woodland. Quiet game drives reward travelers who linger, listen, and let the bush set the pace.',
                        'points' => ['Lion prides and hunting behavior', 'Cheetahs on open flats', 'Leopard habitat along drainage lines'],
                    ],
                    [
                        'label' => 'Chapter 04',
                        'title' => 'Evenings Under a Safari Sky',
                        'body' => 'Campfire conversations, star-filled nights, and the distant call of hyena close each day. From mobile camps to lodge comfort, Serengeti nights keep the wilderness close without losing warmth and welcome.',
                        'points' => ['Lodge or camping options', 'Guided storytelling around wildlife', 'Rest before the next dawn drive'],
                    ],
                ],
                'highlights' => [
                    'UNESCO-listed Serengeti ecosystem',
                    'Great Migration and river crossings in season',
                    'Excellent Big Five and big-cat viewing',
                    'Open plains ideal for photography',
                ],
                'wildlife' => ['Lion', 'Leopard', 'Cheetah', 'Elephant', 'Buffalo', 'Wildebeest', 'Zebra', 'Giraffe', 'Hippo', 'Crocodile'],
                'best_time' => 'June to October for dry-season clarity and classic predator viewing; December to March for southern calving grounds; July to October for western/northern river-crossing drama.',
                'getting_there' => 'Most travelers fly into Kilimanjaro International (JRO) or Arusha, then continue by safari vehicle or scheduled/light aircraft into Serengeti airstrips depending on itinerary.',
                'tips' => [
                    'Plan at least 3–4 nights in Serengeti for meaningful wildlife time.',
                    'Match camp location to migration season when possible.',
                    'Bring binoculars, neutral clothing, and dust protection for cameras.',
                ],
                'facts' => [
                    ['label' => 'Park Status', 'value' => 'UNESCO World Heritage'],
                    ['label' => 'Best For', 'value' => 'Migration & Big Cats'],
                    ['label' => 'Typical Stay', 'value' => '3–5 Nights'],
                    ['label' => 'Safari Style', 'value' => 'Game Drives'],
                ],
                'faqs' => [
                    [
                        'question' => 'When is the best time for a Serengeti safari?',
                        'answer' => 'Dry months from June to October are excellent overall. For calving, aim for southern Serengeti around December to March. For dramatic river crossings, focus on the western and northern corridors roughly July to October.',
                    ],
                    [
                        'question' => 'Can I combine Serengeti with Ngorongoro and Tarangire?',
                        'answer' => 'Yes. Most Northern Circuit itineraries pair Serengeti with Tarangire and Ngorongoro for contrasting habitats and stronger wildlife variety.',
                    ],
                    [
                        'question' => 'Is Serengeti suitable for first-time safari travelers?',
                        'answer' => 'Absolutely. Clear sightlines, high wildlife density, and experienced local guides make Serengeti one of the best introductions to African safari.',
                    ],
                ],
            ],
            [
                'short' => 'Ng',
                'title' => 'Ngorongoro',
                'full_name' => 'Ngorongoro Conservation Area',
                'region' => 'Northern Tanzania',
                'slug' => 'ngorongoro',
                'needles' => ['Ngorongoro'],
                'starts' => 'From 1 Day',
                'seo_title' => 'Ngorongoro Crater Safari | Tanzania Safaris and Trekking',
                'seo_description' => 'Discover Ngorongoro Crater safaris in a UNESCO conservation area—dense wildlife, Maasai culture, and dramatic caldera views on day trips or multi-day Tanzania itineraries.',
                'seo_keywords' => 'Ngorongoro Crater safari, Ngorongoro Conservation Area, UNESCO Tanzania safari, Maasai culture tour',
                'headline' => 'A Caldera Where Wildlife Gathers Close',
                'description' => 'A UNESCO crater sanctuary packed with wildlife, Maasai culture, and one of Africa’s most dramatic viewpoints.',
                'story_intro' => 'Descend the rim of Ngorongoro and the world narrows into a natural amphitheater. Lakes glitter below, forests cling to the walls, and wildlife concentrates in a living bowl unlike anywhere else on the Northern Circuit.',
                'story_chapters' => [
                    [
                        'label' => 'Chapter 01',
                        'title' => 'Standing on the Crater Rim',
                        'body' => 'Cloud and light shift across the caldera as you arrive. From the rim, the floor looks quiet—until your guide points out elephant trails, flamingo color on the lake, and the distant dust of buffalo herds.',
                        'points' => ['Rim viewpoints and orientation', 'Conservation area context', 'Photo stops before descent'],
                    ],
                    [
                        'label' => 'Chapter 02',
                        'title' => 'Game Drive Inside the Bowl',
                        'body' => 'On the crater floor, habitats compress: grassland, swamp, forest edge, and soda lake. Sightings can come quickly—black rhino possibilities, lion prides, hippos, and dense herds in one compact landscape.',
                        'points' => ['High wildlife density', 'Rhino and big mammal chances', 'Guided crater circuit'],
                    ],
                    [
                        'label' => 'Chapter 03',
                        'title' => 'Maasai Stories Beyond the View',
                        'body' => 'Ngorongoro is a conservation area where people and wildlife share space. Cultural encounters, when arranged respectfully, add human texture to the crater’s natural drama.',
                        'points' => ['Optional cultural visits', 'Community context', 'Responsible travel etiquette'],
                    ],
                    [
                        'label' => 'Chapter 04',
                        'title' => 'From Day Trip to Overnight Rim Stays',
                        'body' => 'Some travelers visit Ngorongoro as a powerful day safari; others overnight on the rim for sunrise and a fuller crater experience before continuing to Serengeti or Tarangire.',
                        'points' => ['Flexible 1-day or multi-day plans', 'Rim lodge atmosphere', 'Easy Northern Circuit links'],
                    ],
                ],
                'highlights' => [
                    'UNESCO World Heritage crater landscape',
                    'Exceptional wildlife concentration',
                    'Scenic rim viewpoints',
                    'Cultural depth with Maasai communities',
                ],
                'wildlife' => ['Lion', 'Elephant', 'Buffalo', 'Black rhino', 'Hippo', 'Flamingo', 'Hyena', 'Wildebeest', 'Zebra'],
                'best_time' => 'Year-round destination. Dry months (June–October) offer easier crater-floor driving; green season brings lush scenery and fewer vehicles on some days.',
                'getting_there' => 'About a half-day drive from Arusha via the Conservation Area gate. Often combined with Tarangire inbound and Serengeti outbound on classic safari circuits.',
                'tips' => [
                    'Start early for crater descent timing and cooler wildlife activity.',
                    'Pack a warm layer—rim mornings can be chilly.',
                    'Ask about picnic rules and crater stay limits for your permit day.',
                ],
                'facts' => [
                    ['label' => 'Status', 'value' => 'UNESCO Conservation Area'],
                    ['label' => 'Best For', 'value' => 'Crater Wildlife'],
                    ['label' => 'Typical Visit', 'value' => '1–2 Days'],
                    ['label' => 'Highlight', 'value' => 'Caldera Game Drive'],
                ],
                'faqs' => [
                    [
                        'question' => 'Is one day enough for Ngorongoro Crater?',
                        'answer' => 'A full day crater game drive is rewarding. Overnighting on the rim adds sunrise atmosphere and softer pacing, especially if combined with Serengeti.',
                    ],
                    [
                        'question' => 'What makes Ngorongoro different from Serengeti?',
                        'answer' => 'Ngorongoro concentrates wildlife inside a collapsed volcanic caldera, creating intimate viewing. Serengeti offers vast open plains and seasonal migration movement across a much larger ecosystem.',
                    ],
                    [
                        'question' => 'Can families visit Ngorongoro?',
                        'answer' => 'Yes, with age and vehicle guidelines for crater access. We tailor pacing, picnic stops, and route choices for comfortable family safaris.',
                    ],
                ],
            ],
            [
                'short' => 'Ki',
                'title' => 'Kilimanjaro',
                'full_name' => 'Mount Kilimanjaro',
                'region' => 'Kilimanjaro Region, Tanzania',
                'slug' => 'kilimanjaro',
                'needles' => ['Kilimanjaro'],
                'starts' => 'From 5 Days',
                'seo_title' => 'Kilimanjaro Trekking Tours & Climb Routes | Tanzania Safaris and Trekking',
                'seo_description' => 'Climb Mount Kilimanjaro with guided treks on Machame, Marangu, Lemosho, Rongai, Umbwe, and Northern Circuit routes—paced for safety, acclimatization, and summit success.',
                'seo_keywords' => 'Kilimanjaro trek, climb Mount Kilimanjaro, Machame route, Lemosho route, Marangu route, Kilimanjaro guide',
                'headline' => 'Africa’s Roof, One Careful Step at a Time',
                'description' => 'Africa’s highest peak—guided treks on classic routes with experienced mountain crews and carefully paced itineraries.',
                'story_intro' => 'Kilimanjaro is not conquered in a rush. It is earned through forest mist, alpine desert silence, and a midnight summit push under cold stars. With the right route, crew, and pacing, the mountain becomes a powerful personal story—not just a peak on a map.',
                'story_chapters' => [
                    [
                        'label' => 'Chapter 01',
                        'title' => 'Choosing Your Route Story',
                        'body' => 'Machame’s scenic ridges, Marangu’s hut comfort, Lemosho’s quieter western approach, Rongai’s drier north side, Umbwe’s steep challenge, and the longer Northern Circuit each tell a different ascent narrative.',
                        'points' => ['Route matching to fitness and time', 'Acclimatization profile', 'Hut vs camping style'],
                    ],
                    [
                        'label' => 'Chapter 02',
                        'title' => 'Through Forest Into Moorland',
                        'body' => 'Early days move from rainforest birdsong into heather and lobelia landscapes. Guides keep a polite “pole pole” rhythm while your body learns altitude and your eyes adjust to changing ecosystems.',
                        'points' => ['Gradual elevation gain', 'Crew support and hot meals', 'Daily health monitoring'],
                    ],
                    [
                        'label' => 'Chapter 03',
                        'title' => 'Alpine Desert and High Camp',
                        'body' => 'The mountain grows stark and lunar. Rest, hydration, and honest communication matter most as you prepare for the summit bid from high camp.',
                        'points' => ['Layered clothing strategy', 'Sleep and recovery focus', 'Weather-aware timing'],
                    ],
                    [
                        'label' => 'Chapter 04',
                        'title' => 'Summit Dawn and Safe Descent',
                        'body' => 'Uhuru Peak at sunrise is the emotional crest—then the story turns toward careful descent, celebration, and the long walk back to green hills and warm beds.',
                        'points' => ['Guided summit push', 'Glacier and crater views', 'Supported descent and debrief'],
                    ],
                ],
                'highlights' => [
                    'Highest free-standing mountain on Earth (5,895 m)',
                    'Multiple route options for different experience levels',
                    'Professional guides, cooks, and porter teams',
                    'Combine with Northern Circuit safari after the trek',
                ],
                'wildlife' => ['Colobus monkeys (lower forest)', 'Birdlife on trail approaches', 'Unique alpine flora zones'],
                'best_time' => 'Primary windows are January–March and June–October for more stable climbing weather. Shoulder months can work with flexible planning.',
                'getting_there' => 'Fly into Kilimanjaro International Airport (JRO). Most climbs start from Moshi or Arusha with gear checks and briefings before gate transfer.',
                'tips' => [
                    'Favor longer routes (7–9 days) for better acclimatization odds.',
                    'Train with loaded day hikes before arrival.',
                    'Invest in broken-in boots, quality layers, and a reliable sleeping system.',
                ],
                'facts' => [
                    ['label' => 'Summit', 'value' => 'Uhuru Peak 5,895 m'],
                    ['label' => 'Best For', 'value' => 'Guided Trekking'],
                    ['label' => 'Typical Duration', 'value' => '5–9 Days'],
                    ['label' => 'Style', 'value' => 'Camping or Huts'],
                ],
                'faqs' => [
                    [
                        'question' => 'Which Kilimanjaro route is best for beginners?',
                        'answer' => 'Machame and Lemosho are popular scenic choices with strong acclimatization profiles when given enough days. Marangu offers hut stays and a familiar out-and-back path.',
                    ],
                    [
                        'question' => 'Do I need technical climbing skills?',
                        'answer' => 'No technical mountaineering is required on standard routes, but strong fitness, mental stamina, and respect for altitude are essential.',
                    ],
                    [
                        'question' => 'Can I add a safari after Kilimanjaro?',
                        'answer' => 'Yes. Many travelers recover with a short rest, then continue to Tarangire, Ngorongoro, and Serengeti for a complete Tanzania journey.',
                    ],
                ],
            ],
            [
                'short' => 'Ta',
                'title' => 'Tarangire',
                'full_name' => 'Tarangire National Park',
                'region' => 'Northern Tanzania',
                'slug' => 'tarangire',
                'needles' => ['Tarangire'],
                'starts' => 'From 1 Day',
                'seo_title' => 'Tarangire National Park Safari | Elephants & Baobabs in Tanzania',
                'seo_description' => 'Visit Tarangire National Park for elephant herds, baobab landscapes, and quieter Northern Circuit game drives—ideal as a day safari or the opening chapter of a longer Tanzania trip.',
                'seo_keywords' => 'Tarangire safari, Tarangire National Park, elephant safari Tanzania, baobab safari, day safari from Arusha',
                'headline' => 'Baobabs, Elephants, and Soft Morning Light',
                'description' => 'Baobab landscapes, elephant herds, and quiet safari trails just a short drive from Arusha.',
                'story_intro' => 'Tarangire feels intimate compared with Serengeti’s scale. Ancient baobabs rise from dry earth, the river draws wildlife in the dry season, and elephant families write the day’s plot in dust and water.',
                'story_chapters' => [
                    [
                        'label' => 'Chapter 01',
                        'title' => 'A Short Road Into Big Wilderness',
                        'body' => 'From Arusha, Tarangire is an easy first safari chapter. Within hours you can be watching zebra, giraffe, and the first silhouettes of elephants against baobab trunks.',
                        'points' => ['Convenient day-safari access', 'Strong first-game-drive energy', 'Scenic baobab photography'],
                    ],
                    [
                        'label' => 'Chapter 02',
                        'title' => 'Elephant Country by the River',
                        'body' => 'In the dry months, Tarangire’s river system becomes a wildlife magnet. Large elephant gatherings, predators nearby, and birdlife along water create layered sightings in a compact park.',
                        'points' => ['Famous elephant concentrations', 'Riverine game viewing', 'Birdwatching opportunities'],
                    ],
                    [
                        'label' => 'Chapter 03',
                        'title' => 'Quiet Trails Between Iconic Parks',
                        'body' => 'Tarangire often opens multi-day circuits before Manyara viewpoints, Ngorongoro, and Serengeti. It adds texture—dry-country giants and tree-studded horizons—before the crater and plains.',
                        'points' => ['Ideal circuit opener', 'Contrasting habitats', 'Flexible 1–3 night stays'],
                    ],
                    [
                        'label' => 'Chapter 04',
                        'title' => 'Golden Hour Among the Giants',
                        'body' => 'Late light turns baobabs copper and elephants into moving sculpture. Whether you stay for a day or longer, Tarangire rewards unhurried loops and patient guides.',
                        'points' => ['Sunset game drives', 'Picnic or lodge lunches', 'Easy pairing with crater safaris'],
                    ],
                ],
                'highlights' => [
                    'Iconic baobab scenery',
                    'Outstanding dry-season elephant viewing',
                    'Excellent add-on to Ngorongoro and Serengeti',
                    'Great value day safari from Arusha',
                ],
                'wildlife' => ['Elephant', 'Lion', 'Leopard', 'Buffalo', 'Giraffe', 'Zebra', 'Wildebeest', 'Python', 'Diverse birdlife'],
                'best_time' => 'June to October is peak for river-focused wildlife density. Green season still offers beauty, birds, and fewer crowds.',
                'getting_there' => 'Roughly a 2-hour drive from Arusha, making Tarangire one of the most accessible national parks on the Northern Circuit.',
                'tips' => [
                    'Ideal as day one of a camping or lodge safari circuit.',
                    'Carry binoculars for distant elephant herds and birdlife.',
                    'Combine with Ngorongoro the following day for contrast.',
                ],
                'facts' => [
                    ['label' => 'Access', 'value' => 'Near Arusha'],
                    ['label' => 'Best For', 'value' => 'Elephants & Baobabs'],
                    ['label' => 'Typical Visit', 'value' => '1–2 Days'],
                    ['label' => 'Circuit Role', 'value' => 'Safari Opener'],
                ],
                'faqs' => [
                    [
                        'question' => 'Is Tarangire worth it as a day trip?',
                        'answer' => 'Yes. It is one of the strongest day safaris from Arusha, especially in the dry season when elephants concentrate near water.',
                    ],
                    [
                        'question' => 'How does Tarangire compare with Serengeti?',
                        'answer' => 'Tarangire is smaller and often quieter, with signature baobabs and elephant herds. Serengeti is vastly larger with migration spectacle and expansive plains.',
                    ],
                    [
                        'question' => 'Should Tarangire come before Ngorongoro?',
                        'answer' => 'Many classic itineraries start in Tarangire, continue to Serengeti, and finish with Ngorongoro—an efficient loop with varied landscapes.',
                    ],
                ],
            ],
            [
                'short' => 'Ar',
                'title' => 'Arusha',
                'full_name' => 'Arusha & Nearby Day Adventures',
                'region' => 'Northern Tanzania Gateway',
                'slug' => 'arusha',
                'needles' => ['Arusha', 'Materuni', 'Kikuletwa', 'Chemka'],
                'starts' => 'Day Safari',
                'seo_title' => 'Arusha Day Safaris & Cultural Tours | Tanzania Trip Gateway',
                'seo_description' => 'Use Arusha as your Tanzania safari gateway—Arusha National Park day safaris, Materuni waterfalls and coffee tours, Chemka hot springs, and smooth links to Tarangire and Ngorongoro.',
                'seo_keywords' => 'Arusha National Park, Arusha day safari, Materuni waterfalls, Chemka hot springs, Tanzania safari gateway',
                'headline' => 'The Gateway Where Tanzania Trips Begin',
                'description' => 'Gateway to the Northern Circuit—day trips, cultural visits, and easy links into Tarangire and Ngorongoro.',
                'story_intro' => 'Arusha is more than a transit city. It is the first page of many Tanzania stories: coffee-scented hills, a national park beneath Meru’s silhouette, waterfall spray at Materuni, and warm springs at Chemka before the bigger parks call you north and west.',
                'story_chapters' => [
                    [
                        'label' => 'Chapter 01',
                        'title' => 'Touchdown and Soft Landing',
                        'body' => 'After landing at Kilimanjaro International or arriving overland, Arusha offers rest, gear checks, briefings, and the first taste of Northern Tanzania hospitality before your main safari or trek begins.',
                        'points' => ['Airport meet-and-greet options', 'Pre-trek or pre-safari briefing', 'Comfortable overnight staging'],
                    ],
                    [
                        'label' => 'Chapter 02',
                        'title' => 'Arusha National Park Day Safari',
                        'body' => 'A compact park with giraffes on open glades, colobus in forest, crater lakes, and views toward Mount Meru. Perfect when you want wildlife without a long transfer.',
                        'points' => ['Half-day or full-day game viewing', 'Forest and lake habitats', 'Ideal arrival or buffer day'],
                    ],
                    [
                        'label' => 'Chapter 03',
                        'title' => 'Materuni Waterfalls and Coffee Culture',
                        'body' => 'In the Moshi foothills, village paths lead to waterfall swim spots and hands-on coffee experiences—an engaging cultural counterpoint to park game drives.',
                        'points' => ['Waterfall hike and swim', 'Coffee tour storytelling', 'Local Chagga hospitality'],
                    ],
                    [
                        'label' => 'Chapter 04',
                        'title' => 'Chemka Hot Springs and Onward Adventures',
                        'body' => 'Clear spring pools at Kikuletwa (Chemka) make a refreshing day trip. From Arusha you can then launch into Tarangire, Ngorongoro, Serengeti, or Kilimanjaro with logistics already aligned.',
                        'points' => ['Hot springs swim stop', 'Flexible add-on days', 'Seamless Northern Circuit departures'],
                    ],
                ],
                'highlights' => [
                    'Primary Northern Circuit launch point',
                    'Arusha National Park wildlife day trips',
                    'Materuni waterfalls and coffee experiences',
                    'Chemka / Kikuletwa hot springs escapes',
                ],
                'wildlife' => ['Giraffe', 'Buffalo', 'Colobus monkey', 'Flamingo (seasonal lakes)', 'Bushbuck', 'Rich birdlife'],
                'best_time' => 'Year-round for day trips. Dry months favor clearer mountain views; green season deepens waterfall flow and coffee-country scenery.',
                'getting_there' => 'Kilimanjaro International Airport (JRO) is the main arrival gateway, with road transfer to Arusha or Moshi depending on your plan.',
                'tips' => [
                    'Add a buffer day before Kilimanjaro for rest and briefing.',
                    'Use Arusha National Park when flight timing blocks a longer park day.',
                    'Combine Materuni and coffee culture for an active non-park day.',
                ],
                'facts' => [
                    ['label' => 'Role', 'value' => 'Safari & Trek Gateway'],
                    ['label' => 'Best For', 'value' => 'Day Trips & Staging'],
                    ['label' => 'Nearby Peak', 'value' => 'Mount Meru Views'],
                    ['label' => 'Add-Ons', 'value' => 'Waterfalls & Springs'],
                ],
                'faqs' => [
                    [
                        'question' => 'Should I stay in Arusha before my safari?',
                        'answer' => 'Yes for most itineraries. An arrival night helps with rest, briefing, and an optional day trip before heading to Tarangire or the crater.',
                    ],
                    [
                        'question' => 'What day trips are best from Arusha?',
                        'answer' => 'Arusha National Park for wildlife, Materuni for waterfalls and coffee culture, and Chemka hot springs for a relaxed swim day are top choices.',
                    ],
                    [
                        'question' => 'Is Arusha close to Kilimanjaro climbs?',
                        'answer' => 'Climbs often stage from Moshi or Arusha. We arrange transfers, gear checks, and route briefings based on your chosen Kilimanjaro itinerary.',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function postDefinitions(): array
    {
        return [
            [
                'slug' => 'best-time-serengeti-great-migration',
                'title' => 'Best Time to Visit Serengeti for the Great Migration',
                'category' => 'Safari Tips',
                'date' => 'Nov 12, 2025',
                'author' => 'Guide Team',
                'seo_title' => 'Best Time for Serengeti Great Migration | Tanzania Safaris and Trekking',
                'seo_description' => 'Learn when to visit Serengeti for the Great Migration, calving season, river crossings, and how to plan a Northern Circuit safari around wildebeest movements.',
                'intro' => 'The Great Migration is not a single date on the calendar. It is a year-long circuit across the Serengeti-Mara ecosystem, and the best time to visit depends on the wildlife chapter you want to witness—newborn calves on short grass, thunderous river crossings, or quiet predator drama on open plains.',
                'quote' => [
                    'text' => 'In the Serengeti, timing is less about chasing a crowd and more about meeting the herds where the grass and rain lead them.',
                    'name' => 'Guide Team',
                ],
                'sections' => [
                    [
                        'title' => 'Match the Season to the Story',
                        'body' => 'From roughly December to March, southern Serengeti and Ndutu host calving season. Mid-year brings herds north toward the Western Corridor and Grumeti, then toward the northern Serengeti for Mara River crossings in peak dry months.',
                        'points' => [
                            'Dec–Mar: calving and predator action in the south',
                            'Apr–Jun: herds push west and north with the rains fading',
                            'Jul–Oct: classic northern crossings and dry-season game drives',
                        ],
                    ],
                    [
                        'title' => 'Plan Beyond One Famous Crossing',
                        'body' => 'River crossings are spectacular, but they are not guaranteed on any single day. A strong Serengeti safari balances migration timing with Big Five viewing, flexible game-drive plans, and nights that keep you close to the action.',
                        'points' => [
                            'Build 3–4 nights in the right Serengeti sector',
                            'Pair with Ngorongoro or Tarangire for variety',
                            'Keep daily plans flexible for herd movement reports',
                        ],
                    ],
                ],
                'tips' => [
                    'Ask your guide which Serengeti sector fits your travel month.',
                    'Pack layers for cool mornings and warm midday plains.',
                    'Allow buffer days if river crossings are your top priority.',
                    'Combine migration timing with lodge or camping style that matches your pace.',
                ],
                'tags' => ['Serengeti', 'Migration', 'Safari Tips'],
                'cta_label' => 'Explore Serengeti Safaris',
                'cta_url_name' => 'destinations.show',
                'cta_url_param' => 'serengeti',
            ],
            [
                'slug' => 'choosing-kilimanjaro-route-machame-lemosho-marangu',
                'title' => 'Choosing Your Kilimanjaro Route: Machame, Lemosho, or Marangu',
                'category' => 'Trekking',
                'date' => 'Oct 28, 2025',
                'author' => 'Mountain Crew',
                'seo_title' => 'Kilimanjaro Routes Compared: Machame, Lemosho, Marangu',
                'seo_description' => 'Compare Kilimanjaro trek routes Machame, Lemosho, and Marangu—duration, scenery, acclimatization, and which climb style fits your fitness and summit goals.',
                'intro' => 'Every Kilimanjaro route reaches the same roof of Africa, but the journey feels different. Machame is scenic and popular, Lemosho offers space and strong acclimatization, and Marangu remains the classic hut-to-hut path. The right choice depends on days available, comfort style, and how carefully you want to gain altitude.',
                'quote' => [
                    'text' => 'Pole pole is not just a saying on Kilimanjaro—it is the strategy that turns a hard climb into a successful summit story.',
                    'name' => 'Mountain Crew',
                ],
                'sections' => [
                    [
                        'title' => 'Machame: Scenic and Steady',
                        'body' => 'Often called the Whiskey Route, Machame balances rainforest, moorland, and alpine desert with a profile that supports acclimatization when paced well. It suits trekkers who want memorable scenery and a camping-style climb.',
                        'points' => [
                            'Typically 6–7 days',
                            'Camping trek with varied landscapes',
                            'Popular for first-time high-altitude climbers with good fitness',
                        ],
                    ],
                    [
                        'title' => 'Lemosho and Marangu: Space vs Simplicity',
                        'body' => 'Lemosho usually starts quieter on the western approach and pairs beautifully with longer itineraries. Marangu uses mountain huts and a more direct profile—simpler logistics, but less gradual for some climbers unless extra days are added.',
                        'points' => [
                            'Lemosho: excellent scenery and acclimatization on 7–8 day plans',
                            'Marangu: hut comfort and a classic out-and-back feel',
                            'Longer itineraries generally improve summit success odds',
                        ],
                    ],
                ],
                'tips' => [
                    'Choose days for acclimatization, not only for speed.',
                    'Train with long walks and pack weight before you arrive.',
                    'Confirm gear, crew ratios, and rescue protocols with your operator.',
                    'Talk honestly about sleep style: tents versus huts.',
                ],
                'tags' => ['Kilimanjaro', 'Trekking', 'Routes'],
                'cta_label' => 'Browse Kilimanjaro Treks',
                'cta_url_name' => 'packages.index',
                'cta_url_param' => null,
                'cta_query' => ['category' => 'Trekking'],
            ],
            [
                'slug' => 'ngorongoro-crater-one-day-what-to-expect',
                'title' => 'Ngorongoro Crater in One Day: What to Expect',
                'category' => 'Destinations',
                'date' => 'Oct 10, 2025',
                'author' => 'Safari Desk',
                'seo_title' => 'Ngorongoro Crater Day Safari: What to Expect',
                'seo_description' => 'What a one-day Ngorongoro Crater safari feels like—rim views, wildlife density, timing tips, and how to fit the caldera into a Northern Circuit itinerary.',
                'intro' => 'A day in Ngorongoro Crater compresses an entire safari into one dramatic bowl. From the rim overlook to the crater floor, you move through grassland, swamp edges, and lake shore while wildlife gathers in unusually close quarters. It is intense, scenic, and one of Tanzania’s most efficient wildlife days.',
                'quote' => [
                    'text' => 'Ngorongoro does not ask for a week to impress you—one careful circuit can fill a camera and a lifetime of stories.',
                    'name' => 'Safari Desk',
                ],
                'sections' => [
                    [
                        'title' => 'Morning on the Rim, Wildlife on the Floor',
                        'body' => 'Most day trips begin with a rim viewpoint, then descend for a guided game drive. Sightings can include lion, elephant, buffalo, hippo, flamingos, and—on lucky days—black rhino. Picnic lunch is usually taken at a designated site inside the crater.',
                        'points' => [
                            'Early start from Arusha or the crater rim',
                            'Dense wildlife in a compact landscape',
                            'Return ascent with golden-hour rim light on longer days',
                        ],
                    ],
                    [
                        'title' => 'Make One Day Count',
                        'body' => 'Because crater time is regulated and popular, a good guide, clear pacing, and realistic expectations matter. Some travelers overnight on the rim to soften the schedule and catch a fuller morning.',
                        'points' => [
                            'Bring a jacket for cool rim temperatures',
                            'Pair with Tarangire or Lake Manyara on multi-day plans',
                            'Consider a rim night if you want a less rushed experience',
                        ],
                    ],
                ],
                'tips' => [
                    'Confirm crater fees and picnic arrangements in advance.',
                    'Use binoculars—habitat shifts quickly across short distances.',
                    'Respect vehicle rules and designated stop areas.',
                    'Ask about cultural visits only when arranged respectfully.',
                ],
                'tags' => ['Ngorongoro', 'Day Safari', 'Wildlife'],
                'cta_label' => 'Visit Ngorongoro',
                'cta_url_name' => 'destinations.show',
                'cta_url_param' => 'ngorongoro',
            ],
            [
                'slug' => 'tarangire-perfect-safari-opener-from-arusha',
                'title' => 'Why Tarangire Is the Perfect Safari Opener from Arusha',
                'category' => 'Safari Tips',
                'date' => 'Sep 22, 2025',
                'author' => 'Guide Team',
                'seo_title' => 'Tarangire Safari from Arusha: Perfect Northern Circuit Opener',
                'seo_description' => 'Why Tarangire National Park is an ideal first safari stop from Arusha—elephants, baobabs, birdlife, and easy pairing with Ngorongoro and Serengeti.',
                'intro' => 'Tarangire sits close enough to Arusha for a strong first safari day, yet wild enough to feel like classic Tanzania. Baobabs punctuate the skyline, elephant herds gather near the river, and birdlife thrives across swamp and savannah. For many travelers, it is the perfect overture before Ngorongoro and Serengeti.',
                'quote' => [
                    'text' => 'Start in Tarangire and your safari language begins early—tracks, elephant dust, and the quiet confidence of a good first game drive.',
                    'name' => 'Guide Team',
                ],
                'sections' => [
                    [
                        'title' => 'Elephants, Baobabs, and Easy Access',
                        'body' => 'From Arusha, Tarangire is a practical first park: less transfer fatigue, immediate wildlife immersion, and landscapes that photograph beautifully in both dry and green seasons.',
                        'points' => [
                            'Large elephant gatherings in dry months',
                            'Iconic baobab scenery',
                            'Excellent birding across riverine and swamp habitats',
                        ],
                    ],
                    [
                        'title' => 'How It Fits a Northern Circuit',
                        'body' => 'A night or two in Tarangire sets rhythm before the crater and plains. It also gives first-time safari guests space to settle into vehicle time, camera settings, and guide communication.',
                        'points' => [
                            'Ideal 1–2 night opener',
                            'Natural bridge toward Manyara, Ngorongoro, Serengeti',
                            'Works for both camping and lodge styles',
                        ],
                    ],
                ],
                'tips' => [
                    'Dry season often concentrates wildlife near water.',
                    'Bring binoculars for bird-rich river edges.',
                    'Use Tarangire to test packing comfort before longer park stays.',
                    'Ask for sunset viewpoints among the baobabs.',
                ],
                'tags' => ['Tarangire', 'Elephants', 'Safari Tips'],
                'cta_label' => 'Discover Tarangire',
                'cta_url_name' => 'destinations.show',
                'cta_url_param' => 'tarangire',
            ],
            [
                'slug' => 'day-trips-near-arusha-materuni-chemka-park-safaris',
                'title' => 'Day Trips Near Arusha: Materuni, Chemka, and Park Safaris',
                'category' => 'Day Adventures',
                'date' => 'Sep 05, 2025',
                'author' => 'Local Hosts',
                'seo_title' => 'Best Day Trips from Arusha: Materuni, Chemka & Parks',
                'seo_description' => 'Plan day trips from Arusha to Materuni waterfall, Chemka hot springs, and nearby park safaris—perfect for arrival days or short Tanzania stopovers.',
                'intro' => 'Arusha is more than a safari gateway. Within a day you can stand under Materuni’s waterfall, swim at Chemka’s clear springs, or track wildlife in Arusha National Park. These shorter adventures are ideal for arrival recovery, post-trek celebration, or travelers with limited time.',
                'quote' => [
                    'text' => 'A good Arusha day trip resets the journey—cool water, coffee trees, and just enough adventure before the bigger parks begin.',
                    'name' => 'Local Hosts',
                ],
                'sections' => [
                    [
                        'title' => 'Materuni and Chemka',
                        'body' => 'Materuni pairs waterfall walks with coffee culture on Kilimanjaro’s slopes. Chemka offers a relaxed swim day with turquoise pools—simple, scenic, and restorative after flights or treks.',
                        'points' => [
                            'Materuni: waterfall, village paths, coffee experiences',
                            'Chemka: swimming and easy nature time',
                            'Both work well as soft adventure days',
                        ],
                    ],
                    [
                        'title' => 'Nearby Park Safaris',
                        'body' => 'Arusha National Park delivers giraffes, colobus monkeys, crater lakes, and Mount Meru views without a long transfer. It is a smart wildlife day when your schedule cannot yet reach Tarangire or Ngorongoro.',
                        'points' => [
                            'Half-day or full-day game and walking options',
                            'Great for families and first wildlife outings',
                            'Easy to combine with an overnight in Arusha',
                        ],
                    ],
                ],
                'tips' => [
                    'Keep day trips light on the day before an early safari departure.',
                    'Carry a swimsuit and dry bag for Chemka.',
                    'Wear shoes with grip for Materuni paths.',
                    'Confirm park fees and walking-guide rules in advance.',
                ],
                'tags' => ['Arusha', 'Day Trips', 'Materuni'],
                'cta_label' => 'See Arusha Day Adventures',
                'cta_url_name' => 'destinations.show',
                'cta_url_param' => 'arusha',
            ],
            [
                'slug' => 'plan-northern-circuit-safari-with-confidence',
                'title' => 'How to Plan a Northern Circuit Safari with Confidence',
                'category' => 'Travel Planning',
                'date' => 'Aug 18, 2025',
                'author' => 'Admin',
                'seo_title' => 'Plan a Northern Circuit Safari in Tanzania',
                'seo_description' => 'A clear guide to planning Tanzania’s Northern Circuit—park order, trip length, lodge vs camping, budgets, and how to build a confident safari itinerary.',
                'intro' => 'Tanzania’s Northern Circuit links Arusha, Tarangire, Ngorongoro, and Serengeti into one of Africa’s great safari sequences. Confident planning means choosing the right number of nights, matching seasons to priorities, and deciding where comfort matters most—without overcomplicating the adventure.',
                'quote' => [
                    'text' => 'A strong Northern Circuit plan is simple: enough nights in the right parks, honest pacing, and a guide team you trust with the details.',
                    'name' => 'Admin',
                ],
                'sections' => [
                    [
                        'title' => 'Build the Backbone First',
                        'body' => 'Start with trip length and wildlife goals. Four to six days can cover highlights; seven to ten days deepen Serengeti time and reduce road pressure. Decide whether migration, crater wildlife, elephants, or a balanced mix sits at the center.',
                        'points' => [
                            'Map nights by park, not only by total days',
                            'Place longer stays where your priority wildlife is',
                            'Keep one buffer night near Arusha when flights are tight',
                        ],
                    ],
                    [
                        'title' => 'Choose Style, Then Refine Details',
                        'body' => 'Camping, mid-range lodges, and premium stays each change budget and atmosphere. Once style is clear, refine season, vehicle type, and whether Kilimanjaro or day trips belong in the same journey.',
                        'points' => [
                            'Align accommodation with comfort and budget',
                            'Confirm park fee logic and inclusions in writing',
                            'Share dietary needs, mobility notes, and photo goals early',
                        ],
                    ],
                ],
                'tips' => [
                    'Book peak migration and holiday periods early.',
                    'Ask for a day-by-day draft before paying a deposit.',
                    'Balance bucket-list parks with recovery time.',
                    'Use local operators who handle permits and pacing daily.',
                ],
                'tags' => ['Planning', 'Northern Circuit', 'Safari'],
                'cta_label' => 'Browse Safari Packages',
                'cta_url_name' => 'packages.index',
                'cta_url_param' => null,
                'cta_query' => ['category' => 'Safari'],
            ],
        ];
    }
}
