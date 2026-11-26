@php
    $reviewsPage = $reviewsPageContent
        ?? ($c ?? null)
        ?? (app(\App\Support\ManagedPageContent::class)->for('home')['content'] ?? []);
    $defaultTripadvisor = [
        [
            'author' => 'Cylris G',
            'title' => 'Hike',
            'quote' => 'We had a very good day with a hike, a visit to a traditional village with coffee preparation and tasting, then the guide took us to town and accompanied us to do what we wanted. I recommend Jerry who is great.',
        ],
        [
            'author' => 'Jonah T',
            'title' => 'Kilimanjaro visit',
            'quote' => 'The experience was so nice, we went to Kilimanjaro Gates and waterfall we had fun.',
        ],
        [
            'author' => 'Romaric L',
            'title' => 'An unforgettable adventure',
            'quote' => 'An absolutely incredible experience from start to finish! Tanzania Safaris And Trekking exceeded every expectation I had. The organization was flawless, the scenery was breathtaking, and the whole adventure felt truly magical. But what made this trip truly unforgettable was our guide Jerry. From day one, his passion for the land, the wildlife, and the culture was contagious. He was knowledgeable, attentive, patient, and always went above and beyond to make sure we had the best possible experience. He knew exactly when and where to spot animals, shared fascinating stories, and made every moment feel special. Beyond being an outstanding professional, Jerry is just a wonderful human being. His warmth and humor made the whole group feel like family. By the end of the trip, he wasn\'t just our guide anymore — he had become a true friend, and that is something I will treasure forever. If you\'re looking for an adventure in Tanzania, look no further. And if Jerry is your guide, consider yourself incredibly lucky!',
        ],
        [
            'author' => 'TripAdvisor traveler',
            'title' => 'Excellent',
            'quote' => 'Jerry is so nice to us, he can be trusted. Thanks for a good day and a smooth experience with Tanzania Safaris and Trekking.',
        ],
    ];
    $defaultGoogle = [
        [
            'author' => 'Clara L',
            'title' => 'Great day out',
            'quote' => 'We had an incredible day. We went on a well-explained hike to see the magnificent waterfall, followed by a delicious lunch and the discovery of coffee. The guides were incredibly kind to us and adapted the day according to our wishes. I recommend it 100%. Thanks again.',
        ],
        [
            'author' => 'Google reviewer',
            'title' => 'Trusted guide',
            'quote' => 'Jerry is so nice to us, he can be trusted. Everything felt safe, friendly, and well looked after from start to finish.',
        ],
        [
            'author' => 'Google reviewer',
            'title' => 'Meru trek with Jerry & Gabby',
            'quote' => 'I did the 3-Day Meru Trek with Jerry and Gabby as my guides and I really enjoyed every moment of the adventure. The pacing was careful, the support was excellent, and the whole trip felt well organized and personal.',
        ],
        [
            'author' => 'Google reviewer',
            'title' => 'Local experts',
            'quote' => 'A well-planned safari and trek experience with friendly local guides. Clear communication, careful logistics, and a warm welcome made the journey easy to enjoy.',
        ],
    ];

    $decodeReviews = static function (?string $json, array $fallback): array {
        if (! is_string($json) || trim($json) === '') {
            return $fallback;
        }

        $decoded = json_decode($json, true);

        return is_array($decoded) && $decoded !== [] ? $decoded : $fallback;
    };

    $tripadvisorReviews = $decodeReviews($reviewsPage['reviews_tripadvisor_json'] ?? null, $defaultTripadvisor);
    $googleReviews = $decodeReviews($reviewsPage['reviews_google_json'] ?? null, $defaultGoogle);
@endphp

<section class="platform-reviews-section" id="traveler-reviews" aria-labelledby="platform-reviews-heading" data-cms-section="platform-reviews">
    <div class="auto-container">
        <div class="sec-title centred">
            <div class="sub-title"><i class="flaticon-parachuting"></i><span data-cms-field="reviews_subtitle">{{ $reviewsPage['reviews_subtitle'] ?? 'Traveler Reviews' }}</span></div>
            <h2 id="platform-reviews-heading" data-cms-field="reviews_heading">{{ $reviewsPage['reviews_heading'] ?? 'Rated Excellent on TripAdvisor & Google' }}</h2>
            <x-rich-text class="mt_12" data-cms-field="reviews_body" :content="$reviewsPage['reviews_body'] ?? null" fallback="Choose a tab, then swipe through reviews without leaving this page." />
        </div>

        <div class="platform-reviews-tabs" role="tablist" aria-label="Review platforms">
            <button
                type="button"
                class="platform-reviews-tab platform-reviews-tab--tripadvisor is-active"
                role="tab"
                id="reviews-tab-tripadvisor"
                aria-selected="true"
                aria-controls="reviews-panel-tripadvisor"
                data-reviews-tab="tripadvisor"
            >
                <span class="ta-brand-row">
                    <img
                        src="https://www.tripadvisor.com/img/cdsi/img2/branding/v2/Tripadvisor_lockup_horizontal_secondary_registered-11900-2.svg"
                        alt="Tripadvisor"
                        width="118"
                        height="26"
                        loading="lazy"
                    >
                </span>
                <span class="ta-aggregate">
                    @include('partials.aventour.tripadvisor-ollie', ['height' => 28])
                    @include('partials.aventour.tripadvisor-bubbles', ['rating' => 5, 'size' => 'lg'])
                    <span class="ta-aggregate__meta">
                        <strong>5.0</strong>
                        <span>10 reviews</span>
                    </span>
                </span>
            </button>
            <button
                type="button"
                class="platform-reviews-tab"
                role="tab"
                id="reviews-tab-google"
                aria-selected="false"
                aria-controls="reviews-panel-google"
                data-reviews-tab="google"
            >
                <span class="platform-reviews-tab__google">
                    <svg viewBox="0 0 24 24" aria-hidden="true" width="20" height="20">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Google Reviews
                </span>
                <span class="platform-reviews-tab__score">4.9 · Excellent</span>
            </button>
        </div>

        <div
            class="platform-reviews-panel is-active"
            id="reviews-panel-tripadvisor"
            role="tabpanel"
            aria-labelledby="reviews-tab-tripadvisor"
            data-reviews-panel="tripadvisor"
        >
            <div class="platform-reviews-carousel-wrap">
                <div class="platform-reviews-carousel swiper" data-reviews-carousel="tripadvisor">
                    <div class="swiper-wrapper">
                        @foreach ($tripadvisorReviews as $review)
                            <div class="swiper-slide">
                                <article class="platform-review-card platform-review-card--tripadvisor">
                                    <div class="platform-review-card__top platform-review-card__top--tripadvisor">
                                        <span class="ta-card-brand">
                                            @include('partials.aventour.tripadvisor-ollie', ['height' => 22])
                                            <span>Tripadvisor</span>
                                        </span>
                                        @include('partials.aventour.tripadvisor-bubbles', ['rating' => 5, 'size' => 'md'])
                                    </div>
                                    <h3 class="platform-review-card__title">{{ $review['title'] }}</h3>
                                    <div class="platform-review-card__quote rich-text">“{!! rich($review['quote'] ?? null) !!}”</div>
                                    <button type="button" class="platform-review-card__toggle" hidden>Show more</button>
                                    <div class="platform-review-card__author">
                                        <strong>{{ $review['author'] }}</strong>
                                        <span>A Tripadvisor traveler review</span>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="platform-reviews-nav">
                    <button type="button" class="platform-reviews-prev" data-reviews-prev="tripadvisor" aria-label="Previous Tripadvisor reviews">
                        <i class="flaticon-arrow-1"></i>
                    </button>
                    <div class="platform-reviews-pagination" data-reviews-pagination="tripadvisor"></div>
                    <button type="button" class="platform-reviews-next" data-reviews-next="tripadvisor" aria-label="Next Tripadvisor reviews">
                        <i class="flaticon-arrow"></i>
                    </button>
                </div>
            </div>
        </div>

        <div
            class="platform-reviews-panel"
            id="reviews-panel-google"
            role="tabpanel"
            aria-labelledby="reviews-tab-google"
            data-reviews-panel="google"
            hidden
        >
            <div class="platform-reviews-carousel-wrap">
                <div class="platform-reviews-carousel swiper" data-reviews-carousel="google">
                    <div class="swiper-wrapper">
                        @foreach ($googleReviews as $review)
                            <div class="swiper-slide">
                                <article class="platform-review-card platform-review-card--google">
                                    <div class="platform-review-card__top">
                                        <span class="platform-review-card__badge platform-review-card__badge--google">
                                            <svg viewBox="0 0 24 24" aria-hidden="true" width="16" height="16">
                                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                            </svg>
                                            Google
                                        </span>
                                        <div class="platform-reviews-list__stars" aria-hidden="true">
                                            <i class="flaticon-star"></i><i class="flaticon-star"></i><i class="flaticon-star"></i><i class="flaticon-star"></i><i class="flaticon-star"></i>
                                        </div>
                                    </div>
                                    <h3 class="platform-review-card__title">{{ $review['title'] }}</h3>
                                    <div class="platform-review-card__quote rich-text">“{!! rich($review['quote'] ?? null) !!}”</div>
                                    <button type="button" class="platform-review-card__toggle" hidden>Show more</button>
                                    <div class="platform-review-card__author">
                                        <strong>{{ $review['author'] }}</strong>
                                        <span>Google review</span>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="platform-reviews-nav">
                    <button type="button" class="platform-reviews-prev" data-reviews-prev="google" aria-label="Previous Google reviews">
                        <i class="flaticon-arrow-1"></i>
                    </button>
                    <div class="platform-reviews-pagination" data-reviews-pagination="google"></div>
                    <button type="button" class="platform-reviews-next" data-reviews-next="google" aria-label="Next Google reviews">
                        <i class="flaticon-arrow"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
