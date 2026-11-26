@extends('layouts.admin')

@section('title', 'Packages')

@section('content')
    <div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h4 class="mb-1">Packages</h4>
            <p class="text-muted mb-0">Safari, Kilimanjaro, and day-trip packages shown on the public site.</p>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            @if ($packages instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">Add package</a>
                <form action="{{ route('admin.packages.sync') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">Sync from catalog</button>
                </form>
            @else
                <form action="{{ route('admin.packages.sync') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">Import from catalog</button>
                </form>
            @endif
        </div>
    </div>

    @if ($packages instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Package</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($packages as $package)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @if ($package->image)
                                                <img src="{{ $package->image }}" alt="" width="56" height="40" class="rounded object-fit-cover" style="object-fit:cover;">
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $package->name }}</div>
                                                <div class="text-muted small">{{ $package->slug }} · {{ $package->duration }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $package->category ?: '—' }}</td>
                                    <td>
                                        @if ($package->is_published)
                                            <span class="badge bg-primary-subtle text-primary">Published</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Draft</span>
                                        @endif
                                        @if ($package->is_featured)
                                            <span class="badge bg-warning-subtle text-warning">Featured</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="{{ route('packages.show', $package->slug) }}" class="btn btn-sm btn-light" target="_blank" rel="noopener">Live</a>
                                        <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this package?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted">No packages in the database yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $packages->links() }}</div>
            </div>
        </div>
    @else
        <div class="alert alert-light border mb-4">
            Packages are still loaded from the website catalog files. Import them into the database to edit content here.
        </div>

        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="{{ route('admin.packages.index') }}" class="btn btn-sm {{ $selectedCategory === 'all' ? 'btn-primary' : 'btn-light' }}">All</a>
            @foreach ($categories as $category)
                <a href="{{ route('admin.packages.index', ['category' => $category['label']]) }}" class="btn btn-sm {{ $selectedCategory === $category['label'] ? 'btn-primary' : 'btn-light' }}">
                    {{ $category['label'] }} ({{ $category['count'] }})
                </a>
            @endforeach
        </div>

        <div class="row">
            @forelse ($fallbackPackages as $package)
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100">
                        <img src="{{ $package['image'] }}" class="card-img-top" alt="{{ $package['name'] }}" style="height:180px;object-fit:cover;">
                        <div class="card-body">
                            <div class="text-muted small mb-1">{{ $package['category'] }} · {{ $package['duration'] }}</div>
                            <h5 class="card-title">{{ $package['name'] }}</h5>
                            <p class="text-muted mb-3">{{ $package['location'] }} · {{ $package['photo_count'] }} photos</p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('admin.packages.show', $package['slug']) }}" class="btn btn-sm btn-outline-primary">Details</a>
                                <a href="{{ route('packages.show', $package['slug']) }}" class="btn btn-sm btn-light" target="_blank" rel="noopener">View live</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light mb-0">No packages found for this filter.</div>
                </div>
            @endforelse
        </div>
    @endif
@endsection