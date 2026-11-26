@extends('layouts.aventour')

@section('title', $page?->seo_title ?? 'Contact Us | Tanzania Safaris and Trekking')
@section('meta_description', $page?->seo_description ?? 'Contact Tanzania Safaris and Trekking for safari and Kilimanjaro trip planning support.')

@section('content')
    @php
        $contactHeading = $pageContent['heading'] ?? 'Reach Out to Us';
        $contactBody = $pageContent['body'] ?? 'Find answers in our Faq section or contact us directly for more help.';
        $contactPhone = $siteSettings['phone'] ?? '+255 769 079 298';
        $contactEmail = $siteSettings['email'] ?? 'info@tanzaniasafarisandtrekking.com';
        $contactAddress = $siteSettings['address'] ?? 'Arusha, Tanzania';
        $contactPhoneHref = 'tel:'.preg_replace('/\s+/', '', $contactPhone);
    @endphp
    @include('partials.aventour.page-title', [
        'title' => $page?->title ?? 'Contact Us',
        'bg' => $pageBg,
        'crumbs' => [
            ['label' => $page?->title ?? 'Contact Us'],
        ],
    ])

    <section class="contact-section sec-pad-3">
        <div class="auto-container">
            <div class="inner-container">
                <div class="shape" style="background-image: url(/aventour/assets/images/shape/shape-55.png);"></div>
                <div class="row clearfix">
                    <div class="col-lg-5 col-md-12 col-sm-12 info-column">
                        <div class="info-box mr_65">
                            <div class="text-box">
                                <h2 data-cms-field="heading">{{ $contactHeading }}</h2>
                                <x-rich-text data-cms-field="body" :content="$contactBody" />
                                <p><a href="{{ route('faq') }}">Faq</a></p>
                            </div>
                            <div class="inner-box">
                                <div class="single-item">
                                    <div class="inner">
                                        <h5>Talk to Us</h5>
                                        <p><a href="{{ $contactPhoneHref }}">{{ $contactPhone }}</a></p>
                                    </div>
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-80.png" alt=""></div>
                                </div>
                                <div class="single-item">
                                    <div class="inner">
                                        <h5>Message Us</h5>
                                        <p><a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></p>
                                    </div>
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-81.png" alt=""></div>
                                </div>
                                <div class="single-item">
                                    <div class="inner">
                                        <h5>Locate Us</h5>
                                        <p>{{ $contactAddress }}</p>
                                    </div>
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-82.png" alt=""></div>
                                </div>
                            </div>
                            <div class="social-box">
                                <h5>Social Connect</h5>
                                <ul class="social-links">
                                    <li><a href="{{ route('home') }}"><i class="flaticon-instagram-logo"></i></a></li>
                                    <li><a href="{{ route('home') }}"><i class="flaticon-youtube"></i></a></li>
                                    <li><a href="{{ route('home') }}"><i class="flaticon-facebook-app-symbol"></i></a></li>
                                    <li><a href="{{ route('home') }}"><i class="flaticon-twitter"></i></a></li>
                                    <li><a href="{{ route('home') }}"><i class="flaticon-whatsapp"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-12 col-sm-12 form-column">
                        <div class="booking-form">
                            @if ($errors->any())
                                <div class="book-now-alert" role="alert" style="margin-bottom: 18px;">
                                    <strong>Please fix the highlighted fields and try again.</strong>
                                </div>
                            @endif
                            <form method="post" action="{{ route('contact.store') }}" class="contact-form">
                                @csrf
                                @include('partials.aventour.form-spam-guard')
                                <input type="hidden" name="source" value="contact">
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 input-column">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" name="username" placeholder="Your name" value="{{ old('username', old('full_name')) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 input-column">
                                        <div class="form-group">
                                            <label>Subject</label>
                                            <input type="text" name="subject" placeholder="Tour inquiry" value="{{ old('subject') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 input-column">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" placeholder="Enter email address" value="{{ old('email') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12 input-column">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="text" name="phone" placeholder="Your ph num" value="{{ old('phone') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 input-column">
                                        <div class="form-group">
                                            <label>Tour Interest</label>
                                            <div class="select-box">
                                                <select class="wide" name="interest">
                                                    <option value="Tour inquiry" @selected(old('interest', 'Tour inquiry') === 'Tour inquiry')>Tour inquiry</option>
                                                    @foreach ($packages->take(8) as $package)
                                                        <option value="{{ $package['slug'] }}" @selected(old('interest') === $package['slug'])>{{ $package['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 input-column">
                                        <div class="form-group">
                                            <label>Message</label>
                                            <textarea name="message" placeholder="Message goes here">{{ old('message') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 btn-column">
                                        <div class="form-group message-btn">
                                            <button type="submit" class="theme-btn" name="submit-form"><span>Send Your Message</span><i class="flaticon-arrow"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="location-section contact-map-section">
        <div class="map-inner">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31864.051785639647!2d37.300221888737816!3d-3.3485387250407874!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2c5246003d7f4cf1%3A0x59b566538081c72d!2sTanzania%20Safaris%20And%20Trekking!5e0!3m2!1sen!2stz!4v1795720237744!5m2!1sen!2stz"
                width="600"
                height="450"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="strict-origin-when-cross-origin"
                title="Tanzania Safaris And Trekking location map"
            ></iframe>
        </div>
    </section>
@endsection
