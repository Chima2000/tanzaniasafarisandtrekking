@extends('layouts.admin')

@section('title', 'Create FAQ')

@section('content')
    <div class="page-title-box">
        <h4 class="mb-1">Create FAQ</h4>
        <p class="text-muted mb-0">Add a new question for the public FAQ page.</p>
    </div>

    <form method="POST" action="{{ route('admin.faqs.store') }}">
        @csrf
        @include('admin.faqs._form', ['submitLabel' => 'Create FAQ'])
    </form>
@endsection