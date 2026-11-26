@php
    /** @var array{intro_eyebrow: string, intro_heading: string, intro_lead: string, intro_body: string, intro_points: list<array{title: string, body: string}>} $intro */
    $introImage = $introImage ?? ($pageBg ?? '/aventour/assets/images/background/page-title.jpg');
@endphp

<section class="package-category-intro">
    <div class="auto-container">
        <div class="package-category-intro__grid">
            <div class="package-category-intro__copy">
                <div class="sec-title">
                    <div class="sub-title"><i class="flaticon-parachuting"></i><span>{{ $intro['intro_eyebrow'] }}</span></div>
                    <h2>{{ $intro['intro_heading'] }}</h2>
                </div>

                <div class="package-category-intro__text">
                    <x-rich-text class="package-category-intro__lead" :content="$intro['intro_lead'] ?? null" />
                    <x-rich-text :content="$intro['intro_body'] ?? null" />
                </div>

                @if (! empty($intro['intro_points']))
                    <div class="package-category-intro__points">
                        @foreach ($intro['intro_points'] as $point)
                            <article class="package-category-intro__point">
                                <h3>{{ $point['title'] }}</h3>
                                <x-rich-text :content="$point['body'] ?? null" />
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="package-category-intro__media" aria-hidden="false">
                <div class="package-category-intro__sticky">
                    <figure class="package-category-intro__figure">
                        <img src="{{ $introImage }}" alt="{{ $intro['intro_heading'] }}">
                    </figure>
                </div>
            </aside>
        </div>
    </div>
</section>
