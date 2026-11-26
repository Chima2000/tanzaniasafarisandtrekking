<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

class RichText
{
    /**
     * @var list<string>
     */
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'ul', 'ol', 'li',
        'a', 'h2', 'h3', 'h4', 'blockquote', 'img', 'figure', 'figcaption',
        'span', 'div', 'iframe', 'video', 'source', 'hr',
    ];

    /**
     * @var array<string, list<string>>
     */
    private const ALLOWED_ATTRIBUTES = [
        'a' => ['href', 'title', 'target', 'rel', 'class'],
        'img' => ['src', 'alt', 'title', 'width', 'height', 'class'],
        'iframe' => ['src', 'width', 'height', 'frameborder', 'allow', 'allowfullscreen', 'class', 'title'],
        'video' => ['src', 'controls', 'poster', 'width', 'height', 'class', 'preload'],
        'source' => ['src', 'type'],
        'p' => ['class'],
        'div' => ['class'],
        'span' => ['class'],
        'figure' => ['class'],
        'blockquote' => ['class'],
        'h2' => ['class'],
        'h3' => ['class'],
        'h4' => ['class'],
    ];

    public static function sanitize(?string $html): string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return '';
        }

        if (! self::looksLikeHtml($html)) {
            return $html;
        }

        $wrapped = '<div id="rich-text-root">'.$html.'</div>';
        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8">'.$wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('rich-text-root');

        if (! $root instanceof DOMElement) {
            return strip_tags($html, '<'.implode('><', self::ALLOWED_TAGS).'>');
        }

        self::sanitizeNode($root);

        $output = '';
        foreach ($root->childNodes as $child) {
            $output .= $document->saveHTML($child);
        }

        return trim(html_entity_decode($output, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }

    public static function render(?string $value, ?string $fallback = null): string
    {
        $value = trim((string) ($value ?? ''));

        if ($value === '') {
            $value = trim((string) ($fallback ?? ''));
        }

        if ($value === '') {
            return '';
        }

        $html = ! self::looksLikeHtml($value)
            ? self::plainToHtml($value)
            : self::sanitize($value);

        return InternalLinker::apply($html);
    }

    public static function prepareForEditor(?string $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return '';
        }

        if (! self::looksLikeHtml($value)) {
            return self::plainToHtml($value);
        }

        return self::sanitize($value);
    }

    public static function looksLikeHtml(string $value): bool
    {
        return (bool) preg_match('/<\/?[a-z][\s\S]*>/i', $value);
    }

    public static function plainToHtml(string $plain): string
    {
        $plain = str_replace(["\r\n", "\r"], "\n", trim($plain));

        if ($plain === '') {
            return '';
        }

        $paragraphs = preg_split("/\n{2,}/", $plain) ?: [];
        $html = [];

        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);

            if ($paragraph === '') {
                continue;
            }

            $html[] = '<p>'.nl2br(e($paragraph), false).'</p>';
        }

        return implode('', $html);
    }

    private static function sanitizeNode(DOMNode $node): void
    {
        if (! $node->hasChildNodes()) {
            return;
        }

        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            if ($child->nodeType === XML_TEXT_NODE || $child->nodeType === XML_CDATA_SECTION_NODE) {
                continue;
            }

            if ($child->nodeType === XML_COMMENT_NODE) {
                $child->parentNode?->removeChild($child);

                continue;
            }

            if (! $child instanceof DOMElement) {
                $child->parentNode?->removeChild($child);

                continue;
            }

            $tag = strtolower($child->tagName);

            if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                self::unwrapNode($child);

                continue;
            }

            self::sanitizeAttributes($child, $tag);
            self::sanitizeNode($child);
        }
    }

    private static function sanitizeAttributes(DOMElement $element, string $tag): void
    {
        $allowed = self::ALLOWED_ATTRIBUTES[$tag] ?? [];
        $remove = [];

        foreach ($element->attributes ?? [] as $attribute) {
            $name = strtolower($attribute->name);

            if (! in_array($name, $allowed, true)) {
                $remove[] = $attribute->name;

                continue;
            }

            $value = trim($attribute->value);

            if (in_array($name, ['href', 'src'], true) && self::isDangerousUrl($value)) {
                $remove[] = $attribute->name;

                continue;
            }

            if ($name === 'href' && str_starts_with(strtolower($value), 'mailto:')) {
                continue;
            }

            if ($tag === 'a' && $name === 'target' && $value === '_blank') {
                $element->setAttribute('rel', 'noopener noreferrer');
            }

            if ($tag === 'iframe' && $name === 'src' && ! self::isAllowedIframeSrc($value)) {
                $remove[] = $attribute->name;
            }

            if ($tag === 'video' && $name === 'controls') {
                $element->setAttribute('controls', 'controls');
            }
        }

        foreach ($remove as $name) {
            $element->removeAttribute($name);
        }

        if ($tag === 'iframe' && ! $element->hasAttribute('src')) {
            self::unwrapNode($element);
        }
    }

    private static function unwrapNode(DOMElement $element): void
    {
        $parent = $element->parentNode;

        if ($parent === null) {
            return;
        }

        while ($element->firstChild) {
            $parent->insertBefore($element->firstChild, $element);
        }

        $parent->removeChild($element);
    }

    private static function isDangerousUrl(string $value): bool
    {
        $normalized = strtolower(trim($value));

        if ($normalized === '') {
            return true;
        }

        if (str_starts_with($normalized, '#') || str_starts_with($normalized, '/')) {
            return false;
        }

        if (str_starts_with($normalized, 'mailto:') || str_starts_with($normalized, 'tel:')) {
            return false;
        }

        if (str_starts_with($normalized, '//')) {
            return false;
        }

        if (preg_match('/^(https?:|data:image\/)/i', $normalized) === 1) {
            return false;
        }

        return true;
    }

    private static function isAllowedIframeSrc(string $value): bool
    {
        $normalized = strtolower(trim($value));

        if ($normalized === '') {
            return false;
        }

        return (bool) preg_match(
            '#^(https?:)?//(www\.)?(youtube\.com|youtu\.be|player\.vimeo\.com|vimeo\.com)/#i',
            $normalized
        );
    }
}
