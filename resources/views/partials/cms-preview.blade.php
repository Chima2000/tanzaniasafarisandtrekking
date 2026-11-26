@if (! empty($cmsPreview))
<style>
    [data-cms-section],
    [data-cms-field] {
        outline: 2px dashed transparent;
        outline-offset: 4px;
        transition: outline-color .2s ease, box-shadow .2s ease;
        cursor: pointer;
    }
    .cms-preview-active {
        outline-color: #0d6efd !important;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, .15);
    }
    .cms-preview-badge {
        position: fixed;
        top: 12px;
        left: 12px;
        z-index: 99999;
        background: #0d6efd;
        color: #fff;
        font: 600 12px/1.2 system-ui, sans-serif;
        padding: .55rem .8rem;
        border-radius: 999px;
        box-shadow: 0 8px 24px rgba(0,0,0,.18);
    }
</style>
<div class="cms-preview-badge">CMS preview · click a block to edit</div>
<script>
(() => {
    const highlight = (selector) => {
        document.querySelectorAll('.cms-preview-active').forEach((el) => el.classList.remove('cms-preview-active'));
        const target = document.querySelector(selector);
        if (!target) return;
        target.classList.add('cms-preview-active');
        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
    };

    window.addEventListener('message', (event) => {
        if (!event.data || event.data.type !== 'cms-focus') return;
        if (event.data.field) {
            highlight(`[data-cms-field="${event.data.field}"]`);
        } else if (event.data.section) {
            highlight(`[data-cms-section="${event.data.section}"]`);
        }
    });

    document.addEventListener('click', (event) => {
        const fieldEl = event.target.closest('[data-cms-field]');
        const sectionEl = event.target.closest('[data-cms-section]');
        if (!fieldEl && !sectionEl) return;
        event.preventDefault();
        window.parent.postMessage({
            type: 'cms-request-field',
            field: fieldEl?.dataset.cmsField || null,
            section: sectionEl?.dataset.cmsSection || null,
        }, '*');
    }, true);

    if (window.location.hash.startsWith('#cms-')) {
        highlight(`[data-cms-section="${window.location.hash.slice(5)}"]`);
    }
})();
</script>
@endif
