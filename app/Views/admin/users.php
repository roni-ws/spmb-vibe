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

<?= $this->include('components/admin/users/modals/add_modal') ?>

<?= $this->include('components/admin/users/modals/edit_modal') ?>

<?= $this->include('components/admin/users/modals/password_modal') ?>

<?= $this->include('components/admin/users/modals/delete_modal') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const BASE_URL = '<?= base_url() ?>';
</script>
<?= script_tag('js/admin/users.js') ?>
<?= $this->endSection() ?>
