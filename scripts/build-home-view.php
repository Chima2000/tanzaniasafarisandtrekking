<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$src = $root.'/public/aventourhtml-10/aventourhtml-10/Aventour Pack/Aventour/index.html';
$dest = $root.'/resources/views/home.blade.php';

$html = file_get_contents($src);

if ($html === false) {
    fwrite(STDERR, "Unable to read index.html\n");
    exit(1);
}

$home = "{{ url('/') }}";

$html = str_replace(
    ['href="assets/', 'src="assets/', 'url(assets/'],
    ['href="/aventour/assets/', 'src="/aventour/assets/', 'url(/aventour/assets/'],
    $html
);

$linkMap = [
    'index.html' => "{{ route('home') }}",
    'index-2.html' => "{{ route('home') }}",
    'index-3.html' => "{{ route('home') }}",
    'index-4.html' => "{{ route('home') }}",
    'tour.html' => "{{ route('packages.index') }}",
    'tour-2.html' => "{{ route('packages.index', ['category' => 'Trekking']) }}",
    'tour-details.html' => "{{ route('packages.index') }}",
    'destination-1.html' => "{{ route('destinations.index') }}",
    'destination-2.html' => "{{ route('packages.index') }}",
    'destination-3.html' => "{{ route('packages.index') }}",
    'destination-4.html' => "{{ route('packages.index') }}",
    'about.html' => "{{ route('about') }}",
    'team.html' => "{{ route('about') }}",
    'gallery.html' => "{{ route('destinations.index') }}",
    'gallery-2.html' => "{{ route('destinations.index') }}",
    'faq.html' => "{{ route('faq') }}",
    'comingsoon.html' => "{{ route('home') }}",
    'error.html' => "{{ route('home') }}",
    'blog.html' => $home.'#blog',
    'blog-2.html' => $home.'#blog',
    'blog-3.html' => $home.'#blog',
    'blog-details.html' => $home.'#blog',
    'contact.html' => "{{ route('contact') }}",
];

foreach ($linkMap as $from => $to) {
    $html = str_replace('href="'.$from.'"', 'href="'.$to.'"', $html);
    $html = str_replace('action="'.$from.'"', 'action="'.$home.'"', $html);
}

$html = str_replace(
    [
        '<title>Aventour - HTML 5 Template Preview</title>',
        "Aventour's",
        'Aventour',
        'Hey Explorer!',
        'Enjoy the <br />best adventure <br />in the nature',
        'Escape the Ordinary',
        'Let’s go where <br />the map dares <br />not go',
        'The Island Life',
        'Find your island bliss, far from the ordinary',
        'From tropical escapes to cultural tours, we expertly design travel experiences that fit your dreams and budget.',
        'Step off the beaten path and explore hidden gems, untamed beauty, and unforgettable places beyond the typical tourist trail.',
        'Unwind on sun-kissed shores, embrace serenity, and experience a tropical escape unlike anything you\'ve known.',
        '<span>See Adventures</span>',
        'Adventure starts at',
        'Find the Right Tour for You',
        'Destination',
        '<option>Adventure Lite</option>',
        '<option>Island Vibes</option>',
        '<option>City Breaks</option>',
        '<option>Wildlife Trails</option>',
        ' Handpicked Tour Packages',
        'Experience travel the Tanzania Safaris and Trekking way.',
        'Experience travel the Aventour way.',
        'Tour Categories',
        'Choose Your Travel Style',
        'Adventure, luxury, culture — your choice, your way.',
        'Recommended Countries',
        'Favorites for Every Explorer',
        'Plan your journey to our most sought-after spots.',
        'Personalized Journeys',
        'Personal Travel, Memorable <br />Experiences',
        'Travel is more than seeing places — it\'s about making memories that last. Whether you\'re dreaming of beaches, culture, wildlife, or romance, our experts are here to plan your perfect trip.',
        'Chicago 12, Melborne City, USA',
        '+88 01682648101',
        'tel:+8801682648101',
        'info@example.com',
        'mailus@example.com',
        '+800 45 6789 01',
        'tel:80045678901',
        'Ready to Plan?',
        'Must-See Moments',
        'We Accpet',
    ],
    [
        '<title>Tanzania Safaris and Trekking</title>',
        'Tanzania Safaris and Trekking',
        'Tanzania Safaris and Trekking',
        'Karibu Tanzania!',
        'Safari & Trek <br />the wild heart <br />of Tanzania',
        'Wildlife Awaits',
        'Discover Serengeti <br />& Ngorongoro <br />wonders',
        'Summit Calling',
        'Climb Kilimanjaro, Africa’s highest peak',
        'From Big Five safaris to Kilimanjaro treks, we craft Tanzania adventures that fit your dreams and travel style.',
        'Step into the Northern Circuit and explore hidden gems, wildlife drama, and unforgettable landscapes beyond ordinary tourism.',
        'Rise above the clouds on carefully guided Kilimanjaro routes with expert local mountain teams.',
        '<span>See Packages</span>',
        'Adventures start from',
        'Find the Right Safari or Trek',
        'Serengeti, Kilimanjaro...',
        '<option>Wildlife Safaris</option>',
        '<option>Kilimanjaro Treks</option>',
        '<option>Day Adventures</option>',
        '<option>Cultural Tours</option>',
        ' Safari & Trek Packages',
        'Experience travel the Tanzania way.',
        'Experience travel the Tanzania way.',
        'Travel Styles',
        'Choose Your Tanzania Style',
        'Safari, summit, culture — your Tanzania, your way.',
        'Popular Destinations',
        'Iconic Places in Tanzania',
        'Explore our most loved safari and trekking experiences.',
        'Behind the Journey',
        'Personal Safaris, Memorable <br />Experiences',
        'Travel is more than seeing places — it\'s about making memories that last. Whether you dream of wildlife, culture, or Kilimanjaro, our local experts plan your perfect Tanzania trip.',
        'Arusha & Moshi, Tanzania',
        '+255 769 079 298',
        'tel:+255769079298',
        'info@tanzaniasafarisandtrekking.com',
        'info@tanzaniasafarisandtrekking.com',
        '+255 769 079 298',
        'tel:+255769079298',
        'Ready to Safari?',
        'Must-See Moments',
        'We Accept',
    ],
    $html
);

$logoStyle = <<<'CSS'

<style>
    .brand-text-logo {
        display: inline-flex;
        flex-direction: column;
        justify-content: center;
        line-height: 1.1;
        font-family: "Merienda", cursive;
        color: inherit;
        white-space: nowrap;
    }
    .brand-text-logo strong {
        font-size: 20px;
        font-weight: 700;
    }
    .brand-text-logo span {
        font-size: 12px;
        font-family: "Mona Sans", sans-serif;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        opacity: 0.8;
    }

    /* Keep photos inside template frames using original Aventour aspect ratios */
    .package-block-one .image-box,
    .package-block-one .image-box .image,
    .categories-block-one .image-box,
    .destinations-block-one .image-box .image,
    .news-block-one .image-box .image,
    .journeys-section .image-box .image {
        overflow: hidden;
    }
    .package-block-one .image-box .image img {
        width: 100%;
        height: auto;
        aspect-ratio: 350 / 290;
        object-fit: cover;
        object-position: center;
        display: block;
    }
    .categories-block-one .image-box img {
        width: 100%;
        height: auto;
        aspect-ratio: 270 / 298;
        object-fit: cover;
        object-position: center;
        display: block;
    }
    .destinations-block-one .image-box .image img {
        width: 100%;
        height: auto;
        aspect-ratio: 270 / 270;
        object-fit: cover;
        object-position: center;
        display: block;
    }
    .news-block-one .image-box .image img {
        width: 100%;
        height: auto;
        aspect-ratio: 370 / 300;
        object-fit: cover;
        object-position: center;
        display: block;
    }
    .journeys-section .image-box .image-1 img {
        width: 100%;
        height: auto;
        aspect-ratio: 360 / 380;
        object-fit: cover;
        object-position: center;
        display: block;
    }
    .journeys-section .image-box .image-2 img {
        width: 100%;
        height: auto;
        aspect-ratio: 278 / 364;
        object-fit: cover;
        object-position: center;
        display: block;
    }
    .journeys-section .image-box .image-3 img {
        width: 100%;
        height: auto;
        aspect-ratio: 338 / 244;
        object-fit: cover;
        object-position: center;
        display: block;
    }
    .booking-section .customer-column .image img,
    .booking-section .inner-box > .image img {
        width: 100%;
        max-width: 270px;
        height: auto;
        aspect-ratio: 270 / 320;
        object-fit: cover;
        object-position: center;
        border-radius: 20px;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
    .booking-section .customer-box .image-list img,
    .testimonial-block-one .clients-image img {
        width: 48px;
        height: 48px;
        object-fit: cover;
        object-position: center;
        border-radius: 50%;
    }
    .testimonial-block-one .clients-image img {
        width: 60px;
        height: 60px;
    }
    .testimonial-section > .bg-image img {
        max-width: 420px;
        width: 100%;
        height: auto;
        aspect-ratio: 420 / 480;
        object-fit: cover;
        object-position: center;
        border-radius: 20px;
    }
    .footer-widget .post .image-box {
        width: 90px;
        height: 90px;
        overflow: hidden;
        border-radius: 10px;
        flex-shrink: 0;
    }
    .footer-widget .post .image-box img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        object-position: center;
        display: block;
    }
    .banner-section .bg-layer,
    .chooseus-section .bg-image .bg-image-1,
    .award-section .bg-layer,
    .categories-section .bg-layer,
    .journeys-section .single-item .inner-box .bg-layer {
        background-size: cover !important;
        background-position: center center !important;
        background-repeat: no-repeat !important;
    }
</style>
CSS;

$html = str_replace('</head>', $logoStyle."\n</head>", $html);

$brandLogo = <<<'HTML'
<a href="{{ url('/') }}" class="brand-text-logo" aria-label="Tanzania Safaris and Trekking">
                                    <strong>Tanzania Safaris</strong>
                                    <span>&amp; Trekking</span>
                                </a>
HTML;

$html = preg_replace(
    '/<figure class="logo(?:-box)?"><a href="\{\{ url\(\'\/\'\) \}\}"><img src="\/aventour\/assets\/images\/logo[^"]*" alt=""><\/a><\/figure>/',
    '<figure class="logo">'.$brandLogo.'</figure>',
    $html
);

$html = preg_replace(
    '/<div class="nav-logo"><a href="\{\{ url\(\'\/\'\) \}\}"><img src="\/aventour\/assets\/images\/logo[^"]*" alt="" title=""><\/a><\/div>/',
    '<div class="nav-logo">'.$brandLogo.'</div>',
    $html
);

$html = preg_replace(
    '/<figure class="footer-logo"><a href="\{\{ url\(\'\/\'\) \}\}"><img src="\/aventour\/assets\/images\/logo[^"]*" alt=""><\/a><\/figure>/',
    '<figure class="footer-logo">'.$brandLogo.'</figure>',
    $html
);

$nav = <<<'HTML'
                                    <ul class="navigation clearfix">
                                        <li><a href="{{ route('packages.index', ['category' => 'Safari']) }}">Safari</a></li>
                                        <li><a href="{{ route('packages.index', ['category' => 'Trekking']) }}">Kilimanjaro Treks</a></li>
                                        <li><a href="{{ route('packages.index', ['category' => 'Day Trip']) }}">Day Trips</a></li>
                                        <li><a href="{{ route('destinations.index') }}">Destinations</a></li>
                                        <li><a href="{{ route('about') }}">About Us</a></li>
                                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                                    </ul>
HTML;

$html = preg_replace(
    '/<ul class="navigation clearfix">.*?<\/ul>\s*<\/div>\s*<\/nav>/s',
    $nav."\n                                </div>\n                            </nav>",
    $html,
    1
);

$html = str_replace(
    [
        '<section class="categories-section pt_110">',
        '<section class="destinations-section pt_80 pb_110">',
        '<section class="journeys-section sec-pad bg-color-1">',
        '<section class="package-section">',
        '<section class="news-section">',
        '<section class="faq-section sec-pad">',
        '<footer ',
    ],
    [
        '<section class="categories-section pt_110" id="categories">',
        '<section class="destinations-section pt_80 pb_110" id="destinations">',
        '<section class="journeys-section sec-pad bg-color-1" id="about">',
        '<section class="package-section" id="packages">',
        '<section class="news-section" id="blog">',
        '<section class="faq-section sec-pad" id="faq">',
        '<footer id="contact" ',
    ],
    $html
);

function replaceBetween(string $html, string $startMarker, string $endMarker, string $replacement): string
{
    $start = strpos($html, $startMarker);
    $end = strpos($html, $endMarker, $start === false ? 0 : $start);

    if ($start === false || $end === false) {
        fwrite(STDERR, "Marker not found: {$startMarker}\n");

        return $html;
    }

    return substr($html, 0, $start).$replacement.substr($html, $end);
}

// Banner backgrounds from package photos
$html = replaceBetween(
    $html,
    '<div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="slide-item">
                            <div class="bg-layer" style="background-image: url(/aventour/assets/images/banner/banner-1.jpg);">',
    '<div class="banner-navigation">',
    <<<'BLADE'
<div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="slide-item">
                            <div class="bg-layer" style="background-image: url('{{ $packages[0]['image'] ?? '/aventour/assets/images/banner/banner-1.jpg' }}');"></div>
                            <div class="auto-container">
                                <div class="content-box">
                                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Karibu Tanzania!</span></div>
                                    <h2>Safari & Trek <br />the wild heart <br />of Tanzania</h2>
                                    <div class="inner-box">
                                        <div class="text-box">
                                            <p>From Big Five safaris to Kilimanjaro treks, we craft Tanzania adventures that fit your dreams and travel style.</p>
                                            <a href="{{ url('/') }}#safari" class="theme-btn"><span>See Packages</span><i class="flaticon-arrow"></i></a>
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
                            <div class="bg-layer" style="background-image: url('{{ $packages[1]['image'] ?? '/aventour/assets/images/banner/banner-2.jpg' }}');"></div>
                            <div class="auto-container">
                                <div class="content-box">
                                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Wildlife Awaits</span></div>
                                    <h2>Discover Serengeti <br />& Ngorongoro <br />wonders</h2>
                                    <div class="inner-box">
                                        <div class="text-box">
                                            <p>Step into the Northern Circuit and explore hidden gems, wildlife drama, and unforgettable landscapes beyond ordinary tourism.</p>
                                            <a href="{{ url('/') }}#safari" class="theme-btn"><span>See Packages</span><i class="flaticon-arrow"></i></a>
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
                            <div class="bg-layer" style="background-image: url('{{ $trekPackages[0]['image'] ?? ($packages[2]['image'] ?? '/aventour/assets/images/banner/banner-3.jpg') }}');"></div>
                            <div class="auto-container">
                                <div class="content-box">
                                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Summit Calling</span></div>
                                    <h2>Climb Kilimanjaro, Africa’s highest peak</h2>
                                    <div class="inner-box">
                                        <div class="text-box">
                                            <p>Rise above the clouds on carefully guided Kilimanjaro routes with expert local mountain teams.</p>
                                            <a href="{{ url('/') }}#kilimanjaro" class="theme-btn"><span>See Packages</span><i class="flaticon-arrow"></i></a>
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
                
BLADE
);

// Categories
$html = replaceBetween(
    $html,
    '<div class="row clearfix">
                    <div class="col-lg-3 col-md-6 col-sm-12 categories-block">',
    '</div>
            </div>
        </section>
        <!-- categories-section end -->',
    "                @include('partials.home-categories')
            </div>
        </section>
        <!-- categories-section end -->"
);

// Destinations carousel
$html = replaceBetween(
    $html,
    '<div class="six-item-carousel">',
    '</div>
            </div>
        </section>
        <!-- destinations-section end -->',
    "                @include('partials.home-destinations')
            </div>
        </section>
        <!-- destinations-section end -->"
);

// Journeys images
$html = str_replace(
    [
        'src="/aventour/assets/images/resource/journeys-1.jpg"',
        'src="/aventour/assets/images/resource/journeys-2.jpg"',
        'src="/aventour/assets/images/resource/journeys-3.jpg"',
        'url(/aventour/assets/images/resource/journeys-4.jpg)',
        'url(/aventour/assets/images/resource/journeys-5.jpg)',
    ],
    [
        'src="{{ $packages[0][\'image\'] ?? \'/aventour/assets/images/resource/journeys-1.jpg\' }}"',
        'src="{{ $packages[1][\'image\'] ?? \'/aventour/assets/images/resource/journeys-2.jpg\' }}"',
        'src="{{ $trekPackages[0][\'image\'] ?? ($packages[2][\'image\'] ?? \'/aventour/assets/images/resource/journeys-3.jpg\') }}"',
        'url(\'{{ $packages[3][\'image\'] ?? \'/aventour/assets/images/resource/journeys-4.jpg\' }}\')',
        'url(\'{{ $packages[4][\'image\'] ?? \'/aventour/assets/images/resource/journeys-5.jpg\' }}\')',
    ],
    $html
);

// Package section -> safari + kilimanjaro
$html = replaceBetween(
    $html,
    '<section class="package-section" id="packages">',
    '<!-- package-section end -->',
    <<<'BLADE'
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
                    <div class="link-box"><a href="{{ url('/') }}#safari"><span>View Safari Packages</span><i class="flaticon-arrow"></i></a></div>
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
                    <div class="link-box"><a href="{{ url('/') }}#kilimanjaro"><span>View Trek Packages</span><i class="flaticon-arrow"></i></a></div>
                </div>
            </div>
        </section>
        <!-- package-section end -->
BLADE
);

// News section -> real published blog posts
$html = replaceBetween(
    $html,
    '<section class="news-section" id="blog">',
    '<!-- news-section end -->',
    <<<'BLADE'
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
BLADE
);

// Booking counts
$html = str_replace(
    '<span class="special-text">80+</span>',
    '<span class="special-text">{{ $packageCount }}+</span>',
    $html
);

// News images
$html = preg_replace_callback(
    '/src="\/aventour\/assets\/images\/news\/news-(\d+)\.jpg"/',
    function (array $matches): string {
        $index = ((int) $matches[1]) % 6;

        return 'src="{{ $galleryImages['.$index.'] ?? \'/aventour/assets/images/news/news-'.$matches[1].'.jpg\' }}"';
    },
    $html
);

$html = str_replace(
    [
        'src="/aventour/assets/images/resource/post-1.jpg"',
        'src="/aventour/assets/images/resource/post-2.jpg"',
        'src="/aventour/assets/images/resource/travel-1.png"',
        'src="/aventour/assets/images/resource/customer-1.png"',
        'src="/aventour/assets/images/resource/customer-2.png"',
        'src="/aventour/assets/images/resource/customer-3.png"',
        'src="/aventour/assets/images/resource/rotate-1.png"',
        'src="/aventour/assets/images/resource/testimonial-1.png"',
        'src="/aventour/assets/images/resource/testimonial-2.png"',
        'src="/aventour/assets/images/resource/testimonial-3.png"',
        'src="/aventour/assets/images/resource/testimonial-4.png"',
        'url(/aventour/assets/images/background/categories-bg.jpg)',
        'url(/aventour/assets/images/background/chooseus-bg.png)',
        'url(/aventour/assets/images/background/award-bg.jpg)',
    ],
    [
        'src="{{ $galleryImages[0] ?? \'/aventour/assets/images/resource/post-1.jpg\' }}"',
        'src="{{ $galleryImages[1] ?? \'/aventour/assets/images/resource/post-2.jpg\' }}"',
        'src="{{ $packages[5][\'image\'] ?? \'/aventour/assets/images/resource/travel-1.png\' }}"',
        'src="{{ $galleryImages[0] ?? \'/aventour/assets/images/resource/customer-1.png\' }}"',
        'src="{{ $galleryImages[1] ?? \'/aventour/assets/images/resource/customer-2.png\' }}"',
        'src="{{ $galleryImages[2] ?? \'/aventour/assets/images/resource/customer-3.png\' }}"',
        'src="{{ $packages[10][\'image\'] ?? ($galleryImages[3] ?? \'/aventour/assets/images/resource/rotate-1.png\') }}"',
        'src="{{ $galleryImages[0] ?? \'/aventour/assets/images/resource/testimonial-1.png\' }}"',
        'src="{{ $galleryImages[1] ?? \'/aventour/assets/images/resource/testimonial-2.png\' }}"',
        'src="{{ $galleryImages[2] ?? \'/aventour/assets/images/resource/testimonial-3.png\' }}"',
        'src="{{ $packages[9][\'image\'] ?? ($galleryImages[2] ?? \'/aventour/assets/images/resource/testimonial-4.png\') }}"',
        'url(\'{{ $packages[7][\'image\'] ?? ($galleryImages[1] ?? \'/aventour/assets/images/background/categories-bg.jpg\') }}\')',
        'url(\'{{ $packages[6][\'image\'] ?? ($galleryImages[0] ?? \'/aventour/assets/images/background/chooseus-bg.png\') }}\')',
        'url(\'{{ $trekPackages[1][\'image\'] ?? ($packages[8][\'image\'] ?? \'/aventour/assets/images/background/award-bg.jpg\') }}\')',
    ],
    $html
);

// News lightbox hrefs should open the same photo shown in the card
$html = preg_replace_callback(
    '/href="\/aventour\/assets\/images\/news\/news-(\d+)\.jpg"/',
    function (array $matches): string {
        $index = ((int) $matches[1]) % 6;

        return 'href="{{ $galleryImages['.$index.'] ?? \'/aventour/assets/images/news/news-'.$matches[1].'.jpg\' }}"';
    },
    $html
);

if (file_put_contents($dest, $html) === false) {
    fwrite(STDERR, "Unable to write home.blade.php\n");
    exit(1);
}

echo "Wrote {$dest} (".strlen($html)." bytes)\n";
echo 'index.html based: '.(str_contains($html, 'banner-section') && str_contains($html, 'main-header">') ? 'yes' : 'check')."\n";
echo 'safari section: '.(str_contains($html, 'id="safari"') ? 'yes' : 'no')."\n";
echo 'kilimanjaro section: '.(str_contains($html, 'id="kilimanjaro"') ? 'yes' : 'no')."\n";
echo 'menu Safari: '.(str_contains($html, '#safari">Safari') ? 'yes' : 'no')."\n";
echo 'categories include: '.(str_contains($html, 'home-categories') ? 'yes' : 'no')."\n";
echo 'destinations include: '.(str_contains($html, 'home-destinations') ? 'yes' : 'no')."\n";
