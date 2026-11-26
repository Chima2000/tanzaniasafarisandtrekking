@extends('layouts.admin')

@section('title', 'Pages')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="mb-1">Pages</h4>
                <p class="text-muted mb-0">Edit static website pages such as About, Contact, and FAQs layout copy.</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Updated</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pages as $page)
                            <tr>
                                <td class="fw-semibold">{{ $page->title }}</td>
                                <td class="text-muted small">{{ $page->slug }}</td>
                                <td>
                                    @if ($page->is_published)
                                        <span class="badge bg-primary-subtle text-primary">Published</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $page->updated_at?->diffForHumans() }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted">No pages found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection