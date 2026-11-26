<?php

namespace App\Support;

class PackagePricing
{
    /**
     * @param  array{category?: string, duration?: string, name?: string}  $package
     * @return array{cost_per_person: float, profit_per_person: float}
     */
    public static function defaultsFor(array $package): array
    {
        $category = strtolower((string) ($package['category'] ?? ''));
        $duration = (string) ($package['duration'] ?? ($package['name'] ?? ''));
        $days = 1;

        if (preg_match('/(\d+)/', $duration, $matches) === 1) {
            $days = max(1, (int) $matches[1]);
        }

        if (str_contains($category, 'day')) {
            return [
                'cost_per_person' => 180.00,
                'profit_per_person' => 70.00,
            ];
        }

        if (str_contains($category, 'cultural')) {
            return [
                'cost_per_person' => 120.00,
                'profit_per_person' => 55.00,
            ];
        }

        if (str_contains($category, 'trek')) {
            return [
                'cost_per_person' => round(310 * $days, 2),
                'profit_per_person' => round(115 * $days, 2),
            ];
        }

        return [
            'cost_per_person' => round(360 * $days, 2),
            'profit_per_person' => round(125 * $days, 2),
        ];
    }

    public static function sellingPrice(?float $costPerPerson, ?float $profitPerPerson): float
    {
        return round((float) $costPerPerson + (float) $profitPerPerson, 2);
    }

    public static function estimatedTotal(float $pricePerPerson, int $adults, int $children = 0): float
    {
        $travellers = max(0, $adults) + max(0, $children);

        return round($pricePerPerson * $travellers, 2);
    }

    public static function formatUsd(float $amount, bool $showCents = false): string
    {
        if ($showCents || fmod($amount, 1) !== 0.0) {
            return '$'.number_format($amount, 2);
        }

        return '$'.number_format($amount, 0);
    }

    /**
     * @param  list<string>|null  $blockedDates
     */
    public static function isDateAvailable(?string $date, ?array $blockedDates = null): bool
    {
        if ($date === null || $date === '') {
            return false;
        }

        if ($date < now()->toDateString()) {
            return false;
        }

        $blocked = array_values(array_filter((array) $blockedDates));

        return ! in_array($date, $blocked, true);
    }
}
