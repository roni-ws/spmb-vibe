<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Manajemen User<?= $this->endSection() ?>
<?= $this->section('page-title') ?>Manajemen User<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-[1200px] mx-auto">

    <!-- ================================
         PAGE HEADER
         ================================ -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-headline-md font-bold text-primary">Kelola Akun Staff</h2>
            <p class="text-body-sm text-on-surface-variant mt-1">
                Kelola hak akses administrator dan panitia penerimaan siswa baru secara terpusat.
                Total <strong class="text-on-surface"><?= count($staffs) ?></strong> akun staff terdaftar.
            </p>
        </div>
        <div>
            <button onclick="openAddModal()"
                    class="w-full sm:w-auto flex items-center justify-center gap-2 bg-primary text-on-primary font-semibold text-body-sm px-5 py-3 rounded-xl hover:opacity-90 transition-opacity shadow-md">
                <span class="material-symbols-outlined text-[18px]">person_add</span> Tambah Staff Baru
            </button>
        </div>
    </div>

    <!-- Alert Success / Errors -->
    <?php if (session()->has('success')): ?>
        <div class="mb-6 p-4 rounded-xl bg-tertiary-container text-on-tertiary-container border border-on-tertiary-container/20 flex items-center gap-3 text-body-sm font-semibold animate-fade-in shadow-sm">
            <span class="material-symbols-outlined text-tertiary">check_circle</span>
            <?= session('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->has('errors')): ?>
        <div class="mb-6 p-4 rounded-xl bg-error-container text-on-error-container border border-on-error-container/20 flex flex-col gap-1 text-body-sm animate-fade-in shadow-sm">
            <div class="flex items-center gap-3 font-semibold mb-1">
                <span class="material-symbols-outlined text-error">error</span>
                Terdapat kesalahan dalam pengisian form:
            </div>
            <ul class="list-disc list-inside pl-4 text-caption">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="mb-6 p-4 rounded-xl bg-error-container text-on-error-container border border-on-error-container/20 flex items-center gap-3 text-body-sm font-semibold animate-fade-in shadow-sm">
            <span class="material-symbols-outlined text-error">error</span>
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <!-- ================================
         FILTER BAR
         ================================ -->
    <div class="filter-bar bg-white rounded-2xl border border-outline-variant shadow-sm p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <!-- Search -->
            <div class="md:col-span-8 relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
                <input id="custom-search" type="text"
                       placeholder="Cari nama staff, username, atau email..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
            </div>
            <!-- Role Filter -->
            <div class="md:col-span-3">
                <select id="role-filter"
                        class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest">
                    <option value="">Semua Peran (Role)</option>
                    <option value="ADMIN">ADMIN</option>
                    <option value="PANITIA">PANITIA</option>
                </select>
            </div>
            <!-- Reset -->
            <div class="md:col-span-1 flex justify-end">
                <button id="reset-filter" title="Reset filter"
                        class="w-full md:w-auto p-2.5 text-primary border border-outline-variant rounded-xl hover:bg-surface-container transition-colors flex justify-center items-center">
                    <span class="material-symbols-outlined">filter_list_off</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ================================
         DATA TABLE
         ================================ -->
    <div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table id="users-table" class="w-full text-left border-collapse min-w-[800px]" style="width:100%">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider w-16">No</th>
                        <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider">Nama Staff (Username)</th>
                        <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider">Email Resmi</th>
                        <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider w-36">Peran (Role)</th>
                        <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider w-44">Tgl Dibuat</th>
                        <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider text-right w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($staffs)): ?>
                        <?php foreach ($staffs as $i => $s): ?>
                        <?php
                            $isSelf = (auth()->id() == $s['id']);
                            $username = esc($s['username']);
                            $email = esc($s['email']);
                            $roleName = strtoupper($s['role']);
                            $inisial = strtoupper(mb_substr($username, 0, 2));

                            $roleClass = match($roleName) {
                                'ADMIN'       => 'bg-tertiary-container text-on-tertiary-container border border-on-tertiary-container/10',
                                'PANITIA'     => 'bg-primary-container text-on-primary-container border border-on-primary-container/10',
                                default       => 'bg-surface-container text-on-surface-variant border border-outline-variant/30',
                            };
                            $roleIcon = match($roleName) {
                                'ADMIN'       => 'admin_panel_settings',
                                'PANITIA'     => 'assignment_ind',
                                default       => 'person',
                            };

                            // Avatar colors
                            $avatarColors = ['bg-primary-container text-on-primary-container', 'bg-secondary-container text-on-secondary-container', 'bg-tertiary-container text-on-tertiary-container', 'bg-surface-container-high text-primary'];
                            $avatarClass  = $avatarColors[$i % count($avatarColors)];
                        ?>
                        <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                            <!-- No -->
                            <td class="p-4 text-body-sm text-on-surface-variant">
                                <?= $i + 1 ?>
                            </td>
                            <!-- Username -->
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full <?= $avatarClass ?> flex items-center justify-center font-bold text-xs shrink-0">
                                        <?= $inisial ?>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-body-sm text-on-surface truncate">
                                            <?= $username ?>
                                            <?php if ($isSelf): ?>
                                                <span class="ml-1 text-[10px] font-bold bg-secondary text-on-secondary px-1.5 py-0.5 rounded-full uppercase">Anda</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <!-- Email -->
                            <td class="p-4 text-body-sm text-on-surface">
                                <?= $email ?>
                            </td>
                            <!-- Role Badge -->
                            <td class="p-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-caption font-bold <?= $roleClass ?>">
                                    <span class="material-symbols-outlined text-[14px]"><?= $roleIcon ?></span>
                                    <?= $roleName ?>
                                </span>
                            </td>
                            <!-- Created At -->
                            <td class="p-4 text-body-sm text-on-surface-variant whitespace-nowrap">
                                <?= date('d M Y, H:i', strtotime($s['created_at'])) ?>
                            </td>
                            <!-- Actions -->
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <!-- Edit Staff -->
                                    <button onclick="openEditModal(<?= $s['id'] ?>, '<?= $username ?>', '<?= $email ?>', '<?= esc($s['role']) ?>')"
                                            class="p-1.5 text-primary hover:bg-primary-container/20 rounded-full transition-colors"
                                            title="Edit Data Staff">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>

                                    <!-- Change Password -->
                                    <button onclick="openPasswordModal(<?= $s['id'] ?>, '<?= $username ?>')"
                                            class="p-1.5 text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors"
                                            title="Ubah Kata Sandi">
                                        <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                                    </button>
                                    
                                    <!-- Delete Staff (Disabled if own account) -->
                                    <?php if ($isSelf): ?>
                                        <button class="p-1.5 text-outline opacity-40 cursor-not-allowed rounded-full"
                                                title="Tidak dapat menghapus akun Anda sendiri" disabled>
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    <?php else: ?>
                                        <button onclick="confirmDelete(<?= $s['id'] ?>, '<?= $username ?>')"
                                                class="p-1.5 text-error hover:bg-error-container/20 rounded-full transition-colors"
                                                title="Hapus Akun Staff">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="py-16 text-center text-on-surface-variant text-body-sm">
                                <span class="material-symbols-outlined text-5xl block mb-3 text-outline">group_off</span>
                                Belum ada data staff terdaftar.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Table Footer Info -->
        <div class="px-4 py-3 border-t border-outline-variant bg-surface-container-lowest flex items-center justify-between flex-wrap gap-2">
            <span class="text-caption text-on-surface-variant" id="table-info-text">
                Menampilkan <?= count($staffs) ?> data staff
            </span>
            <div id="dt-paginate-wrapper"></div>
        </div>
    </div>

</div>

<!-- ==========================================
     MODAL 1: TAMBAH STAFF
     ========================================== -->
<div id="add-modal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 p-4"
     style="display: none; align-items: center; justify-content: center; z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-in fade-in zoom-in-95 duration-200"
         style="margin: auto; max-height: 90vh; display: flex; flex-direction: column; width: 100%; max-width: 450px;">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">person_add</span>
                </div>
                <div>
                    <h3 class="font-semibold text-body-md text-on-surface">Tambah Staff Baru</h3>
                    <p class="text-caption text-on-surface-variant">Daftarkan administrator atau panitia baru</p>
                </div>
            </div>
            <button onclick="closeAddModal()" class="p-1.5 rounded-full hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-on-surface-variant">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <form action="<?= base_url('admin/users/store') ?>" method="post" style="display: flex; flex-direction: column; overflow: hidden; margin: 0;">
            <?= csrf_field() ?>
            <div class="px-6 py-5 space-y-4 overflow-y-auto custom-scrollbar" style="flex: 1 1 auto; max-height: 60vh;">
                <!-- Username -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Username</label>
                    <input type="text" name="username" required minlength="3" maxlength="30" pattern="^[a-zA-Z0-9_]+$"
                           placeholder="Contoh: panitia_spmb"
                           class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                    <p class="text-[10px] text-on-surface-variant mt-1">Hanya huruf, angka, dan underscore.</p>
                </div>
                <!-- Email -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Alamat Email Resmi</label>
                    <input type="email" name="email" required
                           placeholder="Contoh: staff@sekolah.sch.id"
                           class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                </div>
                <!-- Role -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Peran / Akses Sistem</label>
                    <select name="role" required
                            class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest">
                        <option value="panitia">Panitia (Akses Terbatas ke Pendaftar)</option>
                        <option value="admin">Admin (Akses Penuh Pengaturan & User)</option>
                    </select>
                </div>
                <!-- Password -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Kata Sandi</label>
                    <input type="password" name="password" required minlength="6"
                           placeholder="Minimal 6 karakter"
                           class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                </div>
                <!-- Confirm Password -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirm" required minlength="6"
                           placeholder="Ulangi kata sandi"
                           class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-2 px-6 pb-6 pt-3 border-t border-outline-variant/30 shrink-0">
                <button type="button" onclick="closeAddModal()"
                        class="flex-1 py-2.5 border border-outline-variant rounded-xl text-body-sm font-semibold text-on-surface hover:bg-surface-container transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-primary text-on-primary rounded-xl text-body-sm font-semibold hover:opacity-90 transition-opacity">
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================
     MODAL 2: EDIT STAFF
     ========================================== -->
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
                    <h3 class="font-semibold text-body-md text-on-surface">Ubah Data Staff</h3>
                    <p class="text-caption text-on-surface-variant">Perbarui username, email, dan hak akses</p>
                </div>
            </div>
            <button onclick="closeEditModal()" class="p-1.5 rounded-full hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-on-surface-variant">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="edit-form" method="post" action="" style="display: flex; flex-direction: column; overflow: hidden; margin: 0;">
            <?= csrf_field() ?>
            <div class="px-6 py-5 space-y-4 overflow-y-auto custom-scrollbar" style="flex: 1 1 auto; max-height: 60vh;">
                <!-- Username -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Username</label>
                    <input type="text" name="username" id="edit-username" required minlength="3" maxlength="30" pattern="^[a-zA-Z0-9_]+$"
                           class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                </div>
                <!-- Email -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Alamat Email</label>
                    <input type="email" name="email" id="edit-email" required
                           class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                </div>
                <!-- Role -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Peran / Akses Sistem</label>
                    <select name="role" id="edit-role" required
                            class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest">
                        <option value="panitia">Panitia (Akses Terbatas ke Pendaftar)</option>
                        <option value="admin">Admin (Akses Penuh Pengaturan & User)</option>
                    </select>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-2 px-6 pb-6 pt-3 border-t border-outline-variant/30 shrink-0">
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

<!-- ==========================================
     MODAL 3: UBAH PASSWORD
     ========================================== -->
<div id="password-modal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 p-4"
     style="display: none; align-items: center; justify-content: center; z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-in fade-in zoom-in-95 duration-200"
         style="margin: auto; max-height: 90vh; display: flex; flex-direction: column; width: 100%; max-width: 450px;">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">lock_reset</span>
                </div>
                <div>
                    <h3 class="font-semibold text-body-md text-on-surface">Ubah Kata Sandi</h3>
                    <p id="password-modal-subtitle" class="text-caption text-on-surface-variant">Ganti kata sandi milik staff</p>
                </div>
            </div>
            <button onclick="closePasswordModal()" class="p-1.5 rounded-full hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-on-surface-variant">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="password-form" method="post" action="" style="display: flex; flex-direction: column; overflow: hidden; margin: 0;">
            <?= csrf_field() ?>
            <div class="px-6 py-5 space-y-4 overflow-y-auto custom-scrollbar" style="flex: 1 1 auto; max-height: 60vh;">
                <!-- Password -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Kata Sandi Baru</label>
                    <input type="password" name="password" required minlength="6"
                           placeholder="Minimal 6 karakter baru"
                           class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                </div>
                <!-- Confirm Password -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirm" required minlength="6"
                           placeholder="Ulangi kata sandi baru"
                           class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-2 px-6 pb-6 pt-3 border-t border-outline-variant/30 shrink-0">
                <button type="button" onclick="closePasswordModal()"
                        class="flex-1 py-2.5 border border-outline-variant rounded-xl text-body-sm font-semibold text-on-surface hover:bg-surface-container transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-primary text-on-primary rounded-xl text-body-sm font-semibold hover:opacity-90 transition-opacity">
                    Perbarui Sandi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==========================================
     MODAL 4: HAPUS CONFIRM MODAL
     ========================================== -->
<div id="delete-modal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 p-4"
     style="display: none; align-items: center; justify-content: center; z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm animate-in fade-in zoom-in-95 duration-200"
         style="margin: auto; max-height: 90vh; display: flex; flex-direction: column; width: 100%; max-width: 380px;">
        <div class="px-6 py-5 text-center flex-grow overflow-y-auto">
            <div class="w-14 h-14 rounded-full bg-error-container flex items-center justify-center mx-auto mb-4 shrink-0 shadow-sm border border-on-error-container/10">
                <span class="material-symbols-outlined text-error text-2xl animate-pulse">delete_forever</span>
            </div>
            <h3 class="font-semibold text-body-lg text-on-surface mb-1">Hapus Akun Staff?</h3>
            <p class="text-body-sm text-on-surface-variant">
                Akun staf <strong id="delete-username" class="text-on-surface"></strong> akan dihapus permanen dari database. Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
        <div class="flex gap-3 px-6 pb-6 shrink-0">
            <button onclick="closeDeleteModal()"
                    class="flex-1 py-2.5 border border-outline-variant rounded-xl text-body-sm font-semibold hover:bg-surface-container transition-colors">
                Batal
            </button>
            <a id="confirm-delete-btn" href=""
               class="flex-1 py-2.5 bg-error text-on-error text-center rounded-xl text-body-sm font-semibold hover:opacity-90 transition-opacity flex justify-center items-center shadow-md">
                Ya, Hapus
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const BASE_URL = '<?= base_url() ?>';

    // ----------------------------------------------------
    // Modal 1: Tambah Staff
    // ----------------------------------------------------
    function openAddModal() {
        const modal = document.getElementById('add-modal');
        modal.style.display = 'flex';
    }
    function closeAddModal() {
        document.getElementById('add-modal').style.display = 'none';
    }

    // ----------------------------------------------------
    // Modal 2: Edit Staff
    // ----------------------------------------------------
    function openEditModal(id, username, email, role) {
        const modal = document.getElementById('edit-modal');
        const form = document.getElementById('edit-form');
        
        form.action = BASE_URL + 'admin/users/edit/' + id;
        document.getElementById('edit-username').value = username;
        document.getElementById('edit-email').value = email;
        document.getElementById('edit-role').value = role;

        modal.style.display = 'flex';
    }
    function closeEditModal() {
        document.getElementById('edit-modal').style.display = 'none';
    }

    // ----------------------------------------------------
    // Modal 3: Ganti Password
    // ----------------------------------------------------
    function openPasswordModal(id, username) {
        const modal = document.getElementById('password-modal');
        const form = document.getElementById('password-form');
        
        form.action = BASE_URL + 'admin/users/change-password/' + id;
        document.getElementById('password-modal-subtitle').innerText = 'Ganti sandi akun ' + username;

        modal.style.display = 'flex';
    }
    function closePasswordModal() {
        document.getElementById('password-modal').style.display = 'none';
    }

    // ----------------------------------------------------
    // Modal 4: Konfirmasi Hapus
    // ----------------------------------------------------
    function confirmDelete(id, username) {
        const modal = document.getElementById('delete-modal');
        document.getElementById('delete-username').innerText = username;
        document.getElementById('confirm-delete-btn').href = BASE_URL + 'admin/users/delete/' + id;
        modal.style.display = 'flex';
    }
    function closeDeleteModal() {
        document.getElementById('delete-modal').style.display = 'none';
    }

    // ----------------------------------------------------
    // DataTables & Filter Initialization
    // ----------------------------------------------------
    $(document).ready(function() {
        const table = $('#users-table').DataTable({
            responsive: true,
            dom: 'tp', // layout: table + pagination only, custom search bar is used
            pageLength: 10,
            language: {
                zeroRecords: 'Staff tidak ditemukan.',
                paginate: {
                    next: '<span class="material-symbols-outlined text-[16px] flex items-center">chevron_right</span>',
                    previous: '<span class="material-symbols-outlined text-[16px] flex items-center">chevron_left</span>'
                }
            },
            drawCallback: function(settings) {
                // Relocate DataTable pagination markup to custom footer wrapper
                const paginateHtml = $('.dataTables_paginate').html();
                if (paginateHtml) {
                    $('#dt-paginate-wrapper').html(
                        '<div class="dt-custom-pagination flex items-center gap-1">' + paginateHtml + '</div>'
                    );
                    $('.dataTables_paginate').hide();
                } else {
                    $('#dt-paginate-wrapper').html('');
                }

                // Make pagination style match material components
                $('#dt-paginate-wrapper span a').addClass('px-3 py-1.5 rounded-lg border border-outline-variant/60 hover:bg-surface-container text-body-sm font-semibold transition-all');
                $('#dt-paginate-wrapper span a.current').addClass('bg-primary text-on-primary border-primary hover:bg-primary').removeClass('hover:bg-surface-container');
                $('#dt-paginate-wrapper > div > a').addClass('p-1.5 rounded-lg border border-outline-variant/60 hover:bg-surface-container transition-all flex items-center');

                // Update information label
                const info = table.page.info();
                if (info.recordsTotal > 0) {
                    $('#table-info-text').text(
                        `Menampilkan ${info.start + 1} sampai ${info.end} dari ${info.recordsTotal} data staff`
                    );
                } else {
                    $('#table-info-text').text('Menampilkan 0 data staff');
                }
            }
        });

        // Search action
        $('#custom-search').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Filter Role action
        $('#role-filter').on('change', function() {
            const role = this.value;
            // Column indices: Role is column 3 (0-indexed)
            table.column(3).search(role ? '^' + role + '$' : '', true, false).draw();
        });

        // Reset Filter
        $('#reset-filter').on('click', function() {
            $('#custom-search').val('');
            $('#role-filter').val('');
            table.search('').column(3).search('').draw();
        });
    });
</script>
<?= $this->endSection() ?>
