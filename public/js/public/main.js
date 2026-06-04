/**
 * SPMB Frontend Public Interactions
 */

function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    const icon = document.getElementById('hamburger-icon');
    if (menu) {
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            if (icon) icon.textContent = 'close';
        } else {
            menu.classList.add('hidden');
            if (icon) icon.textContent = 'menu';
        }
    }
}
