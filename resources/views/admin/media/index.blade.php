@extends('layouts.admin')

@section('title', 'Media Library')

@push('styles')
<link rel="stylesheet" href="{{ admin_asset('css/media-library.css') }}?v={{ filemtime(public_path('panel-assets/css/media-library.css')) }}">
<link rel="stylesheet" href="{{ admin_asset('libs/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
@php
    $itemIds = $items->pluck('id')->all();
    $detailItems = $items->map(fn ($m) => [
        'id' => $m->id,
        'title' => $m->displayTitle(),
        'filename' => $m->filename,
        'url' => $m->url(),
        'category' => $m->category,
        'category_label' => $categories[$m->category] ?? ucfirst($m->category),
        'mime_type' => $m->mime_type,
        'human_size' => $m->humanSize(),
        'width' => $m->width,
        'height' => $m->height,
        'folder' => $m->folder,
        'disk' => $m->disk,
        'alt_text' => $m->alt_text,
        'caption' => $m->caption,
        'description' => $m->description,
        'is_image' => $m->isImage(),
        'uploaded_at' => $m->created_at?->format('M j, Y g:i A'),
        'modified_at' => $m->file_modified_at?->format('M j, Y g:i A'),
    ])->values()->all();
    $libraryConfig = [
        'layout' => $filters['layout'],
        'itemIds' => $itemIds,
        'uploadUrl' => route('admin.media.store'),
        'bulkDeleteUrl' => route('admin.media.bulk-destroy'),
        'updateUrlTemplate' => route('admin.media.update', ['mediaItem' => '__ID__']),
    ];
@endphp

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h4 class="mb-1">Media Library</h4>
                <p class="text-muted mb-0">Upload, organise, and manage images and files used across the site.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <form method="POST" action="{{ route('admin.media.scan') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="category" value="{{ $filters['category'] }}">
                    <input type="hidden" name="q" value="{{ $filters['q'] }}">
                    <input type="hidden" name="folder" value="{{ $filters['folder'] }}">
                    <input type="hidden" name="layout" value="{{ $filters['layout'] }}">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="iconoir-refresh me-1"></i> Scan library
                    </button>
                </form>
                <button type="button" class="btn btn-primary" data-media-upload-open>
                    <i class="iconoir-cloud-upload me-1"></i> Upload files
                </button>
            </div>
        </div>
    </div>
</div>

<div data-media-library="{{ json_encode($libraryConfig) }}">
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-4 col-xl-2">
            <a href="{{ route('admin.media.index', request()->except(['category', 'page'])) }}" class="admin-media-stat {{ ! $filters['category'] ? 'is-active' : '' }}">
                <div class="label">All media</div>
                <div class="value">{{ $total }}</div>
                <div class="hint">{{ $totalBytesHuman }} total</div>
            </a>
        </div>
        @foreach ($categories as $key => $label)
            <div class="col-6 col-md-4 col-xl-2">
                <a href="{{ route('admin.media.index', array_merge(request()->except('page'), ['category' => $key])) }}" class="admin-media-stat {{ $filters['category'] === $key ? 'is-active' : '' }}">
                    <div class="label">{{ $label }}</div>
                    <div class="value">{{ $stats[$key] ?? 0 }}</div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.media.index') }}">
                <input type="hidden" name="layout" value="{{ $filters['layout'] }}">
                @if ($filters['category'])
                    <input type="hidden" name="category" value="{{ $filters['category'] }}">
                @endif
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="media-search" class="form-label">Search media</label>
                        <input type="search" id="media-search" name="q" value="{{ $filters['q'] }}" placeholder="Filename, title, alt text, or path…" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="media-folder">Folder</label>
                        <select id="media-folder" name="folder" class="form-select">
                            <option value="">All folders</option>
                            @foreach ($folders as $folder)
                                <option value="{{ $folder }}" @selected($filters['folder'] === $folder)>{{ $folder }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex flex-wrap gap-2">
                        <div class="btn-group admin-media-layout-toggle" role="group" aria-label="Layout">
                            <a href="{{ route('admin.media.index', array_merge(request()->except('page'), ['layout' => 'grid'])) }}" class="btn btn-outline-secondary {{ $filters['layout'] === 'grid' ? 'is-active' : '' }}">Grid</a>
                            <a href="{{ route('admin.media.index', array_merge(request()->except('page'), ['layout' => 'list'])) }}" class="btn btn-outline-secondary {{ $filters['layout'] === 'list' ? 'is-active' : '' }}">List</a>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="admin-media-bulk klt-media-bulk" data-media-bulk>
        <strong><span data-media-bulk-count>0</span> selected</strong>
        <button type="button" class="btn btn-sm btn-outline-danger" data-media-delete-selected>Delete selected</button>
        <button type="button" class="btn btn-sm btn-light" data-media-clear>Clear</button>
    </div>

    @if ($total === 0)
        <div class="card">
            <div class="card-body admin-media-empty">
                <i class="iconoir-media-image"></i>
                <h5 class="mt-3 mb-2">No media indexed yet</h5>
                <p class="mb-3 text-muted">Scan storage to index existing files, or upload new ones.</p>
                <form method="POST" action="{{ route('admin.media.scan') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Scan library now</button>
                </form>
            </div>
        </div>
    @elseif ($items->total() === 0)
        <div class="card">
            <div class="card-body text-muted text-center py-5">No files match your filters.</div>
        </div>
    @elseif ($filters['layout'] === 'list')
        <div class="card mb-3">
            <div class="card-header">
                <label class="mb-0 d-flex align-items-center gap-2 small">
                    <input type="checkbox" class="form-check-input" data-media-select-all>
                    Select all on this page
                </label>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width:40px"></th>
                                <th style="width:70px">Preview</th>
                                <th>File</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Folder</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $media)
                                <tr data-media-id="{{ $media->id }}">
                                    <td>
                                        <input type="checkbox" class="form-check-input" value="{{ $media->id }}" data-media-toggle>
                                    </td>
                                    <td>
                                        @if ($media->isImage())
                                            <img src="{{ $media->url() }}" alt="" class="admin-media-thumb">
                                        @else
                                            <div class="admin-media-ext">{{ $media->extension }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-link p-0 text-start text-decoration-none fw-semibold" data-media-open="{{ json_encode($detailItems[$loop->index], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) }}">{{ $media->displayTitle() }}</button>
                                        <div class="text-muted small">{{ $media->filename }}</div>
                                    </td>
                                    <td>{{ $categories[$media->category] ?? $media->category }}</td>
                                    <td>{{ $media->humanSize() }}</td>
                                    <td class="text-muted small">{{ $media->folder ?: '—' }}</td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-media-open="{{ json_encode($detailItems[$loop->index], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) }}">Details</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mt-3">{{ $items->onEachSide(1)->links() }}</div>
    @else
        <label class="d-inline-flex align-items-center gap-2 mb-3 small">
            <input type="checkbox" class="form-check-input" data-media-select-all>
            Select all on this page
        </label>
        <div class="admin-media-grid mb-4">
            @foreach ($items as $media)
                <div class="admin-media-tile klt-media-tile" data-media-id="{{ $media->id }}">
                    <div class="admin-media-check klt-media-check">
                        <input type="checkbox" class="form-check-input" value="{{ $media->id }}" data-media-toggle>
                    </div>
                    <button type="button" class="border-0 bg-transparent p-0 w-100 text-start" data-media-open="{{ json_encode($detailItems[$loop->index], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) }}">
                        <div class="preview">
                            @if ($media->isImage())
                                <img src="{{ $media->url() }}" alt="{{ $media->alt_text }}">
                            @elseif ($media->category === 'video')
                                <div class="d-flex flex-column align-items-center gap-1 text-muted">
                                    <i class="iconoir-media-video" style="font-size:1.6rem;"></i>
                                    <span class="small text-uppercase fw-semibold">{{ $media->extension }}</span>
                                </div>
                            @elseif ($media->category === 'audio')
                                <div class="d-flex flex-column align-items-center gap-1 text-muted">
                                    <i class="iconoir-microphone" style="font-size:1.6rem;"></i>
                                    <span class="small text-uppercase fw-semibold">{{ $media->extension }}</span>
                                </div>
                            @else
                                <div class="d-flex flex-column align-items-center gap-1 text-muted">
                                    <span class="fw-bold text-uppercase">{{ $media->extension }}</span>
                                    <span class="small">{{ $categories[$media->category] ?? '' }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="meta">
                            <p class="title">{{ $media->displayTitle() }}</p>
                            <p class="sub">{{ $media->humanSize() }} · {{ $categories[$media->category] ?? $media->category }}</p>
                        </div>
                    </button>
                </div>
            @endforeach
            <button type="button" class="admin-media-upload-tile klt-media-upload-tile" data-media-upload-open>
                <i class="iconoir-plus" style="font-size:1.4rem;"></i>
                <span class="mt-1">Upload</span>
            </button>
        </div>
        <div class="mt-3">{{ $items->onEachSide(1)->links() }}</div>
    @endif

    <div class="modal fade" id="mediaUploadModal" tabindex="-1" aria-labelledby="mediaUploadTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediaUploadTitle">Upload files</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="admin-media-dropzone klt-media-dropzone" data-media-dropzone>
                        <div class="klt-dropzone-badge" aria-hidden="true"><i class="iconoir-cloud-upload"></i></div>
                        <div class="klt-dropzone-copy">
                            <p class="klt-dropzone-title"><span class="klt-dropzone-idle">Drag and drop files here</span><span class="klt-dropzone-active">Drop files to upload</span></p>
                            <p class="klt-dropzone-hint">or browse to upload one file or several at once.</p>
                        </div>
                        <label class="btn btn-primary mb-0 klt-dropzone-browse">
                            Browse files
                            <input type="file" multiple class="d-none" data-media-upload>
                        </label>
                        <p class="klt-dropzone-meta">Up to 20 files · 50 MB each</p>
                    </div>
                    <p class="mb-0 mt-3 text-danger small" data-media-upload-error hidden></p>
                    <p class="klt-upload-count" data-media-upload-count hidden></p>
                    <div class="klt-upload-grid" data-media-upload-preview></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" data-media-upload-submit disabled>Upload</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mediaDetailModal" tabindex="-1" aria-labelledby="mediaDetailTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediaDetailTitle">Attachment details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <img src="" alt="" class="admin-media-preview-lg klt-media-preview-lg" data-detail-image>
                            <div class="admin-media-file-placeholder klt-media-file-placeholder" data-detail-placeholder hidden>
                                <p class="mb-1 fs-4 fw-bold text-uppercase" data-detail-ext></p>
                                <p class="mb-0 small" data-detail-category></p>
                            </div>
                            <div class="mt-3 small text-muted">
                                <p class="mb-1"><strong class="text-body">File:</strong> <span data-detail-filename></span></p>
                                <p class="mb-1"><strong class="text-body">Type:</strong> <span data-detail-mime></span></p>
                                <p class="mb-1"><strong class="text-body">Size:</strong> <span data-detail-size></span></p>
                                <p class="mb-1" data-detail-dims-wrap><strong class="text-body">Dimensions:</strong> <span data-detail-dims></span></p>
                                <p class="mb-0"><strong class="text-body">Uploaded:</strong> <span data-detail-uploaded></span></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" data-detail-title>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alt text</label>
                                <input type="text" class="form-control" data-detail-alt placeholder="Describe image for accessibility">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Caption</label>
                                <input type="text" class="form-control" data-detail-caption>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" rows="3" data-detail-description></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">File URL</label>
                                <div class="input-group">
                                    <input type="text" class="form-control font-monospace small" readonly data-detail-url>
                                    <button type="button" class="btn btn-outline-secondary" data-detail-copy>Copy</button>
                                </div>
                                <p class="mb-0 mt-1 text-success small" data-detail-copy-feedback hidden></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" target="_blank" class="btn btn-outline-primary" data-detail-view>View file</a>
                    <button type="button" class="btn btn-primary" data-detail-save>Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ admin_asset('libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script src="{{ admin_asset('js/media-library.js') }}?v={{ filemtime(public_path('panel-assets/js/media-library.js')) }}"></script>
@endpush
