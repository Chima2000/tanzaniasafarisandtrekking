@extends('layouts.admin')

@section('title', 'Email Notifications')

@php
    $creatingRecipient = session('notification_recipient_form') === 'create';
    $editingRecipientId = (int) session('notification_recipient_edit_id');
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-1">Email Notifications</h4>
                <p class="text-muted mb-0">Add the addresses that should receive booking and contact emails, and choose which kinds each address gets.</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-1">Add notification email</h4>
            <p class="text-muted mb-0 small">Each address can receive booking requests, contact messages, or both.</p>
        </div>
        <div class="card-body pt-0">
            <form method="post" action="{{ route('admin.notifications.store') }}" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label" for="notificationRecipientName">Name / label</label>
                    <input id="notificationRecipientName" name="name" type="text" maxlength="120" class="form-control" value="{{ $creatingRecipient ? old('name') : '' }}" placeholder="Bookings desk">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="notificationRecipientEmail">Email address</label>
                    <input id="notificationRecipientEmail" name="email" type="email" maxlength="255" class="form-control" value="{{ $creatingRecipient ? old('email') : '' }}" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" id="notificationRecipientActive" name="is_active" value="1" @checked($creatingRecipient ? filter_var(old('is_active', true), FILTER_VALIDATE_BOOLEAN) : true)>
                        <label class="form-check-label" for="notificationRecipientActive">Active</label>
                    </div>
                </div>
                <div class="col-12">
                    <p class="mb-2 fw-semibold">Receives</p>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach (\App\Models\NotificationRecipient::TYPE_LABELS as $type => $label)
                            @php
                                $oldTypes = $creatingRecipient ? old('notification_types', []) : [];
                                $oldTypes = is_array($oldTypes) ? $oldTypes : [];
                                $checked = $creatingRecipient
                                    ? in_array($type, $oldTypes, true)
                                    : true;
                            @endphp
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notificationType{{ $type }}" name="notification_types[]" value="{{ $type }}" @checked($checked)>
                                <label class="form-check-label" for="notificationType{{ $type }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Add email</button>
                </div>
                @if ($creatingRecipient)
                    @foreach (['name', 'email', 'notification_types', 'notification_types.*', 'is_active'] as $field)
                        @error($field)
                            <div class="col-12"><div class="invalid-feedback d-block">{{ $message }}</div></div>
                        @enderror
                    @endforeach
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title mb-1">Configured emails</h4>
            <p class="text-muted mb-0 small">Turn an address off without deleting it, or remove it when it should stop receiving mail.</p>
        </div>
        <div class="card-body pt-0">
            @forelse ($recipients as $recipient)
                @php $editingRecipient = $editingRecipientId === (int) $recipient->id; @endphp
                <article class="border rounded p-3 mb-3">
                    <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                        <div>
                            <div class="fw-semibold">{{ $recipient->displayName() }}</div>
                            <div class="text-muted small">{{ $recipient->email }}</div>
                        </div>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            @if ($recipient->is_active)
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Paused</span>
                            @endif
                            @foreach ($recipient->typeLabels() as $label)
                                <span class="badge bg-primary-subtle text-primary">{{ $label }}</span>
                            @endforeach
                        </div>
                    </div>

                    <form method="post" action="{{ route('admin.notifications.update', $recipient) }}" class="row g-3">
                        @csrf
                        <div class="col-md-4">
                            <label class="form-label" for="notificationRecipientName{{ $recipient->id }}">Name / label</label>
                            <input id="notificationRecipientName{{ $recipient->id }}" name="name" type="text" maxlength="120" class="form-control" value="{{ $editingRecipient ? old('name', $recipient->name) : $recipient->name }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="notificationRecipientEmail{{ $recipient->id }}">Email address</label>
                            <input id="notificationRecipientEmail{{ $recipient->id }}" name="email" type="email" maxlength="255" class="form-control" value="{{ $editingRecipient ? old('email', $recipient->email) : $recipient->email }}" required>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="notificationRecipientActive{{ $recipient->id }}" name="is_active" value="1" @checked($editingRecipient ? filter_var(old('is_active', $recipient->is_active), FILTER_VALIDATE_BOOLEAN) : $recipient->is_active)>
                                <label class="form-check-label" for="notificationRecipientActive{{ $recipient->id }}">Active</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <p class="mb-2 fw-semibold">Receives</p>
                            <div class="d-flex flex-wrap gap-3">
                                @php
                                    $selectedTypes = $editingRecipient
                                        ? old('notification_types', $recipient->notification_types ?? [])
                                        : ($recipient->notification_types ?? []);
                                    $selectedTypes = is_array($selectedTypes) ? $selectedTypes : [];
                                @endphp
                                @foreach (\App\Models\NotificationRecipient::TYPE_LABELS as $type => $label)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="notificationType{{ $recipient->id }}{{ $type }}" name="notification_types[]" value="{{ $type }}" @checked(in_array($type, $selectedTypes, true))>
                                        <label class="form-check-label" for="notificationType{{ $recipient->id }}{{ $type }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12 d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                        @if ($editingRecipient)
                            @foreach (['name', 'email', 'notification_types', 'notification_types.*', 'is_active'] as $field)
                                @error($field)
                                    <div class="col-12"><div class="invalid-feedback d-block">{{ $message }}</div></div>
                                @enderror
                            @endforeach
                        @endif
                    </form>

                    <form method="post" action="{{ route('admin.notifications.destroy', $recipient) }}" class="mt-3" onsubmit="return confirm('Remove this notification email?');">
                        @csrf
                        <button type="submit" class="btn btn-danger">Remove email</button>
                    </form>
                </article>
            @empty
                <p class="text-muted mb-0">No notification emails yet. Until you add one, messages go to the fallback address in <code>BOOK_NOW_RECIPIENT</code>.</p>
            @endforelse
        </div>
    </div>
@endsection
