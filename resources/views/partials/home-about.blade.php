@php
    $c = $pageContent ?? [];
    $aboutImage1 = \App\Support\CmsMedia::url($c['about_image_1'] ?? null, $sectionImages['about'][0] ?? ($galleryImages[0] ?? ($packages[0]['image'] ?? '/aventour/assets/images/resource/about-1.jpg')));
    $aboutImage2 = \App\Support\CmsMedia::url($c['about_image_2'] ?? null, $sectionImages['about'][1] ?? ($galleryImages[1] ?? ($packages[1]['image'] ?? '/aventour/assets/images/resource/about-2.jpg')));
    $aboutImage3 = \App\Support\CmsMedia::url($c['about_image_3'] ?? null, $sectionImages['about'][2] ?? ($galleryImages[2] ?? ($packages[2]['image'] ?? '/aventour/assets/images/resource/about-3.jpg')));
    $aboutImage4 = \App\Support\CmsMedia::url($c['about_image_4'] ?? null, $sectionImages['about'][3] ?? ($galleryImages[3] ?? ($packages[3]['image'] ?? '/aventour/assets/images/resource/about-4.jpg')));
@endphp
<section class="about-section sec-pad-3" id="about" data-cms-section="about">
    <div class="auto-container">
        <div class="about-content pb_90">
            <div class="row clearfix">
                <div class="col-lg-4 col-md-12 col-sm-12 image-column">
                    <div class="image-box">
                        <div class="image-shape z_1 float-bob-y" style="background-image: url(/aventour/assets/images/shape/shape-51.png);"></div>
                        <figure class="image image-1" data-cms-field="about_image_1">
                            <img src="{{ $aboutImage1 }}" alt="About Tanzania Safaris" style="{{ \App\Support\CmsMedia::objectStyle($c['about_image_1_position'] ?? null) }}">
                        </figure>
                        <figure class="image image-2" data-cms-field="about_image_2">
                            <img src="{{ $aboutImage2 }}" alt="Safari moments" style="{{ \App\Support\CmsMedia::objectStyle($c['about_image_2_position'] ?? null) }}">
                        </figure>
                        <div class="video-box" data-cms-field="about_image_3" style="{{ \App\Support\CmsMedia::backgroundStyle($aboutImage3, $c['about_image_3_position'] ?? null) }}">
                            <a href="{{ route('about') }}"><img src="/aventour/assets/images/icons/icon-27.png" alt=""></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 content-column">
                    <div class="content-box">
                        <div class="sec-title">
                            <div class="sub-title"><i class="flaticon-parachuting"></i><span data-cms-field="about_subtitle">{{ $c['about_subtitle'] ?? 'About Us' }}</span></div>
                            <h2 data-cms-field="about_heading">{{ $c['about_heading'] ?? 'Inspired by Tanzania, Designed for Your Journey' }}</h2>
                        </div>
                        <div class="inner-box mb_60">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 image-column">
                                    <figure class="image mr_30" data-cms-field="about_image_4">
                                        <img src="{{ $aboutImage4 }}" alt="Northern Circuit" style="{{ \App\Support\CmsMedia::objectStyle($c['about_image_4_position'] ?? null) }}">
                                    </figure>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 text-column">
                                    <div class="text-box">
                                        <x-rich-text data-cms-field="about_body" :content="$c['about_body'] ?? null" fallback="We craft safari and Kilimanjaro experiences across Serengeti, Ngorongoro, Tarangire, and beyond—personal itineraries with trusted local guides, careful pacing, and authentic wilderness moments." />
                                        <a href="{{ route('about') }}" class="theme-btn"><span data-cms-field="about_cta">{{ $c['about_cta'] ?? 'Learn More' }}</span><i class="flaticon-arrow"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="feature-box">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 feature-block">
                                    <div class="feature-block-one">
                                        <div class="icon-box"><img src="/aventour/assets/images/icons/icon-28.png" alt=""></div>
                                        <h3 data-cms-field="about_feature_one_title">{{ $c['about_feature_one_title'] ?? 'Curated With Care' }}</h3>
                                        <x-rich-text data-cms-field="about_feature_one_body" :content="$c['about_feature_one_body'] ?? null" fallback="Designed for comfort, quality and unforgettable memories." />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 feature-block">
                                    <div class="feature-block-one">
                                        <div class="icon-box"><img src="/aventour/assets/images/icons/icon-29.png" alt=""></div>
                                        <h3 data-cms-field="about_feature_two_title">{{ $c['about_feature_two_title'] ?? 'Explore With Ease' }}</h3>
                                        <x-rich-text data-cms-field="about_feature_two_body" :content="$c['about_feature_two_body'] ?? null" fallback="Travel made smooth, simple, and worry-free for you." />
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
                            <span class="odometer" data-count="{{ $destinations->count() }}">00</span><span class="symble">+</span>
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
