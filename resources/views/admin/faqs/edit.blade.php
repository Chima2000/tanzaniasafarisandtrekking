@extends('layouts.admin')

@section('title', 'Edit FAQ')

@section('content')
    <div class="page-title-box">
        <h4 class="mb-1">Edit FAQ</h4>
        <p class="text-muted mb-0">{{ $faq->question }}</p>
    </div>

    <form method="POST" action="{{ route('admin.faqs.update', $faq) }}">
        @csrf
        @method('PUT')
        @include('admin.faqs._form', ['submitLabel' => 'Save changes'])
    </form>
@endsection