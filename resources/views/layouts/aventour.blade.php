<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

<title>@yield('title', 'Tanzania Safaris and Trekking')</title>
@hasSection('meta_description')
    <meta name="description" content="@yield('meta_description')">
@endif
@hasSection('meta_keywords')
    <meta name="keywords" content="@yield('meta_keywords')">
@endif
<link rel="canonical" href="@yield('canonical', url()->current())">
@stack('head')

@include('partials.aventour.styles')
@stack('styles')
</head>

<body @if (! empty($bodyClass)) class="{{ $bodyClass }}" @endif>
    @include('partials.aventour.ajax-loader')
    <div class="boxed_wrapper">
        @include('partials.aventour.sidebar-popup')
        @include('partials.aventour.header')
        @include('partials.aventour.mobile-menu')

        @yield('content')

        @include('partials.aventour.award')
        @include('partials.aventour.footer')

        <button class="scroll-top scroll-to-target" data-target="html">
            <i class="flaticon-arrow-1"></i>
        </button>
    </div>

    @include('partials.aventour.scripts')
    @stack('scripts')
</body>
</html>
