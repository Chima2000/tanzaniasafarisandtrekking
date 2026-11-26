<script>
    document.addEventListener('click', function (event) {
        const dock = event.target.closest('[data-wa-dock]');
        const closer = event.target.closest('[data-wa-close]');
        const launcher = event.target.closest('.klt-whatsapp');

        if (closer && dock) {
            event.preventDefault();
            closeWhatsappDock(dock);
            return;
        }

        if (launcher && dock && launcher.closest('[data-wa-dock]') === dock) {
            event.preventDefault();
            if (dock.classList.contains('is-open')) {
                closeWhatsappDock(dock);
            } else {
                openWhatsappDock(dock);
            }
            return;
        }

        document.querySelectorAll('[data-wa-dock].is-open').forEach(function (openDock) {
            if (!openDock.contains(event.target)) {
                closeWhatsappDock(openDock);
            }
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') {
            return;
        }

        document.querySelectorAll('[data-wa-dock].is-open').forEach(closeWhatsappDock);
    });

    function openWhatsappDock(dock) {
        document.querySelectorAll('[data-wa-dock].is-open').forEach(closeWhatsappDock);
        dock.classList.add('is-open');
        const popup = dock.querySelector('.klt-wa-popup');
        const button = dock.querySelector('.klt-whatsapp');
        if (popup) {
            popup.hidden = false;
        }
        if (button) {
            button.setAttribute('aria-expanded', 'true');
        }
    }

    function closeWhatsappDock(dock) {
        dock.classList.remove('is-open');
        const popup = dock.querySelector('.klt-wa-popup');
        const button = dock.querySelector('.klt-whatsapp');
        if (popup) {
            popup.hidden = true;
        }
        if (button) {
            button.setAttribute('aria-expanded', 'false');
        }
    }
</script>
