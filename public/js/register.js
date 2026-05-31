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
