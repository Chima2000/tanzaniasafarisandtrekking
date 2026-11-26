@extends('layouts.admin')

@section('title', 'Contacts')

@section('content')
    <div class="page-title-box">
        <h4 class="mb-1">Contact Submissions</h4>
        <p class="text-muted mb-0">Messages from the contact page and package enquiry forms.</p>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Guest</th>
                            <th>Subject</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contacts as $contact)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $contact->full_name }}</div>
                                    <div class="text-muted small">{{ $contact->email }}</div>
                                </td>
                                <td>{{ $contact->subject ?: ($contact->package_name ?: '—') }}</td>
                                <td>{{ str_replace('_', ' ', $contact->source) }}</td>
                                <td><span class="badge badge-status-{{ $contact->status }}">{{ ucfirst($contact->status) }}</span></td>
                                <td>{{ $contact->created_at?->diffForHumans() }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-outline-primary">Open</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted">No contact submissions yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $contacts->links() }}</div>
        </div>
    </div>
@endsection
