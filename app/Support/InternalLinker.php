<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;
use Throwable;

class InternalLinker
{
    /**
     * @var list<array{phrase: string, url: string}>|null
     */
    private static ?array $rules = null;

    public static function flush(): void
    {
        self::$rules = null;
    }

    public static function apply(string $html): string
    {
        $html = trim($html);

        if ($html === '') {
            return '';
        }

        try {
            $rules = self::rules();
        } catch (Throwable) {
            return $html;
        }

        if ($rules === []) {
            return $html;
        }

        $wrapped = '<div id="internal-linker-root">'.$html.'</div>';
        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8">'.$wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('internal-linker-root');

        if (! $root instanceof DOMElement) {
            return $html;
        }

        /** @var array<string, true> $usedPhrases */
        $usedPhrases = [];

        self::linkifyNode($root, $rules, $usedPhrases);

        $output = '';
        foreach ($root->childNodes as $child) {
            $output .= $document->saveHTML($child);
        }

        return trim(html_entity_decode($output, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    public static function linkPlain(?string $text): string
    {
        $text = trim((string) $text);

        if ($text === '') {
            return '';
        }

        try {
            $html = self::apply('<span id="plain-link-root">'.e($text).'</span>');
        } catch (Throwable) {
            return e($text);
        }

        if (preg_match('#<span id="plain-link-root">(.*)</span>#su', $html, $matches) === 1) {
            return $matches[1];
        }

        if (preg_match('#<span>(.*)</span>#su', $html, $matches) === 1) {
            return $matches[1];
        }

        return e($text);
    }

    /**
     * @return list<array{phrase: string, url: string}>
     */
    public static function rules(): array
    {
        if (self::$rules !== null) {
            return self::$rules;
        }

        $rules = [];

        foreach (self::staticKeywordMap() as $phrase => $url) {
            self::pushRule($rules, $phrase, $url);
        }

        foreach (self::destinationKeywordMap() as $phrase => $url) {
            self::pushRule($rules, $phrase, $url);
        }

        foreach (self::categoryKeywordMap() as $phrase => $url) {
            self::pushRule($rules, $phrase, $url);
        }

        foreach (self::packageKeywordMap() as $phrase => $url) {
            self::pushRule($rules, $phrase, $url);
        }

        usort($rules, function (array $left, array $right): int {
            return mb_strlen($right['phrase']) <=> mb_strlen($left['phrase']);
        });

        return self::$rules = $rules;
    }

    /**
     * @return array<string, string>
     */
    private static function staticKeywordMap(): array
    {
        return [
            'Great Migration' => route('destinations.show', 'serengeti'),
            'wildebeest migration' => route('destinations.show', 'serengeti'),
            'Northern Circuit' => route('packages.safari'),
            'Uhuru Peak' => route('destinations.show', 'kilimanjaro'),
            'Mount Kilimanjaro' => route('destinations.show', 'kilimanjaro'),
            'Kilimanjaro International Airport' => route('destinations.show', 'arusha'),
            'Ngorongoro Crater' => route('destinations.show', 'ngorongoro'),
            'Ngorongoro Conservation Area' => route('destinations.show', 'ngorongoro'),
            'Serengeti National Park' => route('destinations.show', 'serengeti'),
            'Tarangire National Park' => route('destinations.show', 'tarangire'),
            'Arusha National Park' => route('destinations.show', 'arusha'),
            'Lake Manyara' => route('packages.safari'),
            'Lake Eyasi' => route('packages.safari'),
            'Materuni Waterfall' => route('packages.cultural'),
            'Materuni waterfall' => route('packages.cultural'),
            'Chemka Hot Springs' => route('packages.day-trips'),
            'Kikuletwa' => route('packages.day-trips'),
            'Machame Route' => route('packages.trekking'),
            'Lemosho Route' => route('packages.trekking'),
            'Marangu Route' => route('packages.trekking'),
            'Rongai Route' => route('packages.trekking'),
            'Umbwe Route' => route('packages.trekking'),
            'Machame' => route('packages.trekking'),
            'Lemosho' => route('packages.trekking'),
            'Marangu' => route('packages.trekking'),
            'Rongai' => route('packages.trekking'),
            'Umbwe' => route('packages.trekking'),
            'Big Five' => route('packages.safari'),
            'game drives' => route('packages.safari'),
            'game drive' => route('packages.safari'),
            'Safari Packages' => route('packages.safari'),
            'safari packages' => route('packages.safari'),
            'Kilimanjaro Treks' => route('packages.trekking'),
            'Kilimanjaro treks' => route('packages.trekking'),
            'Day Adventures' => route('packages.day-trips'),
            'Day Trips' => route('packages.day-trips'),
            'day trips' => route('packages.day-trips'),
            'Cultural Experiences' => route('packages.cultural'),
            'cultural experiences' => route('packages.cultural'),
            'Tour Packages' => route('packages.index'),
            'tour packages' => route('packages.index'),
            'Book Now' => route('book-now'),
            'book now' => route('book-now'),
            'Contact Us' => route('contact'),
            'contact us' => route('contact'),
            'About Us' => route('about'),
            'Destinations' => route('destinations.index'),
            'Packages' => route('packages.index'),
            'FAQ page' => route('faq'),
            'FAQ' => route('faq'),
            'Faqs' => route('faq'),
            'Blog' => route('blog'),
            'Contact' => route('contact'),
            'About' => route('about'),
            'Moshi' => route('packages.trekking'),
            'Maasai' => route('destinations.show', 'ngorongoro'),
            'Oldupai' => route('destinations.show', 'ngorongoro'),
            'Ndutu' => route('destinations.show', 'serengeti'),
            'Seronera' => route('destinations.show', 'serengeti'),
            'Mara River' => route('destinations.show', 'serengeti'),
            'Materuni' => route('packages.cultural'),
            'Chemka' => route('packages.day-trips'),
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function destinationKeywordMap(): array
    {
        $map = [];

        try {
            $destinations = app(DestinationDefinitions::class)->all();
        } catch (Throwable) {
            return [];
        }

        foreach ($destinations as $destination) {
            $slug = (string) ($destination['slug'] ?? '');

            if ($slug === '') {
                continue;
            }

            $url = route('destinations.show', $slug);

            foreach ([
                $destination['full_name'] ?? null,
                $destination['title'] ?? null,
                ...((array) ($destination['needles'] ?? [])),
            ] as $phrase) {
                $phrase = trim((string) $phrase);

                if ($phrase !== '') {
                    $map[$phrase] = $url;
                }
            }
        }

        return $map;
    }

    /**
     * @return array<string, string>
     */
    private static function categoryKeywordMap(): array
    {
        $map = [];

        foreach (PackageCategories::all() as $definition) {
            $url = route($definition['route']);
            $map[$definition['label']] = $url;
            $map[$definition['category']] = $url;
        }

        $map['Safari'] = route('packages.safari');
        $map['Trekking'] = route('packages.trekking');
        $map['Cultural'] = route('packages.cultural');

        return $map;
    }

    /**
     * @return array<string, string>
     */
    private static function packageKeywordMap(): array
    {
        $map = [];

        try {
            if (! app()->bound(TourCatalog::class)) {
                return [];
            }

            foreach (app(TourCatalog::class)->packages() as $package) {
                $name = trim((string) ($package['name'] ?? ''));
                $slug = trim((string) ($package['slug'] ?? ''));

                if ($name === '' || $slug === '') {
                    continue;
                }

                $map[$name] = route('packages.show', $slug);

                $cleaned = clean_display_title($name);

                if ($cleaned !== '' && strcasecmp($cleaned, $name) !== 0) {
                    $map[$cleaned] = route('packages.show', $slug);
                }
            }
        } catch (Throwable) {
            return [];
        }

        return $map;
    }

    /**
     * @param  list<array{phrase: string, url: string}>  $rules
     */
    private static function pushRule(array &$rules, string $phrase, string $url): void
    {
        $phrase = trim($phrase);
        $url = trim($url);

        if ($phrase === '' || $url === '') {
            return;
        }

        if (mb_strlen($phrase) < 3) {
            return;
        }

        foreach ($rules as $existing) {
            if (strcasecmp($existing['phrase'], $phrase) === 0) {
                return;
            }
        }

        $rules[] = [
            'phrase' => $phrase,
            'url' => $url,
        ];
    }

    /**
     * @param  list<array{phrase: string, url: string}>  $rules
     * @param  array<string, true>  $usedPhrases
     */
    private static function linkifyNode(DOMNode $node, array $rules, array &$usedPhrases): void
    {
        if ($node instanceof DOMElement) {
            $tag = strtolower($node->tagName);

            if (in_array($tag, ['a', 'script', 'style', 'code', 'pre', 'textarea', 'button', 'iframe', 'svg'], true)) {
                return;
            }
        }

        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            if ($child instanceof DOMText) {
                self::linkifyTextNode($child, $rules, $usedPhrases);

                continue;
            }

            self::linkifyNode($child, $rules, $usedPhrases);
        }
    }

    /**
     * @param  list<array{phrase: string, url: string}>  $rules
     * @param  array<string, true>  $usedPhrases
     */
    private static function linkifyTextNode(DOMText $textNode, array $rules, array &$usedPhrases): void
    {
        $text = $textNode->wholeText;

        if (trim($text) === '') {
            return;
        }

        $segments = self::segmentText($text, $rules, $usedPhrases);

        if ($segments === [['type' => 'text', 'value' => $text]]) {
            return;
        }

        $document = $textNode->ownerDocument;
        $parent = $textNode->parentNode;

        if (! $document instanceof DOMDocument || $parent === null) {
            return;
        }

        foreach ($segments as $segment) {
            if ($segment['type'] === 'link') {
                $anchor = $document->createElement('a');
                $anchor->setAttribute('class', 'internal-link');
                $anchor->setAttribute('href', $segment['url']);
                $anchor->appendChild($document->createTextNode($segment['value']));
                $parent->insertBefore($anchor, $textNode);

                continue;
            }

            $parent->insertBefore($document->createTextNode($segment['value']), $textNode);
        }

        $parent->removeChild($textNode);
    }

    /**
     * @param  list<array{phrase: string, url: string}>  $rules
     * @param  array<string, true>  $usedPhrases
     * @return list<array{type: 'text'|'link', value: string, url?: string}>
     */
    private static function segmentText(string $text, array $rules, array &$usedPhrases): array
    {
        /** @var list<array{start: int, length: int, phrase: string, url: string}> $matches */
        $matches = [];

        foreach ($rules as $rule) {
            $phraseKey = mb_strtolower($rule['phrase']);

            if (isset($usedPhrases[$phraseKey])) {
                continue;
            }

            if (self::isCurrentUrl($rule['url'])) {
                continue;
            }

            $pattern = '/(?<![\p{L}\p{N}])'.preg_quote($rule['phrase'], '/').'(?![\p{L}\p{N}])/iu';

            if (preg_match($pattern, $text, $found, PREG_OFFSET_CAPTURE) !== 1) {
                continue;
            }

            $byteOffset = $found[0][1];
            $matched = $found[0][0];
            $start = self::byteOffsetToCharacterOffset($text, $byteOffset);
            $length = mb_strlen($matched);

            if (self::overlapsExisting($matches, $start, $length)) {
                continue;
            }

            $matches[] = [
                'start' => $start,
                'length' => $length,
                'phrase' => $matched,
                'url' => $rule['url'],
            ];

            $usedPhrases[$phraseKey] = true;
        }

        if ($matches === []) {
            return [['type' => 'text', 'value' => $text]];
        }

        usort($matches, fn (array $left, array $right): int => $left['start'] <=> $right['start']);

        $segments = [];
        $cursor = 0;

        foreach ($matches as $match) {
            if ($match['start'] > $cursor) {
                $segments[] = [
                    'type' => 'text',
                    'value' => mb_substr($text, $cursor, $match['start'] - $cursor),
                ];
            }

            $segments[] = [
                'type' => 'link',
                'value' => $match['phrase'],
                'url' => $match['url'],
            ];

            $cursor = $match['start'] + $match['length'];
        }

        if ($cursor < mb_strlen($text)) {
            $segments[] = [
                'type' => 'text',
                'value' => mb_substr($text, $cursor),
            ];
        }

        return $segments;
    }

    /**
     * @param  list<array{start: int, length: int, phrase: string, url: string}>  $matches
     */
    private static function overlapsExisting(array $matches, int $start, int $length): bool
    {
        $end = $start + $length;

        foreach ($matches as $match) {
            $matchEnd = $match['start'] + $match['length'];

            if ($start < $matchEnd && $end > $match['start']) {
                return true;
            }
        }

        return false;
    }

    private static function byteOffsetToCharacterOffset(string $text, int $byteOffset): int
    {
        return mb_strlen(substr($text, 0, $byteOffset));
    }

    private static function isCurrentUrl(string $url): bool
    {
        $current = self::normalizePath(self::currentPath());
        $target = self::normalizePath(self::urlPath($url));

        return $current !== '/' && $current === $target;
    }

    private static function currentPath(): string
    {
        try {
            return request()->path();
        } catch (Throwable) {
            return '';
        }
    }

    private static function urlPath(string $url): string
    {
        if (str_starts_with($url, '/')) {
            return $url;
        }

        $path = parse_url($url, PHP_URL_PATH);

        return is_string($path) ? $path : $url;
    }

    private static function normalizePath(string $path): string
    {
        $path = '/'.trim($path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }
}
