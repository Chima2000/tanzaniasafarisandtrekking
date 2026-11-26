@extends('layouts.aventour')

@section('title', $package['name'].' | Tanzania Safaris and Trekking')

@php
    $bodyClass = trim(($bodyClass ?? '').' has-tour-sticky');
@endphp

@section('content')
    @php
        $categoryCrumb = match ($package['category']) {
            'Safari' => 'Safari Packages',
            'Trekking' => 'Kilimanjaro Treks',
            'Day Trip' => 'Day Adventures',
            'Cultural' => 'Cultural Experiences',
            default => 'Tour Packages',
        };
    @endphp
    @include('partials.aventour.page-title', [
        'title' => $package['name'],
        'bg' => $package['image'],
        'crumbs' => [
            ['label' => 'Tour Packages', 'url' => route('packages.index')],
            ['label' => $categoryCrumb, 'url' => route('packages.index', ['category' => $package['category']])],
            ['label' => $package['name']],
        ],
    ])

    <section class="tour-details sec-pad-3">
        <div class="auto-container">
            <div class="tabs-box tour-scroll-spy">
                <div class="tour-scroll-nav-bar">
                    <button type="button" class="tour-scroll-nav-arrow tour-scroll-nav-prev" aria-label="Scroll tabs left">
                        <i class="flaticon-arrow-1"></i>
                    </button>
                    <ul class="tab-btns tour-scroll-nav">
                        <li class="tab-btn active-btn"><a href="#tab-1"><span>Overview</span><i class="flaticon-up-arrow"></i></a></li>
                        <li class="tab-btn"><a href="#tab-2"><span>Itinerary</span><i class="flaticon-up-arrow"></i></a></li>
                        <li class="tab-btn"><a href="#tab-gallery"><span>Gallery</span><i class="flaticon-up-arrow"></i></a></li>
                        <li class="tab-btn"><a href="#tab-3"><span>Includes &amp; Excludes</span><i class="flaticon-up-arrow"></i></a></li>
                        <li class="tab-btn"><a href="#tab-4"><span>Faq’s</span><i class="flaticon-up-arrow"></i></a></li>
                        <li class="tab-btn"><a href="#tab-5"><span>Enquiry</span><i class="flaticon-up-arrow"></i></a></li>
                    </ul>
                    <button type="button" class="tour-scroll-nav-arrow tour-scroll-nav-next" aria-label="Scroll tabs right">
                        <i class="flaticon-arrow-1"></i>
                    </button>
                </div>
                <div class="title-box">
                    <div class="title-text">
                        <div class="location"><i class="flaticon-location"></i><span>{{ $package['location'] }}</span></div>
                        <h2>{{ $package['name'] }}</h2>
                    </div>
                    <div class="other-text">
                        <div class="duration-box">
                            <div class="icon-box"><i class="flaticon-calendar-1"></i></div>
                            <div class="text-box">
                                <span>Duration</span>
                                <p>{{ $package['duration'] }}</p>
                            </div>
                        </div>
                        <div class="rating"><i class="flaticon-star">&nbsp;{{ $package['rating'] }}</i><span>{{ $package['photo_count'] }} Photos</span></div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-8 col-md-12 col-sm-12 content-side">
                        <div class="tabs-content">
                            <div class="tab active-tab tour-section" id="tab-1">
                                <div class="overview-content">
                                    <figure class="image-box"><img src="{{ $package['image'] }}" alt="{{ $package['name'] }}"></figure>
                                    <p>{{ $package['overview'] }}</p>
                                    <ul class="list-item clearfix">
                                        <li><div class="icon"><img src="/aventour/assets/images/icons/icon-65.png" alt=""></div><span>Tour Type</span><span>{{ $package['category'] }}</span></li>
                                        <li><div class="icon"><img src="/aventour/assets/images/icons/icon-66.png" alt=""></div><span>Departure Location</span><span>Arusha / Moshi area</span></li>
                                        <li><div class="icon"><img src="/aventour/assets/images/icons/icon-67.png" alt=""></div><span>Return Location</span><span>Arusha / Moshi area</span></li>
                                        <li><div class="icon"><img src="/aventour/assets/images/icons/icon-68.png" alt=""></div><span>Departure Dates</span><span>Flexible — private departures</span></li>
                                        <li><div class="icon"><img src="/aventour/assets/images/icons/icon-69.png" alt=""></div><span>Accommodation</span><span>{{ $package['category'] === 'Trekking' ? 'Mountain camps / huts' : 'Lodge or camping as selected' }}</span></li>
                                        <li><div class="icon"><img src="/aventour/assets/images/icons/icon-70.png" alt=""></div><span>Meals Included</span><span>As per itinerary</span></li>
                                        <li><div class="icon"><img src="/aventour/assets/images/icons/icon-71.png" alt=""></div><span>Transportation</span><span>{{ $package['category'] === 'Trekking' ? 'Transfers + mountain crew support' : '4x4 safari vehicle with guide' }}</span></li>
                                        <li><div class="icon"><img src="/aventour/assets/images/icons/icon-72.png" alt=""></div><span>Photos</span><span>{{ $package['photo_count'] }} gallery images</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab active-tab tour-section" id="tab-2">
                                <div class="itinerary-content itinerary-day-by-day">
                                    <div class="single-item">
                                        <h2>Tour Itinerary</h2>
                                        <div class="itinerary-days">
                                            @foreach ($package['itinerary'] as $item)
                                                @php
                                                    $dayLabel = strtoupper((string) ($item['day'] ?? ''));
                                                    $description = collect($item['points'] ?? [])
                                                        ->filter()
                                                        ->map(fn (string $point): string => rtrim($point, '.'))
                                                        ->implode('. ');
                                                    if ($description !== '') {
                                                        $description .= '.';
                                                    }
                                                @endphp
                                                <details class="itinerary-day" @if ($loop->first) open @endif>
                                                    <summary class="itinerary-day__header">
                                                        <span class="itinerary-day__badge">{{ $dayLabel }}</span>
                                                        <h3 class="itinerary-day__title">{{ $item['title'] }}</h3>
                                                        <span class="itinerary-day__toggle" aria-hidden="true"></span>
                                                    </summary>
                                                    <div class="itinerary-day__body">
                                                        @if (! empty($item['image']))
                                                            <figure class="itinerary-day__image">
                                                                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}">
                                                            </figure>
                                                        @endif
                                                        @if ($description !== '')
                                                            <p class="itinerary-day__description">{{ $description }}</p>
                                                        @endif
                                                        @if (! empty($item['meals']) || ! empty($item['destinations']))
                                                            <dl class="itinerary-day__meta">
                                                                @if (! empty($item['meals']))
                                                                    <div>
                                                                        <dt>Meals:</dt>
                                                                        <dd>{{ $item['meals'] }}</dd>
                                                                    </div>
                                                                @endif
                                                                @if (! empty($item['destinations']))
                                                                    <div>
                                                                        <dt>Destinations:</dt>
                                                                        <dd>{{ $item['destinations'] }}</dd>
                                                                    </div>
                                                                @endif
                                                            </dl>
                                                        @endif
                                                        @if (! empty($item['accommodation']))
                                                            @php $stay = $item['accommodation']; @endphp
                                                            <div class="itinerary-day__stay">
                                                                <h4>Accommodation Selection:</h4>
                                                                <div class="itinerary-day__stay-card">
                                                                    @if (! empty($stay['image']))
                                                                        <figure class="itinerary-day__stay-image">
                                                                            <img src="{{ $stay['image'] }}" alt="{{ $stay['name'] ?? 'Accommodation' }}">
                                                                        </figure>
                                                                    @endif
                                                                    <div class="itinerary-day__stay-copy">
                                                                        @if (! empty($stay['type']))
                                                                            <span class="itinerary-day__stay-type">{{ $stay['type'] }}</span>
                                                                        @endif
                                                                        @if (! empty($stay['name']))
                                                                            <strong>{{ $stay['name'] }}</strong>
                                                                        @endif
                                                                        @if (! empty($stay['location']))
                                                                            <span>{{ $stay['location'] }}</span>
                                                                        @endif
                                                                    </div>
                                                                    @if (! empty($stay['url']))
                                                                        <a href="{{ $stay['url'] }}" class="itinerary-day__stay-link">View <i class="flaticon-arrow"></i></a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </details>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="link-box">
                                        <a href="{{ route('book-now', ['package' => $package['slug']]) }}"><span>Book This Package</span><i class="flaticon-arrow"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="tab active-tab tour-section" id="tab-gallery">
                                <div class="tour-gallery-content">
                                    <h2>Tour Gallery</h2>
                                    <p class="mb_30">Photos from the {{ $package['name'] }} experience.</p>
                                    @if (! empty($package['gallery']))
                                        <div class="tour-gallery-slider">
                                            <div class="single-item-carousel">
                                                <div class="swiper-wrapper">
                                                    @foreach ($package['gallery'] as $galleryImage)
                                                        <div class="swiper-slide">
                                                            <figure class="image-box">
                                                                <a href="{{ $galleryImage }}" class="lightbox-image" data-fancybox="tour-gallery">
                                                                    <img src="{{ $galleryImage }}" alt="{{ $package['name'] }} gallery">
                                                                </a>
                                                            </figure>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="single-column-pagination"></div>
                                        </div>
                                    @else
                                        <p>Gallery photos will appear here once available for this package.</p>
                                    @endif
                                </div>
                            </div>
                            <div class="tab active-tab tour-section" id="tab-3">
                                <div class="includes-content">
                                    <div class="single-item">
                                        <div class="title-text">
                                            <h2>Tour <span>Includes</span></h2>
                                            <p>Items covered in the package.</p>
                                        </div>
                                        <div class="list-text">
                                            <ul class="list-item">
                                                @foreach ($package['includes'] as $include)
                                                    <li><i class="flaticon-check"></i><span>{{ $include }}</span></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="single-item">
                                        <div class="title-text">
                                            <h2>Tour <span>Excludes</span></h2>
                                            <p>Items not covered in the package.</p>
                                        </div>
                                        <div class="list-text">
                                            <ul class="list-item">
                                                @foreach ($package['excludes'] as $exclude)
                                                    <li><i class="flaticon-failed light"></i><span>{{ $exclude }}</span></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab active-tab tour-section" id="tab-4">
                                <div class="faq-content">
                                    <h2>Frequently Asked Questions</h2>
                                    <ul class="accordion-box accordion-style-one">
                                        <li class="accordion block active-block">
                                            <div class="acc-btn active">
                                                <div class="icon-box"><i class="flaticon-plus"></i></div>
                                                <h4>How do I book this tour?</h4>
                                            </div>
                                            <div class="acc-content current">
                                                <div class="text">
                                                    <p>Send an enquiry from this page or contact us directly. We will confirm availability, suggest dates, and share a tailored quote for {{ $package['name'] }}.</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="accordion block">
                                            <div class="acc-btn">
                                                <div class="icon-box"><i class="flaticon-plus"></i></div>
                                                <h4>Can I customize the itinerary?</h4>
                                            </div>
                                            <div class="acc-content">
                                                <div class="text">
                                                    <p>Yes. Most safari and trek plans can be adjusted for pace, lodging style, and add-on destinations across the Northern Circuit.</p>
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
                                                    <p>International flights are not included. We can help with airport transfers and domestic flight recommendations when needed.</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab active-tab tour-section" id="tab-5">
                                <div class="booking-form">
                                    <h2>Enquire About This Tour</h2>
                                    <form action="{{ route('contact') }}" method="get">
                                        <div class="row clearfix">
                                            <div class="col-lg-6 col-md-6 col-sm-12 single-column">
                                                <div class="form-group">
                                                    <label>First Name</label>
                                                    <input type="text" name="fname" placeholder="First name" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 single-column">
                                                <div class="form-group">
                                                    <label>Last name</label>
                                                    <input type="text" name="lname" placeholder="Last name" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 single-column">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" name="email" placeholder="Enter email address" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12 single-column">
                                                <div class="form-group">
                                                    <label>Phone</label>
                                                    <input type="text" name="phone" placeholder="Your ph num" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 single-column">
                                                <div class="form-group">
                                                    <label>Subject</label>
                                                    <input type="text" name="subject" value="{{ $package['name'] }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 single-column">
                                                <div class="form-group">
                                                    <label>Message</label>
                                                    <textarea name="message" placeholder="Message goes here"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 single-column">
                                                <div class="message-btn">
                                                    <button type="submit" class="theme-btn"><span>Send Enquiry</span><i class="flaticon-arrow"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 sidebar-side">
                        <div class="tour-sidebar">
                            <div class="package-widget mb_30">
                                <span class="text">Experience</span>
                                <h3 class="special-text">{{ $package['category'] }}<span> / {{ $package['duration'] }}</span></h3>
                                <div class="booking-form">
                                    <div class="message-btn">
                                        <a href="{{ route('book-now', ['package' => $package['slug']]) }}" class="theme-btn"><span>Book Now</span><i class="flaticon-arrow"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="contact-widget mb_30">
                                <div class="bg-image" style="background-image: url('{{ $package['gallery'][0] ?? ($package['images'][1] ?? $package['image']) }}');"></div>
                                <h4>Tanzania Safaris</h4>
                                <h3>Traveler’s Guide Corner.</h3>
                                <p>Plan smarter. Travel better.</p>
                                <div class="inner-box">
                                    <div class="icon-box"><img src="/aventour/assets/images/icons/icon-73.png" alt=""></div>
                                    <div class="inner">
                                        <span class="text">Still deciding where to travel?</span>
                                        <a href="tel:+255769079298">+255 769 079 298</a>
                                    </div>
                                </div>
                            </div>
                            <div class="share-box">
                                <a href="{{ route('book-now', ['package' => $package['slug']]) }}"><i class="fas fa-share-alt"></i><span>Book This Tour</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($relatedPackages->isNotEmpty())
        <section class="package-section pb_110">
            <div class="auto-container">
                <div class="sec-title centred mb_40">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>More Experiences</span></div>
                    <h2>Related Packages</h2>
                </div>
                <div class="row clearfix">
                    @foreach ($relatedPackages as $related)
                        @include('partials.aventour.package-card', [
                            'package' => $related,
                            'columnClass' => 'col-lg-4 col-md-6 col-sm-12 package-block',
                        ])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@push('scripts')
<script>
(function ($) {
    const $spy = $('.tour-scroll-spy');
    if (!$spy.length) {
        return;
    }

    const $navBar = $spy.find('.tour-scroll-nav-bar');
    const $nav = $spy.find('.tour-scroll-nav');
    const $links = $nav.find('a');
    const $sections = $spy.find('.tour-section');
    const $prev = $navBar.find('.tour-scroll-nav-prev');
    const $next = $navBar.find('.tour-scroll-nav-next');
    const stickyOffset = 110;
    const scrollStep = 180;
    let activeId = null;

    function updateArrowState() {
        const el = $nav.get(0);

        if (!el) {
            return;
        }

        const maxScroll = el.scrollWidth - el.clientWidth;
        const atStart = el.scrollLeft <= 2;
        const atEnd = el.scrollLeft >= maxScroll - 2;

        $prev.toggleClass('is-disabled', atStart).prop('disabled', atStart);
        $next.toggleClass('is-disabled', atEnd || maxScroll <= 0).prop('disabled', atEnd || maxScroll <= 0);
    }

    function scrollNavBy(amount) {
        const el = $nav.get(0);

        if (!el) {
            return;
        }

        el.scrollBy({ left: amount, behavior: 'smooth' });
    }

    function scrollActiveIntoView() {
        const el = $nav.get(0);
        const $active = $nav.find('.tab-btn.active-btn').first();

        if (!el || !$active.length || window.innerWidth > 991) {
            return;
        }

        const active = $active.get(0);
        const left = active.offsetLeft;
        const right = left + active.offsetWidth;
        const viewLeft = el.scrollLeft;
        const viewRight = viewLeft + el.clientWidth;
        const pad = 24;

        if (left < viewLeft + pad) {
            el.scrollTo({ left: Math.max(0, left - pad), behavior: 'smooth' });
        } else if (right > viewRight - pad) {
            el.scrollTo({ left: right - el.clientWidth + pad, behavior: 'smooth' });
        }
    }

    $prev.on('click', function () {
        scrollNavBy(-scrollStep);
    });

    $next.on('click', function () {
        scrollNavBy(scrollStep);
    });

    $nav.on('scroll', updateArrowState);
    $(window).on('resize', function () {
        updateArrowState();
        scrollActiveIntoView();
    });

    $links.on('click', function (event) {
        event.preventDefault();

        const target = $(this).attr('href');
        const $section = $(target);

        if (!$section.length) {
            return;
        }

        $('html, body').animate({
            scrollTop: $section.offset().top - stickyOffset - 20
        }, 450);
    });

    function setActive(id) {
        if (activeId === id) {
            return;
        }

        activeId = id;
        $nav.find('.tab-btn').removeClass('active-btn');
        $nav.find('a[href="#' + id + '"]').closest('.tab-btn').addClass('active-btn');
        scrollActiveIntoView();
        updateArrowState();
    }

    function onScroll() {
        const scrollPos = $(window).scrollTop() + stickyOffset + 40;
        let current = $sections.first().attr('id');

        $sections.each(function () {
            if ($(this).offset().top <= scrollPos) {
                current = $(this).attr('id');
            }
        });

        if (current) {
            setActive(current);
        }
    }

    $(window).on('scroll', onScroll);
    onScroll();
    updateArrowState();
})(jQuery);
</script>
@endpush
