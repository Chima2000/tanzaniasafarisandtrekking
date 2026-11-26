<?php

use App\Support\InternalLinker;
use App\Support\RichText;
use App\Support\SiteSettings;
use Illuminate\Support\Str;

if (! function_exists('rich')) {
    function rich(?string $value, ?string $fallback = null): string
    {
        return RichText::render($value, $fallback);
    }
}

if (! function_exists('linked')) {
    function linked(?string $value): string
    {
        return InternalLinker::linkPlain($value);
    }
}

if (! function_exists('site_settings')) {
    /**
     * @return array<string, string>|string
     */
    function site_settings(?string $key = null, ?string $default = null): array|string
    {
        $settings = app(SiteSettings::class);

        if ($key === null) {
            return $settings->all();
        }

        return $settings->get($key, $default);
    }
}

if (! function_exists('clean_display_title')) {
    function clean_display_title(?string $title): string
    {
        $title = trim(html_entity_decode((string) $title, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        if ($title === '') {
            return '';
        }

        if (str_contains($title, ' | ')) {
            $title = trim(explode(' | ', $title, 2)[0]);
        }

        $stripped = trim((string) preg_replace('/^\s*\d+\s*-?\s*days?\s+/i', '', $title));

        return $stripped !== '' ? $stripped : $title;
    }
}

if (! function_exists('clean_copy')) {
    function clean_copy(?string $text): string
    {
        $text = html_entity_decode(strip_tags((string) $text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return trim($text);
    }
}

if (! function_exists('site_title')) {
    function site_title(?string $page = null, ?string $site = null): string
    {
        if ($site === null || $site === '') {
            $site = site_settings('company_name', 'Tanzania Safaris and Trekking');
        }

        $site = trim((string) ($site ?: 'Tanzania Safaris and Trekking'));
        $page = clean_display_title($page);

        if ($page === '' || strcasecmp($page, $site) === 0) {
            return $site;
        }

        return $page.' | '.$site;
    }
}

if (! function_exists('truncate_meta')) {
    function truncate_meta(?string $text, int $limit = 160): string
    {
        $text = clean_copy($text);
        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        $cut = mb_substr($text, 0, $limit);
        $space = mb_strrpos($cut, ' ');

        return ($space ? mb_substr($cut, 0, $space) : $cut).'...';
    }
}

if (! function_exists('absolute_url')) {
    function absolute_url(?string $url): ?string
    {
        if (! filled($url)) {
            return null;
        }

        $url = (string) $url;
        if (Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        return rtrim(request()->getSchemeAndHttpHost(), '/').'/'.ltrim($url, '/');
    }
}
