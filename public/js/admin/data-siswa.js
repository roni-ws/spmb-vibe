/* ── Set untuk menyimpan ID student terpilih (lintas halaman) ── */
const selectedStudentIds = new Set();
let gridSiswa = null;

/* ── Grid.js Init ──────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    const avatarColors = [
        'bg-primary-container text-on-primary-container',
        'bg-secondary-container text-on-secondary-container',
        'bg-tertiary-container text-on-tertiary-container',
        'bg-surface-container-high text-primary',
    ];

    gridSiswa = new gridjs.Grid({
        plugins: [
            {
                id: 'selection',
                component: gridjs.plugins.selection.RowSelection,
            },
        ],
        columns: [
            {
                id: 'selection',
                name: gridjs.html('<input type="checkbox" id="grid-select-all" class="rounded border-outline-variant text-primary focus:ring-primary cursor-pointer" />'),
                width: '50px',
                sort: false,
                plugin: {
                    component: gridjs.plugins.selection.RowSelection,
                },
            },
            {
                data: (row) => row,
                name: 'Calon Siswa',
                formatter: (cell) => {
                    const nama = cell.nama_lengkap || cell.username || '-';
                    const inisial = nama.trim().substring(0, 2).toUpperCase();
                    const colorIdx = Math.abs(cell.id % avatarColors.length);
                    return gridjs.html(
                        '<div class="flex items-center gap-2.5">' +
                            '<div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 ' + avatarColors[colorIdx] + '">' + inisial + '</div>' +
                            '<div class="min-w-0">' +
                                '<p class="font-semibold text-body-sm text-on-surface truncate" style="max-width:140px">' + escHtml(nama) + '</p>' +
                                '<p class="text-caption text-on-surface-variant truncate" style="max-width:140px">NISN: ' + escHtml(cell.nisn || '-') + '</p>' +
                            '</div>' +
                        '</div>'
                    );
                },
            },
            {
                id: 'no_hp',
                name: 'No. Telp',
                formatter: (cell) => cell || '-',
            },
            {
                id: 'nik',
                name: 'NIK',
                hidden: true,
                formatter: (cell) => cell || '-',
            },
            {
                id: 'nama_sekolah',
                name: 'Asal Sekolah',
                formatter: (cell) => cell || '-',
            },

            {
                data: (row) => row,
                name: 'Status Berkas',
                formatter: (cell) => {
                    let status = cell.status_verifikasi || 'Belum Verifikasi';
                    if (Number(cell.is_finalized) && (status === 'Revisi' || status === 'Tidak Valid')) {
                        status = 'Belum Verifikasi';
                    }
                    const cls = status === 'Valid' ? 'badge-valid' : status === 'Revisi' ? 'badge-invalid' : 'badge-pending';
                    const icon = status === 'Valid' ? 'check_circle' : status === 'Revisi' ? 'edit_note' : 'pending_actions';
                    return gridjs.html(
                        '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold whitespace-nowrap ' + cls + '">' +
                            '<span class="material-symbols-outlined" style="font-size:14px">' + icon + '</span> ' + status +
                        '</span>'
                    );
                },
            },
            {
                data: (row) => row,
                name: 'Finalisasi',
                formatter: (cell) => {
                    if (Number(cell.is_finalized)) {
                        return gridjs.html(
                            '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-caption font-semibold bg-tertiary-container/40 text-on-tertiary-container">' +
                                '<span class="material-symbols-outlined" style="font-size:14px">lock</span> Final' +
                            '</span>'
                        );
                    }
                    return gridjs.html(
                        '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-caption font-semibold bg-surface-container text-on-surface-variant">' +
                            '<span class="material-symbols-outlined" style="font-size:14px">lock_open</span> Belum' +
                        '</span>'
                    );
                },
            },
            {
                data: (row) => row,
                name: gridjs.html('<div style="text-align:center">Aksi</div>'),
                width: '120px',
                sort: false,
                formatter: (cell) => {
                    const studentJson = escAttr(JSON.stringify(cell));
                    return gridjs.html(
                        '<div class="flex items-center justify-center gap-0">' +
                            '<button class="view-student-btn p-1 text-info hover:bg-info-container/20 rounded-full transition-colors" title="Lihat Detail" data-student="' + studentJson + '">' +
                                '<span class="material-symbols-outlined" style="font-size:16px">visibility</span>' +
                            '</button>' +
                            '<button class="delete-student-btn p-1 text-error hover:bg-error-container/20 rounded-full transition-colors" title="Hapus Calon Siswa" data-student="' + studentJson + '">' +
                                '<span class="material-symbols-outlined" style="font-size:16px">delete</span>' +
                            '</button>' +
                            '<a href="' + window.BASE_URL + 'admin/arsip/' + cell.id + '" target="_blank" class="p-1 text-secondary hover:bg-secondary-container/20 rounded-full transition-colors" title="Cetak Arsip">' +
                                '<span class="material-symbols-outlined" style="font-size:16px">print</span>' +
                            '</a>' +
                        '</div>'
                    );
                },
            },
        ],
        server: {
            url: window.BASE_URL + 'admin/grid-data',
            then: data => data.data,
            total: data => data.total,
        },
        pagination: {
            limit: 15,
            server: {
                url: function (prev, page, limit) {
                    const url = new URL(prev, window.location.origin);
                    url.searchParams.set('limit', limit);
                    url.searchParams.set('offset', page * limit);
                    const searchEl = document.getElementById('custom-search');
                    if (searchEl && searchEl.value)
                        url.searchParams.set('search', searchEl.value);
                    const statusEl = document.getElementById('status-filter');
                    if (statusEl && statusEl.value)
                        url.searchParams.set('status', statusEl.value);
                    return url.toString();
                },
            },
        },
        search: false,
        sort: false,
        className: {
            table: 'w-full text-left border-collapse',
            th: 'p-3 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider bg-surface-container-low border-b border-outline-variant',
            td: 'p-3 text-body-sm',
            header: 'bg-surface-container-low',
            footer: 'gridjs-footer-custom',
        },
    });

    gridSiswa.render(document.getElementById('grid-siswa-wrapper'));

    /* ── RowSelection Store Sync ─────────────────────────── */
    const gridRowIdToStudentId = new Map();
    gridSiswa.config.store.subscribe(function (state) {
        if (state.data && state.data.rows) {
            gridRowIdToStudentId.clear();
            state.data.rows.forEach(function (row) {
                const studentData = row.cells[1]?.data;
                if (studentData && studentData.id)
                    gridRowIdToStudentId.set(row.id, studentData.id);
            });
        }
        const selectedRowIds = state.rowSelection?.rowIds || [];
        selectedStudentIds.clear();
        selectedRowIds.forEach(function (gridRowId) {
            const studentId = gridRowIdToStudentId.get(gridRowId);
            if (studentId) selectedStudentIds.add(String(studentId));
        });
        updateBulkToolbar();
    });

    /* ── Event Delegation: Action Buttons ────────────────── */
    document.getElementById('grid-siswa-wrapper').addEventListener('click', function (e) {
        const btn = e.target.closest('button');
        if (!btn) return;

        document.querySelectorAll('[id^="menu-"]').forEach(function (m) { m.classList.add('hidden'); });

        if (btn.classList.contains('view-student-btn')) {
            const d = JSON.parse(btn.dataset.student);
            openViewModal(d.id, d);
        } else if (btn.classList.contains('delete-student-btn')) {
            const d = JSON.parse(btn.dataset.student);
            confirmDelete(d.id, d.nama_lengkap || d.username || '-');
        }
    });

    /* ── Select-All Checkbox ─────────────────────────────── */
    document.getElementById('grid-siswa-wrapper').addEventListener('change', function (e) {
        if (e.target.id === 'grid-select-all') {
            const checked = e.target.checked;
            const state = gridSiswa.config.store.getState();
            const rows = state.data?.rows || [];
            const RowSelectionActions = gridjs.plugins.selection.RowSelectionActions;
            rows.forEach(function (row) {
                if (checked) {
                    gridSiswa.config.store.dispatch(RowSelectionActions.CheckRow(row.id));
                } else {
                    gridSiswa.config.store.dispatch(RowSelectionActions.UncheckRow(row.id));
                }
            });
        }
    });

    /* ── Select-All Checkbox Sync (after store update via API) ─ */
    gridSiswa.config.store.subscribe(function (state) {
        const selectAll = document.getElementById('grid-select-all');
        if (!selectAll) return;
        const rows = state.data?.rows || [];
        const selectedRowIds = state.rowSelection?.rowIds || [];
        if (rows.length > 0 && selectedRowIds.length === rows.length) {
            selectAll.checked = true;
            selectAll.indeterminate = false;
        } else if (selectedRowIds.length > 0) {
            selectAll.checked = false;
            selectAll.indeterminate = true;
        } else {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        }
    });

    /* ── Status Filter ───────────────────────────────────── */
    document.getElementById('status-filter').addEventListener('change', function () {
        gridSiswa.forceRender();
    });

    /* ── Custom Search ───────────────────────────────────── */
    let searchTimeout;
    document.getElementById('custom-search').addEventListener('input', function () {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function () {
            gridSiswa.forceRender();
        }, 300);
    });

    /* ── Reset Filter ────────────────────────────────────── */
    document.getElementById('reset-filter').addEventListener('click', function () {
        document.getElementById('custom-search').value = '';
        document.getElementById('status-filter').value = '';
        gridSiswa.forceRender();
    });
});

/* ── Helper: escape HTML ──────────────────────────────────── */
function escHtml(str) {
    const d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}

function escAttr(str) {
    return str.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}

/* ── Bulk Select / Toolbar ────────────────────────────────── */
function updateBulkToolbar() {
    const toolbar = document.getElementById('bulk-toolbar');
    const countEl = document.getElementById('selected-count');
    if (selectedStudentIds.size > 0) {
        toolbar.classList.remove('hidden');
        toolbar.classList.add('flex');
        countEl.textContent = selectedStudentIds.size + ' data dipilih';
    } else {
        toolbar.classList.add('hidden');
        toolbar.classList.remove('flex');
    }
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
    const fd = new FormData();
    ids.forEach(function (id) { fd.append('ids[]', id); });
    fd.append(window.CSRF_TOKEN_NAME, window.CSRF_HASH);

    fetch(window.BASE_URL + 'admin/bulk-delete-students', { method: 'POST', body: fd })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.status === 'success') {
                selectedStudentIds.clear();
                location.reload();
            } else {
                showToast(data.message, 'error');
            }
        });
});

/* ── Delete Modal ─────────────────────────────────────────── */
function confirmDelete(userId, nama) {
    document.getElementById('delete-student-name').textContent = nama;
    document.getElementById('delete-form').action = window.BASE_URL + 'admin/delete-student/' + userId;
    document.getElementById('delete-modal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('delete-modal').style.display = 'none';
}

document.getElementById('delete-modal')?.addEventListener('click', function (e) {
    if (e.target === this) closeDeleteModal();
});

/* ── View Modal ───────────────────────────────────────────── */
function openViewModal(userId, d) {
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

    document.getElementById('view-alamat_desa').textContent = d.alamat_desa || '-';
    document.getElementById('view-alamat_kecamatan').textContent = d.alamat_kecamatan || '-';
    document.getElementById('view-alamat_kabupaten').textContent = d.alamat_kabupaten || '-';

    document.getElementById('view-nama_ayah').textContent = d.nama_ayah || '-';
    document.getElementById('view-pekerjaan_ayah').textContent = d.pekerjaan_ayah || '-';
    document.getElementById('view-nama_ibu').textContent = d.nama_ibu || '-';
    document.getElementById('view-pekerjaan_ibu').textContent = d.pekerjaan_ibu || '-';

    document.getElementById('view-sekolah').textContent = d.nama_sekolah || '-';
    document.getElementById('view-alamat_sekolah').textContent = d.alamat_sekolah || '-';

    const badge = document.getElementById('view-status_badge');
    let statusText = d.status_verifikasi || 'Belum Verifikasi';
    if (Number(d.is_finalized) && (statusText === 'Revisi' || statusText === 'Tidak Valid')) {
        statusText = 'Belum Verifikasi';
    }
    badge.textContent = statusText;
    const stClass = statusText === 'Valid' ? 'badge-valid' : statusText === 'Revisi' ? 'badge-invalid' : 'badge-pending';
    badge.className = 'px-2 py-0.5 rounded-full text-caption font-bold inline-block ' + stClass;

    const finalBadge = document.getElementById('view-final_badge');
    finalBadge.textContent = Number(d.is_finalized) ? 'Final' : 'Belum Final';
    finalBadge.className = 'px-2 py-0.5 rounded-full text-caption font-bold inline-block badge-' + (Number(d.is_finalized) ? 'valid' : 'default');

    document.getElementById('view-verify-status').value = d.status_verifikasi_id || 1;
    document.getElementById('view-verify-catatan').value = d.catatan_verifikasi || '';
    document.getElementById('view-verify-form').action = window.BASE_URL + 'admin/verify/' + userId;

    const verifyWrapper = document.getElementById('view-verify-form-wrapper');
    const banner = document.getElementById('view-not-finalized-banner');
    if (Number(d.is_finalized)) {
        verifyWrapper?.classList.remove('hidden');
        banner?.classList.add('hidden');
    } else {
        verifyWrapper?.classList.add('hidden');
        banner?.classList.remove('hidden');
    }

    const tgl = d.created_at ? new Date(d.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
    document.getElementById('view-tgl_daftar').textContent = tgl;

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

    function setBerkasLink(file, linkId, badgeId) {
        const link = document.getElementById(linkId);
        const badgeEl = document.getElementById(badgeId);
        if (file) {
            link.href = window.BASE_URL + 'uploads/berkas/' + file;
            link.classList.remove('hidden');
            badgeEl.textContent = 'SUDAH DIUNGGAH';
            badgeEl.className = 'px-2 py-0.5 rounded-full text-caption font-bold badge-valid';
        } else {
            link.classList.add('hidden');
            badgeEl.textContent = 'BELUM DIUNGGAH';
            badgeEl.className = 'px-2 py-0.5 rounded-full text-caption font-bold badge-pending';
        }
    }
    setBerkasLink(d.fc_ijazah_skl, 'view-link-ijazah', 'view-badge-ijazah');
    setBerkasLink(d.fc_kk, 'view-link-kk', 'view-badge-kk');

    document.getElementById('view-modal').style.display = 'flex';
}

function closeViewModal() {
    document.getElementById('view-modal').style.display = 'none';
}

document.getElementById('view-modal')?.addEventListener('click', function (e) {
    if (e.target === this) closeViewModal();
});

/* ── Add Modal (Multi-Step) ───────────────────────────────── */
let currentAddStep = 1;
const totalAddSteps = 4;

function openAddModal() {
    document.querySelectorAll('[id^="menu-"]').forEach(function (m) { m.classList.add('hidden'); });
    document.getElementById('add-form').reset();
    currentAddStep = 1;
    updateAddStepper();
    document.getElementById('add-modal').style.display = 'flex';
}

function closeAddModal() {
    document.getElementById('add-modal').style.display = 'none';
}

function updateAddStepper() {
    document.getElementById('add-step-indicator-text').innerText = currentAddStep;

    for (let i = 1; i <= totalAddSteps; i++) {
        const content = document.getElementById('add-step-content-' + i);
        if (content) content.classList.toggle('hidden', i !== currentAddStep);
    }

    for (let i = 1; i <= totalAddSteps; i++) {
        const circle = document.getElementById('add-step-circle-' + i);
        if (circle) {
            if (i < currentAddStep) {
                circle.className = 'w-8 h-8 rounded-full flex items-center justify-center text-caption font-bold bg-primary text-on-primary border-2 border-primary transition-colors';
                circle.innerHTML = '<span class="material-symbols-outlined text-[16px]">check</span>';
            } else if (i === currentAddStep) {
                circle.className = 'w-8 h-8 rounded-full flex items-center justify-center text-caption font-bold bg-primary text-on-primary border-2 border-primary transition-colors';
                circle.innerHTML = i;
            } else {
                circle.className = 'w-8 h-8 rounded-full flex items-center justify-center text-caption font-bold bg-surface-container-high text-on-surface-variant border-2 border-outline-variant transition-colors';
                circle.innerHTML = i;
            }
        }
    }

    const progress = document.getElementById('add-stepper-progress');
    if (progress) {
        progress.style.width = ((currentAddStep - 1) / (totalAddSteps - 1)) * 100 + '%';
    }

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
    const container = document.getElementById('add-step-content-' + step);
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
