<div class="row clearfix">
    @foreach ($vibes as $vibe)
        @php
            $categoryHref = match ($vibe['title']) {
                'Kilimanjaro Treks' => route('packages.index', ['category' => 'Trekking']),
                'Day Adventures' => route('packages.index', ['category' => 'Day Trip']),
                'Cultural Experiences' => route('packages.index', ['category' => 'Cultural']),
                default => route('packages.index', ['category' => 'Safari']),
            };
        @endphp
        <div class="col-lg-3 col-md-6 col-sm-12 categories-block">
            <div class="categories-block-one">
                <div class="title-box">
                    <h3><a href="{{ $categoryHref }}">{{ $vibe['title'] }}</a></h3>
                </div>
                <figure class="image-box"><img src="{{ $vibe['image'] }}" alt="{{ $vibe['title'] }}"></figure>
                <div class="lower-content">
                    <p>{{ $vibe['count'] }}</p>
                    <span class="special-text price">Tanzania</span>
                    <div class="link"><a href="{{ $categoryHref }}"><i class="flaticon-arrow"></i></a></div>
                </div>
            </div>
        </div>
    @endforeach
</div>
