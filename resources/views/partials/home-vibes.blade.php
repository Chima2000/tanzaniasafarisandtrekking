<div class="four-item-carousel">
    <div class="swiper-wrapper">
        @foreach ($vibes as $vibe)
            <div class="swiper-slide">
                <div class="vibes-block-one">
                    <div class="title-text"><h3><a href="{{ url('/') }}#packages">{{ $vibe['title'] }}</a></h3></div>
                    <div class="image-box">
                        <figure class="image"><img src="{{ $vibe['image'] }}" alt="{{ $vibe['title'] }}"></figure>
                        <div class="link"><a href="{{ url('/') }}#packages"><i class="flaticon-arrow"></i></a></div>
                    </div>
                    <span class="text">{{ $vibe['count'] }}</span>
                </div>
            </div>
        @endforeach
    </div>
    <div class="four-column-pagination dots-style-one"></div>
    <div class="common-lower-text centred pt_20">
        <p>Discover more Tanzania adventures.</p>
        <div class="link-box"><a href="{{ url('/') }}#packages"><span>View All Packages</span><i class="flaticon-arrow"></i></a></div>
    </div>
</div>
