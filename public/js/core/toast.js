/* ── Toast Notification ──────────────────────────────────── */
function showToast(message, type) {
    type = type || 'success';
    const colors = type === 'success'
        ? 'bg-tertiary-fixed-dim text-on-tertiary-fixed border-l-tertiary'
        : 'bg-error-container text-on-error-container border-l-error';
    const icon = type === 'success' ? 'check_circle' : 'error';

    const toast = document.createElement('div');
    toast.className = 'w-full flex items-start gap-3 px-4 py-3 rounded-xl border-l-4 shadow-sm text-body-sm font-semibold mb-4 z-[100] transition-all duration-300 opacity-0 -translate-y-2 ' + colors;
    toast.innerHTML = '<span class="material-symbols-outlined shrink-0 text-lg">' + icon + '</span> <span>' + message + '</span>';

    var target = document.querySelector('.filter-bar');
    if (!target) { var tbl = document.querySelector('table'); if (tbl) target = tbl.parentNode; }
    if (!target) target = document.querySelector('main');
    if (target) target.parentNode.insertBefore(toast, target);
    else document.body.insertBefore(toast, document.body.firstChild);

    setTimeout(function () {
        toast.classList.replace('opacity-0', 'opacity-100');
        toast.classList.replace('-translate-y-2', 'translate-y-0');
    }, 50);
    setTimeout(function () {
        toast.classList.replace('opacity-100', 'opacity-0');
        setTimeout(function () { toast.remove(); }, 300);
    }, 3500);
}

/* ── Flash Data → Toast on page load ────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    var flashEl = document.getElementById('flash-toast-data');
    if (flashEl) {
        try {
            var data = JSON.parse(flashEl.textContent);
            if (data.success) showToast(data.success, 'success');
            else if (data.error) showToast(data.error, 'error');
            else if (data.message) showToast(data.message, 'success');
            else if (data.errors) {
                var msgs = Array.isArray(data.errors) ? data.errors : Object.values(data.errors);
                if (msgs.length) showToast(msgs.join(', '), 'error');
            }
        } catch (e) {
            // ignore JSON parse errors
        }
    }
});
