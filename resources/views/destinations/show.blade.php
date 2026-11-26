@extends('layouts.aventour')

@section('title', $destination['seo_title'])
@section('meta_description', $destination['seo_description'])
@section('meta_keywords', $destination['seo_keywords'])
@section('canonical', route('destinations.show', $destination['slug']))
@section('og_title', $destination['seo_title'])
@section('og_description', $destination['seo_description'])
@section('og_image', absolute_url($destination['image']) ?? '')
@section('twitter_title', $destination['seo_title'])
@section('twitter_description', $destination['seo_description'])
@section('twitter_image', absolute_url($destination['image']) ?? '')

@section('content')
    @include('partials.aventour.page-title', [
        'title' => $destination['full_name'],
        'bg' => $destination['image'],
        'crumbs' => [
            ['label' => 'Destinations', 'url' => route('destinations.index')],
            ['label' => $destination['full_name']],
        ],
    ])

    <section class="destination-guide" id="destination-overview">
        <div class="auto-container">
            <div class="destination-guide__overview">
                <div class="destination-guide__overview-copy">
                    <div class="sec-title">
                        <div class="sub-title"><i class="flaticon-parachuting"></i><span>{{ $destination['region'] }} · {{ $destination['places'] }}</span></div>
                        <h2>{{ $destination['headline'] }}</h2>
                    </div>
                    <div class="destination-guide__meta">
                        <span><i class="flaticon-location"></i>{{ $destination['region'] }}</span>
                        <span><i class="flaticon-calendar-1"></i>{{ $destination['starts'] }}</span>
                        <span><i class="flaticon-parachuting"></i>{{ $destination['places'] }}</span>
                    </div>
                    <x-rich-text class="destination-guide__lead" :content="$destination['description'] ?? null" />
                    <div class="destination-guide__actions">
                        <a href="{{ route('book-now', ['destination' => $destination['slug']]) }}" class="theme-btn"><span>Book This Destination</span><i class="flaticon-arrow"></i></a>
                        <a href="#destination-packages" class="theme-btn secondary-bg"><span>View Matching Tours</span><i class="flaticon-arrow"></i></a>
                    </div>
                </div>
                <div class="destination-guide__overview-media">
                    <figure class="destination-guide__hero-image">
                        <img src="{{ $destination['image'] }}" alt="{{ $destination['full_name'] }} safari and travel guide">
                    </figure>
                    <div class="destination-guide__thumb-row">
                        @foreach (array_slice($galleryImages, 0, 3) as $image)
                            <figure>
                                <img src="{{ $image }}" alt="{{ $destination['title'] }} travel moment {{ $loop->iteration }}">
                            </figure>
                        @endforeach
                    </div>
                </div>
            </div>

            @if (! empty($destination['facts']))
                <div class="destination-guide__facts" id="destination-facts">
                    @foreach ($destination['facts'] as $fact)
                        <article class="destination-guide__fact">
                            <h3>{{ $fact['value'] }}</h3>
                            <p>{{ $fact['label'] }}</p>
                        </article>
                    @endforeach
                </div>
            @endif

            <nav class="destination-guide__toc" aria-label="Destination guide sections">
                <a href="#destination-overview">Overview</a>
                <a href="#destination-story">Story</a>
                <a href="#destination-highlights">Highlights</a>
                <a href="#destination-wildlife">Wildlife</a>
                <a href="#destination-best-time">Best Time</a>
                <a href="#destination-getting-there">Getting There</a>
                <a href="#destination-tips">Tips</a>
                <a href="#destination-faq">FAQ</a>
                <a href="#destination-packages">Tours</a>
            </nav>
        </div>
    </section>

    <section class="destination-guide-section bg-color-1" id="destination-story">
        <div class="auto-container">
            <div class="sec-title mb_30">
                <div class="sub-title"><i class="flaticon-parachuting"></i><span>Destination Story</span></div>
                <h2>The {{ $destination['title'] }} Journey, Told in Chapters</h2>
            </div>

            <div class="destination-guide__story-intro">
                <x-rich-text :content="$destination['story_intro'] ?? null" />
            </div>

            <div class="destination-guide__chapters">
                @foreach ($destination['story_chapters'] ?? [] as $chapter)
                    <article class="destination-guide__chapter">
                        @if ($galleryImages[$loop->index] ?? false)
                            <figure class="destination-guide__chapter-image">
                                <img src="{{ $galleryImages[$loop->index] }}" alt="{{ $chapter['title'] }} — {{ $destination['title'] }}">
                            </figure>
                        @endif
                        <div class="destination-guide__chapter-body">
                            <span class="destination-guide__chapter-label">{{ $chapter['label'] }}</span>
                            <h3>{{ $chapter['title'] }}</h3>
                            <x-rich-text :content="$chapter['body'] ?? null" />
                            @if (! empty($chapter['points']))
                                <ul class="destination-guide__points has-internal-links">
                                    @foreach ($chapter['points'] as $point)
                                        <li>{!! linked($point) !!}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="destination-guide-section" id="destination-highlights">
        <div class="auto-container">
            <div class="sec-title mb_30">
                <div class="sub-title"><i class="flaticon-parachuting"></i><span>{{ $destination['title'] }} Highlights</span></div>
                <h2>What Makes This Place Essential</h2>
                <p class="mt_12">Signature experiences and landscape notes that define {{ $destination['full_name'] }}.</p>
            </div>
            <div class="destination-guide__grid has-internal-links">
                @foreach ($destination['highlights'] ?? [] as $highlight)
                    <article class="destination-guide__card">
                        <div class="destination-guide__card-icon"><img src="/aventour/assets/images/icons/icon-8.png" alt=""></div>
                        <h3>{!! linked($highlight) !!}</h3>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="destination-guide-section bg-color-1" id="destination-wildlife">
        <div class="auto-container">
            <div class="row clearfix align-items-center">
                <div class="col-lg-5 col-md-12 col-sm-12">
                    <div class="sec-title mb_30">
                        <div class="sub-title"><i class="flaticon-parachuting"></i><span>Wildlife & Nature</span></div>
                        <h2>Species and Nature Moments</h2>
                        <p class="mt_12">A full list of the animals and nature notes travelers commonly encounter around {{ $destination['title'] }}.</p>
                    </div>
                    <figure class="destination-guide__side-image">
                        <img src="{{ $galleryImages[1] ?? $destination['image'] }}" alt="Wildlife around {{ $destination['full_name'] }}">
                    </figure>
                </div>
                <div class="col-lg-7 col-md-12 col-sm-12">
                    <ul class="destination-guide__wildlife">
                        @foreach ($destination['wildlife'] ?? [] as $animal)
                            <li>{{ $animal }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="destination-guide-section" id="destination-best-time">
        <div class="auto-container">
            <div class="destination-guide__split">
                <div>
                    <div class="sec-title mb_20">
                        <div class="sub-title"><i class="flaticon-parachuting"></i><span>Best Time to Visit</span></div>
                        <h2>When {{ $destination['title'] }} Shines</h2>
                    </div>
                    <x-rich-text :content="$destination['best_time'] ?? null" />
                </div>
                <figure>
                    <img src="{{ $galleryImages[2] ?? $destination['image'] }}" alt="Best season to visit {{ $destination['title'] }}">
                </figure>
            </div>
        </div>
    </section>

    <section class="destination-guide-section bg-color-1" id="destination-getting-there">
        <div class="auto-container">
            <div class="destination-guide__split destination-guide__split--reverse">
                <div>
                    <div class="sec-title mb_20">
                        <div class="sub-title"><i class="flaticon-parachuting"></i><span>Getting There</span></div>
                        <h2>How Travelers Reach {{ $destination['title'] }}</h2>
                    </div>
                    <x-rich-text :content="$destination['getting_there'] ?? null" />
                </div>
                <figure>
                    <img src="{{ $galleryImages[3] ?? $destination['image'] }}" alt="Travel access to {{ $destination['full_name'] }}">
                </figure>
            </div>
        </div>
    </section>

    <section class="destination-guide-section" id="destination-tips">
        <div class="auto-container">
            <div class="sec-title mb_30">
                <div class="sub-title"><i class="flaticon-parachuting"></i><span>Local Travel Tips</span></div>
                <h2>Practical Advice Before You Go</h2>
                <p class="mt_12">Field-tested notes from local guides to help you enjoy {{ $destination['full_name'] }} with better timing and comfort.</p>
            </div>
            <div class="destination-guide__tips has-internal-links">
                @foreach ($destination['tips'] ?? [] as $tip)
                    <article class="destination-guide__tip">
                        <span class="destination-guide__tip-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <p>{!! linked($tip) !!}</p>
                    </article>
                @endforeach
            </div>
            <div class="destination-guide__actions destination-guide__actions--center mt_40">
                <a href="{{ route('book-now', ['destination' => $destination['slug']]) }}" class="theme-btn"><span>Plan This With a Local Expert</span><i class="flaticon-arrow"></i></a>
            </div>
        </div>
    </section>

    <section class="faq-section sec-pad destination-faq-section" id="destination-faq">
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
                        @foreach ($destination['faqs'] ?? [] as $faq)
                            <li class="accordion block {{ $loop->first ? 'active-block' : '' }}">
                                <div class="acc-btn {{ $loop->first ? 'active' : '' }}">
                                    <div class="icon-box"><i class="flaticon-plus"></i></div>
                                    <h4>{{ $faq['question'] }}</h4>
                                </div>
                                <div class="acc-content {{ $loop->first ? 'current' : '' }}">
                                    <div class="text">
                                        <x-rich-text :content="$faq['answer'] ?? null" />
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="package-section pb_90" id="destination-packages">
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
        <section class="destination-guide-section bg-color-1 pb_110">
            <div class="auto-container">
                <div class="sec-title centred mb_40">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>Keep Exploring</span></div>
                    <h2>Related Tanzania Destinations</h2>
                </div>
                <div class="row clearfix">
                    @foreach ($relatedDestinations as $related)
                        <div class="col-lg-6 col-md-12 col-sm-12 mb_30">
                            <a href="{{ route('destinations.show', $related['slug']) }}" class="destination-guide__related">
                                <figure>
                                    <img src="{{ $related['image'] }}" alt="{{ $related['full_name'] }}">
                                </figure>
                                <div>
                                    <span class="destination-guide__related-region">{{ $related['region'] }}</span>
                                    <h3>{{ $related['full_name'] }}</h3>
                                    <p>{{ \Illuminate\Support\Str::limit(strip_tags((string) ($related['description'] ?? '')), 140) }}</p>
                                    <span class="destination-guide__related-meta">{{ $related['places'] }} · {{ $related['starts'] }}</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
