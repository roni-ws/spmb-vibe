/**
 * public/js/admin.js
 * Shared JavaScript for the Admin Dashboard layout.
 * Handles: mobile sidebar toggle.
 */

/**
 * Toggle the mobile sidebar open/closed.
 * Called by the hamburger button in the top app bar.
 */
function toggleSidebar() {
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const isHidden = sidebar.classList.contains('-translate-x-full');
    sidebar.classList.toggle('-translate-x-full', !isHidden);
    overlay.classList.toggle('hidden', !isHidden);
}
