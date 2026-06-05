/**
 * public/js/admin/pendaftar.js
 * JavaScript for the Admin "Data Pendaftar" page.
 * Handles: DataTables init, custom filters, checkbox/bulk select,
 *          action dropdown menus, verify modal, delete modal, toast.
 *
 * Requires globals set inline from PHP:
 *   window.BASE_URL          – base_url()
 *   window.CSRF_TOKEN_NAME   – csrf_token()
 *   window.CSRF_HASH         – csrf_hash()
 */

/* ── Set untuk menyimpan ID student terpilih (lintas halaman) ── */
const selectedStudentIds = new Set();

/* ── DataTables Init ─────────────────────────────────────── */
$(document).ready(function () {
    const table = $('#student-table').DataTable({
        responsive: false,
        pageLength: 15,
        lengthMenu: [10, 15, 25, 50],
        language: { url: window.BASE_URL + 'vendor/datatables/i18n/id.json' },
        columnDefs: [
            { orderable: false, targets: [0, 7] },
            { searchable: false, targets: [0, 6, 7] }
        ],
        order: [[4, 'desc']],
        drawCallback: function () { restoreCheckboxStates(); }
    });

    $('#custom-search').on('keyup', function () { table.search(this.value).draw(); });
    $('#status-filter').on('change', function () { table.column(5).search(this.value).draw(); });

    $('#reset-filter').on('click', function () {
        $('#custom-search').val('');
        $('#status-filter').val('');
        table.search('').columns().search('').draw();
    });

    // Move DataTables pagination & info into our footer
    $('#dt-paginate-wrapper').append($('.dataTables_paginate'));
    $('#table-info-text').text('');
    $('.dataTables_info').appendTo('#table-info-text');

    // View Student click handler
    $(document).on('click', '.view-student-btn', function () {
        const userId = $(this).data('id');
        openViewModal(userId, this);
    });

    // Edit Student click handler
    $(document).on('click', '.edit-student-btn', function () {
        const userId = $(this).data('id');
        openEditModal(userId, this);
    });

    // Delete Student click handler
    $(document).on('click', '.delete-student-btn', function () {
        const userId = $(this).data('id');
        const nama = $(this).data('nama');
        confirmDelete(userId, nama);
    });

    // Checkbox / Bulk Select event delegation
    $(document).on('change', '.row-checkbox', function () {
        if (this.checked) {
            selectedStudentIds.add(this.value);
        } else {
            selectedStudentIds.delete(this.value);
        }
        syncCheckboxes();
        updateBulkToolbar();
    });
    $(document).on('change', '#select-all', function (e) {
        if (e.target.checked) {
            $('.row-checkbox:visible').each(function () {
                selectedStudentIds.add(this.value);
                this.checked = true;
            });
        } else {
            $('.row-checkbox:visible').each(function () {
                selectedStudentIds.delete(this.value);
                this.checked = false;
            });
        }
        updateBulkToolbar();
    });
});

/* ── Checkbox / Bulk Select ──────────────────────────────── */
function syncCheckboxes() {
    const selectAll = document.getElementById('select-all');
    if (!selectAll) return;

    const $visibleCbs = $('.row-checkbox:visible');
    const $checkedVisibleCbs = $('.row-checkbox:visible:checked');

    if ($visibleCbs.length > 0 && $visibleCbs.length === $checkedVisibleCbs.length) {
        selectAll.checked = true;
    } else {
        selectAll.checked = false;
    }
}

function updateBulkToolbar() {
    const toolbar = document.getElementById('bulk-toolbar');
    const countEl = document.getElementById('selected-count');
    if (selectedStudentIds.size > 0) {
        toolbar.classList.remove('hidden');
        toolbar.classList.add('flex');
        const total = $('#student-table').DataTable().page.info().recordsTotal;
        countEl.textContent = selectedStudentIds.size + ' dari ' + total + ' data dipilih';
    } else {
        toolbar.classList.add('hidden');
        toolbar.classList.remove('flex');
    }
}

function restoreCheckboxStates() {
    $('.row-checkbox:visible').each(function () {
        this.checked = selectedStudentIds.has(this.value);
    });
    syncCheckboxes();
    updateBulkToolbar();
}

function openBulkDeleteModal() {
    document.getElementById('bulk-delete-count').textContent = selectedStudentIds.size;
    document.getElementById('bulk-delete-modal').style.display = 'flex';
}

function closeBulkDeleteModal() {
    document.getElementById('bulk-delete-modal').style.display = 'none';
}

document.getElementById('bulk-delete-modal')?.addEventListener('click', function (e) {
    if (e.target === this) closeBulkDeleteModal();
});

document.getElementById('bulk-delete-btn')?.addEventListener('click', function () {
    if (!selectedStudentIds.size) return;
    openBulkDeleteModal();
});

document.getElementById('bulk-confirm-delete-btn')?.addEventListener('click', function () {
    if (!selectedStudentIds.size) return;
    closeBulkDeleteModal();

    const ids = Array.from(selectedStudentIds);
    const fd  = new FormData();
    ids.forEach(id => fd.append('ids[]', id));
    fd.append(window.CSRF_TOKEN_NAME, window.CSRF_HASH);

    fetch(window.BASE_URL + 'admin/bulk-delete-students', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                selectedStudentIds.clear();
                location.reload();
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

/* ── Delete Modal ────────────────────────────────────────── */
function confirmDelete(userId, nama) {
    document.querySelectorAll('[id^="menu-"]').forEach(m => m.classList.add('hidden'));
    document.getElementById('delete-student-name').textContent = nama;
    document.getElementById('delete-form').action = window.BASE_URL + 'admin/delete-student/' + userId;
    const modal = document.getElementById('delete-modal');
    modal.style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('delete-modal').style.display = 'none';
}

document.getElementById('delete-modal')?.addEventListener('click', function (e) {
    if (e.target === this) closeDeleteModal();
});

/* ── Edit Modal ──────────────────────────────────────────── */
function populateEditField(id, value) {
    const el = document.getElementById(id);
    if (el) el.value = value ?? '';
}

function openEditModal(userId, btn) {
    document.querySelectorAll('[id^="menu-"]').forEach(m => m.classList.add('hidden'));
    const d = JSON.parse(btn.dataset.student);

    populateEditField('edit-nama', d.nama_lengkap);
    populateEditField('edit-no_registrasi', d.no_registrasi);
    populateEditField('edit-nik', d.nik);
    populateEditField('edit-no_kk', d.no_kk);
    populateEditField('edit-tempat_lahir', d.tempat_lahir);
    populateEditField('edit-tanggal_lahir', d.tanggal_lahir);
    populateEditField('edit-jenis_kelamin', d.jenis_kelamin);
    populateEditField('edit-anak_ke', d.anak_ke);
    populateEditField('edit-jumlah_saudara', d.jumlah_saudara);
    populateEditField('edit-nohp', d.no_hp);
    populateEditField('edit-alamat_desa', d.alamat_desa);
    populateEditField('edit-alamat_kecamatan', d.alamat_kecamatan);
    populateEditField('edit-alamat_kabupaten', d.alamat_kabupaten);
    populateEditField('edit-nama_ayah', d.nama_ayah);
    populateEditField('edit-pekerjaan_ayah', d.pekerjaan_ayah);
    populateEditField('edit-nama_ibu', d.nama_ibu);
    populateEditField('edit-pekerjaan_ibu', d.pekerjaan_ibu);
    populateEditField('edit-sekolah', d.nama_sekolah);
    populateEditField('edit-alamat_sekolah', d.alamat_sekolah);

    document.getElementById('edit-form').action = window.BASE_URL + 'admin/edit-student/' + userId;

    const modal = document.getElementById('edit-modal');
    modal.style.display = 'flex';
}

function closeEditModal() {
    const modal = document.getElementById('edit-modal');
    modal.style.display = 'none';
}

document.getElementById('edit-modal')?.addEventListener('click', function (e) {
    if (e.target === this) closeEditModal();
});

/* ── View Modal ──────────────────────────────────────────── */
function openViewModal(userId, btn) {
    document.querySelectorAll('[id^="menu-"]').forEach(m => m.classList.add('hidden'));
    const d = JSON.parse(btn.dataset.student);

    // Biodata
    document.getElementById('view-nisn').textContent = d.nisn || '-';
    document.getElementById('view-nama').textContent = d.nama_lengkap || '-';
    document.getElementById('view-no_registrasi').textContent = d.no_registrasi || '-';
    document.getElementById('view-nik').textContent = d.nik || '-';
    document.getElementById('view-no_kk').textContent = d.no_kk || '-';
    document.getElementById('view-tempat_lahir').textContent = d.tempat_lahir || '-';
    document.getElementById('view-tanggal_lahir').textContent = d.tanggal_lahir || '-';
    document.getElementById('view-jenis_kelamin').textContent = d.jenis_kelamin === 'L' ? 'Laki-laki' : d.jenis_kelamin === 'P' ? 'Perempuan' : '-';
    document.getElementById('view-anak_ke').textContent = d.anak_ke ?? '-';
    document.getElementById('view-jumlah_saudara').textContent = d.jumlah_saudara ?? '-';
    document.getElementById('view-nohp').textContent = d.no_hp || '-';

    // Alamat
    document.getElementById('view-alamat_desa').textContent = d.alamat_desa || '-';
    document.getElementById('view-alamat_kecamatan').textContent = d.alamat_kecamatan || '-';
    document.getElementById('view-alamat_kabupaten').textContent = d.alamat_kabupaten || '-';

    // Orang Tua
    document.getElementById('view-nama_ayah').textContent = d.nama_ayah || '-';
    document.getElementById('view-pekerjaan_ayah').textContent = d.pekerjaan_ayah || '-';
    document.getElementById('view-nama_ibu').textContent = d.nama_ibu || '-';
    document.getElementById('view-pekerjaan_ibu').textContent = d.pekerjaan_ibu || '-';

    // Sekolah
    document.getElementById('view-sekolah').textContent = d.nama_sekolah || '-';
    document.getElementById('view-alamat_sekolah').textContent = d.alamat_sekolah || '-';

    // Status badge (verifikasi)
    const badge = document.getElementById('view-status_badge');
    let statusText = d.status_verifikasi || 'Belum Verifikasi';
    if (d.is_finalized && (statusText === 'Revisi' || statusText === 'Tidak Valid')) {
        statusText = 'Belum Verifikasi';
    }
    badge.textContent = statusText;
    const stClass = statusText === 'Valid' ? 'badge-valid' : statusText === 'Revisi' ? 'badge-invalid' : 'badge-pending';
    badge.className = 'px-2 py-0.5 rounded-full text-caption font-bold inline-block ' + stClass;

    // Final badge
    const finalBadge = document.getElementById('view-final_badge');
    finalBadge.textContent = d.is_finalized ? 'Final' : 'Belum Final';
    finalBadge.className = 'px-2 py-0.5 rounded-full text-caption font-bold inline-block badge-' + (d.is_finalized ? 'valid' : 'default');

    // Verifikasi form — populate current values
    document.getElementById('view-verify-status').value = d.status_verifikasi_id || 1;
    document.getElementById('view-verify-catatan').value = d.catatan_verifikasi || '';
    document.getElementById('view-verify-form').action = window.BASE_URL + 'admin/verify/' + userId;

    // Tgl Daftar
    const tgl = d.created_at ? new Date(d.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
    document.getElementById('view-tgl_daftar').textContent = tgl;

    // Pas Foto
    const img = document.getElementById('view-pas-foto');
    const placeholder = document.getElementById('view-pas-foto-placeholder');
    if (d.pas_foto) {
        img.src = window.BASE_URL + 'uploads/berkas/' + d.pas_foto;
        img.classList.remove('hidden');
        placeholder?.classList.add('hidden');
    } else {
        img.classList.add('hidden');
        placeholder?.classList.remove('hidden');
    }

    // Dokumen Unggahan
    function setBerkasLink(file, linkId, badgeId) {
        const link = document.getElementById(linkId);
        const badge = document.getElementById(badgeId);
        if (file) {
            link.href = window.BASE_URL + 'uploads/berkas/' + file;
            link.classList.remove('hidden');
            badge.textContent = 'SUDAH DIUNGGAH';
            badge.className = 'px-2 py-0.5 rounded-full text-caption font-bold badge-valid';
        } else {
            link.classList.add('hidden');
            badge.textContent = 'BELUM DIUNGGAH';
            badge.className = 'px-2 py-0.5 rounded-full text-caption font-bold badge-pending';
        }
    }
    setBerkasLink(d.fc_ijazah_skl, 'view-link-ijazah', 'view-badge-ijazah');
    setBerkasLink(d.fc_kk, 'view-link-kk', 'view-badge-kk');

    const modal = document.getElementById('view-modal');
    modal.style.display = 'flex';
}

function closeViewModal() {
    const modal = document.getElementById('view-modal');
    modal.style.display = 'none';
}

document.getElementById('view-modal')?.addEventListener('click', function (e) {
    if (e.target === this) closeViewModal();
});

/* ── Add Modal (Multi-Step) ──────────────────────────────── */
let currentAddStep = 1;
const totalAddSteps = 4;

function openAddModal() {
    document.querySelectorAll('[id^="menu-"]').forEach(m => m.classList.add('hidden'));
    document.getElementById('add-form').reset();
    currentAddStep = 1;
    updateAddStepper();
    const modal = document.getElementById('add-modal');
    modal.style.display = 'flex';
}

function closeAddModal() {
    const modal = document.getElementById('add-modal');
    modal.style.display = 'none';
}

function updateAddStepper() {
    document.getElementById('add-step-indicator-text').innerText = currentAddStep;
    
    // Update contents
    for (let i = 1; i <= totalAddSteps; i++) {
        const content = document.getElementById(`add-step-content-${i}`);
        if (content) {
            content.classList.toggle('hidden', i !== currentAddStep);
        }
    }

    // Update circles
    for (let i = 1; i <= totalAddSteps; i++) {
        const circle = document.getElementById(`add-step-circle-${i}`);
        if (circle) {
            if (i < currentAddStep) {
                // Completed
                circle.className = 'w-8 h-8 rounded-full flex items-center justify-center text-caption font-bold bg-primary text-on-primary border-2 border-primary transition-colors';
                circle.innerHTML = '<span class="material-symbols-outlined text-[16px]">check</span>';
            } else if (i === currentAddStep) {
                // Current
                circle.className = 'w-8 h-8 rounded-full flex items-center justify-center text-caption font-bold bg-primary text-on-primary border-2 border-primary transition-colors';
                circle.innerHTML = i;
            } else {
                // Future
                circle.className = 'w-8 h-8 rounded-full flex items-center justify-center text-caption font-bold bg-surface-container-high text-on-surface-variant border-2 border-outline-variant transition-colors';
                circle.innerHTML = i;
            }
        }
    }

    // Update progress bar line
    const progress = document.getElementById('add-stepper-progress');
    if (progress) {
        progress.style.width = ((currentAddStep - 1) / (totalAddSteps - 1)) * 100 + '%';
    }

    // Update buttons
    const btnPrev = document.getElementById('add-btn-prev');
    const btnCancel = document.getElementById('add-btn-cancel');
    const btnNext = document.getElementById('add-btn-next');
    const btnSubmit = document.getElementById('add-btn-submit');

    if (currentAddStep === 1) {
        btnPrev.classList.add('hidden');
        btnCancel.classList.remove('hidden');
    } else {
        btnPrev.classList.remove('hidden');
        btnCancel.classList.add('hidden');
    }

    if (currentAddStep === totalAddSteps) {
        btnNext.classList.add('hidden');
        btnSubmit.classList.remove('hidden');
    } else {
        btnNext.classList.remove('hidden');
        btnSubmit.classList.add('hidden');
    }
}

function validateAddStep(step) {
    const container = document.getElementById(`add-step-content-${step}`);
    if (!container) return true;
    const inputs = container.querySelectorAll('input[required], select[required], textarea[required]');
    for (const input of inputs) {
        if (!input.checkValidity()) {
            input.reportValidity();
            return false;
        }
    }
    return true;
}

function addNextStep() {
    if (validateAddStep(currentAddStep) && currentAddStep < totalAddSteps) {
        currentAddStep++;
        updateAddStepper();
    }
}

function addPrevStep() {
    if (currentAddStep > 1) {
        currentAddStep--;
        updateAddStepper();
    }
}

document.getElementById('add-modal')?.addEventListener('click', function (e) {
    if (e.target === this) closeAddModal();
});
