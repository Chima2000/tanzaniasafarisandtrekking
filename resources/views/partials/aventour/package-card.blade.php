@php
    /** @var array{name: string, slug: string, location: string, duration: string, category: string, rating: string, image: string} $package */
    $columnClass = $columnClass ?? 'col-lg-6 col-md-6 col-sm-12 package-block';
@endphp
<div class="{{ $columnClass }}">
    <div class="package-block-one">
        <div class="inner-box">
            <div class="title-box">
                <div class="location"><i class="flaticon-location"></i><span>{{ $package['location'] }}</span></div>
                <h3><a href="{{ route('packages.show', $package['slug']) }}">{{ $package['name'] }}</a></h3>
            </div>
            <div class="image-box">
                <figure class="image"><img src="{{ $package['image'] }}" alt="{{ $package['name'] }}"></figure>
            </div>
            <div class="lower-content">
                <div class="price-rating">
                    <div class="price"><span class="special-text">{{ $package['category'] }}</span><span class="text"> / Package</span></div>
                    <div class="rating"><i class="flaticon-star"></i><span>{{ $package['rating'] }}</span></div>
                </div>
                <div class="lower-box">
                    <div class="duration-box">
                        <div class="icon-box"><i class="flaticon-calendar-1"></i></div>
                        <div class="text-box">
                            <span>Duration</span>
                            <p>{{ $package['duration'] }}</p>
                        </div>
                    </div>
                    <div class="link"><a href="{{ route('packages.show', $package['slug']) }}"><i class="flaticon-arrow"></i></a></div>
                </div>
            </div>
        </div>
    </div>
</div>
