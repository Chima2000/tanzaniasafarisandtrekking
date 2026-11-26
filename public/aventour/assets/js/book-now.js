(() => {
  const form = document.getElementById('bookNowForm');
  if (!form) {
    return;
  }

  const panels = Array.from(form.querySelectorAll('[data-book-panel]'));
  const stepItems = Array.from(form.querySelectorAll('[data-book-steps] [data-step-jump]'));
  const prevBtn = form.querySelector('[data-book-prev]');
  const nextBtn = form.querySelector('[data-book-next]');
  const submitBtn = form.querySelector('[data-book-submit]');
  const summaryList = form.querySelector('[data-book-summary-list]');
  const totalSteps = panels.length;
  let current = Number(form.dataset.startStep || 1);
  let maxReached = current;

  const messages = {
    adults: 'Please enter at least one adult traveller.',
    duration_days: 'Please choose your trip length.',
    travel_date: 'Please choose a travel start date.',
    destinations: 'Please select at least one destination.',
    budget: 'Please choose a budget range.',
    accommodation: 'Please choose an accommodation style.',
    full_name: 'Please enter your full name.',
    email: 'Please enter a valid email address.',
  };

  function clamp(value, min, max) {
    return Math.min(max, Math.max(min, value));
  }

  function escapeHtml(value) {
    return String(value)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function csrfToken() {
    return form.querySelector('input[name="_token"]')?.value || '';
  }

  function fieldGroup(name) {
    if (name === 'destinations') {
      return form.querySelector('[data-error-for="destinations"]')?.closest('.form-group');
    }
    const field = form.querySelector(`[name="${name}"]`);
    return field?.closest('.form-group') || null;
  }

  function setFieldError(name, message) {
    const group = fieldGroup(name);
    const error = form.querySelector(`[data-error-for="${name}"]`);
    if (!group || !error) {
      return;
    }
    if (message) {
      group.classList.add('is-invalid');
      error.textContent = message;
    } else {
      group.classList.remove('is-invalid');
      error.textContent = '';
    }
  }

  function clearPanelErrors(step) {
    const panel = panels.find((item) => Number(item.dataset.bookPanel) === step);
    panel?.querySelectorAll('.form-group.is-invalid').forEach((group) => {
      group.classList.remove('is-invalid');
      const error = group.querySelector('.field-error');
      if (error) {
        error.textContent = '';
      }
    });
  }

  function fieldValue(name) {
    if (name === 'destinations') {
      return Array.from(form.querySelectorAll('input[name="destinations[]"]:checked')).map((input) => input.value);
    }
    return form.querySelector(`[name="${name}"]`)?.value?.trim() || '';
  }

  function validateField(name) {
    const value = fieldValue(name);
    if (name === 'adults') {
      return Number(value) >= 1;
    }
    if (name === 'duration_days') {
      return Number(value) >= 1;
    }
    if (name === 'travel_date') {
      if (!value) {
        return false;
      }
      const min = form.querySelector('[name="travel_date"]')?.min || '';
      return !min || value >= min;
    }
    if (name === 'destinations') {
      return Array.isArray(value) && value.length > 0;
    }
    if (name === 'budget' || name === 'accommodation' || name === 'full_name') {
      return Boolean(value);
    }
    if (name === 'email') {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
    }
    return true;
  }

  function validateStep(step, showMessage) {
    const panel = panels.find((item) => Number(item.dataset.bookPanel) === step);
    const fields = (panel?.dataset.validate || '')
      .split(',')
      .map((item) => item.trim())
      .filter(Boolean);

    let firstInvalid = null;
    fields.forEach((field) => {
      const ok = validateField(field);
      if (!ok) {
        firstInvalid = firstInvalid || field;
        if (showMessage) {
          setFieldError(field, messages[field] || 'This field is required.');
        }
      } else if (showMessage) {
        setFieldError(field, '');
      }
    });

    return !firstInvalid;
  }

  function selectedDestinationLabels() {
    return Array.from(form.querySelectorAll('input[name="destinations[]"]:checked')).map((input) => {
      return input.closest('label')?.querySelector('strong')?.textContent?.trim() || input.value;
    });
  }

  function formatTravelDate(value) {
    if (!value) {
      return '';
    }
    const date = new Date(`${value}T00:00:00`);
    if (Number.isNaN(date.getTime())) {
      return value;
    }
    return date.toLocaleDateString(undefined, {
      weekday: 'short',
      year: 'numeric',
      month: 'short',
      day: 'numeric',
    });
  }

  function focusField(name) {
    window.requestAnimationFrame(() => {
      let target = null;

      if (name === 'destinations') {
        target = form.querySelector('input[name="destinations[]"]:checked')
          || form.querySelector('input[name="destinations[]"]');
      } else {
        target = form.querySelector(`[name="${name}"]`);
      }

      if (!target) {
        return;
      }

      const group = target.closest('.form-group') || target;
      group.scrollIntoView({ behavior: 'smooth', block: 'center' });
      group.classList.add('is-editing');
      window.setTimeout(() => group.classList.remove('is-editing'), 1600);

      if (typeof target.focus === 'function') {
        try {
          target.focus({ preventScroll: true });
        } catch (error) {
          target.focus();
        }
      }
    });
  }

  function editSummaryItem(step, field) {
    const target = clamp(Number(step) || 1, 1, totalSteps);
    maxReached = Math.max(maxReached, target);
    setStep(target);
    focusField(field);
  }

  function refreshSummary() {
    if (!summaryList) {
      return;
    }

    const rows = [
      ['icon-65.png', 'Travellers', [fieldValue('adults') ? `${fieldValue('adults')} adult${Number(fieldValue('adults')) === 1 ? '' : 's'}` : '', fieldValue('children') && Number(fieldValue('children')) > 0 ? `${fieldValue('children')} child${Number(fieldValue('children')) === 1 ? '' : 'ren'}` : ''].filter(Boolean).join(', '), 1, 'adults'],
      ['icon-68.png', 'Travel date', formatTravelDate(fieldValue('travel_date')), 1, 'travel_date'],
      ['icon-66.png', 'Trip length', fieldValue('duration_days') ? `${fieldValue('duration_days')} days` : '', 1, 'duration_days'],
      ['icon-67.png', 'Destinations', selectedDestinationLabels().join(', '), 2, 'destinations'],
      ['icon-69.png', 'Trip focus', fieldValue('interest'), 2, 'interest'],
      ['icon-70.png', 'Budget', fieldValue('budget'), 3, 'budget'],
      ['icon-71.png', 'Accommodation', form.querySelector('[name="accommodation"] option:checked')?.textContent?.split('—')[0]?.trim() || fieldValue('accommodation'), 3, 'accommodation'],
      ['icon-72.png', 'Contact', [fieldValue('full_name'), fieldValue('email'), fieldValue('phone')].filter(Boolean).join(' · '), 4, 'full_name'],
    ].filter(([, , value]) => Boolean(value));

    if (!rows.length) {
      summaryList.innerHTML = `
        <li class="book-now-summary__empty">
          <div class="icon"><img src="/aventour/assets/images/icons/icon-65.png" alt=""></div>
          <span>Preview</span>
          <span>Complete the earlier steps to see your request summary here.</span>
        </li>`;
      return;
    }

    summaryList.innerHTML = rows
      .map(
        ([icon, label, value, step, field]) => `
        <li>
          <div class="icon"><img src="/aventour/assets/images/icons/${icon}" alt=""></div>
          <span>${escapeHtml(label)}</span>
          <span class="book-now-summary__value">${escapeHtml(value)}</span>
          <button
            type="button"
            class="book-now-summary__edit"
            data-summary-edit
            data-edit-step="${step}"
            data-edit-field="${escapeHtml(field)}"
            aria-label="Edit ${escapeHtml(label)}"
            title="Edit ${escapeHtml(label)}"
          >
            <i class="fas fa-pen" aria-hidden="true"></i>
          </button>
        </li>`
      )
      .join('');
  }

  function setStep(step) {
    current = clamp(step, 1, totalSteps);
    maxReached = Math.max(maxReached, current);

    panels.forEach((panel) => {
      panel.classList.toggle('active', Number(panel.dataset.bookPanel) === current);
    });

    stepItems.forEach((button, index) => {
      const stepNumber = index + 1;
      const reachable = stepNumber <= maxReached;
      button.classList.toggle('active', stepNumber === current);
      button.classList.toggle('is-done', stepNumber < current);
      button.disabled = !reachable;
    });

    if (prevBtn) {
      prevBtn.disabled = current <= 1;
    }
    if (nextBtn) {
      nextBtn.hidden = current === totalSteps;
    }
    if (submitBtn) {
      const onLast = current === totalSteps;
      submitBtn.hidden = !onLast;
      submitBtn.disabled = !onLast;
    }

    refreshSummary();
  }

  function jumpToStep(step) {
    const target = Number(step);
    if (!Number.isFinite(target) || target < 1 || target > totalSteps || target > maxReached) {
      return;
    }
    setStep(target);
  }

  prevBtn?.addEventListener('click', () => {
    if (current > 1) {
      setStep(current - 1);
    }
  });

  nextBtn?.addEventListener('click', () => {
    if (!validateStep(current, true)) {
      return;
    }
    clearPanelErrors(current);
    setStep(current + 1);
  });

  form.addEventListener('click', (event) => {
    const edit = event.target.closest('[data-summary-edit]');
    if (edit && form.contains(edit)) {
      editSummaryItem(edit.dataset.editStep, edit.dataset.editField);
      return;
    }

    const jump = event.target.closest('[data-step-jump]');
    if (!jump || !form.contains(jump)) {
      return;
    }
    jumpToStep(jump.dataset.stepJump);
  });

  form.addEventListener('change', () => {
    refreshSummary();
  });
  form.addEventListener('input', () => {
    refreshSummary();
  });

  form.addEventListener('submit', (event) => {
    event.preventDefault();

    for (let step = 1; step <= totalSteps; step += 1) {
      if (!validateStep(step, false)) {
        setStep(step);
        validateStep(step, true);
        return;
      }
    }

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.dataset._label = submitBtn.innerHTML;
      submitBtn.innerHTML = '<span>Sending...</span>';
    }

    if (window.SiteAjaxLoader) {
      window.SiteAjaxLoader.show();
    }

    fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken(),
        Accept: 'application/json',
      },
      credentials: 'same-origin',
    })
      .then((response) => response.json().then((data) => ({ ok: response.ok, status: response.status, data })).catch(() => ({ ok: false, status: response.status, data: null })))
      .then((res) => {
        if (res.ok && res.data?.ok) {
          window.alert(res.data.message || 'Your booking request has been sent.');
          form.reset();
          maxReached = 1;
          setStep(1);
          return;
        }

        const errors = res.data?.errors || {};
        Object.keys(errors).forEach((key) => {
          const name = key.startsWith('destinations') ? 'destinations' : key;
          const message = Array.isArray(errors[key]) ? errors[key][0] : String(errors[key]);
          setFieldError(name, message);
        });

        const firstErrorStep = panels.findIndex((panel) => {
          const fields = (panel.dataset.validate || '').split(',').map((item) => item.trim());
          return fields.some((field) => Boolean(errors[field] || (field === 'destinations' && errors['destinations.0'])));
        });
        if (firstErrorStep >= 0) {
          setStep(firstErrorStep + 1);
        }
      })
      .catch(() => {
        window.alert('Sorry, we could not send your booking request. Please try again.');
      })
      .finally(() => {
        if (window.SiteAjaxLoader) {
          window.SiteAjaxLoader.hide();
        }
        if (submitBtn) {
          submitBtn.disabled = false;
          if (submitBtn.dataset._label) {
            submitBtn.innerHTML = submitBtn.dataset._label;
          }
        }
      });
  });

  if (Number(form.dataset.startStep || 1) > 1) {
    maxReached = totalSteps;
  }

  setStep(current);

  if (form.dataset.successMessage) {
    window.alert(form.dataset.successMessage);
  }
})();
