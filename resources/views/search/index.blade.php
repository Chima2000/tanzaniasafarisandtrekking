@extends('layouts.aventour')

@section('title', ($query !== '' ? 'Search: '.$query : 'Search').' | Tanzania Safaris and Trekking')
@section('meta_description', $query !== ''
    ? 'Search results for “'.$query.'” across packages, destinations, blog posts, and pages.'
    : 'Search Tanzania Safaris and Trekking packages, destinations, blog posts, and pages.')
@section('canonical', route('search.index', $query !== '' ? ['q' => $query] : []))
@section('meta_robots', 'noindex, follow')

@section('content')
    @include('partials.aventour.page-title', [
        'title' => $query !== '' ? 'Search results' : 'Search',
        'bg' => $pageBg,
        'crumbs' => [
            ['label' => 'Search'],
        ],
    ])

    <section class="site-search-page sec-pad-3">
        <div class="auto-container">
            <form class="site-search-page__form" action="{{ route('search.index') }}" method="get" role="search">
                <label class="visually-hidden" for="site-search-page-input">Search the site</label>
                <div class="site-search-page__field">
                    <input
                        id="site-search-page-input"
                        type="search"
                        name="q"
                        value="{{ $query }}"
                        placeholder="Search packages, destinations, blog…"
                        autocomplete="off"
                        autofocus
                    >
                    <button type="submit" aria-label="Search">
                        <i class="flaticon-zoom"></i>
                    </button>
                </div>
            </form>

            @if ($query === '')
                <p class="site-search-page__hint">Type a keyword to search the whole site.</p>
            @elseif ($results->isEmpty())
                <p class="site-search-page__hint">No results found for “{{ $query }}”. Try another keyword.</p>
            @else
                <p class="site-search-page__meta">{{ $results->count() }} {{ \Illuminate\Support\Str::plural('result', $results->count()) }} for “{{ $query }}”</p>

                <div class="site-search-page__list">
                    @foreach ($results as $result)
                        <a href="{{ $result['url'] }}" class="site-search-page__item">
                            <div class="site-search-page__thumb">
                                @if (! empty($result['image']))
                                    <img src="{{ $result['image'] }}" alt="">
                                @else
                                    <span class="site-search-page__thumb-fallback" aria-hidden="true">
                                        <i class="flaticon-zoom"></i>
                                    </span>
                                @endif
                            </div>
                            <div class="site-search-page__body">
                                <span class="site-search-page__type">{{ $result['type_label'] }}</span>
                                <h3>{{ $result['title'] }}</h3>
                                @if ($result['description'] !== '')
                                    <p>{{ $result['description'] }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
