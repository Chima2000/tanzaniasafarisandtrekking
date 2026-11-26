@extends('layouts.aventour')

@section('title', $page?->seo_title ?? 'About Us | Tanzania Safaris and Trekking')
@section('meta_description', $page?->seo_description ?? 'Learn about Tanzania Safaris and Trekking, our local safari and Kilimanjaro team, and how we plan Northern Circuit journeys from Arusha.')
@section('meta_keywords', 'about Tanzania Safaris and Trekking, Tanzania safari company, Kilimanjaro trek operator Arusha, Northern Circuit safari guides')
@section('canonical', route('about'))

@php
    $bodyClass = trim(($bodyClass ?? '').' has-sticky-sidebar');
@endphp

@section('content')
    @php
        $aboutSubtitle = $pageContent['subtitle'] ?? 'Tanzania Safaris and Trekking';
        $aboutHeading = $pageContent['heading'] ?? 'A Local Safari & Kilimanjaro Company Based in Arusha';
        $aboutBody = $pageContent['body'] ?? 'Tanzania Safaris and Trekking is a locally run travel company specializing in Northern Circuit wildlife safaris, Mount Kilimanjaro climbs, day trips, and cultural experiences. From our base in Arusha we plan private and small-group itineraries with clear park days, trusted mountain crews, and practical trip support before you arrive.';
        $featureOneTitle = $pageContent['feature_one_title'] ?? 'Local Planning Desk';
        $featureOneBody = $pageContent['feature_one_body'] ?? 'Itineraries are built around real park logistics, seasonal wildlife movement, and the pace that fits your group.';
        $featureTwoTitle = $pageContent['feature_two_title'] ?? 'Field Teams You Can Trust';
        $featureTwoBody = $pageContent['feature_two_body'] ?? 'Safari guides and Kilimanjaro crews handle the day-to-day details so you can focus on the journey itself.';
        $aboutCta = $pageContent['cta_label'] ?? 'Explore Packages';
        $img = fn (int $index) => $galleryImages[$index] ?? $pageBg;
        $pos = fn (int $index) => $imagePositions[$index] ?? '50% 50%';
    @endphp

    @include('partials.aventour.page-title', [
        'title' => 'About Us',
        'bg' => $pageBg,
        'crumbs' => [
            ['label' => 'About Us'],
        ],
    ])

    {{-- Company introduction --}}
    <section class="about-section about-company-intro sec-pad-3">
        <div class="auto-container">
            <div class="about-content pb_40">
                <div class="row clearfix">
                    <div class="col-lg-4 col-md-12 col-sm-12 image-column">
                        <div class="image-box" data-cms-section="about-main">
                            <div class="image-shape z_1 float-bob-y" style="background-image: url(/aventour/assets/images/shape/shape-51.png);"></div>
                            <figure class="image image-1" data-cms-field="image_1">
                                <img src="{{ $img(0) }}" alt="Tanzania Safaris and Trekking team in the field" style="object-fit: cover; object-position: {{ $pos(0) }};">
                            </figure>
                            <figure class="image image-2" data-cms-field="image_2">
                                <img src="{{ $img(1) }}" alt="Northern Circuit safari landscape" style="object-fit: cover; object-position: {{ $pos(1) }};">
                            </figure>
                            <div class="video-box" data-cms-field="image_3" style="background-image: url('{{ $img(2) }}'); background-position: {{ $pos(2) }}; background-size: cover;">
                                <a href="{{ route('packages.index') }}" aria-label="Browse tour packages"><img src="/aventour/assets/images/icons/icon-27.png" alt=""></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-12 col-sm-12 content-column">
                        <div class="content-box">
                            <div class="sec-title" data-cms-section="about-main">
                                <div class="sub-title"><i class="flaticon-parachuting"></i><span data-cms-field="subtitle">{{ $aboutSubtitle }}</span></div>
                                <h2 data-cms-field="heading">{{ $aboutHeading }}</h2>
                            </div>
                            <div class="inner-box">
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 image-column">
                                        <figure class="image mr_30" data-cms-field="image_4">
                                            <img src="{{ $img(3) }}" alt="Kilimanjaro and safari travel planning" style="object-fit: cover; object-position: {{ $pos(3) }};">
                                        </figure>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 text-column">
                                        <div class="text-box about-company-intro__copy">
                                            <x-rich-text data-cms-field="body" :content="$aboutBody" />
                                            <x-rich-text data-cms-field="body_extra" :content="$pageContent['body_extra'] ?? null" fallback="Whether you are planning a short Ngorongoro day, a classic Serengeti safari, or a multi-day Kilimanjaro route, we keep the process clear: route options, accommodation style, inclusions, and a day-by-day outline before you confirm." />
                                            <a href="{{ route('packages.index') }}" class="theme-btn"><span data-cms-field="cta_label">{{ $aboutCta }}</span><i class="flaticon-arrow"></i></a>
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

    {{-- Planning and field team highlights --}}
    <section class="about-highlights-section pb_90" data-cms-section="about-main">
        <div class="auto-container">
            <div class="about-highlights-grid">
                <article class="about-highlight-card">
                    <div class="about-highlight-card__icon"><img src="/aventour/assets/images/icons/icon-28.png" alt=""></div>
                    <h3 data-cms-field="feature_one_title">{{ $featureOneTitle }}</h3>
                    <x-rich-text data-cms-field="feature_one_body" :content="$featureOneBody" />
                </article>
                <article class="about-highlight-card about-highlight-card--alt">
                    <div class="about-highlight-card__icon"><img src="/aventour/assets/images/icons/icon-29.png" alt=""></div>
                    <h3 data-cms-field="feature_two_title">{{ $featureTwoTitle }}</h3>
                    <x-rich-text data-cms-field="feature_two_body" :content="$featureTwoBody" />
                </article>
            </div>
        </div>
    </section>

    {{-- Mission and vision --}}
    <section class="about-mission-section pb_90" data-cms-section="about-mission">
        <div class="auto-container">
            <div class="sec-title centred mb_50">
                <div class="sub-title"><i class="flaticon-parachuting"></i><span data-cms-field="mission_subtitle">{{ $pageContent['mission_subtitle'] ?? 'Mission & Vision' }}</span></div>
                <h2 data-cms-field="mission_heading">{{ $pageContent['mission_heading'] ?? 'What Guides Our Work in Tanzania' }}</h2>
                <x-rich-text class="mt_12" data-cms-field="mission_intro" :content="$pageContent['mission_intro'] ?? null" fallback="We exist to make Tanzania travel practical, well paced, and locally grounded from first enquiry to final drop-off." />
            </div>
            <div class="row clearfix">
                <div class="col-lg-6 col-md-12 col-sm-12 mb_30">
                    <article class="about-info-card">
                        <span class="about-info-card__label">Our Mission</span>
                        <h3 data-cms-field="mission_title">{{ $pageContent['mission_title'] ?? 'Plan Tanzania trips that work on the ground' }}</h3>
                        <x-rich-text data-cms-field="mission_body" :content="$pageContent['mission_body'] ?? null" fallback="Our mission is to design safari and Kilimanjaro journeys with honest timing, reliable transport, experienced guides, and inclusions that match what travelers actually need in the parks and on the mountain." />
                        <ul class="about-info-card__list">
                            <li>Clear day-by-day itineraries before booking</li>
                            <li>Local guides with Northern Circuit and mountain experience</li>
                            <li>Responsive support from enquiry through departure</li>
                        </ul>
                    </article>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 mb_30">
                    <article class="about-info-card about-info-card--alt">
                        <span class="about-info-card__label">Our Vision</span>
                        <h3 data-cms-field="vision_title">{{ $pageContent['vision_title'] ?? 'Be a trusted Arusha partner for Africa travel' }}</h3>
                        <x-rich-text data-cms-field="vision_body" :content="$pageContent['vision_body'] ?? null" fallback="We aim to be the company travelers return to for Tanzania wildlife, Kilimanjaro, and short regional add-ons because the planning felt straightforward and the field delivery matched the promise." />
                        <ul class="about-info-card__list">
                            <li>Long-term relationships with guides, camps, and crews</li>
                            <li>Responsible travel choices that respect parks and communities</li>
                            <li>Itineraries that balance wildlife time with comfort and rest</li>
                        </ul>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- Our story / approach --}}
    <section class="about-story-section sec-pad bg-color-1" data-cms-section="about-story">
        <div class="auto-container">
            <div class="about-story-grid">
                <div class="about-story-grid__copy">
                    <div class="sec-title">
                        <div class="sub-title"><i class="flaticon-parachuting"></i><span data-cms-field="story_subtitle">{{ $pageContent['story_subtitle'] ?? 'Our Story' }}</span></div>
                        <h2 data-cms-field="story_heading">{{ $pageContent['story_heading'] ?? 'Built Around Northern Tanzania Travel' }}</h2>
                    </div>
                    <div class="about-story-grid__text">
                        <x-rich-text data-cms-field="story_body_1" :content="$pageContent['story_body_1'] ?? null" fallback="Tanzania Safaris and Trekking grew from day-to-day work on the Northern Circuit: Tarangire game drives, Serengeti plains routes, Ngorongoro crater mornings, and Kilimanjaro departures from Moshi and Arusha. That field experience shapes how we write packages and advise clients." />
                        <x-rich-text data-cms-field="story_body_2" :content="$pageContent['story_body_2'] ?? null" fallback="Instead of selling one generic tour style, we help you choose the right format. Lodge or camping safari. Five-day Marangu or eight-day Lemosho. A one-day crater visit or a longer circuit that includes Lake Eyasi. The goal is a trip that fits your dates, fitness, and travel style." />
                        <x-rich-text data-cms-field="story_body_3" :content="$pageContent['story_body_3'] ?? null" fallback="Every enquiry is handled by people who understand park fees, gate timing, altitude pacing, and seasonal wildlife patterns. That local detail is what turns a brochure idea into a workable Tanzania itinerary." />
                    </div>
                    <div class="about-story-grid__meta">
                        <article class="about-story-fact">
                            <span class="about-story-fact__index">01</span>
                            <div class="about-story-fact__copy">
                                <strong>Base</strong>
                                <span>Arusha, Tanzania</span>
                            </div>
                        </article>
                        <article class="about-story-fact">
                            <span class="about-story-fact__index">02</span>
                            <div class="about-story-fact__copy">
                                <strong>Focus</strong>
                                <span>Safaris, Kilimanjaro &amp; day tours</span>
                            </div>
                        </article>
                        <article class="about-story-fact">
                            <span class="about-story-fact__index">03</span>
                            <div class="about-story-fact__copy">
                                <strong>Travel style</strong>
                                <span>Private and small groups</span>
                            </div>
                        </article>
                    </div>
                </div>
                <aside class="about-story-grid__media">
                    <div class="about-story-grid__sticky">
                        <figure>
                            <img src="{{ $img(4) }}" alt="Safari and Kilimanjaro landscapes in northern Tanzania">
                        </figure>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    {{-- What we offer --}}
    <section class="about-services-section sec-pad">
        <div class="auto-container">
            <div class="sec-title centred mb_50">
                <div class="sub-title"><i class="flaticon-parachuting"></i><span>What We Offer</span></div>
                <h2>Safari, Mountain & Day Experiences</h2>
                <p class="mt_12">Four core travel categories cover most requests we receive from couples, families, and small groups.</p>
            </div>
            <div class="row clearfix">
                <div class="col-lg-3 col-md-6 col-sm-12 mb_30">
                    <article class="about-service-card">
                        <figure class="about-service-card__image">
                            <img src="{{ $img(5) }}" alt="Tanzania wildlife safari packages">
                        </figure>
                        <h3>Wildlife Safaris</h3>
                        <p>Northern Circuit itineraries across Tarangire, Serengeti, Ngorongoro, Lake Manyara, and Lake Eyasi with lodge or camping options.</p>
                        <a href="{{ route('packages.safari') }}">View safari packages <i class="flaticon-arrow"></i></a>
                    </article>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb_30">
                    <article class="about-service-card">
                        <figure class="about-service-card__image">
                            <img src="{{ $img(6) }}" alt="Kilimanjaro trek packages">
                        </figure>
                        <h3>Kilimanjaro Treks</h3>
                        <p>Route-based climbs on Machame, Lemosho, Marangu, Rongai, Umbwe, and the Northern Circuit with mountain crew support.</p>
                        <a href="{{ route('packages.trekking') }}">View trek packages <i class="flaticon-arrow"></i></a>
                    </article>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb_30">
                    <article class="about-service-card">
                        <figure class="about-service-card__image">
                            <img src="{{ $img(7) }}" alt="Tanzania day trips from Arusha">
                        </figure>
                        <h3>Day Adventures</h3>
                        <p>One-day outings from Arusha and Moshi, including park visits, crater drives, waterfalls, and hot springs.</p>
                        <a href="{{ route('packages.day-trips') }}">View day trips <i class="flaticon-arrow"></i></a>
                    </article>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb_30">
                    <article class="about-service-card">
                        <figure class="about-service-card__image">
                            <img src="{{ $img(3) }}" alt="Cultural experiences around Moshi">
                        </figure>
                        <h3>Cultural Experiences</h3>
                        <p>Village-based tours such as Materuni coffee and waterfall visits that add local life to a safari or trek schedule.</p>
                        <a href="{{ route('packages.cultural') }}">View cultural tours <i class="flaticon-arrow"></i></a>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- Why choose us --}}
    <section class="about-why-section sec-pad bg-color-1">
        <div class="auto-container">
            <div class="sec-title centred mb_50">
                <div class="sub-title"><i class="flaticon-parachuting"></i><span>Why Travel With Us</span></div>
                <h2>Practical Reasons Clients Choose Our Desk</h2>
                <p class="mt_12">We keep planning transparent and field delivery consistent across safari vehicles and mountain crews.</p>
            </div>
            <div class="row clearfix">
                <div class="col-lg-3 col-md-6 col-sm-12 mb_30">
                    <article class="about-why-card">
                        <div class="icon-box"><img src="/aventour/assets/images/icons/icon-9.png" alt=""></div>
                        <h3>Local route knowledge</h3>
                        <p>Advice is based on Northern Circuit roads, gate procedures, crater timing, and Kilimanjaro camp schedules.</p>
                    </article>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb_30">
                    <article class="about-why-card">
                        <div class="icon-box"><img src="/aventour/assets/images/icons/icon-10.png" alt=""></div>
                        <h3>Clear package details</h3>
                        <p>Each tour lists parks or camps, meals, inclusions, and a full day-by-day outline before you enquire.</p>
                    </article>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb_30">
                    <article class="about-why-card">
                        <div class="icon-box"><img src="/aventour/assets/images/icons/icon-11.png" alt=""></div>
                        <h3>Private trip flexibility</h3>
                        <p>Most itineraries can be adjusted for travel dates, lodging style, group size, and add-on destinations.</p>
                    </article>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb_30">
                    <article class="about-why-card">
                        <div class="icon-box"><img src="/aventour/assets/images/icons/icon-12.png" alt=""></div>
                        <h3>End-to-end coordination</h3>
                        <p>From airport pickup planning to final drop-off, we coordinate guides, vehicles, and mountain logistics.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- Values --}}
    <section class="about-values-section sec-pad">
        <div class="auto-container">
            <div class="row clearfix align-items-center">
                <div class="col-lg-5 col-md-12 col-sm-12 mb_30">
                    <div class="sec-title">
                        <div class="sub-title"><i class="flaticon-parachuting"></i><span>Company Values</span></div>
                        <h2>How We Work With Travelers & Partners</h2>
                        <p class="mt_15">These principles guide how we quote trips, brief guides, and handle changes on safari or on the mountain.</p>
                    </div>
                </div>
                <div class="col-lg-7 col-md-12 col-sm-12">
                    <div class="about-values-list">
                        <article>
                            <h3>Honesty in planning</h3>
                            <p>We explain drive times, altitude challenges, seasonal wildlife chances, and what is or is not included in a quote.</p>
                        </article>
                        <article>
                            <h3>Safety first</h3>
                            <p>Safari pacing, crater rules, and Kilimanjaro acclimatization come before rushing a schedule to fit more stops.</p>
                        </article>
                        <article>
                            <h3>Respect for place</h3>
                            <p>We work within park regulations and community visit arrangements that treat hosts and landscapes with care.</p>
                        </article>
                        <article>
                            <h3>Service after booking</h3>
                            <p>Once dates are confirmed, we stay available for packing advice, arrival updates, and on-trip coordination needs.</p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="about-stats-section pb_90">
        <div class="auto-container">
            <div class="funfact-box centred">
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-6 col-sm-12 funfact-block">
                        <div class="funfact-block-one">
                            <div class="light-icon"><i class="flaticon-traveler"></i></div>
                            <div class="count-outer">
                                <span class="odometer" data-count="{{ max($packageCount, 10) }}">00</span><span class="symble">+</span>
                            </div>
                            <h3>Safari & Trek Packages</h3>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 funfact-block">
                        <div class="funfact-block-one">
                            <div class="light-icon"><i class="flaticon-language"></i></div>
                            <div class="count-outer">
                                <span class="odometer" data-count="{{ max($destinationCount, 5) }}">00</span><span class="symble">+</span>
                            </div>
                            <h3>Key Destinations</h3>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 funfact-block">
                        <div class="funfact-block-one">
                            <div class="light-icon"><i class="flaticon-traveler"></i></div>
                            <div class="count-outer">
                                <span class="odometer" data-count="4">00</span>
                            </div>
                            <h3>Experience Categories</h3>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12 funfact-block">
                        <div class="funfact-block-one">
                            <div class="light-icon"><i class="flaticon-traveler"></i></div>
                            <div class="count-outer">
                                <span class="odometer" data-count="24">00</span><span class="symble">/7</span>
                            </div>
                            <h3>Trip Planning Support</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- How we work --}}
    <section class="about-process-section sec-pad bg-color-1">
        <div class="auto-container">
            <div class="sec-title centred mb_50">
                <div class="sub-title"><i class="flaticon-parachuting"></i><span>How Booking Works</span></div>
                <h2>From First Enquiry to Departure Day</h2>
                <p class="mt_12">A simple four-step process keeps planning organized whether you choose safari, Kilimanjaro, or a short day tour.</p>
            </div>
            <div class="row clearfix">
                <div class="col-lg-3 col-md-6 col-sm-12 mb_30">
                    <article class="about-process-card">
                        <span class="about-process-card__step">01</span>
                        <h3>Share your travel idea</h3>
                        <p>Tell us your dates, group size, preferred parks or Kilimanjaro route, and lodging style.</p>
                    </article>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb_30">
                    <article class="about-process-card">
                        <span class="about-process-card__step">02</span>
                        <h3>Receive a tailored outline</h3>
                        <p>We send a clear itinerary with day flow, inclusions, and options that fit your schedule.</p>
                    </article>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb_30">
                    <article class="about-process-card">
                        <span class="about-process-card__step">03</span>
                        <h3>Confirm and prepare</h3>
                        <p>Once you confirm, we finalize logistics and share practical packing and arrival notes.</p>
                    </article>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb_30">
                    <article class="about-process-card">
                        <span class="about-process-card__step">04</span>
                        <h3>Travel with field support</h3>
                        <p>Your guide and crew handle the on-ground journey while our team remains reachable.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="about-cta-section sec-pad">
        <div class="auto-container">
            <div class="about-cta-banner" style="background-image: url('{{ $img(2) }}');">
                <div class="about-cta-banner__overlay"></div>
                <div class="about-cta-banner__content">
                    <div class="sec-title light">
                        <div class="sub-title"><i class="flaticon-parachuting"></i><span>Plan With Us</span></div>
                        <h2>Ready to Start Your Tanzania Journey?</h2>
                        <p class="mt_12">Browse packaged itineraries or contact our Arusha team for a custom safari, Kilimanjaro climb, or day adventure.</p>
                    </div>
                    <div class="about-cta-banner__actions">
                        <a href="{{ route('packages.index') }}" class="theme-btn"><span>Browse Packages</span><i class="flaticon-arrow"></i></a>
                        <a href="{{ route('contact') }}" class="theme-btn about-cta-banner__secondary"><span>Contact Us</span><i class="flaticon-arrow"></i></a>
                        <a href="{{ route('book-now') }}" class="theme-btn about-cta-banner__secondary"><span>Book Now</span><i class="flaticon-arrow"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
