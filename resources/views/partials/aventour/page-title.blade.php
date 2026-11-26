@php
    /** @var string $title */
    /** @var list<array{label: string, url?: string|null}> $crumbs */
    $bg = $bg ?? '/aventour/assets/images/background/page-title.jpg';
@endphp
<section class="page-title centred">
    <div class="bg-layer" style="background-image: url('{{ $bg }}');"></div>
    <div class="auto-container">
        <div class="content-box">
            <h1>{{ $title }}</h1>
            <nav aria-label="Breadcrumb">
                <ul class="bread-crumb">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    @foreach ($crumbs as $crumb)
                        <li aria-hidden="true"><i class="flaticon-arrow-angle-pointing-to-right"></i></li>
                        @if (! empty($crumb['url']))
                            <li><a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a></li>
                        @else
                            <li><span aria-current="page">{{ $crumb['label'] }}</span></li>
                        @endif
                    @endforeach
                </ul>
            </nav>
        </div>
    </div>
</section>
