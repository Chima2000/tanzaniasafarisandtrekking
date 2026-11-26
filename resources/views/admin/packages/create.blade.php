@extends('layouts.admin')

@section('title', 'Create Package')

@section('content')
    <div class="page-title-box">
        <h4 class="mb-1">Create Package</h4>
        <p class="text-muted mb-0">Add a new safari or trekking package for the public site.</p>
    </div>

    <form method="POST" action="{{ route('admin.packages.store') }}" data-upload-url="{{ route('admin.media.upload') }}">
        @csrf
        @include('admin.packages._form', ['submitLabel' => 'Create package'])
    </form>
@endsection

@push('scripts')
@include('admin.partials.media-upload-script')
@endpush