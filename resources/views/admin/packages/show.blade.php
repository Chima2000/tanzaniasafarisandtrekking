@extends('layouts.admin')

@section('title', $package['name'])

@section('content')
    <div class="page-title-box d-flex align-items-center justify-content-between">
        <div>
            <h4 class="mb-1">{{ $package['name'] }}</h4>
            <p class="text-muted mb-0">{{ $package['category'] }} · {{ $package['duration'] }} · {{ $package['location'] }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.packages.index') }}" class="btn btn-light">Back</a>
            <a href="{{ route('packages.show', $package['slug']) }}" class="btn btn-primary" target="_blank" rel="noopener">Open on website</a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-7">
            <div class="card">
                <div class="card-body">
                    <img src="{{ $package['image'] }}" alt="{{ $package['name'] }}" class="img-fluid rounded mb-3">
                    <h5>Overview</h5>
                    <p>{{ $package['overview'] ?? 'No overview available.' }}</p>

                    <h5 class="mt-4">Includes</h5>
                    <ul>
                        @foreach (($package['includes'] ?? []) as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Gallery ({{ count($package['gallery'] ?? $package['images']) }})</h4></div>
                <div class="card-body pt-0">
                    <div class="row g-2">
                        @foreach (($package['gallery'] ?? $package['images']) as $image)
                            <div class="col-4">
                                <img src="{{ $image }}" alt="" class="img-fluid rounded">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
