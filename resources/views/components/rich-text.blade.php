@props([
    'content' => null,
    'fallback' => null,
])

@php
    $html = rich(is_string($content) ? $content : null, is_string($fallback) ? $fallback : null);
@endphp

@if ($html !== '')
    <div {{ $attributes->merge(['class' => 'rich-text']) }}>
        {!! $html !!}
    </div>
@endif
