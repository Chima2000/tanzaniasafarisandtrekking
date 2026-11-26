@extends('layouts.admin')

@section('title', 'Create Destination')

@section('content')
    <div class="page-title-box">
        <h4 class="mb-1">Create Destination</h4>
        <p class="text-muted mb-0">Add a new destination page for the public site.</p>
    </div>

    <form method="POST" action="{{ route('admin.destinations.store') }}">
        @csrf
        @include('admin.destinations._form', ['submitLabel' => 'Create destination'])
    </form>
@endsection