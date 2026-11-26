document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
        button.addEventListener('click', function () {
            var input = document.getElementById(button.getAttribute('data-password-toggle'));
            var icon = button.querySelector('i');

            if (! input) {
                return;
            }

            var isHidden = input.getAttribute('type') === 'password';
            input.setAttribute('type', isHidden ? 'text' : 'password');
            button.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');

            if (icon) {
                icon.classList.toggle('fa-eye', ! isHidden);
                icon.classList.toggle('fa-eye-slash', isHidden);
            }
        });
    });

    var flipCard = document.getElementById('authFlipCard');

    if (! flipCard) {
        return;
    }

    document.querySelectorAll('[data-auth-flip]').forEach(function (trigger) {
        trigger.addEventListener('click', function (event) {
            event.preventDefault();
            flipCard.classList.toggle('is-flipped', trigger.getAttribute('data-auth-flip') === 'reset');
        });
    });
});
