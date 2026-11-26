<section class="about-section sec-pad-3" id="about">
    <div class="auto-container">
        <div class="about-content pb_90">
            <div class="row clearfix">
                <div class="col-lg-4 col-md-12 col-sm-12 image-column">
                    <div class="image-box">
                        <div class="image-shape z_1 float-bob-y" style="background-image: url(/aventour/assets/images/shape/shape-51.png);"></div>
                        <figure class="image image-1"><img src="{{ $sectionImages['about'][0] ?? ($galleryImages[0] ?? ($packages[0]['image'] ?? '/aventour/assets/images/resource/about-1.jpg')) }}" alt="About Tanzania Safaris"></figure>
                        <figure class="image image-2"><img src="{{ $sectionImages['about'][1] ?? ($galleryImages[1] ?? ($packages[1]['image'] ?? '/aventour/assets/images/resource/about-2.jpg')) }}" alt="Safari moments"></figure>
                        <div class="video-box" style="background-image: url('{{ $sectionImages['about'][2] ?? ($galleryImages[2] ?? ($packages[2]['image'] ?? '/aventour/assets/images/resource/about-3.jpg')) }}');">
                            <a href="{{ route('about') }}"><img src="/aventour/assets/images/icons/icon-27.png" alt=""></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12 content-column">
                    <div class="content-box">
                        <div class="sec-title">
                            <div class="sub-title"><i class="flaticon-parachuting"></i><span>About Us</span></div>
                            <h2>Inspired by Tanzania, Designed for Your Journey</h2>
                        </div>
                        <div class="inner-box mb_60">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 image-column">
                                    <figure class="image mr_30"><img src="{{ $sectionImages['about'][3] ?? ($galleryImages[3] ?? ($packages[3]['image'] ?? '/aventour/assets/images/resource/about-4.jpg')) }}" alt="Northern Circuit"></figure>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 text-column">
                                    <div class="text-box">
                                        <p>We craft safari and Kilimanjaro experiences across Serengeti, Ngorongoro, Tarangire, and beyond—personal itineraries with trusted local guides, careful pacing, and authentic wilderness moments.</p>
                                        <a href="{{ route('about') }}" class="theme-btn"><span>Learn More</span><i class="flaticon-arrow"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="feature-box">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 feature-block">
                                    <div class="feature-block-one">
                                        <div class="icon-box"><img src="/aventour/assets/images/icons/icon-28.png" alt=""></div>
                                        <h3>Curated With Care</h3>
                                        <p>Designed for comfort, quality and unforgettable memories.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 feature-block">
                                    <div class="feature-block-one">
                                        <div class="icon-box"><img src="/aventour/assets/images/icons/icon-29.png" alt=""></div>
                                        <h3>Explore With Ease</h3>
                                        <p>Travel made smooth, simple, and worry-free for you.</p>
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
