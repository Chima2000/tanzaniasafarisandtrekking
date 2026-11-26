<script>
(() => {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const form = document.querySelector('[data-upload-url]');
    const uploadUrl = form?.dataset.uploadUrl;
    if (!uploadUrl) return;

    const upload = async (file) => {
        const body = new FormData();
        body.append('file', file);
        const response = await fetch(uploadUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body,
        });
        if (!response.ok) {
            throw new Error('Upload failed');
        }
        return response.json();
    };

    const setImageUrl = (target, url) => {
        target.value = url;
        target.dispatchEvent(new Event('input', { bubbles: true }));
        const preview = document.querySelector(`[data-preview-for="${target.id}"]`);
        if (preview) {
            preview.src = url;
            preview.closest('.cms-image-preview')?.classList.remove('d-none');
        }
    };

    document.addEventListener('change', async (event) => {
        const input = event.target;
        if (!input.classList?.contains('cms-image-upload') && !input.classList?.contains('cms-media-upload') && !input.classList?.contains('cms-gallery-upload')) {
            return;
        }

        const file = input.files?.[0];
        const target = document.querySelector(input.dataset.target);
        if (!file || !target) return;

        try {
            const payload = await upload(file);
            if (input.classList.contains('cms-gallery-upload')) {
                target.value = `${target.value.trim()}\n${payload.url}`.trim();
                target.dispatchEvent(new Event('input', { bubbles: true }));
            } else {
                setImageUrl(target, payload.url);
            }
        } catch (error) {
            alert('Media upload failed.');
        } finally {
            input.value = '';
        }
    });

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-media-library-pick]');
        if (!button) {
            return;
        }

        const target = document.querySelector(button.dataset.target);
        if (!target || typeof window.kltOpenMediaPicker !== 'function') {
            return;
        }

        const mode = button.dataset.mode || 'single';
        const selection = await window.kltOpenMediaPicker({
            mode,
            category: button.dataset.category || 'image',
            title: button.dataset.title || (mode === 'multiple' ? 'Add images' : 'Choose image'),
        });
        if (!selection) {
            return;
        }
        if (mode === 'multiple') {
            const urls = (Array.isArray(selection) ? selection : [selection])
                .map((item) => item.url)
                .filter(Boolean);
            if (!urls.length) {
                return;
            }
            target.value = `${target.value.trim()}\n${urls.join('\n')}`.trim();
            target.dispatchEvent(new Event('input', { bubbles: true }));
            return;
        }
        setImageUrl(target, selection.url);
    });
})();
</script>
