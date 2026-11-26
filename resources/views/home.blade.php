<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

<title>Tanzania Safaris and Trekking</title>

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
                        <p>
                            <a href="{{ $phoneHref }}" class="header-top-icon-link" aria-label="Call {{ $phone }}">
                                <img src="/aventour/assets/images/icons/icon-1.png" alt="">
                                <span class="header-top-text">{{ $phone }}</span>
                            </a>
                        </p>
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
                                        <li><a href="{{ route('packages.index', ['category' => 'Safari']) }}">Safari</a></li>
                                        <li><a href="{{ route('packages.index', ['category' => 'Trekking']) }}">Kilimanjaro Treks</a></li>
                                        <li><a href="{{ route('packages.index', ['category' => 'Day Trip']) }}">Day Trips</a></li>
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


        <!-- banner-section -->
        <section class="banner-section">
            <div class="banner-carousel">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="slide-item">
                            <div class="bg-layer" style="background-image: url('{{ $sectionImages['banner'][0] ?? ($packages[0]['image'] ?? '/aventour/assets/images/banner/banner-1.jpg') }}');"></div>
                            <div class="auto-container">
                                <div class="content-box">
                                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Karibu Tanzania!</span></div>
                                    <h2>Safari & Trek the wild heart of Tanzania</h2>
                                    <div class="inner-box">
                                        <div class="text-box">
                                            <p>From Big Five safaris to Kilimanjaro treks, we craft Tanzania adventures that fit your dreams and travel style.</p>
                                            <a href="{{ route('packages.index', ['category' => 'Safari']) }}" class="theme-btn"><span>See Packages</span><i class="flaticon-arrow"></i></a>
                                        </div>
                                        <div class="price-box centred">
                                            <div class="shape" style="background-image: url(/aventour/assets/images/shape/shape-2.svg);"></div>
                                            <h5>Adventures start from</h5>
                                            <span class="price">{{ $packageCount }}+</span>
                                            <span class="text">Packages</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="slide-item">
                            <div class="bg-layer" style="background-image: url('{{ $sectionImages['banner'][1] ?? ($packages[1]['image'] ?? '/aventour/assets/images/banner/banner-2.jpg') }}');"></div>
                            <div class="auto-container">
                                <div class="content-box">
                                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Wildlife Awaits</span></div>
                                    <h2>Discover Serengeti & Ngorongoro wonders</h2>
                                    <div class="inner-box">
                                        <div class="text-box">
                                            <p>Step into the Northern Circuit and explore hidden gems, wildlife drama, and unforgettable landscapes beyond ordinary tourism.</p>
                                            <a href="{{ route('packages.index', ['category' => 'Safari']) }}" class="theme-btn"><span>See Packages</span><i class="flaticon-arrow"></i></a>
                                        </div>
                                        <div class="price-box centred">
                                            <div class="shape" style="background-image: url(/aventour/assets/images/shape/shape-2.svg);"></div>
                                            <h5>Adventures start from</h5>
                                            <span class="price">Safari</span>
                                            <span class="text">Tours</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="slide-item">
                            <div class="bg-layer" style="background-image: url('{{ $sectionImages['banner'][2] ?? ($trekPackages[0]['image'] ?? ($packages[2]['image'] ?? '/aventour/assets/images/banner/banner-3.jpg')) }}');"></div>
                            <div class="auto-container">
                                <div class="content-box">
                                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Summit Calling</span></div>
                                    <h2>Climb Kilimanjaro, Africa’s highest peak</h2>
                                    <div class="inner-box">
                                        <div class="text-box">
                                            <p>Rise above the clouds on carefully guided Kilimanjaro routes with expert local mountain teams.</p>
                                            <a href="{{ route('packages.index', ['category' => 'Trekking']) }}" class="theme-btn"><span>See Packages</span><i class="flaticon-arrow"></i></a>
                                        </div>
                                        <div class="price-box centred">
                                            <div class="shape" style="background-image: url(/aventour/assets/images/shape/shape-2.svg);"></div>
                                            <h5>Adventures start from</h5>
                                            <span class="price">Trek</span>
                                            <span class="text">Routes</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="banner-navigation">
                    <button class="banner-prev-btn"><i class="flaticon-arrow-1"></i></button>
                    <button class="banner-next-btn"><i class="flaticon-arrow-1"></i></button>
                </div>
            </div>
        </section>
        <!-- banner-section end -->


        @include('partials.home-about')


        <!-- categories-section -->
        <section class="categories-section pt_110" id="categories">
            <div class="bg-layer" style="background-image: url('{{ $sectionImages['categories_bg'] ?? ($galleryImages[1] ?? '/aventour/assets/images/background/categories-bg.jpg') }}');"></div>
            <div class="shape float-bob-y" style="background-image: url(/aventour/assets/images/shape/shape-3.png);"></div>
            <div class="auto-container">
                <div class="sec-title light">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Travel Styles</span></div>
                    <h2>Choose Your Tanzania Style</h2>
                    <p class="mt_12">Safari, summit, culture — your Tanzania, your way.</p>
                    <div class="link-box light"><a href="{{ route('packages.index') }}"><span>View All Categories</span><i class="flaticon-arrow"></i></a></div>
                </div>
                @include('partials.home-categories')
            </div>
        </section>
        <!-- categories-section end -->


        <!-- destinations-section -->
        <section class="destinations-section pt_80 pb_110" id="destinations">
            <div class="shape-box">
                <div class="shape-1" style="background-image: url(/aventour/assets/images/shape/shape-4.png);"></div>
                <div class="shape-2 float-bob-x" style="background-image: url(/aventour/assets/images/shape/shape-5.png);"></div>
            </div>
            <div class="outer-container pl_75 pr_75">
                <div class="sec-title centred">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Popular Destinations</span></div>
                    <h2>Iconic Places in Tanzania</h2>
                    <p class="mt_12">Explore our most loved safari and trekking experiences.</p>
                </div>
                @include('partials.home-destinations')
            </div>
        </section>
        <!-- destinations-section end -->


        <!-- journeys-section -->
        <section class="journeys-section sec-pad bg-color-1" id="journeys">
            <div class="pattern-layer float-bob-x" style="background-image: url(/aventour/assets/images/shape/shape-8.png);"></div>
            <div class="auto-container">
                <div class="sec-title">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Behind the Journey</span></div>
                    <h2>Personal Safaris, Memorable <br />Experiences</h2>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                        <div class="image-box">
                            <div class="image-shape float-bob-y" style="background-image: url(/aventour/assets/images/shape/shape-6.png);"></div>
                            <figure class="image image-1"><img src="{{ $sectionImages['journeys'][0] ?? ($packages[0]['image'] ?? '/aventour/assets/images/resource/journeys-1.jpg') }}" alt=""></figure>
                            <figure class="image image-2"><img src="{{ $sectionImages['journeys'][1] ?? ($packages[1]['image'] ?? '/aventour/assets/images/resource/journeys-2.jpg') }}" alt=""></figure>
                            <figure class="image image-3"><img src="{{ $sectionImages['journeys'][2] ?? ($trekPackages[0]['image'] ?? ($packages[2]['image'] ?? '/aventour/assets/images/resource/journeys-3.jpg')) }}" alt=""></figure>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                        <div class="content-box">
                            <div class="text-box">
                                <p>Travel is more than seeing places — it's about making memories that last. Whether you dream of wildlife, culture, or Kilimanjaro, our local experts plan your perfect Tanzania trip.</p>
                            </div>
                            <div class="single-item">
                                <div class="title-box p_relative clearfix">
                                    <div class="icon-shape" style="background-image: url(/aventour/assets/images/shape/shape-7.png);"></div>
                                    <h3>Made for Small Groups</h3>
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-6.png" alt=""></div>
                                </div>
                                <div class="inner-box">
                                    <div class="bg-layer" style="background-image: url('{{ $sectionImages['journeys'][3] ?? ($packages[3]['image'] ?? '/aventour/assets/images/resource/journeys-4.jpg') }}');"></div>
                                    <p>Explore freely with personalized routes and private service.</p>
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
                                    <h3>Private Travel Made Easy</h3>
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-7.png" alt=""></div>
                                </div>
                                <div class="inner-box">
                                    <div class="bg-layer" style="background-image: url('{{ $sectionImages['journeys'][4] ?? ($packages[4]['image'] ?? '/aventour/assets/images/resource/journeys-5.jpg') }}');"></div>
                                    <p>Tailored trips that fit your pace, style, and comfort.</p>
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
        <section class="package-section" id="safari">
            <div class="auto-container">
                <div class="sec-title centred">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Safari Packages</span></div>
                    <h2>Wildlife Safari Adventures</h2>
                    <p class="mt_12">Explore Tarangire, Serengeti, Ngorongoro, and more.</p>
                </div>
                @include('partials.home-packages', ['items' => $safariPackages])
                <div class="lower-text centred">
                    <p>Discover more Tanzania safari journeys.</p>
                    <div class="link-box"><a href="{{ route('packages.index', ['category' => 'Safari']) }}"><span>View Safari Packages</span><i class="flaticon-arrow"></i></a></div>
                </div>
            </div>
        </section>

        <section class="package-section pt_90" id="kilimanjaro">
            <div class="auto-container">
                <div class="sec-title centred">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Kilimanjaro Treks</span></div>
                    <h2>Climb Mount Kilimanjaro</h2>
                    <p class="mt_12">Marangu, Machame, Lemosho, Rongai, Umbwe, and Northern Circuit.</p>
                </div>
                @include('partials.home-packages', ['items' => $trekPackages])
                <div class="lower-text centred">
                    <p>Discover more Kilimanjaro climbing routes.</p>
                    <div class="link-box"><a href="{{ route('packages.index', ['category' => 'Trekking']) }}"><span>View Trek Packages</span><i class="flaticon-arrow"></i></a></div>
                </div>
            </div>
        </section>
        <!-- package-section end --><!-- package-section end -->


        <!-- chooseus-section -->
        <section class="chooseus-section sec-pad-2 bg-color-1">
            <div class="bg-image">
                <div class="bg-shape-1" style="background-image: url(/aventour/assets/images/shape/shape-9.png);"></div>
                <div class="bg-shape-2 float-bob-x" style="background-image: url(/aventour/assets/images/shape/shape-10.png);"></div>
                <div class="bg-image-1" style="background-image: url('{{ $sectionImages['chooseus_bg'] ?? ($galleryImages[0] ?? '/aventour/assets/images/background/chooseus-bg.png') }}');"></div>
            </div>
            <div class="auto-container">
                <div class="sec-title">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Why Tanzania Safaris and Trekking</span></div>
                    <h2>Your Trusted Travel Partner</h2>
                    <p class="mt_12">Guiding you to unforgettable experiences across the world’s wonders.</p>
                </div>
                <div class="row clearfix">
                    <div class="col-xl-9 col-lg-12 big-column">
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-6 col-sm-12 chooseus-block">
                                <div class="chooseus-block-one">
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-9.png" alt=""></div>
                                    <h3><a href="{{ url('/') }}">Curated Tours</a></h3>
                                    <p>Only the best destinations and experiences, selected just for you.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 chooseus-block">
                                <div class="chooseus-block-one">
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-10.png" alt=""></div>
                                    <h3><a href="{{ url('/') }}">Guides Near You</a></h3>
                                    <p>Get the best value with fair, transparent pricing and no hidden costs.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 chooseus-block">
                                <div class="chooseus-block-one">
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-11.png" alt=""></div>
                                    <h3><a href="{{ url('/') }}">Safety Knowledge</a></h3>
                                    <p>Safe and simple payments for a stress-free booking experience.</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 chooseus-block">
                                <div class="chooseus-block-two">
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-12.png" alt=""></div>
                                    <div class="inner-box">
                                        <h2>15+</h2>
                                        <h3><a href="{{ url('/') }}">Years Experience</a></h3>
                                        <p>Trusted travel expertise since 2010.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 chooseus-block">
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
            </div>
        </section>
        <!-- chooseus-section end -->


        <!-- news-section -->
        <section class="news-section" id="blog">
            <div class="auto-container">
                <div class="sec-title centred">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Blog Post</span></div>
                    <h2>Latest From the Blog</h2>
                    <p class="mt_12">Safari tips, trek advice, and Northern Circuit stories.</p>
                </div>
                @include('partials.home-blog', ['items' => $posts])
                <div class="lower-text centred">
                    <p>Continue your adventure through our blog.</p>
                    <div class="link-box"><a href="{{ route('blog') }}"><span>All Blog Posts</span><i class="flaticon-arrow"></i></a></div>
                </div>
            </div>
        </section>
        <!-- news-section end -->


        <!-- testimonial-section -->
        <section class="testimonial-section bg-color-1">
            <div class="pattern-layer" style="background-image: url(/aventour/assets/images/shape/shape-11.png);"></div>
            <figure class="bg-image p_absolute l_50 b_0"><img src="{{ $sectionImages['testimonial_bg'] ?? ($galleryImages[2] ?? '/aventour/assets/images/resource/testimonial-4.png') }}" alt=""></figure>
            <div class="auto-container">
                <div class="sec-title">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Testimonials</span></div>
                    <h2>Stories From Travelers</h2>
                    <div class="link-box p_absolute r_0 b_3"><a href="{{ url('/') }}"><span>All Testimonials</span><i class="flaticon-arrow"></i></a></div>
                </div>
                <div class="testimonial-content">
                    <div class="three-item-carousel">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="testimonial-block-one">
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-16.png" alt=""></div>
                                    <p>This was hands down the best trip of my life! Everything was perfectly organized — from airport pickup to local guides. I felt safe, excited, and totally free.</p>
                                    <div class="lower-box">
                                        <div class="rating-box"><span>4.6 out of 5</span><i class="flaticon-star"></i></div>
                                        <div class="clients-box">
                                            <figure class="clients-image"><img src="{{ $sectionImages['testimonials'][0] ?? ($galleryImages[0] ?? '/aventour/assets/images/resource/testimonial-1.png') }}" alt=""></figure>
                                            <div class="clients-info">
                                                <h4>Lucas Benjamin</h4>
                                                <span class="designation">Pasadena, CA</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-block-one">
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-16.png" alt=""></div>
                                    <p>This was hands down the best trip of my life! Everything was perfectly organized — from airport pickup to local guides. I felt safe, excited, and totally free.</p>
                                    <div class="lower-box">
                                        <div class="rating-box"><span>4.9 out of 5</span><i class="flaticon-star"></i></div>
                                        <div class="clients-box">
                                            <figure class="clients-image"><img src="{{ $sectionImages['testimonials'][1] ?? ($galleryImages[1] ?? '/aventour/assets/images/resource/testimonial-2.png') }}" alt=""></figure>
                                            <div class="clients-info">
                                                <h4>Jacob Lawrence</h4>
                                                <span class="designation">Melbourne, AU</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-block-one">
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-16.png" alt=""></div>
                                    <p>Thanks to Tanzania Safaris and Trekking’s, we discovered hidden gems and enjoyed every moment. Professional, friendly, and reliable, they made unforgettable.</p>
                                    <div class="lower-box">
                                        <div class="rating-box"><span>5.0 out of 5</span><i class="flaticon-star"></i></div>
                                        <div class="clients-box">
                                            <figure class="clients-image"><img src="{{ $sectionImages['testimonials'][2] ?? ($galleryImages[2] ?? '/aventour/assets/images/resource/testimonial-3.png') }}" alt=""></figure>
                                            <div class="clients-info">
                                                <h4>Jaffer Ahmedt</h4>
                                                <span class="designation">Dubai, UAE</span>
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
        <section class="faq-section sec-pad" id="faq">
            <div class="auto-container">
                <div class="sec-title centred">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Faq’s</span></div>
                    <h2>Frequently Asked Questions</h2>
                    <p class="mt_12">Travel planning has never been easier.</p>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-8 col-md-12 accordion-column">
                        <ul class="accordion-box accordion-style-one">
                            <li class="accordion block active-block">
                                <div class="acc-btn active">
                                    <div class="icon-box"><i class="flaticon-plus"></i></div>
                                    <h4>How do I book a tour?</h4>
                                </div>
                                <div class="acc-content current">
                                    <div class="text">
                                        <p>You can book directly through our website by selecting your preferred tour and dates, then following the secure checkout process.</p>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"><i class="flaticon-plus"></i></div>
                                    <h4>Can I customize my itinerary?</h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <p>You can book directly through our website by selecting your preferred tour and dates, then following the secure checkout process.</p>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"><i class="flaticon-plus"></i></div>
                                    <h4>Are flights included?</h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <p>You can book directly through our website by selecting your preferred tour and dates, then following the secure checkout process.</p>
                                    </div>
                                </div>
                            </li>
                            <li class="accordion block">
                                <div class="acc-btn">
                                    <div class="icon-box"><i class="flaticon-plus"></i></div>
                                    <h4>How many people are in a group tour?</h4>
                                </div>
                                <div class="acc-content">
                                    <div class="text">
                                        <p>You can book directly through our website by selecting your preferred tour and dates, then following the secure checkout process.</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-4 col-md-12 sidebar-column">
                        <div class="faq-sidebar text-center">
                            <div class="inner-box">
                                <div class="icon-box"><img src="/aventour/assets/images/icons/icon-17.png" alt=""></div>
                                <h3>Let us guide you—reach out anytime.</h3>
                                <a href="mailto:info@tanzaniasafarisandtrekking.com">info@tanzaniasafarisandtrekking.com</a>
                            </div>
                            <p>Got more questions? Find answers <br />on our FAQ page</p>
                            <a href="{{ route('faq') }}" class="theme-btn"><span>Read All Faq’s</span><i class="flaticon-arrow"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- faq-section end -->


        <!-- award-section -->
        <section class="award-section">
            <div class="bg-layer" style="background-image: url('{{ $sectionImages['award_bg'] ?? ($trekPackages[1]['image'] ?? ($packages[8]['image'] ?? '/aventour/assets/images/background/award-bg.jpg')) }}');"></div>
            <div class="auto-container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12 text-column">
                        <div class="text-box">
                            <h2>Recognized for Excellence in Travel</h2>
                            <p>Celebrated by travelers and trusted worldwide.</p>
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
                                <p>{{ $siteSettings['footer_about'] ?? 'Expert-led safaris and Kilimanjaro treks across Tanzania.' }}</p>
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
                                        <li><a href="{{ route('packages.index', ['category' => 'Safari']) }}">Wildlife Tours</a></li>
                                        <li><a href="{{ route('packages.index', ['category' => 'Trekking']) }}">Kilimanjaro Treks</a></li>
                                        <li><a href="{{ route('packages.index', ['category' => 'Day Trip']) }}">Day Adventures</a></li>
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
    <script src="/aventour/assets/js/script.js"></script>
    <script src="/aventour/assets/js/ajax-loader.js?v={{ filemtime(public_path('aventour/assets/js/ajax-loader.js')) }}"></script>

</body><!-- End of .page_wrapper -->
</html>
