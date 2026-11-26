@extends('layouts.admin')

@section('title', 'Sitemap')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="mb-1">XML Sitemap</h4>
                    <p class="text-muted mb-0">Generate the sitemap, then submit the URL in Google Search Console.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @if ($exists)
                        <form method="post" action="{{ route('admin.sitemap.regenerate') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Regenerate</button>
                        </form>
                    @else
                        <form method="post" action="{{ route('admin.sitemap.generate') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">Generate</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6 col-md-4 col-xl-2 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="fs-3 fw-bold">{{ $summary['total'] }}</div>
                    <div class="text-muted small">Total URLs</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="fs-3 fw-bold">{{ $summary['pages'] }}</div>
                    <div class="text-muted small">Core pages</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="fs-3 fw-bold">{{ $summary['destinations'] }}</div>
                    <div class="text-muted small">Destinations</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="fs-3 fw-bold">{{ $summary['packages'] }}</div>
                    <div class="text-muted small">Packages</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="fs-3 fw-bold">{{ $summary['posts'] }}</div>
                    <div class="text-muted small">Blog posts</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="fs-3 fw-bold">{{ $summary['categories'] }}</div>
                    <div class="text-muted small">Package categories</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-3">
            <div class="card h-100">
                <div class="card-header"><h4 class="card-title mb-0">Google Search Console link</h4></div>
                <div class="card-body pt-0">
                    <p class="text-muted small">Copy this URL and paste it under Indexing → Sitemaps.</p>
                    <div class="input-group">
                        <input id="sitemapUrl" type="text" class="form-control" value="{{ $sitemapUrl }}" readonly>
                        <button type="button" class="btn btn-outline-secondary" id="copySitemapUrl" data-url="{{ $sitemapUrl }}">Copy</button>
                        <a href="{{ $sitemapUrl }}" target="_blank" rel="noopener" class="btn btn-outline-primary">Open</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-3">
            <div class="card h-100">
                <div class="card-header"><h4 class="card-title mb-0">Status</h4></div>
                <div class="card-body pt-0">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">File</span>
                        @if ($exists)
                            <span class="badge text-bg-success">Generated</span>
                        @else
                            <span class="badge text-bg-warning">Not generated</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Last generated</span>
                        <span>
                            {{ $generatedAt ? $generatedAt->timezone(config('app.timezone'))->format('M j, Y g:i A') : '—' }}
                        </span>
                    </div>
                    <p class="text-muted small mb-0">
                        Visitors and Google can request <code>/sitemap.xml</code> even before you generate a snapshot.
                        Generating stores a controlled file you can refresh after publishing content.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('copySitemapUrl')?.addEventListener('click', function () {
    const url = this.getAttribute('data-url') || '';
    const input = document.getElementById('sitemapUrl');
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url);
        return;
    }
    if (input) {
        input.focus();
        input.select();
        document.execCommand('copy');
    }
});
</script>
@endpush
