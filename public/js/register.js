/**
 * SPMB Register Form Interactions
 */

function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const visibilityIcon = document.getElementById('visibility-icon');

    if (passwordInput && visibilityIcon) {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            visibilityIcon.textContent = 'visibility';
        } else {
            passwordInput.type = 'password';
            visibilityIcon.textContent = 'visibility_off';
        }
    }
}

function toggleConfirmPasswordVisibility() {
    const passwordInput = document.getElementById('password_confirm');
    const visibilityIcon = document.getElementById('confirm-visibility-icon');

    if (passwordInput && visibilityIcon) {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            visibilityIcon.textContent = 'visibility';
        } else {
            passwordInput.type = 'password';
            visibilityIcon.textContent = 'visibility_off';
        }
    }
}

// Validasi kecocokan kata sandi secara real-time
document.addEventListener("DOMContentLoaded", function() {
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');
    const mismatchError = document.getElementById('password-mismatch-error');
    const form = document.querySelector('form');

    function checkPasswordsMatch() {
        if (!password || !passwordConfirm || !mismatchError) return;

        // Hanya validasi jika kolom konfirmasi kata sandi telah diisi text
        if (passwordConfirm.value.length > 0) {
            if (password.value !== passwordConfirm.value) {
                mismatchError.classList.remove('hidden');
                passwordConfirm.classList.add('border-error', 'focus:border-error', 'focus:ring-error', 'focus:ring-1');
                passwordConfirm.classList.remove('border-outline-variant', 'focus:border-primary', 'focus:ring-primary');
            } else {
                mismatchError.classList.add('hidden');
                passwordConfirm.classList.remove('border-error', 'focus:border-error', 'focus:ring-error');
                passwordConfirm.classList.add('border-outline-variant', 'focus:border-primary', 'focus:ring-primary');
            }
        } else {
            mismatchError.classList.add('hidden');
            passwordConfirm.classList.remove('border-error', 'focus:border-error', 'focus:ring-error');
            passwordConfirm.classList.add('border-outline-variant', 'focus:border-primary', 'focus:ring-primary');
        }
    }

    if (password && passwordConfirm) {
        password.addEventListener('input', checkPasswordsMatch);
        passwordConfirm.addEventListener('input', checkPasswordsMatch);
    }

    // Blokir pengiriman form jika kata sandi tidak cocok
    if (form) {
        form.addEventListener('submit', function(event) {
            if (password && passwordConfirm && password.value !== passwordConfirm.value) {
                event.preventDefault();
                checkPasswordsMatch();
                passwordConfirm.focus();
            }
        });
    }
});
