@extends('layouts.admin')

@section('title', $destination['title'])

@section('content')
    <div class="page-title-box d-flex align-items-center justify-content-between">
        <div>
            <h4 class="mb-1">{{ $destination['full_name'] ?? $destination['title'] }}</h4>
            <p class="text-muted mb-0">{{ $destination['region'] ?? 'Tanzania' }} · {{ $destination['places'] }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.destinations.index') }}" class="btn btn-light">Back</a>
            <a href="{{ route('destinations.show', $destination['slug']) }}" class="btn btn-primary" target="_blank" rel="noopener">Open on website</a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-7">
            <div class="card">
                <div class="card-body">
                    <img src="{{ $destination['image'] }}" alt="{{ $destination['title'] }}" class="img-fluid rounded mb-3">
                    <p>{{ $destination['description'] ?? '' }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Linked packages ({{ $packages->count() }})</h4></div>
                <div class="card-body pt-0">
                    <div class="list-group list-group-flush">
                        @forelse ($packages as $package)
                            <a href="{{ route('admin.packages.show', $package['slug']) }}" class="list-group-item list-group-item-action px-0 d-flex align-items-center gap-3">
                                <img src="{{ $package['image'] }}" alt="" class="admin-thumb">
                                <div>
                                    <div class="fw-semibold">{{ $package['name'] }}</div>
                                    <div class="text-muted small">{{ $package['duration'] }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="text-muted py-3">No linked packages.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
