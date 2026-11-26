<header class="main-header">
    <div class="large-container">
        <div class="header-top">
            <div class="bg-layer"></div>
            <div class="top-inner">
                @php
                    $phone = $siteSettings['phone'] ?? '+255 769 079 298';
                    $email = $siteSettings['email'] ?? 'info@tanzaniasafarisandtrekking.com';
                    $twitterUrl = ($siteSettings['twitter_url'] ?? '') !== '' ? $siteSettings['twitter_url'] : route('home');
                    $instagramUrl = ($siteSettings['instagram_url'] ?? '') !== '' ? $siteSettings['instagram_url'] : route('home');
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
        <div class="header-lower">
            <div class="outer-box pl_30 pr_30">
                <figure class="logo">@include('partials.aventour.brand-logo')</figure>
                <div class="menu-area clearfix">
                    <div class="mobile-nav-toggler">
                        <i class="icon-bar"></i>
                        <i class="icon-bar"></i>
                        <i class="icon-bar"></i>
                    </div>
                    <nav class="main-menu navbar-expand-md navbar-light">
                        <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                            @include('partials.aventour.nav')
                        </div>
                    </nav>
                </div>
                <div class="menu-right-content">
                    <div class="btn-box"><a href="{{ route('book-now') }}" class="theme-btn"><span>Book Now</span><i class="flaticon-arrow"></i></a></div>
                </div>
            </div>
        </div>
    </div>

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
                    <nav class="main-menu clearfix"></nav>
                </div>
                <div class="menu-right-content">
                    <div class="btn-box"><a href="{{ route('book-now') }}" class="theme-btn"><span>Book Now</span><i class="flaticon-arrow"></i></a></div>
                </div>
            </div>
        </div>
    </div>
</header>
