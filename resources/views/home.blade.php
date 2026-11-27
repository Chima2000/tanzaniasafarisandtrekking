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
    $resolvedTitle = $page?->seo_title ?: ($pageSeo['title'] ?? $siteTitle);
    $resolvedDescription = $page?->seo_description ?: ($pageSeo['description'] ?? '');
    $resolvedKeywords = $page?->seo_keywords ?: ($pageSeo['keywords'] ?? '');
    $canonicalUrl = request()->getSchemeAndHttpHost().request()->getPathInfo();
    $ogImage = $pageSeo['og_image'] ?? absolute_url(asset('logo/tanzania-safaris-and-trekking-logo.png'));
    $gtmId = \App\Support\PageSeo::gtmId($siteSettings ?? null);
    $ga4Id = \App\Support\PageSeo::ga4Id($siteSettings ?? null);
@endphp
<title>{{ $resolvedTitle }}</title>
<meta name="description" content="{{ $resolvedDescription }}">
<meta name="keywords" content="{{ $resolvedKeywords }}">
<meta name="author" content="{{ $siteTitle }}">
<meta name="publisher" content="{{ $siteTitle }}">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
<meta name="googlebot" content="index, follow, max-image-preview:large, max-snippet:-1">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteTitle }}">
<meta property="og:title" content="{{ $resolvedTitle }}">
<meta property="og:description" content="{{ $resolvedDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
@if ($ogImage)
<meta property="og:image" content="{{ $ogImage }}">
@endif
<meta property="og:locale" content="en_US">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $resolvedTitle }}">
<meta name="twitter:description" content="{{ $resolvedDescription }}">
@if ($ogImage)
<meta name="twitter:image" content="{{ $ogImage }}">
@endif
<meta name="geo.region" content="TZ">
<meta name="geo.placename" content="Arusha, Tanzania">
<meta name="geo.position" content="-3.3869;36.6830">
<meta name="ICBM" content="-3.3869, 36.6830">
<link rel="canonical" href="{{ $canonicalUrl }}">
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
@if (! empty($pageJsonLd))
<script type="application/ld+json">{!! \App\Support\JsonLd::encode($pageJsonLd) !!}</script>
@endif

<!-- Fav Icon -->
<link rel="icon" href="{{ asset('logo/tanzania-safaris-and-trekking-logo.png') }}" type="image/png">
<link rel="apple-touch-icon" href="{{ asset('logo/tanzania-safaris-and-trekking-logo.png') }}">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Merienda:wght@300..900&display=swap" rel="stylesheet">

<!-- fontawesome -->
<link href="/aventour/assets/css/fontawesome/fontawesome.css" rel="stylesheet" />
<link href="/aventour/assets/css/fontawesome/brands.css" rel="stylesheet" />
<link href="/aventour/assets/css/fontawesome/regular.css" rel="stylesheet" />
<link href="/aventour/assets/css/fontawesome/solid.css" rel="stylesheet" />

<!-- Stylesheets -->
<link href="/aventour/assets/css/flaticon.css" rel="stylesheet">
<link href="/aventour/assets/css/swiper-bundle.min.css" rel="stylesheet">
<link href="/aventour/assets/css/bootstrap.css" rel="stylesheet">
<link href="/aventour/assets/css/jquery.fancybox.min.css" rel="stylesheet">
<link href="/aventour/assets/css/animate.css" rel="stylesheet">
<link href="/aventour/assets/css/nice-select.css" rel="stylesheet">
<link href="/aventour/assets/css/odometer.css" rel="stylesheet">
<link href="/aventour/assets/css/jquery-ui.css" rel="stylesheet">
<link href="/aventour/assets/css/elpath.css" rel="stylesheet">
<link href="/aventour/assets/css/style.css" rel="stylesheet">
<link href="/aventour/assets/css/responsive.css" rel="stylesheet">
<link href="/aventour/assets/css/brand.css?v={{ filemtime(public_path('aventour/assets/css/brand.css')) }}" rel="stylesheet">
<link href="/aventour/assets/css/ajax-loader.css?v={{ filemtime(public_path('aventour/assets/css/ajax-loader.css')) }}" rel="stylesheet">


<style>
    .brand-image-logo {
        display: inline-flex;
        align-items: center;
        line-height: 0;
    }
    .brand-image-logo img {
        display: block;
        width: auto;
        max-width: 100%;
        height: auto;
        object-fit: contain;
    }
</style>
</head>


<!-- page wrapper -->
<body>
    @if (! empty($gtmId))
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    @include('partials.aventour.ajax-loader')

    <div class="boxed_wrapper">

        <!--sidebar popup-->
        <div id="sidebar-popup" class="sidebar-popup">
            <div class="popup-inner">
                <div class="close-popup"><i class="flaticon-failed"></i></div>
                <figure class="logo">@include('partials.aventour.brand-logo')</figure>
                <div class="form-inner">
                    <form action="{{ url('/') }}" method="post">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="hello@example.com" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" placeholder="your password" required>
                        </div>
                        <div class="forgot-password">
                            <div class="check-box-one">
                                <input class="check" type="checkbox" id="checkbox0">
                                <label for="checkbox0">Remember Me</label>
                            </div>
                            <button type="button">Forgot</button>
                        </div>
                        <div class="message-btn mb_17">
                            <button type="submit" class="theme-btn "><span>Appointment</span><i class="flaticon-arrow"></i></button>
                        </div>
                        <div class="text-box">
                            <p>New customer? <a href="{{ url('/') }}">Create account.</a></p>
                        </div>
                    </form>
                </div>
                <div class="inner-box">
                    <div class="icon-box"><i class="flaticon-calendar-1"></i></div>
                    <div class="light-icon"><i class="flaticon-calendar-1"></i></div>
                    <h3>Get <span>Custom <br />Tour</span> Solutions Now!</h3>
                    <a href="{{ url('/') }}" class="theme-btn secondary-bg"><span>Appointment</span><i class="flaticon-arrow"></i></a>
                </div>
                <div class="copyright">
                    <p>Copyrights &copy; 2026 <a href="{{ url('/') }}">Tanzania Safaris and Trekking</a>, All Rights Reserved.</p>
                </div>
            </div>
        </div>


        <!-- main header -->
        <header class="main-header">
            <div class="large-container">
                <!-- header-top -->
                <div class="header-top">
                    <div class="bg-layer"></div>
                    <div class="top-inner">
                        @php
                            $phone = $siteSettings['phone'] ?? '+255 769 079 298';
                            $email = $siteSettings['email'] ?? 'info@tanzaniasafarisandtrekking.com';
                            $twitterUrl = ($siteSettings['twitter_url'] ?? '') !== '' ? $siteSettings['twitter_url'] : url('/');
                            $instagramUrl = ($siteSettings['instagram_url'] ?? '') !== '' ? $siteSettings['instagram_url'] : url('/');
                            $phoneHref = 'tel:'.preg_replace('/\s+/', '', $phone);
                        @endphp
                        <div class="header-top-left">
                            <p>
                                <a href="{{ $phoneHref }}" class="header-top-icon-link" aria-label="Call {{ $phone }}">
                                    <img src="/aventour/assets/images/icons/icon-1.png" alt="">
                                    <span class="header-top-text">{{ $phone }}</span>
                                </a>
                            </p>
                            @include('partials.aventour.header-search')
                        </div>
                        <div class="right-column">
                            <ul class="info-list">
                                <li>
                                    <a href="mailto:{{ $email }}" class="header-top-icon-link" aria-label="Email {{ $email }}">
                                        <img src="/aventour/assets/images/icons/icon-2.png" alt="">
                                        <span class="header-top-text">{{ $email }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('destinations.index') }}" class="header-top-icon-link" aria-label="Must-See Moments">
                                        <img src="/aventour/assets/images/icons/icon-3.png" alt="">
                                        <span class="header-top-text">Must-See Moments</span>
                                    </a>
                                </li>
                            </ul>
                            <ul class="social-links">
                                <li><a href="{{ $twitterUrl }}" aria-label="X / Twitter" @if(($siteSettings['twitter_url'] ?? '') !== '') target="_blank" rel="noopener" @endif><i class="flaticon-twitter"></i></a></li>
                                <li><a href="{{ $instagramUrl }}" aria-label="Instagram" @if(($siteSettings['instagram_url'] ?? '') !== '') target="_blank" rel="noopener" @endif><i class="flaticon-instagram-logo"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- header-lower -->
                <div class="header-lower">
                    <div class="outer-box pl_30 pr_30">
                        <figure class="logo">@include('partials.aventour.brand-logo')</figure>
                        <div class="menu-area clearfix">
                            <!--Mobile Navigation Toggler-->
                            <div class="mobile-nav-toggler">
                                <i class="icon-bar"></i>
                                <i class="icon-bar"></i>
                                <i class="icon-bar"></i>
                            </div>
                            <nav class="main-menu navbar-expand-md navbar-light">
                                <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                                                                        <ul class="navigation clearfix">
                                        <li><a href="{{ route('packages.safari') }}">Safari</a></li>
                                        <li><a href="{{ route('packages.trekking') }}">Kilimanjaro Treks</a></li>
                                        <li><a href="{{ route('packages.day-trips') }}">Day Trips</a></li>
                                        <li><a href="{{ route('destinations.index') }}">Destinations</a></li>
                                        <li><a href="{{ route('blog') }}">Blog</a></li>
                                        <li><a href="{{ route('about') }}">About Us</a></li>
                                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                        <div class="menu-right-content">
                            <div class="btn-box"><a href="{{ route('book-now') }}" class="theme-btn"><span>Book Now</span><i class="flaticon-arrow"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>

            <!--sticky Header-->
            <div class="sticky-header">
                <div class="large-container">
                    <div class="outer-box pl_30 pr_30">
                        <figure class="logo">@include('partials.aventour.brand-logo')</figure>
                        <div class="menu-area clearfix">
                            <div class="mobile-nav-toggler">
                                <i class="icon-bar"></i>
                                <i class="icon-bar"></i>
                                <i class="icon-bar"></i>
                            </div>
                            <nav class="main-menu clearfix">
                                <!--Keep This Empty / Menu will come through Javascript-->
                            </nav>
                        </div>
                        <div class="menu-right-content">
                            <div class="btn-box"><a href="{{ route('book-now') }}" class="theme-btn"><span>Book Now</span><i class="flaticon-arrow"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- main-header end -->


        <!-- Mobile Menu  -->
        <div class="mobile-menu">
            <div class="menu-backdrop"></div>
            <div class="close-btn"><i class="fas fa-times"></i></div>
            
            <nav class="menu-box">
                <div class="nav-logo">@include('partials.aventour.brand-logo', ['logoVariant' => 'white'])</div>
                <div class="menu-outer"><!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header--></div>
                <div class="contact-info">
                    <h4>Contact Info</h4>
                    <ul>
                        <li>Arusha & Moshi, Tanzania</li>
                        <li><a href="tel:+255769079298">+255 769 079 298</a></li>
                        <li><a href="mailto:info@tanzaniasafarisandtrekking.com">info@tanzaniasafarisandtrekking.com</a></li>
                    </ul>
                </div>
                <div class="social-links">
                    <ul class="clearfix">
                        <li><a href="{{ url('/') }}"><span class="fab fa-twitter"></span></a></li>
                        <li><a href="{{ url('/') }}"><span class="fab fa-facebook-square"></span></a></li>
                        <li><a href="{{ url('/') }}"><span class="fab fa-pinterest-p"></span></a></li>
                        <li><a href="{{ url('/') }}"><span class="fab fa-instagram"></span></a></li>
                        <li><a href="{{ url('/') }}"><span class="fab fa-youtube"></span></a></li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- End Mobile Menu -->


        @php
            $c = $pageContent ?? [];
            $heroSlides = $heroSlides ?? [];
        @endphp
        <!-- banner-section -->
        <section class="banner-section" data-cms-section="hero-slideshow" id="cms-hero-slideshow">
            <div class="banner-carousel">
                <div class="swiper-wrapper">
                    @foreach ($heroSlides as $index => $slide)
                        @php
                            $isVideo = ($slide['type'] ?? 'image') === 'video';
                            $media = \App\Support\CmsMedia::url($slide['media'] ?? null, $sectionImages['banner'][$index] ?? ($packages[$index]['image'] ?? '/aventour/assets/images/banner/banner-1.jpg'));
                            $poster = \App\Support\CmsMedia::url($slide['poster'] ?? null, $media);
                            $badgeValue = ($slide['badge_value'] ?? '') !== ''
                                ? $slide['badge_value']
                                : ($index === 0 ? ($packageCount.'+') : '');
                        @endphp
                        <div class="swiper-slide">
                            <div class="slide-item {{ $isVideo ? 'is-video-slide' : '' }}" data-cms-section="hero-slideshow">
                                @if ($isVideo && $media)
                                    <div class="bg-layer bg-layer--video">
                                        <video
                                            class="banner-video"
                                            autoplay
                                            muted
                                            loop
                                            playsinline
                                            @if ($poster) poster="{{ $poster }}" @endif
                                        >
                                            <source src="{{ $media }}" type="{{ str_ends_with(strtolower($media), '.webm') ? 'video/webm' : (str_ends_with(strtolower($media), '.mov') ? 'video/quicktime' : 'video/mp4') }}">
                                        </video>
                                    </div>
                                @else
                                    <div class="bg-layer" style="{{ \App\Support\CmsMedia::backgroundStyle($media, $slide['position'] ?? null) }}"></div>
                                @endif
                                <div class="auto-container">
                                    <div class="content-box">
                                        @if (($slide['kicker'] ?? '') !== '')
                                            <div class="sub-title"><i class="flaticon-parachuting"></i><span>{{ $slide['kicker'] }}</span></div>
                                        @endif
                                        <h2>{{ $slide['title'] }}</h2>
                                        <div class="inner-box">
                                            <div class="text-box">
                                                <x-rich-text :content="$slide['body'] ?? null" />
                                                <a href="{{ $slide['cta_url'] ?: route('packages.safari') }}" class="theme-btn"><span>{{ $slide['cta'] ?: 'See Packages' }}</span><i class="flaticon-arrow"></i></a>
                                            </div>
                                            <div class="price-box centred">
                                                <div class="shape" style="background-image: url(/aventour/assets/images/shape/shape-2.svg);"></div>
                                                <h5>{{ $slide['badge_label'] ?: 'Adventures start from' }}</h5>
                                                <span class="price">{{ $badgeValue !== '' ? $badgeValue : ($packageCount.'+') }}</span>
                                                <span class="text">{{ $slide['badge_text'] ?: 'Packages' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if (count($heroSlides) > 1)
                    <div class="banner-navigation">
                        <button class="banner-prev-btn"><i class="flaticon-arrow-1"></i></button>
                        <button class="banner-next-btn"><i class="flaticon-arrow-1"></i></button>
                    </div>
                @endif
            </div>
        </section>
        <!-- banner-section end -->


        @include('partials.home-about')


        <!-- categories-section -->
        <section class="categories-section pt_110" id="categories" data-cms-section="categories">
            @php
                $categoriesBg = \App\Support\CmsMedia::url($c['categories_bg'] ?? null, $sectionImages['categories_bg'] ?? ($galleryImages[1] ?? '/aventour/assets/images/background/categories-bg.jpg'));
            @endphp
            <div class="bg-layer" data-cms-field="categories_bg" style="{{ \App\Support\CmsMedia::backgroundStyle($categoriesBg, $c['categories_bg_position'] ?? null) }}"></div>
            <div class="shape float-bob-y" style="background-image: url(/aventour/assets/images/shape/shape-3.png);"></div>
            <div class="auto-container">
                <div class="sec-title light">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span data-cms-field="categories_subtitle">{{ $c['categories_subtitle'] ?? 'Travel Styles' }}</span></div>
                    <h2 data-cms-field="categories_heading">{{ $c['categories_heading'] ?? 'Choose Your Tanzania Style' }}</h2>
                    <x-rich-text class="mt_12" data-cms-field="categories_body" :content="$c['categories_body'] ?? null" fallback="Safari, summit, culture — your Tanzania, your way." />
                    <div class="link-box light"><a href="{{ route('packages.index') }}"><span data-cms-field="categories_cta">{{ $c['categories_cta'] ?? 'View All Categories' }}</span><i class="flaticon-arrow"></i></a></div>
                </div>
                @include('partials.home-categories')
            </div>
        </section>
        <!-- categories-section end -->


        <!-- destinations-section -->
        <section class="destinations-section pt_80 pb_110" id="destinations" data-cms-section="destinations">
            <div class="shape-box">
                <div class="shape-1" style="background-image: url(/aventour/assets/images/shape/shape-4.png);"></div>
                <div class="shape-2 float-bob-x" style="background-image: url(/aventour/assets/images/shape/shape-5.png);"></div>
            </div>
            <div class="outer-container pl_75 pr_75">
                <div class="sec-title centred">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span data-cms-field="destinations_subtitle">{{ $c['destinations_subtitle'] ?? 'Popular Destinations' }}</span></div>
                    <h2 data-cms-field="destinations_heading">{{ $c['destinations_heading'] ?? 'Iconic Places in Tanzania' }}</h2>
                    <x-rich-text class="mt_12" data-cms-field="destinations_body" :content="$c['destinations_body'] ?? null" fallback="Explore our most loved safari and trekking experiences." />
                </div>
                @include('partials.home-destinations')
            </div>
        </section>
        <!-- destinations-section end -->


        <!-- journeys-section -->
        @php
            $journeysImage1 = \App\Support\CmsMedia::url($c['journeys_image_1'] ?? null, $sectionImages['journeys'][0] ?? ($packages[0]['image'] ?? '/aventour/assets/images/resource/journeys-1.jpg'));
            $journeysImage2 = \App\Support\CmsMedia::url($c['journeys_image_2'] ?? null, $sectionImages['journeys'][1] ?? ($packages[1]['image'] ?? '/aventour/assets/images/resource/journeys-2.jpg'));
            $journeysImage3 = \App\Support\CmsMedia::url($c['journeys_image_3'] ?? null, $sectionImages['journeys'][2] ?? ($trekPackages[0]['image'] ?? ($packages[2]['image'] ?? '/aventour/assets/images/resource/journeys-3.jpg')));
            $chooseusBg = \App\Support\CmsMedia::url($c['chooseus_bg'] ?? null, $sectionImages['chooseus_bg'] ?? ($galleryImages[0] ?? '/aventour/assets/images/background/chooseus-bg.png'));
        @endphp
        <section class="journeys-section sec-pad bg-color-1" id="journeys" data-cms-section="journeys">
            <div class="pattern-layer float-bob-x" style="background-image: url(/aventour/assets/images/shape/shape-8.png);"></div>
            <div class="auto-container">
                <div class="sec-title">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span data-cms-field="journeys_subtitle">{{ $c['journeys_subtitle'] ?? 'Behind the Journey' }}</span></div>
                    <h2 data-cms-field="journeys_heading">{{ $c['journeys_heading'] ?? 'Personal Safaris, Memorable Experiences' }}</h2>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                        <div class="image-box">
                            <div class="image-shape float-bob-y" style="background-image: url(/aventour/assets/images/shape/shape-6.png);"></div>
                            <figure class="image image-1" data-cms-field="journeys_image_1"><img src="{{ $journeysImage1 }}" alt="" style="{{ \App\Support\CmsMedia::objectStyle($c['journeys_image_1_position'] ?? null) }}"></figure>
                            <figure class="image image-2" data-cms-field="journeys_image_2"><img src="{{ $journeysImage2 }}" alt="" style="{{ \App\Support\CmsMedia::objectStyle($c['journeys_image_2_position'] ?? null) }}"></figure>
                            <figure class="image image-3" data-cms-field="journeys_image_3"><img src="{{ $journeysImage3 }}" alt="" style="{{ \App\Support\CmsMedia::objectStyle($c['journeys_image_3_position'] ?? null) }}"></figure>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                        <div class="content-box">
                            <div class="text-box">
                                <x-rich-text data-cms-field="journeys_body" :content="$c['journeys_body'] ?? null" fallback="Travel is more than seeing places — it's about making memories that last. Whether you dream of wildlife, culture, or Kilimanjaro, our local experts plan your perfect Tanzania trip." />
                            </div>
                            <div class="single-item">
                                <div class="title-box p_relative clearfix">
                                    <div class="icon-shape" style="background-image: url(/aventour/assets/images/shape/shape-7.png);"></div>
                                    <h3 data-cms-field="journeys_card_one_title">{{ $c['journeys_card_one_title'] ?? 'Made for Small Groups' }}</h3>
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-6.png" alt=""></div>
                                </div>
                                <div class="inner-box">
                                    <div class="bg-layer" style="background-image: url('{{ $sectionImages['journeys'][3] ?? ($packages[3]['image'] ?? '/aventour/assets/images/resource/journeys-4.jpg') }}');"></div>
                                    <x-rich-text data-cms-field="journeys_card_one_body" :content="$c['journeys_card_one_body'] ?? null" fallback="Explore freely with personalized routes and private service." />
                                    <ul class="list clearfix">
                                        <li><img src="/aventour/assets/images/icons/icon-8.png" alt=""><span>Handpicked boutique stays</span></li>
                                        <li><img src="/aventour/assets/images/icons/icon-8.png" alt=""><span>Private transportation</span></li>
                                    </ul>
                                    <div class="link-box light"><a href="{{ route('about') }}"><span>Read More</span><i class="flaticon-arrow"></i></a></div>
                                </div>
                            </div>
                            <div class="single-item">
                                <div class="title-box p_relative clearfix">
                                    <div class="icon-shape" style="background-image: url(/aventour/assets/images/shape/shape-7.png);"></div>
                                    <h3 data-cms-field="journeys_card_two_title">{{ $c['journeys_card_two_title'] ?? 'Private Travel Made Easy' }}</h3>
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-7.png" alt=""></div>
                                </div>
                                <div class="inner-box">
                                    <div class="bg-layer" style="background-image: url('{{ $sectionImages['journeys'][4] ?? ($packages[4]['image'] ?? '/aventour/assets/images/resource/journeys-5.jpg') }}');"></div>
                                    <x-rich-text data-cms-field="journeys_card_two_body" :content="$c['journeys_card_two_body'] ?? null" fallback="Tailored trips that fit your pace, style, and comfort." />
                                    <ul class="list clearfix">
                                        <li><img src="/aventour/assets/images/icons/icon-8.png" alt=""><span>Luxury Stays & Handpicked Hotels</span></li>
                                        <li><img src="/aventour/assets/images/icons/icon-8.png" alt=""><span>Flexible Travel Dates & Pace</span></li>
                                    </ul>
                                    <div class="link-box light"><a href="{{ route('about') }}"><span>Read More</span><i class="flaticon-arrow"></i></a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- journeys-section end -->


        <!-- package-section -->
        <section class="package-section" id="safari" data-cms-section="safari-packages">
            <div class="auto-container">
                <div class="sec-title centred">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span data-cms-field="safari_subtitle">{{ $c['safari_subtitle'] ?? 'Safari Packages' }}</span></div>
                    <h2 data-cms-field="safari_heading">{{ $c['safari_heading'] ?? 'Wildlife Safari Adventures' }}</h2>
                    <x-rich-text class="mt_12" data-cms-field="safari_body" :content="$c['safari_body'] ?? null" fallback="Explore Tarangire, Serengeti, Ngorongoro, and more." />
                </div>
                @include('partials.home-packages', ['items' => $safariPackages])
                <div class="lower-text centred">
                    <p data-cms-field="safari_footer">{{ $c['safari_footer'] ?? 'Discover more Tanzania safari journeys.' }}</p>
                    <div class="link-box"><a href="{{ route('packages.safari') }}"><span data-cms-field="safari_cta">{{ $c['safari_cta'] ?? 'View Safari Packages' }}</span><i class="flaticon-arrow"></i></a></div>
                </div>
            </div>
        </section>

        <section class="package-section pt_90" id="kilimanjaro" data-cms-section="trek-packages">
            <div class="auto-container">
                <div class="sec-title centred">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span data-cms-field="trek_subtitle">{{ $c['trek_subtitle'] ?? 'Kilimanjaro Treks' }}</span></div>
                    <h2 data-cms-field="trek_heading">{{ $c['trek_heading'] ?? 'Climb Mount Kilimanjaro' }}</h2>
                    <x-rich-text class="mt_12" data-cms-field="trek_body" :content="$c['trek_body'] ?? null" fallback="Marangu, Machame, Lemosho, Rongai, Umbwe, and Northern Circuit." />
                </div>
                @include('partials.home-packages', ['items' => $trekPackages])
                <div class="lower-text centred">
                    <p data-cms-field="trek_footer">{{ $c['trek_footer'] ?? 'Discover more Kilimanjaro climbing routes.' }}</p>
                    <div class="link-box"><a href="{{ route('packages.trekking') }}"><span data-cms-field="trek_cta">{{ $c['trek_cta'] ?? 'View Trek Packages' }}</span><i class="flaticon-arrow"></i></a></div>
                </div>
            </div>
        </section>
        <!-- package-section end -->


        <!-- chooseus-section -->
        <section class="chooseus-section sec-pad-2 bg-color-1" data-cms-section="chooseus">
            <div class="bg-image" aria-hidden="true">
                <div class="bg-shape-1" style="background-image: url(/aventour/assets/images/shape/shape-9.png);"></div>
                <div class="bg-shape-2 float-bob-x" style="background-image: url(/aventour/assets/images/shape/shape-10.png);"></div>
                <div class="bg-image-1" data-cms-field="chooseus_bg" style="{{ \App\Support\CmsMedia::backgroundStyle($chooseusBg, $c['chooseus_bg_position'] ?? null) }}"></div>
            </div>
            <div class="auto-container">
                <div class="sec-title centred">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span data-cms-field="chooseus_subtitle">{{ $c['chooseus_subtitle'] ?? 'Why Tanzania Safaris and Trekking' }}</span></div>
                    <h2 data-cms-field="chooseus_heading">{{ $c['chooseus_heading'] ?? 'Your Trusted Travel Partner' }}</h2>
                    <x-rich-text class="mt_12" data-cms-field="chooseus_body" :content="$c['chooseus_body'] ?? null" fallback="Guiding you to unforgettable experiences across the world’s wonders." />
                </div>
                <div class="chooseus-grid">
                    <div class="chooseus-block">
                        <div class="chooseus-block-one">
                            <div class="icon-box"><img src="/aventour/assets/images/icons/icon-9.png" alt=""></div>
                            <h3><a href="{{ url('/') }}" data-cms-field="chooseus_item_1_title">{{ $c['chooseus_item_1_title'] ?? 'Curated Tours' }}</a></h3>
                            <x-rich-text data-cms-field="chooseus_item_1_body" :content="$c['chooseus_item_1_body'] ?? null" fallback="Only the best destinations and experiences, selected just for you." />
                        </div>
                    </div>
                    <div class="chooseus-block">
                        <div class="chooseus-block-one">
                            <div class="icon-box"><img src="/aventour/assets/images/icons/icon-10.png" alt=""></div>
                            <h3><a href="{{ url('/') }}" data-cms-field="chooseus_item_2_title">{{ $c['chooseus_item_2_title'] ?? 'Guides Near You' }}</a></h3>
                            <x-rich-text data-cms-field="chooseus_item_2_body" :content="$c['chooseus_item_2_body'] ?? null" fallback="Get the best value with fair, transparent pricing and no hidden costs." />
                        </div>
                    </div>
                    <div class="chooseus-block">
                        <div class="chooseus-block-one">
                            <div class="icon-box"><img src="/aventour/assets/images/icons/icon-11.png" alt=""></div>
                            <h3><a href="{{ url('/') }}" data-cms-field="chooseus_item_3_title">{{ $c['chooseus_item_3_title'] ?? 'Safety Knowledge' }}</a></h3>
                            <x-rich-text data-cms-field="chooseus_item_3_body" :content="$c['chooseus_item_3_body'] ?? null" fallback="Safe and simple payments for a stress-free booking experience." />
                        </div>
                    </div>
                    <div class="chooseus-grid__stats">
                        <div class="chooseus-block">
                            <div class="chooseus-block-two">
                                <div class="icon-box"><img src="/aventour/assets/images/icons/icon-12.png" alt=""></div>
                                <div class="inner-box">
                                    <h2>15+</h2>
                                    <h3><a href="{{ url('/') }}">Years Experience</a></h3>
                                    <p>Trusted travel expertise since 2010.</p>
                                </div>
                            </div>
                        </div>
                        <div class="chooseus-block">
                            <div class="chooseus-block-two">
                                <div class="icon-box"><img src="/aventour/assets/images/icons/icon-13.png" alt=""></div>
                                <div class="inner-box">
                                    <h2>97%</h2>
                                    <h3><a href="{{ url('/') }}">Satisfied Travelers</a></h3>
                                    <p>Backed by real traveler reviews.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- chooseus-section end -->


        <!-- news-section -->
        <section class="news-section" id="blog" data-cms-section="blog">
            <div class="auto-container">
                <div class="sec-title centred">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span data-cms-field="blog_subtitle">{{ $c['blog_subtitle'] ?? 'Blog Post' }}</span></div>
                    <h2 data-cms-field="blog_heading">{{ $c['blog_heading'] ?? 'Latest From the Blog' }}</h2>
                    <x-rich-text class="mt_12" data-cms-field="blog_body" :content="$c['blog_body'] ?? null" fallback="Safari tips, trek advice, and Northern Circuit stories." />
                </div>
                @include('partials.home-blog', ['items' => $posts])
                <div class="lower-text centred">
                    <p data-cms-field="blog_footer">{{ $c['blog_footer'] ?? 'Continue your adventure through our blog.' }}</p>
                    <div class="link-box"><a href="{{ route('blog') }}"><span data-cms-field="blog_cta">{{ $c['blog_cta'] ?? 'All Blog Posts' }}</span><i class="flaticon-arrow"></i></a></div>
                </div>
            </div>
        </section>
        <!-- news-section end -->


        <!-- testimonial-section -->
        @php
            $testimonialBg = \App\Support\CmsMedia::url($c['testimonial_bg'] ?? null, $sectionImages['testimonial_bg'] ?? ($galleryImages[2] ?? '/aventour/assets/images/resource/testimonial-4.png'));
            $testimonial1Image = \App\Support\CmsMedia::url($c['testimonial_1_image'] ?? null, $sectionImages['testimonials'][0] ?? ($galleryImages[0] ?? '/aventour/assets/images/resource/testimonial-1.png'));
            $testimonial2Image = \App\Support\CmsMedia::url($c['testimonial_2_image'] ?? null, $sectionImages['testimonials'][1] ?? ($galleryImages[1] ?? '/aventour/assets/images/resource/testimonial-2.png'));
            $testimonial3Image = \App\Support\CmsMedia::url($c['testimonial_3_image'] ?? null, $sectionImages['testimonials'][2] ?? ($galleryImages[2] ?? '/aventour/assets/images/resource/testimonial-3.png'));
            $awardBg = \App\Support\CmsMedia::url($c['award_bg'] ?? null, $sectionImages['award_bg'] ?? ($trekPackages[1]['image'] ?? ($packages[8]['image'] ?? '/aventour/assets/images/background/award-bg.jpg')));
        @endphp
        <section class="testimonial-section bg-color-1" data-cms-section="testimonials">
            <div class="pattern-layer" style="background-image: url(/aventour/assets/images/shape/shape-11.png);"></div>
            <figure class="bg-image p_absolute l_50 b_0" data-cms-field="testimonial_bg"><img src="{{ $testimonialBg }}" alt="" style="{{ \App\Support\CmsMedia::objectStyle($c['testimonial_bg_position'] ?? null) }}"></figure>
            <div class="auto-container">
                <div class="sec-title">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span data-cms-field="testimonials_subtitle">{{ $c['testimonials_subtitle'] ?? 'Testimonials' }}</span></div>
                    <h2 data-cms-field="testimonials_heading">{{ $c['testimonials_heading'] ?? 'Stories From Travelers' }}</h2>
                    <div class="link-box p_absolute r_0 b_3"><a href="{{ url('/') }}"><span>All Testimonials</span><i class="flaticon-arrow"></i></a></div>
                </div>
                <div class="testimonial-content">
                    <div class="three-item-carousel">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="testimonial-block-one">
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-16.png" alt=""></div>
                                    <x-rich-text data-cms-field="testimonial_1_quote" :content="$c['testimonial_1_quote'] ?? null" fallback="This was hands down the best trip of my life! Everything was perfectly organized — from airport pickup to local guides. I felt safe, excited, and totally free." />
                                    <div class="lower-box">
                                        <div class="rating-box"><span data-cms-field="testimonial_1_rating">{{ $c['testimonial_1_rating'] ?? '4.6 out of 5' }}</span><i class="flaticon-star"></i></div>
                                        <div class="clients-box">
                                            <figure class="clients-image" data-cms-field="testimonial_1_image"><img src="{{ $testimonial1Image }}" alt="" style="{{ \App\Support\CmsMedia::objectStyle($c['testimonial_1_image_position'] ?? null) }}"></figure>
                                            <div class="clients-info">
                                                <h4 data-cms-field="testimonial_1_name">{{ $c['testimonial_1_name'] ?? 'Lucas Benjamin' }}</h4>
                                                <span class="designation" data-cms-field="testimonial_1_location">{{ $c['testimonial_1_location'] ?? 'Pasadena, CA' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-block-one">
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-16.png" alt=""></div>
                                    <x-rich-text data-cms-field="testimonial_2_quote" :content="$c['testimonial_2_quote'] ?? null" fallback="This was hands down the best trip of my life! Everything was perfectly organized — from airport pickup to local guides. I felt safe, excited, and totally free." />
                                    <div class="lower-box">
                                        <div class="rating-box"><span data-cms-field="testimonial_2_rating">{{ $c['testimonial_2_rating'] ?? '4.9 out of 5' }}</span><i class="flaticon-star"></i></div>
                                        <div class="clients-box">
                                            <figure class="clients-image" data-cms-field="testimonial_2_image"><img src="{{ $testimonial2Image }}" alt="" style="{{ \App\Support\CmsMedia::objectStyle($c['testimonial_2_image_position'] ?? null) }}"></figure>
                                            <div class="clients-info">
                                                <h4 data-cms-field="testimonial_2_name">{{ $c['testimonial_2_name'] ?? 'Jacob Lawrence' }}</h4>
                                                <span class="designation" data-cms-field="testimonial_2_location">{{ $c['testimonial_2_location'] ?? 'Melbourne, AU' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-block-one">
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-16.png" alt=""></div>
                                    <x-rich-text data-cms-field="testimonial_3_quote" :content="$c['testimonial_3_quote'] ?? null" fallback="Thanks to Tanzania Safaris and Trekking’s, we discovered hidden gems and enjoyed every moment. Professional, friendly, and reliable, they made unforgettable." />
                                    <div class="lower-box">
                                        <div class="rating-box"><span data-cms-field="testimonial_3_rating">{{ $c['testimonial_3_rating'] ?? '5.0 out of 5' }}</span><i class="flaticon-star"></i></div>
                                        <div class="clients-box">
                                            <figure class="clients-image" data-cms-field="testimonial_3_image"><img src="{{ $testimonial3Image }}" alt="" style="{{ \App\Support\CmsMedia::objectStyle($c['testimonial_3_image_position'] ?? null) }}"></figure>
                                            <div class="clients-info">
                                                <h4 data-cms-field="testimonial_3_name">{{ $c['testimonial_3_name'] ?? 'Jaffer Ahmedt' }}</h4>
                                                <span class="designation" data-cms-field="testimonial_3_location">{{ $c['testimonial_3_location'] ?? 'Dubai, UAE' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="three-item-pagination dots-style-one"></div>
                    </div>
                </div>
            </div>
        </section>
        <!-- testimonial-section end -->


        <!-- faq-section -->
        <section class="faq-section sec-pad" id="faq" data-cms-section="faq">
            <div class="auto-container">
                <div class="sec-title centred">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span data-cms-field="faq_subtitle">{{ $c['faq_subtitle'] ?? 'Faq’s' }}</span></div>
                    <h2 data-cms-field="faq_heading">{{ $c['faq_heading'] ?? 'Frequently Asked Questions' }}</h2>
                    <x-rich-text class="mt_12" data-cms-field="faq_body" :content="$c['faq_body'] ?? null" fallback="Travel planning has never been easier." />
                </div>
                <div class="row clearfix">
                    <div class="col-lg-8 col-md-12 accordion-column">
                        <ul class="accordion-box accordion-style-one">
                            @forelse (($faqs ?? collect()) as $index => $faq)
                                <li class="accordion block @if ($index === 0) active-block @endif">
                                    <div class="acc-btn @if ($index === 0) active @endif">
                                        <div class="icon-box"><i class="flaticon-plus"></i></div>
                                        <h4>{{ $faq->question }}</h4>
                                    </div>
                                    <div class="acc-content @if ($index === 0) current @endif">
                                        <div class="text">
                                            <p>{{ $faq->answer }}</p>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="accordion block active-block">
                                    <div class="acc-btn active">
                                        <div class="icon-box"><i class="flaticon-plus"></i></div>
                                        <h4>How do I book a tour?</h4>
                                    </div>
                                    <div class="acc-content current">
                                        <div class="text">
                                            <p>Browse our packages and send an enquiry from Book Now. Our team will confirm dates and a tailored quote.</p>
                                        </div>
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="col-lg-4 col-md-12 sidebar-column">
                        <div class="faq-sidebar text-center">
                            <div class="inner-box">
                                <div class="icon-box"><img src="/aventour/assets/images/icons/icon-17.png" alt=""></div>
                                <h3 data-cms-field="faq_sidebar_heading">{{ $c['faq_sidebar_heading'] ?? 'Let us guide you—reach out anytime.' }}</h3>
                                <a href="mailto:{{ $siteSettings['email'] ?? 'info@tanzaniasafarisandtrekking.com' }}">{{ $siteSettings['email'] ?? 'info@tanzaniasafarisandtrekking.com' }}</a>
                            </div>
                            <x-rich-text data-cms-field="faq_sidebar_body" :content="$c['faq_sidebar_body'] ?? null" fallback="Got more questions? Find answers on our FAQ page" />
                            <a href="{{ route('faq') }}" class="theme-btn"><span data-cms-field="faq_cta">{{ $c['faq_cta'] ?? 'Read All Faq’s' }}</span><i class="flaticon-arrow"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- faq-section end -->


        <!-- award-section -->
        <section class="award-section" data-cms-section="award">
            <div class="bg-layer" data-cms-field="award_bg" style="{{ \App\Support\CmsMedia::backgroundStyle($awardBg, $c['award_bg_position'] ?? null) }}"></div>
            <div class="auto-container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12 text-column">
                        <div class="text-box">
                            <h2 data-cms-field="award_heading">{{ $c['award_heading'] ?? 'Recognized for Excellence in Travel' }}</h2>
                            <x-rich-text data-cms-field="award_body" :content="$c['award_body'] ?? null" fallback="Celebrated by travelers and trusted worldwide." />
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 award-column">
                        <div class="award-content">
                            <ul class="award-list">
                                <li><img src="/aventour/assets/images/icons/award-1.png" alt=""></li>
                                <li><img src="/aventour/assets/images/icons/award-2.png" alt=""></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- award-section end -->


        @include('partials.aventour.reviews')


        <!-- main-footer -->
        <footer id="contact" class="main-footer bg-color-1">
            <div class="auto-container">
                <div class="widget-section">
                    <div class="row clearfix">
                        <div class="col-lg-3 col-md-6 col-sm-12 footer-column">
                            <div class="footer-widget logo-widget p_relative t_7">
                                @php
                                    $footerPhone = $siteSettings['phone'] ?? '+255 769 079 298';
                                    $footerWhatsappUrl = ($siteSettings['whatsapp_url'] ?? '') !== ''
                                        ? $siteSettings['whatsapp_url']
                                        : 'https://wa.me/'.preg_replace('/\D+/', '', $footerPhone);
                                @endphp
                                <figure class="footer-logo">@include('partials.aventour.brand-logo')</figure>
                                <x-rich-text :content="$siteSettings['footer_about'] ?? null" fallback="Expert-led safaris and Kilimanjaro treks across Tanzania." />
                                <ul class="download-list clearfix">
                                    <li>
                                        <a href="{{ $footerWhatsappUrl }}" target="_blank" rel="noopener">
                                            <img src="/aventour/assets/images/icons/icon-20.png" alt="">
                                            <span>WhatsApp Us</span>
                                            <i class="flaticon-arrow"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('book-now') }}">
                                            <img src="/aventour/assets/images/icons/icon-21.png" alt="">
                                            <span>Book Now</span>
                                            <i class="flaticon-arrow"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 footer-column">
                            <div class="footer-widget links-widget">
                                <div class="widget-title">
                                    <h3>Top Picks</h3>
                                </div>
                                <div class="widget-content">
                                    <ul class="links-list clearfix">
                                        <li><a href="{{ route('packages.safari') }}">Wildlife Tours</a></li>
                                        <li><a href="{{ route('packages.trekking') }}">Kilimanjaro Treks</a></li>
                                        <li><a href="{{ route('packages.day-trips') }}">Day Adventures</a></li>
                                        <li><a href="{{ route('destinations.show', 'serengeti') }}">Serengeti</a></li>
                                        <li><a href="{{ route('destinations.show', 'ngorongoro') }}">Ngorongoro</a></li>
                                        <li><a href="{{ route('destinations.show', 'tarangire') }}">Tarangire</a></li>
                                        <li><a href="{{ route('book-now') }}">Book Now</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 footer-column">
                            <div class="footer-widget links-widget">
                                <div class="widget-title">
                                    <h3>Useful Links</h3>
                                </div>
                                <div class="widget-content">
                                    <ul class="links-list clearfix">
                                        <li><a href="{{ route('home') }}">Home</a></li>
                                        <li><a href="{{ route('packages.index') }}">Tour Packages</a></li>
                                        <li><a href="{{ route('destinations.index') }}">Destinations</a></li>
                                        <li><a href="{{ route('blog') }}">Blog</a></li>
                                        <li><a href="{{ route('about') }}">About Us</a></li>
                                        <li><a href="{{ route('faq') }}">Faq’s</a></li>
                                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 footer-column">
                            <div class="footer-widget subscribe-widget">
                                <div class="widget-title">
                                    <h3>Newsletter</h3>
                                </div>
                                <div class="widget-content">
                                    <p>Subscribe for exclusive offers, expert tips, and curated travel inspiration.</p>
                                    <form action="{{ url('/') }}" method="post">
                                        <div class="form-group">
                                            <input type="email" name="email" placeholder="Email address" required>
                                            <button type="submit" class="theme-btn"><span>Subscribe Us</span><i class="flaticon-arrow"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-info pb_35">
                    <div class="row">
                        @php
                            $footerInfoPhone = $siteSettings['phone'] ?? '+255 769 079 298';
                            $footerInfoEmail = $siteSettings['email'] ?? 'info@tanzaniasafarisandtrekking.com';
                            $footerInfoAddress = $siteSettings['address'] ?? 'Arusha, Tanzania';
                            $footerInfoPhoneHref = 'tel:'.preg_replace('/\s+/', '', $footerInfoPhone);
                        @endphp
                        <div class="col-lg-4 col-md-6 col-sm-12 info-column">
                            <div class="single-info-box">
                                <div class="text-box">
                                    <span>Talk to Us</span>
                                    <p><a href="{{ $footerInfoPhoneHref }}">{{ $footerInfoPhone }}</a></p>
                                </div>
                                <div class="icon-box"><img src="/aventour/assets/images/icons/icon-20.png" alt=""></div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 info-column">
                            <div class="single-info-box">
                                <div class="text-box">
                                    <span>Message Us</span>
                                    <p><a href="mailto:{{ $footerInfoEmail }}">{{ $footerInfoEmail }}</a></p>
                                </div>
                                <div class="icon-box"><img src="/aventour/assets/images/icons/icon-21.png" alt=""></div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 info-column">
                            <div class="single-info-box">
                                <div class="text-box">
                                    <span>Locate Us</span>
                                    <p>{{ $footerInfoAddress }}</p>
                                </div>
                                <div class="icon-box"><img src="/aventour/assets/images/icons/icon-22.png" alt=""></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <div class="bottom-inner">
                        <div class="copyright"><p>&copy; <a href="{{ url('/') }}">{{ $siteSettings['company_name'] ?? 'Tanzania Safaris and Trekking' }}</a> {{ date('Y') }}, All rights reserved.</p></div>
                        <ul class="social-links">
                            <li><a href="{{ ($siteSettings['instagram_url'] ?? '') !== '' ? $siteSettings['instagram_url'] : url('/') }}" @if(($siteSettings['instagram_url'] ?? '') !== '') target="_blank" rel="noopener" @endif><i class="flaticon-instagram-logo"></i></a></li>
                            <li><a href="{{ url('/') }}"><i class="flaticon-youtube"></i></a></li>
                            <li><a href="{{ ($siteSettings['facebook_url'] ?? '') !== '' ? $siteSettings['facebook_url'] : url('/') }}" @if(($siteSettings['facebook_url'] ?? '') !== '') target="_blank" rel="noopener" @endif><i class="flaticon-facebook-app-symbol"></i></a></li>
                            <li><a href="{{ ($siteSettings['twitter_url'] ?? '') !== '' ? $siteSettings['twitter_url'] : url('/') }}" @if(($siteSettings['twitter_url'] ?? '') !== '') target="_blank" rel="noopener" @endif><i class="flaticon-twitter"></i></a></li>
                            <li><a href="{{ ($siteSettings['whatsapp_url'] ?? '') !== '' ? $siteSettings['whatsapp_url'] : url('/') }}" @if(($siteSettings['whatsapp_url'] ?? '') !== '') target="_blank" rel="noopener" @endif><i class="flaticon-whatsapp"></i></a></li>
                        </ul>
                        <div class="right-column">
                            <div class="language-box">
                                <span class="text">Curr:</span>
                                <div class="select-box">
                                    <select class="selectmenu">
                                        <option>USD</option>
                                        <option>AUD</option>
                                        <option>EUR</option>
                                        <option>XCD</option>
                                        <option>ARS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="language-box">
                                <span class="text">Lang:</span>
                                <div class="select-box">
                                    <select class="selectmenu">
                                        <option>En</option>
                                        <option>Ch</option>
                                        <option>Sp</option>
                                        <option>Tu</option>
                                        <option>Jp</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- main-footer end -->


        <!-- scroll to top -->
        <button class="scroll-top scroll-to-target" data-target="html">
            <i class="flaticon-arrow-1"></i>
        </button>
        
    </div>


    <!-- jequery plugins -->
    <script src="/aventour/assets/js/jquery.js"></script>
    <script src="/aventour/assets/js/popper.min.js"></script>
    <script src="/aventour/assets/js/bootstrap.min.js"></script>
    <script src="/aventour/assets/js/swiper-bundle.min.js"></script>
    <script src="/aventour/assets/js/wow.js"></script>
    <script src="/aventour/assets/js/validation.js"></script>
    <script src="/aventour/assets/js/jquery.fancybox.js"></script>
    <script src="/aventour/assets/js/appear.js"></script>
    <script src="/aventour/assets/js/odometer.js"></script>
    <script src="/aventour/assets/js/isotope.js"></script>
    <script src="/aventour/assets/js/jquery.nice-select.min.js"></script>
    <script src="/aventour/assets/js/jquery.lettering.min.js"></script>
    <script src="/aventour/assets/js/jquery.circleType.js"></script>
    <script src="/aventour/assets/js/jquery-ui.js"></script>

    <!-- main-js -->
    <script src="/aventour/assets/js/script.js?v={{ filemtime(public_path('aventour/assets/js/script.js')) }}"></script>
    <script src="/aventour/assets/js/ajax-loader.js?v={{ filemtime(public_path('aventour/assets/js/ajax-loader.js')) }}"></script>
    <script src="/aventour/assets/js/header-search.js?v={{ filemtime(public_path('aventour/assets/js/header-search.js')) }}"></script>

@include('partials.cms-preview')
@include('partials.whatsapp-button')
</body><!-- End of .page_wrapper -->
</html>
