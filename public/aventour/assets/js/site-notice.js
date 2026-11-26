(() => {
  if (window.SiteNotice) {
    return;
  }

  const root = document.getElementById('siteNoticePopup');
  if (!root) {
    window.SiteNotice = {
      show() {},
      hide() {},
    };
    return;
  }

  const titleEl = root.querySelector('[data-site-notice-title]');
  const messageEl = root.querySelector('[data-site-notice-message]');
  const eyebrowEl = root.querySelector('[data-site-notice-eyebrow]');
  const iconWrap = root.querySelector('[data-site-notice-icon]');
  let lastFocus = null;

  function setIcon(type) {
    if (!iconWrap) {
      return;
    }
    iconWrap.innerHTML = type === 'error'
      ? '<i class="fas fa-times" aria-hidden="true"></i>'
      : '<i class="flaticon-check" aria-hidden="true"></i>';
  }

  function hide() {
    root.classList.remove('is-visible', 'is-error', 'is-success');
    root.setAttribute('aria-hidden', 'true');
    root.hidden = true;
    document.body.classList.remove('overflow-hidden');
    if (lastFocus && typeof lastFocus.focus === 'function') {
      lastFocus.focus();
    }
  }

  function show(options = {}) {
    const type = options.type === 'error' ? 'error' : 'success';
    const title = options.title || (type === 'error' ? 'Something went wrong' : 'Thank you!');
    const message = options.message || '';
    const eyebrow = options.eyebrow || (type === 'error' ? 'Notice' : 'Confirmed');

    if (window.SiteAjaxLoader) {
      window.SiteAjaxLoader.reset();
    }

    lastFocus = document.activeElement;
    if (titleEl) {
      titleEl.textContent = title;
    }
    if (messageEl) {
      messageEl.textContent = message;
    }
    if (eyebrowEl) {
      eyebrowEl.textContent = eyebrow;
    }

    setIcon(type);
    root.classList.toggle('is-error', type === 'error');
    root.classList.toggle('is-success', type === 'success');
    root.hidden = false;
    root.setAttribute('aria-hidden', 'false');
    root.classList.add('is-visible');
    document.body.classList.add('overflow-hidden');

    const closeBtn = root.querySelector('.site-notice-popup__close');
    closeBtn?.focus();
  }

  root.querySelectorAll('[data-site-notice-close]').forEach((el) => {
    el.addEventListener('click', hide);
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && root.classList.contains('is-visible')) {
      hide();
    }
  });

  window.SiteNotice = { show, hide };

  const boot = document.getElementById('siteNoticeBoot');
  if (boot) {
    window.SiteNotice.show({
      type: boot.dataset.type || 'success',
      eyebrow: boot.dataset.eyebrow || 'Confirmed',
      title: boot.dataset.title || 'Thank you!',
      message: boot.dataset.message || '',
    });
  }
})();
