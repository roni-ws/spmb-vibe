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

    // ── Helper: Ambil CSRF hash dari hidden input di form ──────────
    function getCsrfHash() {
        var input = document.querySelector('form input[name="' + window.CSRF_TOKEN_NAME + '"]');
        return input ? input.value : '';
    }

    // ── Custom Form Validation (replaces native HTML5) ──────────
    function validateForm() {
        var errors = [];
        var fullName = document.getElementById('fullName');
        var email = document.getElementById('email');
        var username = document.getElementById('username');
        var password = document.getElementById('password');
        var passwordConfirm = document.getElementById('password_confirm');
        var terms = document.getElementById('terms');

        if (fullName && !fullName.value.trim()) {
            errors.push('Nama lengkap wajib diisi.');
        }
        if (email && !email.value.trim()) {
            errors.push('Alamat email wajib diisi.');
        } else if (email && email.value.trim() && !email.value.includes('@')) {
            errors.push('Format alamat email tidak valid.');
        }
        if (username && !username.value.trim()) {
            errors.push('Nama pengguna (username) wajib diisi.');
        } else if (username && username.value.trim().length < 3) {
            errors.push('Username minimal terdiri dari 3 karakter.');
        }
        if (password && !password.value) {
            errors.push('Kata sandi wajib diisi.');
        } else if (password && password.value) {
            if (!/(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])/.test(password.value)) {
                errors.push('Kata sandi harus mengandung kombinasi huruf, angka, dan simbol.');
            }
        }
        if (passwordConfirm && !passwordConfirm.value) {
            errors.push('Konfirmasi kata sandi wajib diisi.');
        } else if (password && passwordConfirm && password.value !== passwordConfirm.value) {
            errors.push('Konfirmasi kata sandi tidak cocok dengan kata sandi yang dimasukkan.');
        }
        if (terms && !terms.checked) {
            errors.push('Anda harus menyetujui Ketentuan Layanan dan Kebijakan Privasi.');
        }

        return errors;
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            var errors = validateForm();
            if (errors.length) {
                e.preventDefault();
                showFormAlert(form, errors);
            }
        });
    }

    // ── Real-time Email Check (on blur) ────────────────────────────
    var emailInput = document.getElementById('email');
    var emailStatus = document.getElementById('email-status');

    if (emailInput && emailStatus) {
        emailInput.addEventListener('blur', function () {
            var val = emailInput.value.trim();
            emailStatus.classList.add('hidden');
            if (!val || !val.includes('@')) return;

            var fd = new FormData();
            fd.append('email', val);
            fd.append(window.CSRF_TOKEN_NAME, getCsrfHash());

            fetch(window.BASE_URL + 'check-email', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    emailStatus.classList.remove('hidden');
                    if (data.available) {
                        emailStatus.className = 'text-caption mt-1 ml-1 flex items-center gap-xs text-on-tertiary-container';
                        emailStatus.innerHTML = '<span class="material-symbols-outlined text-sm">check_circle</span> ' + data.message;
                    } else {
                        emailStatus.className = 'text-caption mt-1 ml-1 flex items-center gap-xs text-red-600';
                        emailStatus.innerHTML = '<span class="material-symbols-outlined text-sm">error</span> ' + data.message;
                    }
                });
        });
    }

    // ── Real-time Username Check (on blur) ──────────────────────────
    var usernameInput = document.getElementById('username');
    var usernameStatus = document.getElementById('username-status');

    if (usernameInput && usernameStatus) {
        usernameInput.addEventListener('blur', function () {
            var val = usernameInput.value.trim();
            usernameStatus.classList.add('hidden');
            if (!val || val.length < 3) return;

            var fd = new FormData();
            fd.append('username', val);
            fd.append(window.CSRF_TOKEN_NAME, getCsrfHash());

            fetch(window.BASE_URL + 'check-username', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    usernameStatus.classList.remove('hidden');
                    if (data.available) {
                        usernameStatus.className = 'text-caption mt-1 ml-1 flex items-center gap-xs text-on-tertiary-container';
                        usernameStatus.innerHTML = '<span class="material-symbols-outlined text-sm">check_circle</span> ' + data.message;
                    } else {
                        usernameStatus.className = 'text-caption mt-1 ml-1 flex items-center gap-xs text-red-600';
                        usernameStatus.innerHTML = '<span class="material-symbols-outlined text-sm">error</span> ' + data.message;
                    }
                });
        });
    }
});
