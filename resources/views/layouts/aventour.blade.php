<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

@php
    $siteTitle = $siteSettings['company_name'] ?? 'Tanzania Safaris and Trekking';
    $pageSeo = $pageSeo ?? \App\Support\PageSeo::siteDefaults();
    $pageJsonLd = $pageJsonLd ?? [];
    // yieldContent() HTML-escapes section values; decode once so {{ }} does not double-escape (&amp;amp;).
    $yieldText = function (string $section, string $default = '') use ($__env): string {
        return trim(html_entity_decode(
            $__env->yieldContent($section, $default),
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        ));
    };
    $resolvedTitle = $yieldText('title', $pageSeo['title']);
    $gtmId = \App\Support\PageSeo::gtmId($siteSettings ?? null);
    $ga4Id = \App\Support\PageSeo::ga4Id($siteSettings ?? null);
    $canonicalUrl = $yieldText('canonical', request()->getSchemeAndHttpHost().request()->getPathInfo());
    $ogImage = $yieldText('og_image', $pageSeo['og_image'] ?? '');
@endphp

<title>{{ $resolvedTitle }}</title>

@if ($gtmId)
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{{ $gtmId }}');</script>
@endif
@if ($ga4Id && ! $gtmId)
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $ga4Id }}');</script>
@endif

<meta name="description" content="@yield('meta_description', $pageSeo['description'])">
<meta name="keywords" content="@yield('meta_keywords', $pageSeo['keywords'])">
<meta name="author" content="{{ $siteTitle }}">
<meta name="publisher" content="{{ $siteTitle }}">
<meta name="robots" content="@yield('meta_robots', 'index, follow, max-image-preview:large, max-snippet:-1')">
<meta name="googlebot" content="index, follow, max-image-preview:large, max-snippet:-1">
<meta name="theme-color" content="#1f6b4f">
<meta name="format-detection" content="telephone=no">
<meta name="referrer" content="strict-origin-when-cross-origin">

<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:site_name" content="{{ $siteTitle }}">
<meta property="og:title" content="{{ $yieldText('og_title', $resolvedTitle) }}">
<meta property="og:description" content="@yield('og_description', $pageSeo['og_description'])">
<meta property="og:url" content="{{ $canonicalUrl }}">
@if ($ogImage !== '')
<meta property="og:image" content="{{ $ogImage }}">
@endif
<meta property="og:locale" content="en_US">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $yieldText('twitter_title', $resolvedTitle) }}">
<meta name="twitter:description" content="@yield('twitter_description', $pageSeo['og_description'])">
@if ($ogImage !== '')
<meta name="twitter:image" content="{{ $yieldText('twitter_image', $ogImage) }}">
@endif

<meta name="geo.region" content="TZ">
<meta name="geo.placename" content="Arusha, Tanzania">
<meta name="geo.position" content="-3.3869;36.6830">
<meta name="ICBM" content="-3.3869, 36.6830">

<link rel="canonical" href="{{ $canonicalUrl }}">
@stack('head')

@include('partials.aventour.styles')
@stack('styles')

@if (! empty($pageJsonLd))
<script type="application/ld+json">{!! \App\Support\JsonLd::encode($pageJsonLd) !!}</script>
@endif
</head>

<body @if (! empty($bodyClass)) class="{{ $bodyClass }}" @endif>
    @if ($gtmId)
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    @include('partials.aventour.ajax-loader')
    @include('partials.aventour.site-notice')
    <div class="boxed_wrapper">
        @include('partials.aventour.sidebar-popup')
        @include('partials.aventour.header')
        @include('partials.aventour.mobile-menu')

        @yield('content')

        @include('partials.aventour.award')
        @include('partials.aventour.reviews')
        @include('partials.aventour.footer')

        <button class="scroll-top scroll-to-target" data-target="html">
            <i class="flaticon-arrow-1"></i>
        </button>
    </div>

    @include('partials.aventour.scripts')
    @include('partials.cms-preview')
    @include('partials.whatsapp-button')
    @stack('scripts')
</body>
</html>
