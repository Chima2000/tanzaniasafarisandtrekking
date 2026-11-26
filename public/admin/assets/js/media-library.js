(function () {
    const root = document.querySelector('[data-media-library]');
    if (!root) {
        return;
    }

    const config = JSON.parse(root.getAttribute('data-media-library') || '{}');
    const selected = new Set();
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const bulkBar = root.querySelector('[data-media-bulk]');
    const bulkCount = root.querySelector('[data-media-bulk-count]');
    const selectAll = root.querySelectorAll('[data-media-select-all]');
    const uploadInputs = root.querySelectorAll('[data-media-upload]');
    const uploadModalEl = document.getElementById('mediaUploadModal');
    const uploadModal = uploadModalEl && window.bootstrap ? new bootstrap.Modal(uploadModalEl) : null;
    const uploadPreview = uploadModalEl?.querySelector('[data-media-upload-preview]');
    const uploadCount = uploadModalEl?.querySelector('[data-media-upload-count]');
    const uploadError = uploadModalEl?.querySelector('[data-media-upload-error]');
    const uploadSubmit = uploadModalEl?.querySelector('[data-media-upload-submit]');
    const uploadDropzone = uploadModalEl?.querySelector('[data-media-dropzone]');
    const detailModalEl = document.getElementById('mediaDetailModal');
    const detailModal = detailModalEl && window.bootstrap ? new bootstrap.Modal(detailModalEl) : null;
    let detailItem = null;
    let pendingUploads = [];
    let uploading = false;

    function csrfHeaders(json) {
        const headers = {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
        };
        if (json) {
            headers['Content-Type'] = 'application/json';
        }
        return headers;
    }

    function refreshBulk() {
        if (!bulkBar) {
            return;
        }
        bulkBar.classList.toggle('is-visible', selected.size > 0);
        if (bulkCount) {
            bulkCount.textContent = String(selected.size);
        }
        selectAll.forEach((box) => {
            const ids = config.itemIds ?? [];
            box.checked = ids.length > 0 && ids.every((id) => selected.has(id));
        });
        root.querySelectorAll('[data-media-id]').forEach((el) => {
            const id = Number(el.getAttribute('data-media-id'));
            el.classList.toggle('is-selected', selected.has(id));
            const box = el.querySelector('[data-media-toggle]');
            if (box) {
                box.checked = selected.has(id);
            }
        });
    }

    function toggleOne(id, on) {
        if (on) {
            selected.add(id);
        } else {
            selected.delete(id);
        }
        refreshBulk();
    }

    selectAll.forEach((box) => {
        box.addEventListener('change', () => {
            (config.itemIds ?? []).forEach((id) => {
                if (box.checked) {
                    selected.add(id);
                } else {
                    selected.delete(id);
                }
            });
            refreshBulk();
        });
    });

    root.querySelectorAll('[data-media-toggle]').forEach((box) => {
        box.addEventListener('change', (event) => {
            event.stopPropagation();
            toggleOne(Number(box.value), box.checked);
        });
        box.addEventListener('click', (event) => event.stopPropagation());
    });

    root.querySelector('[data-media-clear]')?.addEventListener('click', () => {
        selected.clear();
        refreshBulk();
    });

    root.querySelector('[data-media-delete-selected]')?.addEventListener('click', async () => {
        if (!selected.size) {
            return;
        }
        const confirmed = await Swal.fire({
            title: 'Delete files?',
            text: `Delete ${selected.size} file(s)? This cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EA580C',
            cancelButtonColor: '#1E293B',
            confirmButtonText: 'Delete',
            reverseButtons: true,
            customClass: { popup: 'swal-klt' },
        });
        if (!confirmed.isConfirmed) {
            return;
        }
        const res = await fetch(config.bulkDeleteUrl, {
            method: 'DELETE',
            headers: csrfHeaders(true),
            body: JSON.stringify({ ids: Array.from(selected) }),
        });
        if (res.ok) {
            window.location.reload();
        }
    });

    function formatSize(bytes) {
        if (bytes < 1024) {
            return `${bytes} B`;
        }
        if (bytes < 1048576) {
            return `${(bytes / 1024).toFixed(1)} KB`;
        }
        return `${(bytes / 1048576).toFixed(1)} MB`;
    }

    function showUploadError(message) {
        if (!uploadError) {
            return;
        }
        uploadError.hidden = !message;
        uploadError.textContent = message || '';
    }

    function renderPendingUploads() {
        if (!uploadPreview) {
            return;
        }
        uploadPreview.querySelectorAll('img').forEach((img) => {
            if (img.src.startsWith('blob:')) {
                URL.revokeObjectURL(img.src);
            }
        });
        uploadPreview.replaceChildren();
        pendingUploads.forEach((file, index) => {
            const tile = document.createElement('div');
            tile.className = 'klt-upload-tile';
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.alt = '';
                img.src = URL.createObjectURL(file);
                tile.append(img);
            } else {
                const ext = document.createElement('span');
                ext.className = 'klt-upload-ext';
                ext.textContent = (file.name.split('.').pop() || 'file').slice(0, 4);
                tile.append(ext);
            }
            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'klt-upload-remove';
            remove.setAttribute('aria-label', `Remove ${file.name}`);
            remove.innerHTML = '<i class="ti ti-x" aria-hidden="true"></i>';
            remove.addEventListener('click', () => {
                pendingUploads.splice(index, 1);
                renderPendingUploads();
            });
            const caption = document.createElement('span');
            caption.className = 'klt-upload-caption';
            const name = document.createElement('strong');
            name.textContent = file.name;
            const size = document.createElement('span');
            size.textContent = formatSize(file.size);
            caption.append(name, size);
            tile.append(remove, caption);
            uploadPreview.append(tile);
        });
        uploadDropzone?.classList.toggle('is-compact', pendingUploads.length > 0);
        if (uploadCount) {
            uploadCount.hidden = pendingUploads.length === 0;
            uploadCount.textContent = pendingUploads.length === 1
                ? '1 file ready'
                : `${pendingUploads.length} files ready`;
        }
        if (uploadSubmit) {
            uploadSubmit.disabled = pendingUploads.length === 0 || uploading;
            uploadSubmit.textContent = pendingUploads.length > 1
                ? `Upload ${pendingUploads.length} files`
                : 'Upload';
        }
    }

    function addPendingUploads(fileList) {
        const incoming = Array.from(fileList || []);
        if (!incoming.length) {
            return;
        }
        showUploadError('');
        incoming.forEach((file) => {
            if (pendingUploads.length >= 20) {
                showUploadError('You can upload up to 20 files at a time.');
                return;
            }
            const duplicate = pendingUploads.some((queued) => queued.name === file.name && queued.size === file.size && queued.lastModified === file.lastModified);
            if (!duplicate) {
                pendingUploads.push(file);
            }
        });
        renderPendingUploads();
    }

    async function uploadPendingFiles() {
        if (!pendingUploads.length || uploading) {
            return;
        }
        const form = new FormData();
        pendingUploads.forEach((file) => form.append('files[]', file));
        uploading = true;
        uploadInputs.forEach((el) => { el.disabled = true; });
        if (uploadSubmit) {
            uploadSubmit.disabled = true;
            uploadSubmit.textContent = 'Uploading…';
        }
        try {
            const res = await fetch(config.uploadUrl, {
                method: 'POST',
                headers: csrfHeaders(false),
                body: form,
            });
            if (res.ok) {
                window.location.reload();
                return;
            }
            let message = 'Upload failed. Check file size and format.';
            const data = await res.json().catch(() => null);
            const firstError = data?.errors ? Object.values(data.errors).flat()[0] : data?.message;
            if (typeof firstError === 'string' && firstError !== '') {
                message = firstError;
            }
            showUploadError(message);
            uploading = false;
            renderPendingUploads();
        } catch {
            showUploadError('Upload failed.');
            uploading = false;
            renderPendingUploads();
        } finally {
            uploadInputs.forEach((el) => { el.disabled = false; el.value = ''; });
        }
    }

    root.querySelectorAll('[data-media-upload-open]').forEach((button) => {
        button.addEventListener('click', () => {
            if (uploadModalEl && window.bootstrap) {
                bootstrap.Modal.getOrCreateInstance(uploadModalEl).show();
            }
        });
    });

    uploadInputs.forEach((input) => {
        input.addEventListener('change', () => {
            addPendingUploads(input.files);
            input.value = '';
        });
    });

    uploadSubmit?.addEventListener('click', () => uploadPendingFiles());

    if (uploadDropzone) {
        let dragDepth = 0;
        uploadDropzone.addEventListener('click', (event) => {
            if (event.target.closest('label, input, button, a')) {
                return;
            }
            uploadDropzone.querySelector('[data-media-upload]')?.click();
        });
        uploadDropzone.addEventListener('dragenter', (event) => {
            event.preventDefault();
            dragDepth += 1;
            uploadDropzone.classList.add('is-dragover');
        });
        uploadDropzone.addEventListener('dragover', (event) => {
            event.preventDefault();
        });
        uploadDropzone.addEventListener('dragleave', () => {
            dragDepth = Math.max(0, dragDepth - 1);
            if (dragDepth === 0) {
                uploadDropzone.classList.remove('is-dragover');
            }
        });
        uploadDropzone.addEventListener('drop', (event) => {
            event.preventDefault();
            dragDepth = 0;
            uploadDropzone.classList.remove('is-dragover');
            addPendingUploads(event.dataTransfer?.files);
        });
    }

    uploadModalEl?.addEventListener('hidden.bs.modal', () => {
        if (uploading) {
            return;
        }
        pendingUploads = [];
        showUploadError('');
        renderPendingUploads();
    });

    function fillDetail(item) {
        detailItem = { ...item };
        const img = detailModalEl.querySelector('[data-detail-image]');
        const placeholder = detailModalEl.querySelector('[data-detail-placeholder]');
        if (item.is_image) {
            img.hidden = false;
            placeholder.hidden = true;
            img.src = item.url;
            img.alt = item.alt_text || '';
        } else {
            img.hidden = true;
            placeholder.hidden = false;
            placeholder.querySelector('[data-detail-ext]').textContent = (item.filename || '').split('.').pop() || '';
            placeholder.querySelector('[data-detail-category]').textContent = item.category_label || '';
        }
        detailModalEl.querySelector('[data-detail-filename]').textContent = item.filename || '';
        detailModalEl.querySelector('[data-detail-mime]').textContent = item.mime_type || '';
        detailModalEl.querySelector('[data-detail-size]').textContent = item.human_size || '';
        const dims = detailModalEl.querySelector('[data-detail-dims-wrap]');
        if (item.width) {
            dims.hidden = false;
            detailModalEl.querySelector('[data-detail-dims]').textContent = `${item.width} × ${item.height}`;
        } else {
            dims.hidden = true;
        }
        detailModalEl.querySelector('[data-detail-uploaded]').textContent = item.uploaded_at || '';
        detailModalEl.querySelector('[data-detail-title]').value = item.title || '';
        detailModalEl.querySelector('[data-detail-alt]').value = item.alt_text || '';
        detailModalEl.querySelector('[data-detail-caption]').value = item.caption || '';
        detailModalEl.querySelector('[data-detail-description]').value = item.description || '';
        detailModalEl.querySelector('[data-detail-url]').value = item.url || '';
        const view = detailModalEl.querySelector('[data-detail-view]');
        if (view) {
            view.href = item.url || '#';
        }
    }

    root.querySelectorAll('[data-media-open]').forEach((btn) => {
        btn.addEventListener('click', () => {
            let item = null;
            try {
                item = JSON.parse(btn.getAttribute('data-media-open') || '{}');
            } catch {
                item = null;
            }
            if (!item || !item.id || !detailModalEl || !window.bootstrap) {
                return;
            }
            fillDetail(item);
            bootstrap.Modal.getOrCreateInstance(detailModalEl).show();
        });
    });

    detailModalEl?.querySelector('[data-detail-copy]')?.addEventListener('click', async () => {
        const url = detailModalEl.querySelector('[data-detail-url]')?.value || '';
        const feedback = detailModalEl.querySelector('[data-detail-copy-feedback]');
        try {
            await navigator.clipboard.writeText(url);
            if (feedback) {
                feedback.hidden = false;
                feedback.textContent = 'Copied!';
                setTimeout(() => { feedback.hidden = true; }, 2000);
            }
        } catch {
            if (feedback) {
                feedback.hidden = false;
                feedback.textContent = 'Copy failed';
            }
        }
    });

    detailModalEl?.querySelector('[data-detail-save]')?.addEventListener('click', async () => {
        if (!detailItem) {
            return;
        }
        const form = new FormData();
        form.append('_method', 'PUT');
        form.append('title', detailModalEl.querySelector('[data-detail-title]').value);
        form.append('alt_text', detailModalEl.querySelector('[data-detail-alt]').value);
        form.append('caption', detailModalEl.querySelector('[data-detail-caption]').value);
        form.append('description', detailModalEl.querySelector('[data-detail-description]').value);
        const res = await fetch(config.updateUrlTemplate.replace('__ID__', detailItem.id), {
            method: 'POST',
            headers: csrfHeaders(false),
            body: form,
        });
        if (res.ok) {
            window.location.reload();
            return;
        }
        Swal.fire({ icon: 'error', title: 'Could not save changes.', customClass: { popup: 'swal-klt' } });
    });
})();
