@extends('layouts.admin')

@section('title', 'Booking Request')

@section('content')
    <div class="page-title-box d-flex align-items-center justify-content-between">
        <div>
            <h4 class="mb-1">{{ $booking->full_name }}</h4>
            <p class="text-muted mb-0">Submitted {{ $booking->created_at?->toDayDateTimeString() }}</p>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-light">Back</a>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Request details</h4></div>
                <div class="card-body pt-0">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8"><a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a></dd>

                        <dt class="col-sm-4">Phone</dt>
                        <dd class="col-sm-8">{{ $booking->phone ?: '—' }}</dd>

                        <dt class="col-sm-4">Package</dt>
                        <dd class="col-sm-8">{{ $booking->package_name ?: 'Custom trip' }}</dd>

                        <dt class="col-sm-4">Interest</dt>
                        <dd class="col-sm-8">{{ $booking->interest ?: '—' }}</dd>

                        <dt class="col-sm-4">Budget</dt>
                        <dd class="col-sm-8">{{ $booking->budget ?: '—' }}</dd>

                        <dt class="col-sm-4">Duration</dt>
                        <dd class="col-sm-8">{{ $booking->duration_days ? $booking->duration_days.' days' : '—' }}</dd>

                        <dt class="col-sm-4">Travellers</dt>
                        <dd class="col-sm-8">{{ $booking->adults }} adults, {{ $booking->children }} children</dd>

                        <dt class="col-sm-4">Accommodation</dt>
                        <dd class="col-sm-8">{{ $booking->accommodation ?: '—' }}</dd>

                        <dt class="col-sm-4">Travel date</dt>
                        <dd class="col-sm-8">{{ optional($booking->travel_date)->format('F j, Y') ?? '—' }}</dd>

                        <dt class="col-sm-4">Destinations</dt>
                        <dd class="col-sm-8">{{ implode(', ', $booking->destinations ?? []) ?: '—' }}</dd>

                        <dt class="col-sm-4">Notes</dt>
                        <dd class="col-sm-8">{{ $booking->notes ?: '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Status</h4></div>
                <div class="card-body pt-0">
                    <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="status">Update status</label>
                            <select class="form-select" id="status" name="status">
                                @foreach (['new', 'reviewed', 'contacted', 'closed'] as $status)
                                    <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
