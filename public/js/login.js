/**
 * SPMB Login Form Interactions
 */

function switchRole(role) {
    const btnStudent = document.getElementById('btn-student');
    const btnAdmin = document.getElementById('btn-admin');
    const labelIdentifier = document.getElementById('label-identifier');
    const inputIdentifier = document.getElementById('identifier');
    const roleInput = document.getElementById('role');

    if (!btnStudent || !btnAdmin) return;

    const activeClasses = ['bg-surface-container-lowest', 'dark:bg-slate-700', 'text-on-surface', 'dark:text-white', 'shadow-sm'];
    const inactiveClasses = ['text-on-surface-variant', 'dark:text-slate-400', 'bg-transparent', 'shadow-none', 'hover:bg-surface-variant/50', 'hover:dark:bg-slate-700/50'];

    if (role === 'student') {
        btnStudent.classList.add(...activeClasses);
        btnStudent.classList.remove(...inactiveClasses);
        btnStudent.classList.add('hover:text-on-surface'); 

        btnAdmin.classList.add(...inactiveClasses);
        btnAdmin.classList.remove(...activeClasses);
        
        if (labelIdentifier) labelIdentifier.textContent = 'Email atau Username';
        if (inputIdentifier) inputIdentifier.placeholder = 'Masukkan email terdaftar';
        if (roleInput) roleInput.value = 'student';
    } else {
        btnAdmin.classList.add(...activeClasses);
        btnAdmin.classList.remove(...inactiveClasses);
        btnAdmin.classList.add('hover:text-on-surface');

        btnStudent.classList.add(...inactiveClasses);
        btnStudent.classList.remove(...activeClasses);
        
        if (labelIdentifier) labelIdentifier.textContent = 'Username atau NIP';
        if (inputIdentifier) inputIdentifier.placeholder = 'Masukkan username atau NIP admin';
        if (roleInput) roleInput.value = 'admin';
    }
}

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

// Set initial role tab on page load based on old session input (preserves active tab after failed login refresh)
document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById('login-container');
    const initialRole = container ? container.dataset.role : 'student';
    switchRole(initialRole);
});
