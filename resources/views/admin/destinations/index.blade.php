@extends('layouts.admin')

@section('title', 'Destinations')

@section('content')
    <div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h4 class="mb-1">Destinations</h4>
            <p class="text-muted mb-0">Places featured on the public destinations pages.</p>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            @if ($destinations instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary">Add destination</a>
                <form action="{{ route('admin.destinations.sync') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">Sync from catalog</button>
                </form>
            @else
                <form action="{{ route('admin.destinations.sync') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">Import from catalog</button>
                </form>
            @endif
        </div>
    </div>

    @if ($destinations instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Destination</th>
                                <th>Region</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($destinations as $destination)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if ($destination->image)
                                                <img src="{{ $destination->image }}" alt="" width="56" height="40" class="rounded" style="object-fit:cover;">
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $destination->full_name ?? $destination->title }}</div>
                                                <div class="text-muted small">{{ $destination->slug }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $destination->region ?: '—' }}</td>
                                    <td>
                                        @if ($destination->is_published)
                                            <span class="badge bg-primary-subtle text-primary">Published</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Draft</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.destinations.edit', $destination) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="{{ route('destinations.show', $destination->slug) }}" class="btn btn-sm btn-light" target="_blank" rel="noopener">Live</a>
                                        <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this destination?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted">No destinations in the database yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $destinations->links() }}</div>
            </div>
        </div>
    @else
        <div class="alert alert-light border mb-4">
            Destinations are still loaded from the website catalog. Import them into the database to edit content here.
        </div>

        <div class="row">
            @forelse ($fallbackDestinations as $destination)
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100">
                        <img src="{{ $destination['image'] }}" class="card-img-top" alt="{{ $destination['title'] }}" style="height:180px;object-fit:cover;">
                        <div class="card-body">
                            <div class="text-muted small mb-1">{{ $destination['region'] ?? 'Tanzania' }} · {{ $destination['places'] }}</div>
                            <h5 class="card-title">{{ $destination['full_name'] ?? $destination['title'] }}</h5>
                            <p class="text-muted mb-3">{{ \Illuminate\Support\Str::limit(strip_tags((string) ($destination['description'] ?? '')), 110) }}</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.destinations.show', $destination['slug']) }}" class="btn btn-sm btn-outline-primary">Details</a>
                                <a href="{{ route('destinations.show', $destination['slug']) }}" class="btn btn-sm btn-light" target="_blank" rel="noopener">View live</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light mb-0">No catalog destinations found.</div>
                </div>
            @endforelse
        </div>
    @endif
@endsection