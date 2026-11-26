@extends('layouts.admin')

@section('title', 'Edit Destination')

@section('content')
    <div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h4 class="mb-1">Edit Destination</h4>
            <p class="text-muted mb-0">{{ $destination->full_name ?? $destination->title }}</p>
        </div>
        <a href="{{ route('destinations.show', $destination->slug) }}" class="btn btn-outline-primary" target="_blank" rel="noopener">View live</a>
    </div>

    <form method="POST" action="{{ route('admin.destinations.update', $destination) }}">
        @csrf
        @method('PUT')
        @include('admin.destinations._form', ['submitLabel' => 'Save changes'])
    </form>
@endsection