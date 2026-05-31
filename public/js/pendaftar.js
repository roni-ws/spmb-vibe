/**
 * public/js/pendaftar.js
 * JavaScript for the Admin "Data Pendaftar" page.
 * Handles: DataTables init, custom filters, checkbox/bulk select,
 *          action dropdown menus, verify modal, delete modal, toast.
 *
 * Requires globals set inline from PHP:
 *   window.BASE_URL          – base_url()
 *   window.CSRF_TOKEN_NAME   – csrf_token()
 *   window.CSRF_HASH         – csrf_hash()
 */

/* ── DataTables Init ─────────────────────────────────────── */
$(document).ready(function () {
    const table = $('#student-table').DataTable({
        responsive: true,
        pageLength: 15,
        lengthMenu: [10, 15, 25, 50],
        language: { url: window.BASE_URL + 'vendor/datatables/i18n/id.json' },
        columnDefs: [
            { orderable: false, targets: [0, 8] },
            { searchable: false, targets: [0, 7, 8] }
        ],
        order: [[4, 'desc']],
        drawCallback: function () { syncCheckboxes(); }
    });

    $('#custom-search').on('keyup', function () { table.search(this.value).draw(); });
    $('#status-filter').on('change', function () { table.column(5).search(this.value).draw(); });
    $('#kelulusan-filter').on('change', function () { table.column(6).search(this.value).draw(); });

    $('#reset-filter').on('click', function () {
        $('#custom-search').val('');
        $('#status-filter').val('');
        $('#kelulusan-filter').val('');
        table.search('').columns().search('').draw();
    });

    // Move DataTables pagination & info into our footer
    $('#dt-paginate-wrapper').append($('.dataTables_paginate'));
    $('#table-info-text').text('');
    $('.dataTables_info').appendTo('#table-info-text');
});

/* ── Checkbox / Bulk Select ──────────────────────────────── */
function syncCheckboxes() {
    const selectAll = document.getElementById('select-all');
    const rowCbs    = document.querySelectorAll('.row-checkbox');
    rowCbs.forEach(cb => cb.addEventListener('change', updateBulkToolbar));
    if (selectAll) {
        selectAll.addEventListener('change', function (e) {
            rowCbs.forEach(cb => { cb.checked = e.target.checked; });
            updateBulkToolbar();
        });
    }
}

function updateBulkToolbar() {
    const checked = document.querySelectorAll('.row-checkbox:checked');
    const toolbar = document.getElementById('bulk-toolbar');
    const countEl = document.getElementById('selected-count');
    if (checked.length > 0) {
        toolbar.classList.remove('hidden');
        toolbar.classList.add('flex');
        countEl.textContent = checked.length;
    } else {
        toolbar.classList.add('hidden');
        toolbar.classList.remove('flex');
    }
}

document.getElementById('bulk-delete-btn')?.addEventListener('click', function () {
    const checked = Array.from(document.querySelectorAll('.row-checkbox:checked'));
    if (!checked.length) return;
    if (!confirm(`Hapus ${checked.length} pendaftar terpilih secara permanen?`)) return;

    const ids = checked.map(cb => cb.value);
    const fd  = new FormData();
    ids.forEach(id => fd.append('ids[]', id));
    fd.append(window.CSRF_TOKEN_NAME, window.CSRF_HASH);

    fetch(window.BASE_URL + 'admin/bulk-delete', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                checked.forEach(cb => cb.closest('tr').remove());
                updateBulkToolbar();
                showToast(data.message, 'success');
            } else {
                showToast(data.message, 'error');
            }
        });
});

/* ── Action Dropdown Menus ───────────────────────────────── */
function toggleActionMenu(id) {
    const all = document.querySelectorAll('[id^="menu-"]');
    all.forEach(m => { if (m.id !== id) m.classList.add('hidden'); });
    document.getElementById(id).classList.toggle('hidden');
}

window.addEventListener('click', function (e) {
    if (!e.target.closest('.relative')) {
        document.querySelectorAll('[id^="menu-"]').forEach(m => m.classList.add('hidden'));
    }
});

/* ── Verify Modal ────────────────────────────────────────── */
function openVerifyModal(userId, status, kelulusan, nama) {
    document.querySelectorAll('[id^="menu-"]').forEach(m => m.classList.add('hidden'));
    document.getElementById('modal-student-name').textContent = nama;
    document.getElementById('modal-status').value    = status || 'Pending';
    document.getElementById('modal-kelulusan').value = kelulusan || '';
    document.getElementById('verify-form').action    = window.BASE_URL + 'admin/verify/' + userId;
    const modal = document.getElementById('verify-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeVerifyModal() {
    const modal = document.getElementById('verify-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('verify-modal')?.addEventListener('click', function (e) {
    if (e.target === this) closeVerifyModal();
});

/* ── Delete Modal ────────────────────────────────────────── */
let deleteTargetId = null;

function confirmDelete(userId, nama) {
    document.querySelectorAll('[id^="menu-"]').forEach(m => m.classList.add('hidden'));
    deleteTargetId = userId;
    document.getElementById('delete-student-name').textContent = nama;
    const modal = document.getElementById('delete-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
    document.getElementById('delete-modal').classList.remove('flex');
    deleteTargetId = null;
}

document.getElementById('confirm-delete-btn')?.addEventListener('click', function () {
    if (!deleteTargetId) return;
    const fd = new FormData();
    fd.append(window.CSRF_TOKEN_NAME, window.CSRF_HASH);

    fetch(window.BASE_URL + 'admin/delete-student/' + deleteTargetId, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            closeDeleteModal();
            if (data.status === 'success') {
                const row = document.querySelector(`tr[data-id="${deleteTargetId}"]`);
                if (row) row.remove();
                showToast(data.message, 'success');
            } else {
                showToast(data.message, 'error');
            }
        });
});

document.getElementById('delete-modal')?.addEventListener('click', function (e) {
    if (e.target === this) closeDeleteModal();
});

/* ── Toast Notification ──────────────────────────────────── */
function showToast(message, type = 'success') {
    const colors = type === 'success'
        ? 'bg-tertiary-container text-on-surface border-on-tertiary-container/30'
        : 'bg-error-container text-on-error-container border-on-error-container/30';
    const icon = type === 'success' ? 'check_circle' : 'error';

    const toast = document.createElement('div');
    toast.className = `fixed bottom-24 md:bottom-6 right-4 flex items-center gap-3 px-4 py-3 rounded-2xl border shadow-lg text-body-sm font-semibold z-[100] ${colors} transition-all duration-300 opacity-0 translate-y-2`;
    toast.innerHTML = `<span class="material-symbols-outlined">${icon}</span> ${message}`;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.replace('opacity-0', 'opacity-100');
        toast.classList.replace('translate-y-2', 'translate-y-0');
    }, 50);
    setTimeout(() => {
        toast.classList.replace('opacity-100', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}
