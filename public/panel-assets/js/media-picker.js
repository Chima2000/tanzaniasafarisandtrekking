(function () {
    const overlay = document.querySelector('[data-media-picker]');
    if (!overlay) {
        return;
    }

    const browseUrl = overlay.getAttribute('data-browse-url') || '/dashboard/media/browse';
    const uploadUrl = overlay.getAttribute('data-upload-url') || '/dashboard/media/upload';
    const asGalleryUrl = (overlay.getAttribute('data-as-gallery-url') || '/dashboard/media').replace(/\/$/, '');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const titleEl = overlay.querySelector('[data-picker-title]');
    const grid = overlay.querySelector('[data-picker-grid]');
    const queryInput = overlay.querySelector('[data-picker-query]');
    const hintSingle = overlay.querySelector('[data-picker-hint-single]');
    const hintMultiple = overlay.querySelector('[data-picker-hint-multiple]');
    const pageLabel = overlay.querySelector('[data-picker-page]');
    const prevBtn = overlay.querySelector('[data-picker-prev]');
    const nextBtn = overlay.querySelector('[data-picker-next]');
    const confirmBtn = overlay.querySelector('[data-picker-confirm]');
    const emptyEl = overlay.querySelector('[data-picker-empty]');
    const loadingEl = overlay.querySelector('[data-picker-loading]');
    const uploadProgress = overlay.querySelector('[data-picker-progress]');
    const state = {
        open: false,
        mode: 'single',
        items: [],
        selected: [],
        page: 1,
        lastPage: 1,
        query: '',
        loading: false,
    };

    function isSelected(item) {
        return state.selected.some((entry) => entry.id === item.id);
    }

    function settle(value) {
        const resolve = window.__kltMediaPickerResolve;
        window.__kltMediaPickerResolve = null;
        state.open = false;
        state.selected = [];
        overlay.classList.remove('is-open');
        if (typeof resolve === 'function') {
            resolve(value);
        }
    }

    function close() {
        settle(null);
    }

    function render() {
        grid.innerHTML = '';
        state.items.forEach((item) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'klt-media-picker-item' + (isSelected(item) ? ' is-selected' : '');
            const img = document.createElement('img');
            img.src = item.thumb;
            img.alt = '';
            const caption = document.createElement('p');
            caption.className = 'px-2 py-1 mb-0 text-truncate';
            caption.style.fontSize = '.72rem';
            caption.style.fontWeight = '600';
            caption.textContent = item.title || '';
            btn.append(img, caption);
            btn.addEventListener('click', () => toggleItem(item));
            btn.addEventListener('dblclick', (event) => {
                event.preventDefault();
                selectAndInsert(item);
            });
            grid.appendChild(btn);
        });
        emptyEl.hidden = state.loading || state.items.length > 0;
        loadingEl.hidden = !state.loading;
        pageLabel.textContent = `Page ${state.page} of ${state.lastPage}`;
        prevBtn.disabled = state.page <= 1;
        nextBtn.disabled = state.page >= state.lastPage;
        confirmBtn.disabled = state.selected.length === 0;
        confirmBtn.textContent = state.mode === 'multiple'
            ? `Add selected (${state.selected.length})`
            : 'Insert image';
        hintSingle.hidden = state.mode !== 'single';
        hintMultiple.hidden = state.mode !== 'multiple';
    }

    function toggleItem(item) {
        if (state.mode === 'single') {
            state.selected = [item];
            render();
            return;
        }
        if (isSelected(item)) {
            state.selected = state.selected.filter((entry) => entry.id !== item.id);
        } else {
            state.selected = [...state.selected, item];
        }
        render();
    }

    function resolveSelection(value) {
        settle(value);
    }

    function selectAndInsert(item) {
        if (state.mode !== 'single') {
            toggleItem(item);
            return;
        }
        resolveSelection(item);
    }

    function confirmSelection() {
        if (state.mode === 'single') {
            if (state.selected[0]) {
                resolveSelection(state.selected[0]);
            }
            return;
        }
        if (state.selected.length) {
            resolveSelection([...state.selected]);
        }
    }

    async function load(page) {
        state.loading = true;
        state.page = page;
        render();
        const params = new URLSearchParams({ page: String(page), category: state.category || 'image' });
        if (state.query) {
            params.set('q', state.query);
        }
        try {
            const res = await fetch(`${browseUrl}?${params.toString()}`, { headers: { Accept: 'application/json' } });
            if (!res.ok) {
                return;
            }
            const json = await res.json();
            state.items = json.data ?? [];
            state.lastPage = json.meta?.last_page ?? 1;
        } finally {
            state.loading = false;
            render();
        }
    }

    function open(detail) {
        state.mode = detail?.mode || 'single';
        state.category = detail?.category || 'image';
        state.selected = [];
        state.open = true;
        if (titleEl) {
            titleEl.textContent = detail?.title || 'Media library';
        }
        const uploadInput = overlay.querySelector('[data-picker-upload]');
        if (uploadInput) {
            uploadInput.accept = state.category === 'video'
                ? 'video/mp4,video/webm,video/quicktime,video/*'
                : 'image/*';
        }
        overlay.classList.add('is-open');
        load(1);
    }

    window.kltOpenMediaPicker = function (detail) {
        return new Promise((resolve) => {
            window.__kltMediaPickerResolve = resolve;
            open(detail || {});
        });
    };

    window.kltResolveMediaAsGalleryImage = async function (mediaId) {
        const res = await fetch(`${asGalleryUrl}/${mediaId}/gallery-image`, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
        if (!res.ok) {
            throw new Error('Could not use that image');
        }
        return res.json();
    };

    document.addEventListener('open-media-picker', (event) => open(event.detail || {}));
    overlay.querySelectorAll('[data-picker-close]').forEach((button) => {
        button.addEventListener('click', close);
    });
    overlay.querySelector('[data-picker-backdrop]')?.addEventListener('click', close);
    prevBtn?.addEventListener('click', () => load(state.page - 1));
    nextBtn?.addEventListener('click', () => load(state.page + 1));
    confirmBtn?.addEventListener('click', confirmSelection);
    queryInput?.addEventListener('input', (() => {
        let timer;
        return () => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                state.query = queryInput.value.trim();
                load(1);
            }, 300);
        };
    })());
    overlay.querySelector('[data-picker-upload]')?.addEventListener('change', async (event) => {
        const files = event.target.files;
        if (!files?.length) {
            return;
        }
        const form = new FormData();
        Array.from(files).forEach((file) => form.append('files[]', file));
        if (uploadProgress) {
            uploadProgress.hidden = false;
        }
        try {
            const res = await fetch(uploadUrl, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: form,
            });
            if (res.ok) {
                const json = await res.json();
                await load(1);
                if (state.mode === 'multiple' && json.uploaded?.length) {
                    json.uploaded.forEach((item) => {
                        if (!isSelected(item)) {
                            state.selected = [...state.selected, item];
                        }
                    });
                } else if (state.mode === 'single' && json.uploaded?.[0]) {
                    state.selected = [json.uploaded[0]];
                }
                render();
            }
        } finally {
            if (uploadProgress) {
                uploadProgress.hidden = true;
            }
            event.target.value = '';
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && overlay.classList.contains('is-open')) {
            close();
        }
    });
})();
