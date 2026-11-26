@extends('layouts.admin')

@section('title', 'Edit Package')

@section('content')
    <div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h4 class="mb-1">Edit Package</h4>
            <p class="text-muted mb-0">{{ $package->name }}</p>
        </div>
        <a href="{{ route('packages.show', $package->slug) }}" class="btn btn-outline-primary" target="_blank" rel="noopener">View live</a>
    </div>

    <form method="POST" action="{{ route('admin.packages.update', $package) }}">
        @csrf
        @method('PUT')
        @include('admin.packages._form', ['submitLabel' => 'Save changes'])
    </form>
@endsection