@extends('layouts.admin')

@section('title', 'Contact Submission')

@section('content')
    <div class="page-title-box d-flex align-items-center justify-content-between">
        <div>
            <h4 class="mb-1">{{ $contact->full_name }}</h4>
            <p class="text-muted mb-0">Submitted {{ $contact->created_at?->toDayDateTimeString() }}</p>
        </div>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-light">Back</a>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Message details</h4></div>
                <div class="card-body pt-0">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8"><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></dd>

                        <dt class="col-sm-4">Phone</dt>
                        <dd class="col-sm-8">{{ $contact->phone ?: '—' }}</dd>

                        <dt class="col-sm-4">Subject</dt>
                        <dd class="col-sm-8">{{ $contact->subject ?: '—' }}</dd>

                        <dt class="col-sm-4">Interest</dt>
                        <dd class="col-sm-8">{{ $contact->interest ?: '—' }}</dd>

                        <dt class="col-sm-4">Package</dt>
                        <dd class="col-sm-8">{{ $contact->package_name ?: '—' }}</dd>

                        <dt class="col-sm-4">Source</dt>
                        <dd class="col-sm-8">{{ str_replace('_', ' ', $contact->source) }}</dd>

                        <dt class="col-sm-4">Message</dt>
                        <dd class="col-sm-8">{{ $contact->message ?: '—' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header"><h4 class="card-title mb-0">Status</h4></div>
                <div class="card-body pt-0">
                    <form method="POST" action="{{ route('admin.contacts.update', $contact) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="status">Update status</label>
                            <select class="form-select" id="status" name="status">
                                @foreach (['new', 'reviewed', 'contacted', 'closed'] as $status)
                                    <option value="{{ $status }}" @selected($contact->status === $status)>{{ ucfirst($status) }}</option>
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
