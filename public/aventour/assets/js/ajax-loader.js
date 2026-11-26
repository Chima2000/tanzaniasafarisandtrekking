(() => {
  if (window.SiteAjaxLoader) {
    return;
  }

  let pending = 0;
  let activeButton = null;
  let hideTimer = null;
  let bootstrapped = false;

  function ensureMarkup() {
    let root = document.getElementById('siteAjaxLoader');
    if (root) {
      return root;
    }

    root = document.createElement('div');
    root.id = 'siteAjaxLoader';
    root.className = 'site-ajax-loader';
    root.setAttribute('aria-hidden', 'true');
    root.setAttribute('role', 'status');
    root.innerHTML = `
      <div class="site-ajax-loader__panel">
        <div class="site-ajax-loader__spinner" aria-hidden="true"></div>
        <p class="site-ajax-loader__text">Loading</p>
      </div>
    `;

    const mount = () => {
      if (!document.getElementById('siteAjaxLoader')) {
        document.body.appendChild(root);
      }
    };

    if (document.body) {
      mount();
    } else {
      document.addEventListener('DOMContentLoaded', mount, { once: true });
    }

    return root;
  }

  function render() {
    const root = ensureMarkup();
    const active = pending > 0;
    root.classList.toggle('is-active', active);
    root.setAttribute('aria-hidden', active ? 'false' : 'true');
    document.documentElement.classList.toggle('is-site-ajax-loading', active);
  }

  function begin() {
    pending += 1;
    if (hideTimer) {
      window.clearTimeout(hideTimer);
      hideTimer = null;
    }
    render();
  }

  function end() {
    pending = Math.max(0, pending - 1);
    if (pending === 0) {
      hideTimer = window.setTimeout(() => {
        hideTimer = null;
        render();
        clearButtonLoading();
      }, 120);
      return;
    }
    render();
  }

  function forceHide() {
    pending = 0;
    if (hideTimer) {
      window.clearTimeout(hideTimer);
      hideTimer = null;
    }
    render();
    clearButtonLoading();
  }

  function markButton(button, loading) {
    if (!(button instanceof HTMLElement)) {
      return;
    }

    if (loading) {
      if (activeButton && activeButton !== button) {
        clearButtonLoading();
      }
      activeButton = button;
      button.classList.add('is-loading');
      button.setAttribute('aria-busy', 'true');
      if (button instanceof HTMLButtonElement || button instanceof HTMLInputElement) {
        button.disabled = true;
      }
      if (!button.querySelector('.site-ajax-btn-spinner')) {
        const spinner = document.createElement('span');
        spinner.className = 'site-ajax-btn-spinner';
        spinner.setAttribute('aria-hidden', 'true');
        button.appendChild(spinner);
      }
      return;
    }

    button.classList.remove('is-loading');
    button.removeAttribute('aria-busy');
    if (button instanceof HTMLButtonElement || button instanceof HTMLInputElement) {
      button.disabled = false;
    }
    button.querySelectorAll('.site-ajax-btn-spinner').forEach((node) => node.remove());
  }

  function clearButtonLoading() {
    if (activeButton) {
      markButton(activeButton, false);
      activeButton = null;
    }
    document.querySelectorAll('.is-loading.theme-btn, .is-loading.btn, button.is-loading, input.is-loading').forEach((button) => {
      markButton(button, false);
    });
  }

  function shouldShowLoaderForForm(form) {
    if (!(form instanceof HTMLFormElement) || form.hasAttribute('data-no-loader')) {
      return false;
    }

    if (form.hasAttribute('data-ajax-loader')) {
      return true;
    }

    if (form.classList.contains('contact-form') || form.classList.contains('book-now-form')) {
      return true;
    }

    return Boolean(form.closest('.booking-form'));
  }

  function bootstrap() {
    if (bootstrapped) {
      return;
    }
    bootstrapped = true;
    ensureMarkup();

    document.addEventListener('submit', (event) => {
      const form = event.target;
      if (!shouldShowLoaderForForm(form)) {
        return;
      }

      // Wait a tick so AJAX handlers can preventDefault first.
      // Native navigations keep the overlay visible until the next page paints.
      // AJAX forms should call SiteAjaxLoader.show()/hide() themselves.
      window.setTimeout(() => {
        if (event.defaultPrevented) {
          return;
        }

        const submitter = event.submitter instanceof HTMLElement
          ? event.submitter
          : form.querySelector('button[type="submit"], input[type="submit"], .theme-btn[type="submit"]');

        if (submitter) {
          markButton(submitter, true);
        }

        begin();
      }, 0);
    });

    window.addEventListener('pageshow', (event) => {
      if (event.persisted) {
        forceHide();
      }
    });
  }

  window.SiteAjaxLoader = {
    show: begin,
    hide: end,
    reset: forceHide,
    setButtonLoading: markButton,
    clearButtonLoading,
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootstrap, { once: true });
  } else {
    bootstrap();
  }
})();
