(() => {
  const modal = document.getElementById('packageBookingModal');
  const checkForm = document.getElementById('packageAvailabilityForm');
  if (!modal || !checkForm) {
    return;
  }

  const bookForm = document.getElementById('bookNowForm');
  const travelersSelect = checkForm.querySelector('[name="travelers"]');
  const dateInput = checkForm.querySelector('#datepicker');
  const hint = checkForm.querySelector('[data-availability-hint]');
  const sidebarTotal = document.querySelector('[data-sidebar-estimated-total]');
  const modalTotal = modal.querySelector('[data-booking-estimated-total]');
  const pricePerPerson = Number(modal.dataset.pricePerPerson || 0);
  const priceLabel = modal.dataset.priceLabel || '$0';
  let blockedDates = [];

  try {
    blockedDates = JSON.parse(modal.dataset.blockedDates || '[]');
  } catch (error) {
    blockedDates = [];
  }

  function formatMoney(amount) {
    if (!Number.isFinite(amount) || amount <= 0) {
      return priceLabel;
    }
    return `$${Math.round(amount).toLocaleString('en-US')}`;
  }

  function travelerCount() {
    const raw = String(travelersSelect?.value || '2');
    if (raw === '6+') {
      return 6;
    }
    return Math.max(1, Number(raw) || 1);
  }

  function updateTotals() {
    const total = pricePerPerson * travelerCount();
    const label = formatMoney(total);
    if (sidebarTotal) {
      sidebarTotal.textContent = label;
    }
    if (modalTotal) {
      modalTotal.textContent = label;
    }
  }

  function toIsoDate(value) {
    if (!value) {
      return '';
    }
    if (/^\d{4}-\d{2}-\d{2}$/.test(value)) {
      return value;
    }
    const parsed = window.Date.parse(value);
    if (Number.isNaN(parsed)) {
      return '';
    }
    const date = new Date(parsed);
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${date.getFullYear()}-${month}-${day}`;
  }

  function isBlocked(isoDate) {
    return blockedDates.includes(isoDate);
  }

  function setHint(message, state) {
    if (!hint) {
      return;
    }
    hint.textContent = message || '';
    hint.classList.remove('is-error', 'is-ok');
    if (state) {
      hint.classList.add(state);
    }
  }

  function openModal() {
    modal.hidden = false;
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('overflow-hidden');
    const firstField = bookForm?.querySelector('input, select, textarea, button');
    firstField?.focus();
  }

  function closeModal() {
    modal.hidden = true;
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('overflow-hidden');
  }

  function syncIntoBookingForm(isoDate) {
    if (!bookForm) {
      return;
    }

    const adultsField = bookForm.querySelector('[name="adults"]');
    const travelDateField = bookForm.querySelector('[name="travel_date"]');
    const childrenField = bookForm.querySelector('[name="children"]');

    if (adultsField) {
      adultsField.value = String(travelerCount());
    }
    if (travelDateField && isoDate) {
      travelDateField.value = isoDate;
    }
    if (childrenField && !childrenField.value) {
      childrenField.value = '0';
    }

    updateTotals();
  }

  if (window.jQuery && dateInput) {
    const $ = window.jQuery;
    $(dateInput).datepicker('destroy');
    $(dateInput).datepicker({
      dateFormat: 'yy-mm-dd',
      minDate: 0,
      beforeShowDay(date) {
        const iso = $.datepicker.formatDate('yy-mm-dd', date);
        if (isBlocked(iso)) {
          return [false, 'ui-state-disabled', 'Unavailable'];
        }
        return [true, '', 'Available'];
      },
    });
  }

  travelersSelect?.addEventListener('change', updateTotals);
  dateInput?.addEventListener('change', () => setHint('', null));

  checkForm.addEventListener('submit', (event) => {
    event.preventDefault();

    const isoDate = toIsoDate(dateInput?.value || '');
    if (!isoDate) {
      setHint('Please choose a travel date on the calendar.', 'is-error');
      dateInput?.focus();
      return;
    }

    if (isBlocked(isoDate)) {
      setHint('That date is unavailable. Please pick another day.', 'is-error');
      return;
    }

    setHint('Dates look open — continue booking below.', 'is-ok');
    syncIntoBookingForm(isoDate);
    openModal();
  });

  modal.querySelectorAll('[data-booking-modal-close]').forEach((el) => {
    el.addEventListener('click', closeModal);
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !modal.hidden) {
      closeModal();
    }
  });

  updateTotals();
})();
