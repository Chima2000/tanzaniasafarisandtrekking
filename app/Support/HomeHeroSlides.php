<?php

namespace App\Support;

class HomeHeroSlides
{
    /**
     * @param  array<string, mixed>  $content
     * @param  list<string>  $fallbackImages
     * @return list<array{
     *     type: string,
     *     media: string,
     *     poster: string,
     *     position: string,
     *     kicker: string,
     *     title: string,
     *     body: string,
     *     cta: string,
     *     cta_url: string,
     *     badge_label: string,
     *     badge_value: string,
     *     badge_text: string
     * }>
     */
    public function resolve(array $content, array $fallbackImages = []): array
    {
        $slides = $content['hero_slides'] ?? null;

        if (is_string($slides) && $slides !== '') {
            $decoded = json_decode($slides, true);
            $slides = is_array($decoded) ? $decoded : null;
        }

        if (! is_array($slides) || $slides === []) {
            $slides = $this->legacySlides($content);
        }

        $normalized = [];

        foreach (array_values($slides) as $index => $slide) {
            if (! is_array($slide)) {
                continue;
            }

            $item = $this->normalize($slide, $fallbackImages[$index] ?? ($fallbackImages[0] ?? null));

            if ($item['title'] === '' && $item['media'] === '') {
                continue;
            }

            $normalized[] = $item;
        }

        if ($normalized === []) {
            return array_map(
                fn (array $slide, int $index): array => $this->normalize($slide, $fallbackImages[$index] ?? null),
                $this->defaultSlides(),
                array_keys($this->defaultSlides())
            );
        }

        return $normalized;
    }

    /**
     * @param  array<string, mixed>  $content
     * @return list<array<string, mixed>>
     */
    private function legacySlides(array $content): array
    {
        $slides = [];

        foreach ([1, 2, 3] as $index) {
            $title = trim((string) ($content["slide_{$index}_title"] ?? ''));
            $media = trim((string) ($content["slide_{$index}_image"] ?? ''));
            $kicker = trim((string) ($content["slide_{$index}_kicker"] ?? ''));
            $body = trim((string) ($content["slide_{$index}_body"] ?? ''));
            $cta = trim((string) ($content["slide_{$index}_cta"] ?? ''));

            if ($title === '' && $media === '' && $kicker === '' && $body === '') {
                continue;
            }

            $slides[] = [
                'type' => 'image',
                'media' => $media,
                'poster' => '',
                'position' => (string) ($content["slide_{$index}_image_position"] ?? '50% 50%'),
                'kicker' => $kicker,
                'title' => $title,
                'body' => $body,
                'cta' => $cta !== '' ? $cta : 'See Packages',
                'cta_url' => $index === 3 ? '/kilimanjaro-treks' : '/safari-packages',
                'badge_label' => 'Adventures start from',
                'badge_value' => match ($index) {
                    2 => 'Safari',
                    3 => 'Trek',
                    default => '',
                },
                'badge_text' => match ($index) {
                    2 => 'Tours',
                    3 => 'Routes',
                    default => 'Packages',
                },
            ];
        }

        return $slides;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function defaultSlides(): array
    {
        return [
            [
                'type' => 'image',
                'media' => '',
                'poster' => '',
                'position' => '50% 50%',
                'kicker' => 'Karibu Tanzania!',
                'title' => 'Safari & Trek the wild heart of Tanzania',
                'body' => 'From Big Five safaris to Kilimanjaro treks, we craft Tanzania adventures that fit your dreams and travel style.',
                'cta' => 'See Packages',
                'cta_url' => '/safari-packages',
                'badge_label' => 'Adventures start from',
                'badge_value' => '',
                'badge_text' => 'Packages',
            ],
            [
                'type' => 'image',
                'media' => '',
                'poster' => '',
                'position' => '50% 50%',
                'kicker' => 'Wildlife Awaits',
                'title' => 'Discover Serengeti & Ngorongoro wonders',
                'body' => 'Step into the Northern Circuit and explore hidden gems, wildlife drama, and unforgettable landscapes beyond ordinary tourism.',
                'cta' => 'See Packages',
                'cta_url' => '/safari-packages',
                'badge_label' => 'Adventures start from',
                'badge_value' => 'Safari',
                'badge_text' => 'Tours',
            ],
            [
                'type' => 'image',
                'media' => '',
                'poster' => '',
                'position' => '50% 50%',
                'kicker' => 'Summit Calling',
                'title' => 'Climb Kilimanjaro, Africa’s highest peak',
                'body' => 'Rise above the clouds on carefully guided Kilimanjaro routes with expert local mountain teams.',
                'cta' => 'See Packages',
                'cta_url' => '/kilimanjaro-treks',
                'badge_label' => 'Adventures start from',
                'badge_value' => 'Trek',
                'badge_text' => 'Routes',
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $slide
     * @return array{
     *     type: string,
     *     media: string,
     *     poster: string,
     *     position: string,
     *     kicker: string,
     *     title: string,
     *     body: string,
     *     cta: string,
     *     cta_url: string,
     *     badge_label: string,
     *     badge_value: string,
     *     badge_text: string
     * }
     */
    public function normalize(array $slide, ?string $fallbackMedia = null): array
    {
        $type = strtolower(trim((string) ($slide['type'] ?? 'image')));
        if (! in_array($type, ['image', 'video'], true)) {
            $type = 'image';
        }

        $media = trim((string) ($slide['media'] ?? $slide['image'] ?? $slide['video'] ?? ''));
        $poster = trim((string) ($slide['poster'] ?? ''));

        if ($media === '' && $fallbackMedia) {
            $media = $fallbackMedia;
        }

        $ctaUrl = trim((string) ($slide['cta_url'] ?? ''));
        if ($ctaUrl === '') {
            $ctaUrl = '/safari-packages';
        }

        return [
            'type' => $type,
            'media' => $media,
            'poster' => $poster,
            'position' => CmsMedia::position((string) ($slide['position'] ?? $slide['image_position'] ?? '50% 50%')),
            'kicker' => trim((string) ($slide['kicker'] ?? '')),
            'title' => trim((string) ($slide['title'] ?? '')),
            'body' => RichText::sanitize(trim((string) ($slide['body'] ?? ''))),
            'cta' => trim((string) ($slide['cta'] ?? 'See Packages')) ?: 'See Packages',
            'cta_url' => $ctaUrl,
            'badge_label' => trim((string) ($slide['badge_label'] ?? 'Adventures start from')) ?: 'Adventures start from',
            'badge_value' => trim((string) ($slide['badge_value'] ?? '')),
            'badge_text' => trim((string) ($slide['badge_text'] ?? 'Packages')) ?: 'Packages',
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function sanitizeIncoming(mixed $slides): array
    {
        if (! is_array($slides)) {
            return [];
        }

        $clean = [];

        foreach (array_values($slides) as $slide) {
            if (! is_array($slide)) {
                continue;
            }

            $item = $this->normalize($slide);

            if ($item['title'] === '' && $item['media'] === '' && $item['kicker'] === '' && $item['body'] === '') {
                continue;
            }

            $clean[] = $item;
        }

        return $clean;
    }
}
