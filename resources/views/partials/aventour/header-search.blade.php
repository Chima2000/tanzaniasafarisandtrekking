@php
    $searchSuggestUrl = route('search.suggest');
    $searchIndexUrl = route('search.index');
@endphp
<div class="header-site-search" data-header-search data-suggest-url="{{ $searchSuggestUrl }}" data-index-url="{{ $searchIndexUrl }}">
    <button
        type="button"
        class="header-site-search__toggle header-top-icon-link"
        data-header-search-toggle
        aria-expanded="false"
        aria-controls="header-site-search-panel"
        aria-label="Open site search"
    >
        <i class="flaticon-zoom" aria-hidden="true"></i>
        <span class="header-top-text">Search</span>
    </button>

    <div
        id="header-site-search-panel"
        class="header-site-search__panel"
        data-header-search-panel
        hidden
    >
        <form class="header-site-search__form" action="{{ $searchIndexUrl }}" method="get" role="search">
            <label class="visually-hidden" for="header-site-search-input">Search the site</label>
            <div class="header-site-search__field">
                <input
                    id="header-site-search-input"
                    type="search"
                    name="q"
                    value=""
                    placeholder="Search packages, destinations, blog…"
                    autocomplete="off"
                    data-header-search-input
                >
                <button type="submit" aria-label="Search">
                    <i class="flaticon-zoom"></i>
                </button>
            </div>
        </form>

        <div class="header-site-search__results" data-header-search-results hidden>
            <div class="header-site-search__status" data-header-search-status></div>
            <div class="header-site-search__list" data-header-search-list></div>
            <a
                class="header-site-search__view-all"
                href="{{ $searchIndexUrl }}"
                data-header-search-view-all
                hidden
            >
                View all search results
            </a>
        </div>
    </div>
</div>
