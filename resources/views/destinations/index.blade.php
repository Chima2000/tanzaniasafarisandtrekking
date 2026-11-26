@extends('layouts.aventour')

@section('title', 'Tanzania Safari Destinations Guide | Serengeti, Ngorongoro, Kilimanjaro')
@section('meta_description', 'Explore Tanzania destinations with story-led guides to Serengeti, Ngorongoro, Kilimanjaro, Tarangire, and Arusha—plus safari and trek packages matched to each place.')
@section('meta_keywords', 'Tanzania destinations, Serengeti safari, Ngorongoro crater, Kilimanjaro trek, Tarangire elephants, Arusha day trips')
@section('canonical', route('destinations.index'))

@push('head')
    <meta property="og:type" content="website">
    <meta property="og:title" content="Tanzania Safari Destinations Guide | Serengeti, Ngorongoro, Kilimanjaro">
    <meta property="og:description" content="Explore Tanzania destinations with story-led guides to Serengeti, Ngorongoro, Kilimanjaro, Tarangire, and Arusha.">
    <meta property="og:url" content="{{ route('destinations.index') }}">
    <meta property="og:image" content="{{ url($pageBg) }}">
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Tanzania Safari and Trek Destinations',
            'description' => 'Curated destination guides for Northern Tanzania safaris and Kilimanjaro treks.',
            'url' => route('destinations.index'),
            'itemListElement' => $destinations->values()->map(fn (array $destination, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'url' => route('destinations.show', $destination['slug']),
                'name' => $destination['full_name'],
            ])->all(),
        ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
    @include('partials.aventour.page-title', [
        'title' => 'Destinations',
        'bg' => $pageBg,
        'crumbs' => [
            ['label' => 'Destinations'],
        ],
    ])

    <section class="destinations-section pt_80 pb_70">
        <div class="auto-container">
            <div class="sec-title centred mb_40">
                <div class="sub-title"><i class="flaticon-parachuting"></i><span>Story-Led Destination Guides</span></div>
                <h2>Places Worth Building a Trip Around</h2>
                <p class="mt_12">Each guide is written in story mode—with local highlights, wildlife notes, timing advice, FAQs, and packages linked to that place.</p>
            </div>
            <div class="row clearfix">
                @foreach ($destinations as $destination)
                    <div class="col-lg-4 col-md-6 col-sm-12 destinations-block mb_30">
                        <article class="destinations-block-one">
                            <div class="image-box">
                                <figure class="image"><img src="{{ $destination['image'] }}" alt="{{ $destination['full_name'] }} travel guide"></figure>
                                <div class="location"><i class="flaticon-location"></i><h3>{{ $destination['title'] }}</h3></div>
                            </div>
                            <div class="lower-content">
                                <p>{{ $destination['places'] }} - <span class="special-text">{{ $destination['starts'] }}</span></p>
                                <a href="{{ route('destinations.show', $destination['slug']) }}" aria-label="Read the {{ $destination['title'] }} destination story"><i class="flaticon-arrow"></i></a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="destinations-section pb_110">
        <div class="outer-container pl_75 pr_75">
            <div class="sec-title centred">
                <div class="sub-title"><i class="flaticon-parachuting"></i><span>Recommended Places</span></div>
                <h2>Favorites for Every Explorer</h2>
                <p class="mt_12">Plan your journey to our most sought-after safari and trek destinations.</p>
            </div>
            <div class="six-item-carousel">
                <div class="swiper-wrapper">
                    @foreach ($destinations as $destination)
                        <div class="swiper-slide">
                            <div class="destinations-block-one">
                                <div class="image-box">
                                    <figure class="image"><img src="{{ $destination['image'] }}" alt="{{ $destination['title'] }}"></figure>
                                    <div class="location"><i class="flaticon-location"></i><h3>{{ $destination['title'] }}</h3></div>
                                </div>
                                <div class="lower-content">
                                    <p>{{ $destination['places'] }} - <span class="special-text">{{ $destination['starts'] }}</span></p>
                                    <a href="{{ route('destinations.show', $destination['slug']) }}"><i class="flaticon-arrow"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @foreach ($destinations->take(2) as $destination)
                        <div class="swiper-slide">
                            <div class="destinations-block-one">
                                <div class="image-box">
                                    <figure class="image"><img src="{{ $destination['image'] }}" alt="{{ $destination['title'] }}"></figure>
                                    <div class="location"><i class="flaticon-location"></i><h3>{{ $destination['title'] }}</h3></div>
                                </div>
                                <div class="lower-content">
                                    <p>{{ $destination['places'] }} - <span class="special-text">{{ $destination['starts'] }}</span></p>
                                    <a href="{{ route('destinations.show', $destination['slug']) }}"><i class="flaticon-arrow"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="six-item-pagination dots-style-one"></div>
            </div>
        </div>
    </section>

    <section class="popular-section pb_120">
        <div class="outer-container">
            <div class="pattern-layer" style="background-image: url(/aventour/assets/images/shape/shape-16.png);"></div>
            <div class="auto-container">
                <div class="sec-title">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Popular Places</span></div>
                    <h2>Popular Places to Discover</h2>
                    <p class="p_absolute r_0 b_4">Plan your journey to our most sought-after spots.</p>
                </div>
                <div class="inner-container">
                    @foreach ($destinations as $destination)
                        <div class="popular-block-one moving-item {{ $loop->first ? 'active' : '' }}">
                            <figure class="image-box"><img src="{{ $destination['image'] }}" alt="{{ $destination['title'] }}"></figure>
                            <div class="content-box">
                                <span class="text">{{ $destination['places'] }}</span>
                                <div class="link"><a href="{{ route('destinations.show', $destination['slug']) }}"><i class="flaticon-arrow"></i></a></div>
                                <div class="text-box">
                                    <h3 class="short-title">{{ $destination['short'] }}</h3>
                                    <h3><a href="{{ route('destinations.show', $destination['slug']) }}">{{ $destination['title'] }}</a></h3>
                                    <span class="special-text">{{ $destination['starts'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="common-lower-text centred">
                    <p>From Serengeti plains to Kilimanjaro summit.</p>
                    <div class="link-box"><a href="{{ route('packages.index') }}"><span>All Packages</span><i class="flaticon-arrow"></i></a></div>
                </div>
            </div>
        </div>
    </section>
@endsection
