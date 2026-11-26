@extends('layouts.aventour')

@section('title', $page?->seo_title ?? 'About Us | Tanzania Safaris and Trekking')
@section('meta_description', $page?->seo_description ?? 'Learn about Tanzania Safaris and Trekking and how we craft safari and Kilimanjaro journeys.')

@section('content')
    @include('partials.aventour.page-title', [
        'title' => 'About Us',
        'bg' => $pageBg,
        'crumbs' => [
            ['label' => 'About Us'],
        ],
    ])

    <section class="about-section sec-pad-3">
        <div class="auto-container">
            <div class="about-content pb_90">
                <div class="row clearfix">
                    <div class="col-lg-4 col-md-12 col-sm-12 image-column">
                        <div class="image-box">
                            <div class="image-shape z_1 float-bob-y" style="background-image: url(/aventour/assets/images/shape/shape-51.png);"></div>
                            <figure class="image image-1"><img src="{{ $galleryImages[0] ?? $pageBg }}" alt="About Tanzania Safaris"></figure>
                            <figure class="image image-2"><img src="{{ $galleryImages[1] ?? $pageBg }}" alt="Safari moments"></figure>
                            <div class="video-box" style="background-image: url('{{ $galleryImages[2] ?? $pageBg }}');">
                                <a href="{{ route('packages.index') }}"><img src="/aventour/assets/images/icons/icon-27.png" alt=""></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-12 col-sm-12 content-column">
                        <div class="content-box">
                            @php
                                $aboutSubtitle = $pageContent['subtitle'] ?? 'Behind the Adventure';
                                $aboutHeading = $pageContent['heading'] ?? 'Inspired by Tanzania, Designed for Your Journey';
                                $aboutBody = $pageContent['body'] ?? 'We craft safari and Kilimanjaro experiences across Serengeti, Ngorongoro, Tarangire, and beyond—personal itineraries with trusted local guides, careful pacing, and authentic wilderness moments.';
                                $featureOneTitle = $pageContent['feature_one_title'] ?? 'Curated With Care';
                                $featureOneBody = $pageContent['feature_one_body'] ?? 'Designed for comfort, quality and unforgettable memories.';
                                $featureTwoTitle = $pageContent['feature_two_title'] ?? 'Explore With Ease';
                                $featureTwoBody = $pageContent['feature_two_body'] ?? 'Travel made smooth, simple, and worry-free for you.';
                                $aboutCta = $pageContent['cta_label'] ?? 'Explore Packages';
                            @endphp
                            <div class="sec-title">
                                <div class="sub-title"><i class="flaticon-parachuting"></i><span>{{ $aboutSubtitle }}</span></div>
                                <h2>{{ $aboutHeading }}</h2>
                            </div>
                            <div class="inner-box mb_60">
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 image-column">
                                        <figure class="image mr_30"><img src="{{ $galleryImages[3] ?? $pageBg }}" alt="Northern Circuit"></figure>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 text-column">
                                        <div class="text-box">
                                            <p>{{ $aboutBody }}</p>
                                            <a href="{{ route('packages.index') }}" class="theme-btn"><span>{{ $aboutCta }}</span><i class="flaticon-arrow"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="feature-box">
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 feature-block">
                                        <div class="feature-block-one">
                                            <div class="icon-box"><img src="/aventour/assets/images/icons/icon-28.png" alt=""></div>
                                            <h3>{{ $featureOneTitle }}</h3>
                                            <p>{{ $featureOneBody }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 feature-block">
                                        <div class="feature-block-one">
                                            <div class="icon-box"><img src="/aventour/assets/images/icons/icon-29.png" alt=""></div>
                                            <h3>{{ $featureTwoTitle }}</h3>
                                            <p>{{ $featureTwoBody }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="funfact-box centred">
                <div class="row clearfix">
                    <div class="col-lg-4 col-md-6 col-sm-12 funfact-block">
                        <div class="funfact-block-one">
                            <div class="light-icon"><i class="flaticon-traveler"></i></div>
                            <div class="count-outer">
                                <span class="odometer" data-count="{{ max($packageCount, 10) }}">00</span><span class="symble">+</span>
                            </div>
                            <h3>Safari & Trek Packages</h3>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 funfact-block">
                        <div class="funfact-block-one">
                            <div class="light-icon"><i class="flaticon-language"></i></div>
                            <div class="count-outer">
                                <span class="odometer" data-count="{{ $destinationCount }}">00</span><span class="symble">+</span>
                            </div>
                            <h3>Key Destinations</h3>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 funfact-block">
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
@endsection
