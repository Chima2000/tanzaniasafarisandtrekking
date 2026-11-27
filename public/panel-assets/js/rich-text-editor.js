(() => {
    const editors = new WeakMap();

    const toolbar = [
        [{ header: [2, 3, 4, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        ['blockquote', 'link'],
        ['image', 'video'],
        ['clean'],
    ];

    const syncInput = (input, quill) => {
        const html = quill.root.innerHTML;
        const isEmpty = quill.getText().trim() === '';
        input.value = isEmpty ? '' : html;
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.dispatchEvent(new Event('change', { bubbles: true }));
    };

    const ensureQuill = () => typeof window.Quill === 'function';

    const initEditor = (field) => {
        if (!ensureQuill() || editors.has(field)) {
            return;
        }

        const input = field.querySelector('[data-rich-text-input]');
        const mount = field.querySelector('[data-rich-text-editor]');
        if (!input || !mount) {
            return;
        }

        const quill = new window.Quill(mount, {
            theme: 'snow',
            modules: {
                toolbar,
            },
            placeholder: 'Write rich text…',
        });

        if (input.value.trim() !== '') {
            quill.root.innerHTML = input.value;
        }

        quill.on('text-change', () => syncInput(input, quill));
        editors.set(field, quill);

        field.querySelector('[data-rich-insert-image]')?.addEventListener('click', async () => {
            const range = quill.getSelection(true) || { index: quill.getLength(), length: 0 };
            let url = null;

            if (typeof window.kltOpenMediaPicker === 'function') {
                const selection = await window.kltOpenMediaPicker({
                    title: 'Insert image',
                    category: 'image',
                    mode: 'single',
                });
                url = selection?.url || null;
            } else {
                url = window.prompt('Image URL');
            }

            if (!url) {
                return;
            }

            quill.insertEmbed(range.index, 'image', url, 'user');
            quill.setSelection(range.index + 1, 0);
            syncInput(input, quill);
        });

        field.querySelector('[data-rich-insert-video]')?.addEventListener('click', async () => {
            const range = quill.getSelection(true) || { index: quill.getLength(), length: 0 };
            let url = null;

            if (typeof window.kltOpenMediaPicker === 'function') {
                const selection = await window.kltOpenMediaPicker({
                    title: 'Insert video',
                    category: 'video',
                    mode: 'single',
                });
                url = selection?.url || null;
            } else {
                url = window.prompt('Video URL (YouTube, Vimeo, or direct file)');
            }

            if (!url) {
                return;
            }

            if (/\.(mp4|webm|ogg)(\?|$)/i.test(url) || url.startsWith('/')) {
                const safe = String(url).replace(/"/g, '&quot;');
                quill.clipboard.dangerouslyPasteHTML(
                    range.index,
                    `<video controls src="${safe}" style="max-width:100%"></video>`
                );
            } else {
                quill.insertEmbed(range.index, 'video', url, 'user');
            }

            syncInput(input, quill);
        });
    };

    const boot = () => {
        document.querySelectorAll('[data-rich-text-field]').forEach(initEditor);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }

    window.kltInitRichTextEditors = boot;
})();
