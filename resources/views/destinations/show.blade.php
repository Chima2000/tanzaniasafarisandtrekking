@extends('layouts.aventour')

@section('title', $destination['seo_title'])
@section('meta_description', $destination['seo_description'])
@section('meta_keywords', $destination['seo_keywords'])
@section('canonical', route('destinations.show', $destination['slug']))

@push('head')
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $destination['seo_title'] }}">
    <meta property="og:description" content="{{ $destination['seo_description'] }}">
    <meta property="og:url" content="{{ route('destinations.show', $destination['slug']) }}">
    <meta property="og:image" content="{{ url($destination['image']) }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $destination['seo_title'] }}">
    <meta name="twitter:description" content="{{ $destination['seo_description'] }}">
    <meta name="twitter:image" content="{{ url($destination['image']) }}">
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'TouristDestination',
            'name' => $destination['full_name'],
            'description' => $destination['seo_description'],
            'url' => route('destinations.show', $destination['slug']),
            'image' => url($destination['image']),
            'touristType' => $destination['facts'][1]['value'] ?? 'Travelers',
            'containedInPlace' => [
                '@type' => 'Place',
                'name' => $destination['region'],
            ],
            'provider' => [
                '@type' => 'TravelAgency',
                'name' => 'Tanzania Safaris and Trekking',
                'url' => url('/'),
                'telephone' => '+255769079298',
                'email' => 'info@tanzaniasafarisandtrekking.com',
            ],
        ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($destination['faqs'])->map(fn (array $faq): array => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ])->values()->all(),
        ], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
    @include('partials.aventour.page-title', [
        'title' => $destination['full_name'],
        'bg' => $destination['image'],
        'crumbs' => [
            ['label' => 'Destinations', 'url' => route('destinations.index')],
            ['label' => $destination['full_name']],
        ],
    ])

    <section class="about-section sec-pad-3 destination-story-page">
        <div class="auto-container">
            <div class="about-content pb_40">
                <div class="row clearfix">
                    <div class="col-lg-4 col-md-12 col-sm-12 image-column">
                        <div class="image-box">
                            <div class="image-shape z_1 float-bob-y" style="background-image: url(/aventour/assets/images/shape/shape-51.png);"></div>
                            <figure class="image image-1"><img src="{{ $destination['image'] }}" alt="{{ $destination['full_name'] }} safari and travel guide"></figure>
                            @if ($galleryImages[1] ?? false)
                                <figure class="image image-2"><img src="{{ $galleryImages[1] }}" alt="{{ $destination['title'] }} wildlife and landscapes"></figure>
                            @endif
                            <div class="video-box" style="background-image: url('{{ $galleryImages[2] ?? $destination['image'] }}');">
                                <a href="{{ route('packages.index') }}" aria-label="Browse packages featuring {{ $destination['title'] }}"><img src="/aventour/assets/images/icons/icon-27.png" alt=""></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-12 col-sm-12 content-column">
                        <div class="content-box">
                            <div class="sec-title">
                                <div class="sub-title"><i class="flaticon-parachuting"></i><span>{{ $destination['region'] }} · {{ $destination['places'] }}</span></div>
                                <h2>{{ $destination['headline'] }}</h2>
                                <p class="mt_15">{{ $destination['description'] }}</p>
                            </div>
                            <div class="inner-box mb_40">
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 image-column">
                                        <figure class="image mr_30"><img src="{{ $galleryImages[0] ?? $destination['image'] }}" alt="Explore {{ $destination['full_name'] }}"></figure>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 text-column">
                                        <div class="text-box">
                                            <p>{{ $destination['story_intro'] }}</p>
                                            <a href="{{ route('book-now', ['destination' => $destination['slug']]) }}" class="theme-btn"><span>Book This Destination</span><i class="flaticon-arrow"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 feature-block">
                                        <div class="feature-block-one">
                                            <div class="icon-box"><img src="/aventour/assets/images/icons/icon-28.png" alt=""></div>
                                            <h3>{{ $destination['starts'] }}</h3>
                                            <p>Flexible private departures featuring {{ $destination['title'] }} highlights.</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 feature-block">
                                        <div class="feature-block-one">
                                            <div class="icon-box"><img src="/aventour/assets/images/icons/icon-29.png" alt=""></div>
                                            <h3>{{ $destination['places'] }}</h3>
                                            <p>Handpicked itineraries linked to this destination story.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="destination-facts-section pb_80">
        <div class="auto-container">
            <div class="sec-title centred mb_40">
                <div class="sub-title"><i class="flaticon-parachuting"></i><span>{{ $destination['title'] }} Snapshot</span></div>
                <h2>Key Details for Travelers</h2>
            </div>
            <div class="row clearfix">
                @foreach ($destination['facts'] as $fact)
                    <div class="col-lg-3 col-md-6 col-sm-12 feature-block">
                        <div class="feature-block-one destination-fact-card">
                            <h3>{{ $fact['value'] }}</h3>
                            <p>{{ $fact['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="tour-details destination-story-section sec-pad bg-color-1">
        <div class="auto-container">
            <div class="sec-title mb_40">
                <div class="sub-title"><i class="flaticon-parachuting"></i><span>Destination Story Mode</span></div>
                <h2>The {{ $destination['title'] }} Journey, Told in Chapters</h2>
                <p class="mt_12">Follow the destination topics as a continuous travel story—from arrival to the moments that define {{ $destination['full_name'] }}.</p>
            </div>
            <div class="itinerary-content destination-story-chapters">
                <div class="single-item">
                    @foreach ($destination['story_chapters'] as $chapter)
                        <div class="inner-box {{ ! $loop->first ? 'mt_30' : '' }}">
                            @if ($galleryImages[$loop->index] ?? false)
                                <figure class="image-box">
                                    <img src="{{ $galleryImages[$loop->index] }}" alt="{{ $chapter['title'] }} — {{ $destination['title'] }}">
                                </figure>
                            @endif
                            <div class="content-box">
                                <span class="date">{{ $chapter['label'] }}</span>
                                <h3>{{ $chapter['title'] }}</h3>
                                <p>{{ $chapter['body'] }}</p>
                                <ul class="list-item clearfix">
                                    @foreach ($chapter['points'] as $point)
                                        <li>{{ $point }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="journeys-section sec-pad destination-topics-section">
        <div class="pattern-layer float-bob-x" style="background-image: url(/aventour/assets/images/shape/shape-8.png);"></div>
        <div class="auto-container">
            <div class="sec-title">
                <div class="sub-title"><i class="flaticon-parachuting"></i><span>{{ $destination['title'] }} Topics</span></div>
                <h2>Plan With Local Detail</h2>
            </div>
            <div class="row clearfix">
                <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                    <div class="image-box">
                        <div class="image-shape float-bob-y" style="background-image: url(/aventour/assets/images/shape/shape-6.png);"></div>
                        <figure class="image image-1"><img src="{{ $galleryImages[0] ?? $destination['image'] }}" alt="{{ $destination['full_name'] }} highlights"></figure>
                        <figure class="image image-2"><img src="{{ $galleryImages[1] ?? $destination['image'] }}" alt="{{ $destination['title'] }} wildlife moments"></figure>
                        <figure class="image image-3"><img src="{{ $galleryImages[2] ?? $destination['image'] }}" alt="Travel planning for {{ $destination['title'] }}"></figure>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                    <div class="content-box">
                        <div class="text-box">
                            <p>{{ $destination['best_time'] }}</p>
                        </div>
                        <div class="single-item">
                            <div class="title-box p_relative clearfix">
                                <div class="icon-shape" style="background-image: url(/aventour/assets/images/shape/shape-7.png);"></div>
                                <h3>Signature Highlights</h3>
                                <div class="icon-box"><img src="/aventour/assets/images/icons/icon-6.png" alt=""></div>
                            </div>
                            <div class="inner-box">
                                <div class="bg-layer" style="background-image: url('{{ $galleryImages[3] ?? $destination['image'] }}');"></div>
                                <p>What makes {{ $destination['full_name'] }} essential on a Tanzania itinerary.</p>
                                <ul class="list clearfix">
                                    @foreach ($destination['highlights'] as $highlight)
                                        <li><img src="/aventour/assets/images/icons/icon-8.png" alt=""><span>{{ $highlight }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="single-item">
                            <div class="title-box p_relative clearfix">
                                <div class="icon-shape" style="background-image: url(/aventour/assets/images/shape/shape-7.png);"></div>
                                <h3>Wildlife & Nature Notes</h3>
                                <div class="icon-box"><img src="/aventour/assets/images/icons/icon-7.png" alt=""></div>
                            </div>
                            <div class="inner-box">
                                <div class="bg-layer" style="background-image: url('{{ $galleryImages[4] ?? $destination['image'] }}');"></div>
                                <p>Species and nature moments travelers often encounter here.</p>
                                <ul class="list clearfix">
                                    @foreach (array_slice($destination['wildlife'], 0, 6) as $animal)
                                        <li><img src="/aventour/assets/images/icons/icon-8.png" alt=""><span>{{ $animal }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="single-item">
                            <div class="title-box p_relative clearfix">
                                <div class="icon-shape" style="background-image: url(/aventour/assets/images/shape/shape-7.png);"></div>
                                <h3>Getting There & Travel Tips</h3>
                                <div class="icon-box"><img src="/aventour/assets/images/icons/icon-6.png" alt=""></div>
                            </div>
                            <div class="inner-box">
                                <div class="bg-layer" style="background-image: url('{{ $galleryImages[5] ?? $destination['image'] }}');"></div>
                                <p>{{ $destination['getting_there'] }}</p>
                                <ul class="list clearfix">
                                    @foreach ($destination['tips'] as $tip)
                                        <li><img src="/aventour/assets/images/icons/icon-8.png" alt=""><span>{{ $tip }}</span></li>
                                    @endforeach
                                </ul>
                                <div class="link-box light"><a href="{{ route('book-now', ['destination' => $destination['slug']]) }}"><span>Book With a Local Expert</span><i class="flaticon-arrow"></i></a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="faq-section sec-pad destination-faq-section">
        <div class="auto-container">
            <div class="row clearfix">
                <div class="col-lg-5 col-md-12 col-sm-12 content-column">
                    <div class="sec-title">
                        <div class="sub-title"><i class="flaticon-parachuting"></i><span>{{ $destination['title'] }} FAQ</span></div>
                        <h2>Questions Travelers Ask Before Booking</h2>
                        <p class="mt_15">Clear answers to help you choose dates, trip length, and the right {{ $destination['title'] }} experience.</p>
                        <a href="{{ route('contact') }}" class="theme-btn mt_20"><span>Talk to Us</span><i class="flaticon-arrow"></i></a>
                    </div>
                </div>
                <div class="col-lg-7 col-md-12 col-sm-12 accordion-column">
                    <ul class="accordion-box accordion-style-one">
                        @foreach ($destination['faqs'] as $faq)
                            <li class="accordion block {{ $loop->first ? 'active-block' : '' }}">
                                <div class="acc-btn {{ $loop->first ? 'active' : '' }}">
                                    <div class="icon-box"><i class="flaticon-plus"></i></div>
                                    <h4>{{ $faq['question'] }}</h4>
                                </div>
                                <div class="acc-content {{ $loop->first ? 'current' : '' }}">
                                    <div class="text">
                                        <p>{{ $faq['answer'] }}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="package-section pb_90">
        <div class="auto-container">
            <div class="sec-title centred mb_40">
                <div class="sub-title"><i class="flaticon-parachuting"></i><span>{{ $destination['title'] }} Packages</span></div>
                <h2>Tours Featuring {{ $destination['title'] }}</h2>
                <p class="mt_12">{{ $packages->count() }} {{ \Illuminate\Support\Str::plural('package', $packages->count()) }} matched to this destination</p>
            </div>
            <div class="row clearfix">
                @forelse ($packages as $package)
                    @include('partials.aventour.package-card', [
                        'package' => $package,
                        'columnClass' => 'col-lg-4 col-md-6 col-sm-12 package-block',
                    ])
                @empty
                    <div class="col-12 centred">
                        <p>No packages currently list this destination. <a href="{{ route('packages.index') }}">Browse all packages</a>.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    @if ($relatedDestinations->isNotEmpty())
        <section class="destinations-section pb_110">
            <div class="auto-container">
                <div class="sec-title centred mb_40">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Keep Exploring</span></div>
                    <h2>Related Tanzania Destinations</h2>
                </div>
                <div class="row clearfix">
                    @foreach ($relatedDestinations as $related)
                        <div class="col-lg-3 col-md-6 col-sm-12 destinations-block">
                            <div class="destinations-block-one">
                                <div class="image-box">
                                    <figure class="image"><img src="{{ $related['image'] }}" alt="{{ $related['full_name'] }}"></figure>
                                    <div class="location"><i class="flaticon-location"></i><h3>{{ $related['title'] }}</h3></div>
                                </div>
                                <div class="lower-content">
                                    <p>{{ $related['places'] }} - <span class="special-text">{{ $related['starts'] }}</span></p>
                                    <a href="{{ route('destinations.show', $related['slug']) }}" aria-label="View {{ $related['title'] }} destination guide"><i class="flaticon-arrow"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
