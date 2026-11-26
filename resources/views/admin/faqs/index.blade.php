@extends('layouts.admin')

@section('title', 'FAQs')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-1">FAQs</h4>
                    <p class="text-muted mb-0">Questions and answers shown on the public FAQ page.</p>
                </div>
                <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">Add FAQ</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:4rem;">#</th>
                            <th>Question</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($faqs as $faq)
                            <tr>
                                <td class="text-muted">{{ $faq->sort_order }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $faq->question }}</div>
                                    <div class="text-muted small">{{ \Illuminate\Support\Str::limit(strip_tags($faq->answer), 80) }}</div>
                                </td>
                                <td>
                                    @if ($faq->is_published)
                                        <span class="badge bg-primary-subtle text-primary">Published</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $faq->updated_at?->diffForHumans() }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this FAQ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted">No FAQs yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $faqs->links() }}</div>
        </div>
    </div>
@endsection