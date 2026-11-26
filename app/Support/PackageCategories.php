<?php

namespace App\Support;

class PackageCategories
{
    /**
     * @return array<string, array{
     *     category: string,
     *     route: string,
     *     path: string,
     *     label: string,
     *     nav_key: string,
     *     seo_title: string,
     *     seo_description: string,
     *     seo_keywords: string,
     *     intro_eyebrow: string,
     *     intro_heading: string,
     *     intro_lead: string,
     *     intro_body: string,
     *     intro_points: list<array{title: string, body: string}>
     * }>
     */
    public static function all(): array
    {
        return [
            'Safari' => [
                'category' => 'Safari',
                'route' => 'packages.safari',
                'path' => 'safari-packages',
                'label' => 'Safari Packages',
                'nav_key' => 'safari',
                'seo_title' => 'Tanzania Safari Packages | Serengeti, Ngorongoro & Tarangire',
                'seo_description' => 'Browse independent Tanzania safari packages across Tarangire, Serengeti, Ngorongoro, Lake Manyara, and Lake Eyasi with lodge and camping options.',
                'seo_keywords' => 'Tanzania safari packages, Serengeti safari, Ngorongoro safari, Tarangire safari, Northern Circuit safari',
                'intro_eyebrow' => 'Northern Circuit Wildlife Safaris',
                'intro_heading' => 'Tanzania Safari Packages Across Serengeti, Ngorongoro & Tarangire',
                'intro_lead' => 'These safari packages follow Tanzania\'s Northern Circuit, linking Tarangire National Park, Serengeti National Park, Ngorongoro Crater, Lake Manyara, and Lake Eyasi in itineraries designed for real park days rather than rushed transfers.',
                'intro_body' => 'Choose lodge or camping departures from two to seven days. Each tour lists the parks you visit, overnight areas, meal plan, and a clear day-by-day wildlife route so you can compare pace, comfort level, and seasonal viewing goals before you enquire.',
                'intro_points' => [
                    [
                        'title' => 'Classic park combinations',
                        'body' => 'Tarangire elephants, Serengeti plains game, and Ngorongoro Crater wildlife in proven Northern Circuit routes.',
                    ],
                    [
                        'title' => 'Lodge or camping options',
                        'body' => 'Select tented camping for a closer bush feel, or lodge stays when you want more comfort between game drives.',
                    ],
                    [
                        'title' => 'Private 4x4 game drives',
                        'body' => 'Travel with an English speaking guide in a pop up roof Land Cruiser sized for your group.',
                    ],
                ],
            ],
            'Trekking' => [
                'category' => 'Trekking',
                'route' => 'packages.trekking',
                'path' => 'kilimanjaro-treks',
                'label' => 'Kilimanjaro Treks',
                'nav_key' => 'kilimanjaro',
                'seo_title' => 'Kilimanjaro Trek Packages | Machame, Lemosho, Marangu & More',
                'seo_description' => 'Compare Mount Kilimanjaro trek packages on Machame, Lemosho, Marangu, Rongai, Umbwe, and the Northern Circuit with day-by-day mountain itineraries.',
                'seo_keywords' => 'Kilimanjaro trek packages, Machame route, Lemosho route, Marangu route, climb Kilimanjaro Tanzania',
                'intro_eyebrow' => 'Mount Kilimanjaro Climbing Routes',
                'intro_heading' => 'Kilimanjaro Trek Packages on Machame, Lemosho, Marangu & More',
                'intro_lead' => 'Compare official Kilimanjaro routes by length, camping style, and acclimatization profile. Each trek package covers gate-to-gate logistics with mountain crew support, park fees, and a day-by-day camp itinerary up to Uhuru Peak.',
                'intro_body' => 'Machame and Lemosho suit most climbers who want strong scenery and better summit pacing. Marangu uses mountain huts. Rongai approaches from the drier north side. Umbwe is steeper and better for fit trekkers. The Northern Circuit is the longest standard camping route for maximum acclimatization time.',
                'intro_points' => [
                    [
                        'title' => 'Route by route clarity',
                        'body' => 'See camp names, altitudes, and summit approaches before you choose five to nine day schedules.',
                    ],
                    [
                        'title' => 'Guided mountain operations',
                        'body' => 'Certified guides, cooks, and porters handle pacing, meals, and camp setup throughout the climb.',
                    ],
                    [
                        'title' => 'Acclimatization first',
                        'body' => 'Longer itineraries include climb high, sleep low days that improve comfort and summit success odds.',
                    ],
                ],
            ],
            'Day Trip' => [
                'category' => 'Day Trip',
                'route' => 'packages.day-trips',
                'path' => 'day-trips',
                'label' => 'Day Adventures',
                'nav_key' => 'day-trips',
                'seo_title' => 'Tanzania Day Trips | Arusha, Tarangire & Ngorongoro',
                'seo_description' => 'Plan Tanzania day trips from Arusha and Moshi, including Arusha National Park, Tarangire, Ngorongoro Crater, Materuni waterfall, and Chemka hot springs.',
                'seo_keywords' => 'Tanzania day trips, Arusha National Park day safari, Ngorongoro day trip, Materuni waterfall, Chemka hot springs',
                'intro_eyebrow' => 'One Day Tours from Arusha & Moshi',
                'intro_heading' => 'Tanzania Day Trips for Wildlife, Waterfalls & Hot Springs',
                'intro_lead' => 'Day adventures are built for travelers with limited time in Arusha or Moshi who still want a focused park visit, crater safari, waterfall walk, or hot springs outing without overnight camping.',
                'intro_body' => 'Pick a full day in Arusha National Park, Tarangire, or Ngorongoro Crater when wildlife is the priority. Choose Materuni for a Chagga village coffee tour and waterfall, or Kikuletwa Chemka Hot Springs for a relaxed swim day. Each option includes private transport guidance and a clear pickup-to-return outline.',
                'intro_points' => [
                    [
                        'title' => 'Easy add-ons to longer trips',
                        'body' => 'Use a day safari or cultural outing before or after a Northern Circuit safari or Kilimanjaro climb.',
                    ],
                    [
                        'title' => 'Short travel times',
                        'body' => 'Most departures stay within a few hours of Arusha or Moshi so you return the same evening.',
                    ],
                    [
                        'title' => 'Private vehicle and guide',
                        'body' => 'Travel as a couple, family, or small group with a dedicated driver guide for the day.',
                    ],
                ],
            ],
            'Cultural' => [
                'category' => 'Cultural',
                'route' => 'packages.cultural',
                'path' => 'cultural-experiences',
                'label' => 'Cultural Experiences',
                'nav_key' => 'cultural',
                'seo_title' => 'Tanzania Cultural Experiences | Materuni & Local Tours',
                'seo_description' => 'Discover Tanzania cultural experiences around Moshi and the Kilimanjaro foothills, including village visits, coffee tours, and waterfall walks.',
                'seo_keywords' => 'Tanzania cultural tours, Materuni coffee tour, Moshi village experience, Kilimanjaro cultural day trip',
                'intro_eyebrow' => 'Village Life Around Kilimanjaro',
                'intro_heading' => 'Tanzania Cultural Experiences in the Moshi Foothills',
                'intro_lead' => 'These cultural experiences focus on everyday life around Moshi and the lower slopes of Kilimanjaro, where coffee farms, waterfalls, and Chagga village hospitality shape a quieter side of northern Tanzania travel.',
                'intro_body' => 'A Materuni day combines a waterfall walk with a hands-on coffee demonstration from cherry to cup, plus a local lunch. It is a practical cultural stop for climbers resting before or after the mountain, and for safari guests who want time with people as well as wildlife.',
                'intro_points' => [
                    [
                        'title' => 'Local hosts and guides',
                        'body' => 'Visit with village guides who explain farming, coffee processing, and community routines on the ground.',
                    ],
                    [
                        'title' => 'Active but gentle pacing',
                        'body' => 'Expect walking paths, waterfall time, and a sit-down coffee session rather than long game-drive hours.',
                    ],
                    [
                        'title' => 'Pairs well with Moshi stays',
                        'body' => 'Ideal before a Kilimanjaro trek briefing day or as a soft landing after descent.',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array{
     *     category: null,
     *     route: string,
     *     path: string,
     *     label: string,
     *     nav_key: string,
     *     seo_title: string,
     *     seo_description: string,
     *     seo_keywords: string,
     *     intro_eyebrow: string,
     *     intro_heading: string,
     *     intro_lead: string,
     *     intro_body: string,
     *     intro_points: list<array{title: string, body: string}>
     * }
     */
    public static function allPackages(): array
    {
        return [
            'category' => null,
            'route' => 'packages.index',
            'path' => 'packages',
            'label' => 'Tour Packages',
            'nav_key' => 'safari',
            'seo_title' => 'Tanzania Tour Packages | Safaris, Kilimanjaro Treks & Day Trips',
            'seo_description' => 'Explore Tanzania tour packages for Northern Circuit safaris, Kilimanjaro treks, day trips, and cultural experiences with clear itineraries and local guides.',
            'seo_keywords' => 'Tanzania tour packages, safari packages, Kilimanjaro treks, day trips Tanzania, cultural tours Moshi',
            'intro_eyebrow' => 'Safaris, Treks & Day Adventures',
            'intro_heading' => 'Tanzania Tour Packages for Wildlife, Kilimanjaro & Short Excursions',
            'intro_lead' => 'Browse the full collection of Tanzania Safaris and Trekking packages in one place: Northern Circuit wildlife safaris, Mount Kilimanjaro climbing routes, day trips from Arusha and Moshi, and cultural experiences in the Kilimanjaro foothills.',
            'intro_body' => 'Each package page includes an overview, day-by-day itinerary, inclusions, and gallery so you can compare duration, parks or routes, and travel style. Use the experience filters to move into safari, trek, day trip, or cultural pages when you already know the trip type you want.',
            'intro_points' => [
                [
                    'title' => 'Safari packages',
                    'body' => 'Multi day itineraries covering Tarangire, Serengeti, Ngorongoro, Manyara, and Lake Eyasi.',
                ],
                [
                    'title' => 'Kilimanjaro treks',
                    'body' => 'Route based climbs on Machame, Lemosho, Marangu, Rongai, Umbwe, and the Northern Circuit.',
                ],
                [
                    'title' => 'Day and cultural tours',
                    'body' => 'Short departures for parks, waterfalls, hot springs, and village coffee experiences.',
                ],
            ],
        ];
    }

    /**
     * @return array{
     *     category: string|null,
     *     route: string,
     *     path: string,
     *     label: string,
     *     nav_key: string,
     *     seo_title: string,
     *     seo_description: string,
     *     seo_keywords: string,
     *     intro_eyebrow: string,
     *     intro_heading: string,
     *     intro_lead: string,
     *     intro_body: string,
     *     intro_points: list<array{title: string, body: string}>
     * }
     */
    public static function page(?string $category): array
    {
        return self::find($category) ?? self::allPackages();
    }

    /**
     * @return array{
     *     category: string,
     *     route: string,
     *     path: string,
     *     label: string,
     *     nav_key: string,
     *     seo_title: string,
     *     seo_description: string,
     *     seo_keywords: string,
     *     intro_eyebrow: string,
     *     intro_heading: string,
     *     intro_lead: string,
     *     intro_body: string,
     *     intro_points: list<array{title: string, body: string}>
     * }|null
     */
    public static function find(?string $category): ?array
    {
        if ($category === null || $category === '') {
            return null;
        }

        return self::all()[$category] ?? null;
    }

    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function url(?string $category = null, array $parameters = []): string
    {
        $definition = self::find($category);

        if ($definition === null) {
            return route('packages.index', $parameters);
        }

        return route($definition['route'], $parameters);
    }

    public static function label(?string $category): string
    {
        return self::find($category)['label'] ?? 'Tour Packages';
    }

    public static function navKey(?string $category): string
    {
        return self::find($category)['nav_key'] ?? 'safari';
    }
}
