<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="question">Question</label>
                    <input type="text" class="form-control" id="question" name="question" value="{{ old('question', $faq->question) }}" required>
                </div>
                <div class="mb-0">
                    <label class="form-label" for="answer">Answer</label>
                    @include('admin.partials.rich-text-field', [
                        'id' => 'answer',
                        'name' => 'answer',
                        'value' => old('answer', $faq->answer),
                        'rows' => 8,
                        'oldKey' => 'answer',
                    ])
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="sort_order">Sort order</label>
                    <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', $faq->sort_order ?? 0) }}">
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" @checked(old('is_published', $faq->is_published ?? true))>
                    <label class="form-check-label" for="is_published">Published</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">{{ $submitLabel }}</button>
            </div>
        </div>
    </div>
</div>