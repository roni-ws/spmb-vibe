<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Data Pendaftar<?= $this->endSection() ?>
<?= $this->section('page-title') ?>Data Siswa<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- Admin page CSS is loaded from public/css/admin.css via the layout -->
<?= $this->endSection() ?>


<?= $this->section('content') ?>

<!-- ================================
     PAGE HEADER
     ================================ -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
    <div>
        <h2 class="text-headline-md font-bold text-primary">Kelola Pendaftaran</h2>
        <p class="text-body-sm text-on-surface-variant mt-1">
            Kelola dan tinjau semua pendaftaran siswa masuk.
            Total <strong class="text-on-surface"><?= count($students) ?></strong> pendaftar terdaftar.
        </p>
    </div>
    <div class="flex gap-2 flex-wrap">
        <a href="<?= base_url('admin/export-csv') ?>"
           class="flex items-center gap-1 border border-primary text-primary font-semibold text-body-sm px-4 py-2 rounded-xl hover:bg-surface-container-low transition-colors">
            <span class="material-symbols-outlined text-[18px]">download</span> Export CSV
        </a>
        <a href="<?= base_url('admin/export-print') ?>" target="_blank"
           class="flex items-center gap-1 bg-primary text-on-primary font-semibold text-body-sm px-4 py-2 rounded-xl hover:opacity-90 transition-opacity shadow-sm">
            <span class="material-symbols-outlined text-[18px]">print</span> Cetak Data
        </a>
    </div>
</div>

<!-- Flash messages -->
<?php if (session()->has('success')): ?>
<div class="mb-4 flex items-start gap-3 p-4 bg-tertiary-container/30 border border-on-tertiary-container/30 text-on-surface rounded-xl text-body-sm">
    <span class="material-symbols-outlined text-on-tertiary-container shrink-0">check_circle</span>
    <?= session('success') ?>
</div>
<?php endif; ?>
<?php if (session()->has('error')): ?>
<div class="mb-4 flex items-start gap-3 p-4 bg-error-container border border-on-error-container/30 text-on-error-container rounded-xl text-body-sm">
    <span class="material-symbols-outlined shrink-0">error</span>
    <?= session('error') ?>
</div>
<?php endif; ?>

<!-- ================================
     FILTER BAR
     ================================ -->
<div class="filter-bar bg-white rounded-2xl border border-outline-variant shadow-sm p-4 mb-4 space-y-3">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
        <!-- Search -->
        <div class="md:col-span-8 relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
            <input id="custom-search" type="text"
                   placeholder="Cari nama, NIK, atau sekolah..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
        </div>
        <!-- Status Filter -->
        <div class="md:col-span-3">
            <select id="status-filter"
                    class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest">
                <option value="">Semua Status Berkas</option>
                <option value="Valid">Valid</option>
                <option value="Belum Verifikasi">Belum Verifikasi</option>
                <option value="Revisi">Revisi</option>
            </select>
        </div>
        <!-- Reset -->
        <div class="md:col-span-1 flex justify-end">
            <button id="reset-filter" title="Reset filter"
                    class="p-2.5 text-primary border border-outline-variant rounded-xl hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined">filter_list_off</span>
            </button>
        </div>
    </div>

    <!-- Bulk Toolbar (hidden until rows selected) -->
    <div id="bulk-toolbar" class="hidden items-center justify-between bg-primary-container/10 px-4 py-2 rounded-xl border border-primary-container/30">
        <span class="text-primary font-semibold text-body-sm">
            <span id="selected-count">0</span> data dipilih
        </span>
        <div class="flex gap-2">
            <button id="bulk-delete-btn"
                    class="flex items-center gap-1 px-3 py-1.5 text-error font-semibold text-body-sm hover:bg-error-container/30 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-[18px]">delete</span> Hapus
            </button>
        </div>
    </div>
</div>

<!-- ================================
     DATA TABLE
     ================================ -->
<div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
    <div class="overflow-x-auto custom-scrollbar">
        <table id="student-table" class="w-full text-left border-collapse min-w-[1000px]" style="width:100%">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant">
                    <th class="p-4 w-10">
                        <input type="checkbox" id="select-all"
                               class="rounded border-outline-variant text-primary focus:ring-primary cursor-pointer" />
                    </th>
                    <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider">Calon Siswa</th>
                    <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider">NIK</th>
                    <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider">Asal Sekolah</th>
                    <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider">Tgl Daftar</th>
                    <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider">Status Berkas</th>
                    <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider">Finalisasi</th>
                    <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $i => $s): ?>
                    <?php
                        $nama   = esc($s['nama_lengkap'] ?: $s['username'] ?? '-');
                        $inisial = strtoupper(mb_substr($s['nama_lengkap'] ?: $s['username'] ?? 'X', 0, 2));
                        $status = $s['status_verifikasi'] ?? 'Belum Verifikasi';
                        $final  = $s['is_finalized'] ?? 0;

                        $statusClass = match($status) {
                            'Valid'       => 'badge-valid',
                            'Revisi'      => 'badge-invalid',
                            default       => 'badge-pending',
                        };
                        $statusIcon = match($status) {
                            'Valid'       => 'check_circle',
                            'Revisi'      => 'edit_note',
                            default       => 'pending_actions',
                        };

                        // Avatar color cycling
                        $avatarColors = ['bg-primary-container text-on-primary-container', 'bg-secondary-container text-on-secondary-container', 'bg-tertiary-container text-on-tertiary-container', 'bg-surface-container-high text-primary'];
                        $avatarClass  = $avatarColors[$i % count($avatarColors)];
                    ?>
                    <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors"
                        data-id="<?= $s['id'] ?>">
                        <!-- Checkbox -->
                        <td class="p-4">
                            <input type="checkbox" class="row-checkbox rounded border-outline-variant text-primary focus:ring-primary cursor-pointer"
                                   value="<?= $s['id'] ?>" />
                        </td>
                        <!-- Name + Info -->
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full <?= $avatarClass ?> flex items-center justify-center font-bold text-xs shrink-0">
                                    <?= $inisial ?>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-body-sm text-on-surface truncate max-w-[160px]"><?= $nama ?></p>
                                    <p class="text-caption text-on-surface-variant truncate max-w-[160px]">NISN: <?= esc($s['nisn'] ?? '-') ?></p>
                                </div>
                            </div>
                        </td>
                        <!-- NIK -->
                        <td class="p-4 text-body-sm text-on-surface font-semibold">
                            <?= esc($s['nik'] ?? '-') ?>
                        </td>
                        <!-- Sekolah -->
                        <td class="p-4 text-body-sm text-on-surface-variant max-w-[160px]">
                            <span class="truncate block"><?= esc($s['nama_sekolah'] ?? '-') ?></span>
                        </td>
                        <!-- Tgl Daftar -->
                        <td class="p-4 text-body-sm text-on-surface-variant whitespace-nowrap">
                            <?= date('d M Y', strtotime($s['created_at'])) ?>
                        </td>
                        <!-- Status Berkas -->
                        <td class="p-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-caption font-bold <?= $statusClass ?>">
                                <span class="material-symbols-outlined text-[14px]"><?= $statusIcon ?></span>
                                <?= esc($status) ?>
                            </span>
                        </td>
                        <!-- Finalisasi -->
                        <td class="p-4">
                            <?php if ($final): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-caption font-semibold bg-tertiary-container/40 text-on-tertiary-container">
                                    <span class="material-symbols-outlined text-[14px]">lock</span> Final
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-caption font-semibold bg-surface-container text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[14px]">lock_open</span> Belum
                                </span>
                            <?php endif; ?>
                        </td>
                        <!-- Actions -->
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <!-- Quick Edit -->
                                <button class="edit-student-btn p-1.5 text-primary hover:bg-primary-container/20 rounded-full transition-colors"
                                        data-id="<?= $s['id'] ?>"
                                        data-nama="<?= esc($nama) ?>"
                                        data-nik="<?= esc($s['nik'] ?? '') ?>"
                                        data-sekolah="<?= esc($s['nama_sekolah'] ?? '') ?>"
                                        data-nilai=""
                                        data-nohp=""
                                        title="Edit Calon Siswa">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                
                                <!-- Quick Delete -->
                                <button onclick="confirmDelete(<?= $s['id'] ?>, <?= json_encode(esc($nama)) ?>)"
                                        class="p-1.5 text-error hover:bg-error-container/20 rounded-full transition-colors"
                                        title="Hapus Calon Siswa">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                                
                                <!-- Dropdown Menu Wrapper -->
                                <div class="relative">
                                    <button class="p-1.5 hover:bg-surface-container-high rounded-full transition-colors"
                                            onclick="toggleActionMenu('menu-<?= $s['id'] ?>')"
                                            aria-label="Aksi">
                                        <span class="material-symbols-outlined text-on-surface-variant text-[18px]">more_vert</span>
                                    </button>
                                    <!-- Dropdown Menu -->
                                    <div id="menu-<?= $s['id'] ?>"
                                         class="hidden absolute right-0 top-9 w-52 bg-white border border-outline-variant rounded-2xl shadow-xl z-20 py-1 text-left overflow-hidden">
                                        <!-- Verify Button -->
                                        <button onclick="openVerifyModal(<?= $s['id'] ?>, '<?= esc($status) ?>', '', <?= json_encode(esc($nama)) ?>, '<?= !empty($s['pas_foto']) ? base_url('uploads/berkas/' . $s['pas_foto']) : '' ?>')"
                                                class="w-full px-4 py-2.5 text-body-sm hover:bg-surface-container flex items-center gap-3 transition-colors">
                                            <span class="material-symbols-outlined text-[18px] text-primary">verified_user</span>
                                            Verifikasi Berkas
                                        </button>
                                        <div class="h-px bg-outline-variant/50 my-1"></div>
                                        <!-- Download Form -->
                                        <a href="<?= base_url('admin/download-form/' . $s['id']) ?>" target="_blank"
                                           class="w-full px-4 py-2.5 text-body-sm hover:bg-surface-container flex items-center gap-3 transition-colors">
                                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant">article</span>
                                            Cetak Formulir
                                        </a>
                                        <!-- Download CSV single -->
                                        <a href="<?= base_url('admin/export-csv') ?>"
                                           class="w-full px-4 py-2.5 text-body-sm hover:bg-surface-container flex items-center gap-3 transition-colors">
                                            <span class="material-symbols-outlined text-[18px] text-on-surface-variant">download</span>
                                            Export CSV
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="py-16 text-center text-on-surface-variant text-body-sm">
                            <span class="material-symbols-outlined text-5xl block mb-3 text-outline">inbox</span>
                            Belum ada data pendaftar.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Table Footer Info (DataTables pagination goes here) -->
    <div class="px-4 py-3 border-t border-outline-variant bg-surface-container-lowest flex items-center justify-between flex-wrap gap-2">
        <span class="text-caption text-on-surface-variant" id="table-info-text">
            Menampilkan <?= count($students) ?> pendaftar
        </span>
        <div id="dt-paginate-wrapper"></div>
    </div>
</div>

<!-- ================================
     VERIFY MODAL
     ================================ -->
<div id="verify-modal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 p-4"
     style="display: none; align-items: center; justify-content: center; z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-in fade-in zoom-in-95 duration-200"
         style="margin: auto; max-height: 90vh; display: flex; flex-direction: column; width: 100%; max-width: 450px;">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined">verified_user</span>
                </div>
                <div>
                    <h3 class="font-semibold text-body-md text-on-surface">Verifikasi Berkas</h3>
                    <p id="modal-student-name" class="text-caption text-on-surface-variant"></p>
                </div>
            </div>
            <button onclick="closeVerifyModal()" class="p-1.5 rounded-full hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-on-surface-variant">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="verify-form" method="post" action="" style="display: flex; flex-direction: column; overflow: hidden; margin: 0;">
            <?= csrf_field() ?>
            <div class="px-6 py-5 space-y-4 overflow-y-auto custom-scrollbar" style="flex: 1 1 auto; max-height: 60vh;">
                <!-- Pas Foto Preview -->
                <div class="flex flex-col items-center mb-2">
                    <img id="modal-pas-foto" src="" alt="Pas Foto" class="w-24 h-32 object-cover border-2 border-outline-variant rounded-md hidden">
                    <span id="modal-pas-foto-placeholder" class="text-xs text-error hidden font-semibold">Belum ada foto yang diunggah</span>
                </div>

                <!-- Status Berkas -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Status Verifikasi Berkas</label>
                    <select name="status_verifikasi_id" id="modal-status"
                            class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest">
                        <option value="1">Belum Verifikasi</option>
                        <option value="3">Valid – Berkas lengkap & sah</option>
                        <option value="2">Revisi – Perlu perbaikan</option>
                    </select>
                </div>
                <!-- Catatan -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Catatan Verifikasi <span class="text-on-surface-variant font-normal">(opsional)</span></label>
                    <textarea name="catatan_verifikasi" rows="3" placeholder="Tulis catatan untuk calon siswa..."
                              class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none resize-none bg-surface-container-lowest"></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-2 px-6 pb-6 shrink-0">
                <button type="button" onclick="closeVerifyModal()"
                        class="flex-1 py-2.5 border border-outline-variant rounded-xl text-body-sm font-semibold text-on-surface hover:bg-surface-container transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-primary text-on-primary rounded-xl text-body-sm font-semibold hover:opacity-90 transition-opacity">
                    Simpan Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================================
     DELETE CONFIRM MODAL
     ================================ -->
<div id="delete-modal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 p-4"
     style="display: none; align-items: center; justify-content: center; z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm"
         style="margin: auto; max-height: 90vh; display: flex; flex-direction: column; width: 100%; max-width: 380px;">
        <div class="px-6 py-5 text-center flex-grow overflow-y-auto">
            <div class="w-14 h-14 rounded-full bg-error-container flex items-center justify-center mx-auto mb-4 shrink-0">
                <span class="material-symbols-outlined text-error text-2xl">delete_forever</span>
            </div>
            <h3 class="font-semibold text-body-lg text-on-surface mb-1">Hapus Pendaftar?</h3>
            <p class="text-body-sm text-on-surface-variant">
                Data <strong id="delete-student-name" class="text-on-surface"></strong> akan dihapus permanen beserta semua berkas terkait. Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
        <div class="flex gap-3 px-6 pb-6 shrink-0">
            <button onclick="closeDeleteModal()"
                    class="flex-1 py-2.5 border border-outline-variant rounded-xl text-body-sm font-semibold hover:bg-surface-container transition-colors">
                Batal
            </button>
            <button id="confirm-delete-btn"
                    class="flex-1 py-2.5 bg-error text-on-error rounded-xl text-body-sm font-semibold hover:opacity-90 transition-opacity">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<!-- ================================
     EDIT STUDENT MODAL
     ================================ -->
<div id="edit-modal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 p-4"
     style="display: none; align-items: center; justify-content: center; z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-in fade-in zoom-in-95 duration-200"
         style="margin: auto; max-height: 90vh; display: flex; flex-direction: column; width: 100%; max-width: 450px;">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">edit</span>
                </div>
                <div>
                    <h3 class="font-semibold text-body-md text-on-surface">Edit Data Calon Siswa</h3>
                    <p id="edit-modal-subtitle" class="text-caption text-on-surface-variant">Ubah biodata dan data sekolah</p>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" class="p-1.5 rounded-full hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-on-surface-variant">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="edit-form" method="post" action="" style="display: flex; flex-direction: column; overflow: hidden; margin: 0;">
            <?= csrf_field() ?>
            <div class="px-6 py-5 space-y-4 overflow-y-auto custom-scrollbar" style="flex: 1 1 auto; max-height: 60vh;">
                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="edit-nama" required
                           placeholder="Nama lengkap calon siswa"
                           class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                </div>
                
                <!-- NIK -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">NIK (Nomor Induk Kependudukan)</label>
                    <input type="text" name="nik" id="edit-nik" required
                           placeholder="16 digit NIK"
                           class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                </div>

                <!-- Asal Sekolah -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Asal Sekolah</label>
                    <input type="text" name="nama_sekolah" id="edit-sekolah" required
                           placeholder="Nama sekolah asal"
                           class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-2 px-6 pb-6 pt-2 border-t border-outline-variant/30 shrink-0">
                <button type="button" onclick="closeEditModal()"
                        class="flex-1 py-2.5 border border-outline-variant rounded-xl text-body-sm font-semibold text-on-surface hover:bg-surface-container transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-primary text-on-primary rounded-xl text-body-sm font-semibold hover:opacity-90 transition-opacity">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
    // Globals required by public/js/pendaftar.js
    window.BASE_URL        = '<?= base_url() ?>';
    window.CSRF_TOKEN_NAME = '<?= csrf_token() ?>';
    window.CSRF_HASH       = '<?= csrf_hash() ?>';
</script>
<?= script_tag('js/pendaftar.js') ?>
<?= $this->endSection() ?>
