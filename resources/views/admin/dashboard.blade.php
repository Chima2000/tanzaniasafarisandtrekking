@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-4">
        <div class="col-md-12 col-lg-4">
            <div class="d-flex flex-column gap-4 h-100">
                <div class="card mb-0">
                    <div class="card-body border-dashed-bottom pb-3">
                        <div class="row d-flex justify-content-between">
                            <div class="col-auto">
                                <div class="d-flex justify-content-center align-items-center thumb-xl border border-secondary rounded-circle">
                                    <i class="icofont-calendar h1 align-self-center mb-0 text-secondary"></i>
                                </div>
                                <h5 class="mt-2 mb-0 fs-14">Bookings</h5>
                            </div>
                            <div class="col align-self-center">
                                <div id="line-1" class="apex-charts float-end"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row d-flex justify-content-center">
                            <div class="col-12 col-md-6">
                                <h2 class="fs-22 mt-0 mb-1 fw-bold">{{ $bookingCount }}</h2>
                                <p class="mb-0 text-truncate text-muted">
                                    <span class="text-success"><i class="mdi mdi-trending-up"></i>{{ $newBookingCount }}</span> new requests
                                </p>
                            </div>
                            <div class="col-12 col-md-6 align-self-center text-start text-md-end">
                                <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary btn-sm px-2 mt-2 mt-md-0">View Report</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-0">
                    <div class="card-body border-dashed-bottom pb-3">
                        <div class="row d-flex justify-content-between">
                            <div class="col-auto">
                                <div class="d-flex justify-content-center align-items-center thumb-xl border border-secondary rounded-circle">
                                    <i class="icofont-map-pins h1 align-self-center mb-0 text-secondary"></i>
                                </div>
                                <h5 class="mt-2 mb-0 fs-14">Packages</h5>
                            </div>
                            <div class="col align-self-center">
                                <div id="line-2" class="apex-charts float-end"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row d-flex justify-content-center">
                            <div class="col-12 col-md-6">
                                <h2 class="fs-22 mt-0 mb-1 fw-bold">{{ $packageCount }}</h2>
                                <p class="mb-0 text-truncate text-muted">
                                    <span class="text-success"><i class="mdi mdi-trending-up"></i>{{ $destinationCount }}</span> destinations
                                </p>
                            </div>
                            <div class="col-12 col-md-6 align-self-center text-start text-md-end">
                                <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary btn-sm px-2 mt-2 mt-md-0">View Report</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-8">
            <div class="card mb-0 h-100">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Website Overview</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('home') }}" class="btn btn-light btn-sm" target="_blank" rel="noopener">
                                <i class="icofont-external-link fs-5 me-1"></i> Open website
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div id="monthly_income" class="apex-charts"></div>
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-3">
                            <div class="card shadow-none border mb-0">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col text-center">
                                            <span class="fs-18 fw-semibold">{{ $packageCount }}</span>
                                            <h6 class="text-uppercase text-muted mt-2 m-0">Packages</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card shadow-none border mb-0">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col text-center">
                                            <span class="fs-18 fw-semibold">{{ $destinationCount }}</span>
                                            <h6 class="text-uppercase text-muted mt-2 m-0">Destinations</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card shadow-none border mb-0">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col text-center">
                                            <span class="fs-18 fw-semibold">{{ $blogPostCount }}</span>
                                            <h6 class="text-uppercase text-muted mt-2 m-0">Blog posts</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card shadow-none border mb-0">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col text-center">
                                            <span class="fs-18 fw-semibold">{{ $publishedBlogPostCount }}</span>
                                            <h6 class="text-uppercase text-muted mt-2 m-0">Published</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 justify-content-center mt-1">
        <div class="col-md-6 col-lg-8">
            <div class="card mb-0">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Popular Packages</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.packages.index') }}" class="btn btn-light btn-sm">View all</a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-top-0">Package</th>
                                    <th class="border-top-0">Duration</th>
                                    <th class="border-top-0">Category</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($featuredPackages as $package)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $package['image'] }}" height="40" width="56" class="me-3 align-self-center rounded object-fit-cover" alt="{{ $package['name'] }}">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0 text-truncate">{{ $package['name'] }}</h6>
                                                    <span class="fs-12 text-primary">{{ $package['location'] }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $package['duration'] }}</td>
                                        <td>{{ $package['category'] }}</td>
                                        <td><span class="badge bg-primary-subtle text-primary px-2">{{ $package['photo_count'] }} photos</span></td>
                                        <td>
                                            <a href="{{ route('admin.packages.show', $package['slug']) }}"><i class="las la-eye text-secondary fs-18"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-muted">No packages found in the catalog yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card mb-0">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Booking Mix</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div id="customers" class="apex-charts"></div>
                    <div class="bg-light py-3 px-2 mb-0 mt-3 text-center rounded">
                        <h6 class="mb-0">
                            <i class="icofont-calendar fs-5 me-1"></i>
                            {{ $newBookingCount }} new · {{ $bookingCount }} total
                        </h6>
                    </div>
                    <p class="text-secondary text-truncate mb-0 rounded mt-2 text-center">
                        Review new enquiries in
                        <a href="{{ route('admin.bookings.index') }}" class="text-primary text-decoration-underline">bookings</a>
                        and
                        <a href="{{ route('admin.contacts.index') }}" class="text-primary text-decoration-underline">contacts</a>
                        ({{ $newContactCount ?? 0 }} new)
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 justify-content-center mt-1">
        <div class="col-md-6 col-lg-8">
            <div class="card mb-0">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Recent Bookings</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.bookings.index') }}" class="btn btn-light btn-sm">View all</a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-top-0">Guest</th>
                                    <th class="border-top-0">Travel date</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentBookings as $booking)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $booking->full_name }}</div>
                                            <div class="text-muted small">{{ $booking->email }}</div>
                                        </td>
                                        <td>{{ optional($booking->travel_date)->format('M j, Y') ?? '—' }}</td>
                                        <td><span class="badge badge-status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></td>
                                        <td>
                                            <a href="{{ route('admin.bookings.show', $booking) }}"><i class="las la-eye text-secondary fs-18"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-muted">No booking requests yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card mb-0">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Recent Blog Posts</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-primary btn-sm">Add post</a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="list-group list-group-flush">
                        @forelse ($recentPosts as $post)
                            <a href="{{ route('admin.blog-posts.edit', $post) }}" class="list-group-item list-group-item-action px-0">
                                <div class="d-flex justify-content-between gap-3">
                                    <div>
                                        <div class="fw-semibold">{{ $post->title }}</div>
                                        <div class="text-muted small">{{ $post->is_published ? 'Published' : 'Draft' }}</div>
                                    </div>
                                    <i class="iconoir-nav-arrow-right align-self-center"></i>
                                </div>
                            </a>
                        @empty
                            <div class="text-muted py-3">No managed blog posts yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admin/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        window.adminDashboardCharts = {
            bookingCount: {{ (int) $bookingCount }},
            newBookingCount: {{ (int) $newBookingCount }},
            packageCount: {{ (int) $packageCount }},
            destinationCount: {{ (int) $destinationCount }},
            blogPostCount: {{ (int) $blogPostCount }},
            publishedBlogPostCount: {{ (int) $publishedBlogPostCount }},
        };
    </script>
    <script src="{{ asset('admin/assets/js/pages/admin-dashboard.init.js') }}"></script>
@endpush
