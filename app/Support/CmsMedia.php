<?php

namespace App\Support;

class CmsMedia
{
    public static function url(?string $value, ?string $fallback = null): ?string
    {
        $value = is_string($value) ? trim($value) : '';

        if ($value !== '') {
            return $value;
        }

        $fallback = is_string($fallback) ? trim($fallback) : '';

        return $fallback !== '' ? $fallback : null;
    }

    public static function position(?string $value, string $default = '50% 50%'): string
    {
        $value = is_string($value) ? trim($value) : '';

        if ($value === '' || ! preg_match('/^\d{1,3}%\s+\d{1,3}%$/', $value)) {
            return $default;
        }

        return $value;
    }

    /**
     * @return array{x: int, y: int}
     */
    public static function positionParts(?string $value): array
    {
        $position = self::position($value);
        [$x, $y] = array_map(
            static fn (string $part): int => (int) rtrim($part, '%'),
            explode(' ', $position)
        );

        return [
            'x' => max(0, min(100, $x)),
            'y' => max(0, min(100, $y)),
        ];
    }

    public static function backgroundStyle(?string $url, ?string $position = null): string
    {
        if (! $url) {
            return '';
        }

        return sprintf(
            "background-image: url('%s'); background-position: %s; background-size: cover;",
            e($url),
            self::position($position)
        );
    }

    public static function objectStyle(?string $position = null): string
    {
        return 'object-fit: cover; object-position: '.self::position($position).';';
    }
}
