@extends('layouts.admin')

@section('title', 'Bookings')

@section('content')
    <div class="page-title-box">
        <h4 class="mb-1">Booking Requests</h4>
        <p class="text-muted mb-0">Leads submitted from the public Book Now form.</p>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Guest</th>
                            <th>Travel date</th>
                            <th>Package</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $booking->full_name }}</div>
                                    <div class="text-muted small">{{ $booking->email }}</div>
                                </td>
                                <td>{{ optional($booking->travel_date)->format('M j, Y') ?? '—' }}</td>
                                <td>{{ $booking->package_name ?: 'Custom trip' }}</td>
                                <td><span class="badge badge-status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></td>
                                <td>{{ $booking->created_at?->diffForHumans() }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">Open</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted">No booking requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $bookings->links() }}</div>
        </div>
    </div>
@endsection
