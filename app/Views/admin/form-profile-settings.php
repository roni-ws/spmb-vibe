<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Pengaturan Akun<?= $this->endSection() ?>
<?= $this->section('page-title') ?>Pengaturan Akun<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-[600px] mx-auto">

    <!-- ==========================================
         PAGE HEADER
         ========================================== -->
    <div class="flex justify-between items-center mb-md">
        <div>
            <h1 class="font-headline-md text-headline-md font-bold text-primary mb-1">Pengaturan Akun</h1>
            <p class="text-on-surface-variant text-body-sm">Perbarui username, email, atau password akun Anda.</p>
        </div>
    </div>

    <!-- ==========================================
         PROFILE FORM CARD
         ========================================== -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md md:p-lg shadow-sm">

        <?php if (session()->has('success')): ?>
            <div class="flex items-center gap-2 p-3 mb-md bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-body-sm font-medium">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                <?= esc(session('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            <div class="flex items-center gap-2 p-3 mb-md bg-red-50 border border-red-200 text-red-800 rounded-lg text-body-sm font-medium">
                <span class="material-symbols-outlined text-[18px]">error</span>
                <?= esc(session('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="p-3 mb-md bg-red-50 border border-red-200 rounded-lg text-body-sm text-red-800">
                <?php foreach ($errors as $error): ?>
                    <p class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">error</span>
                        <?= esc($error) ?>
                    </p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/profile-settings') ?>" method="POST" class="flex flex-col gap-md">
            <?= csrf_field() ?>

            <div class="flex flex-col gap-1">
                <label for="username" class="text-body-sm font-semibold text-on-surface">Username</label>
                <input type="text" name="username" id="username"
                       value="<?= esc($admin_username) ?>"
                       class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
            </div>

            <div class="flex flex-col gap-1">
                <label for="email" class="text-body-sm font-semibold text-on-surface">Email</label>
                <input type="email" name="email" id="email"
                       value="<?= esc($email) ?>"
                       class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
            </div>

            <hr class="border-outline-variant/50">

            <h3 class="font-label-bold text-body-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">lock</span> Ubah Password
            </h3>

            <div class="flex flex-col gap-1">
                <label for="password_lama" class="text-body-sm font-semibold text-on-surface">Password Lama</label>
                <input type="password" name="password_lama" id="password_lama"
                       class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"
                       placeholder="Masukkan password lama">
            </div>

            <div class="flex flex-col gap-1">
                <label for="password_baru" class="text-body-sm font-semibold text-on-surface">Password Baru</label>
                <input type="password" name="password_baru" id="password_baru"
                       class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"
                       placeholder="Minimal 8 karakter">
            </div>

            <div class="flex flex-col gap-1">
                <label for="password_konfirmasi" class="text-body-sm font-semibold text-on-surface">Konfirmasi Password Baru</label>
                <input type="password" name="password_konfirmasi" id="password_konfirmasi"
                       class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20"
                       placeholder="Ulangi password baru">
            </div>

            <div class="flex items-center gap-sm pt-sm border-t border-outline-variant">
                <button type="submit"
                        class="bg-primary text-on-primary font-semibold text-body-sm px-5 py-3 rounded-xl hover:opacity-90 transition-opacity flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">save</span> Simpan Pengaturan
                </button>
                <a href="<?= base_url('admin/dashboard') ?>"
                   class="px-5 py-3 rounded-xl border border-outline text-on-surface-variant hover:bg-surface-container-high font-semibold text-body-sm transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
