<?= $this->extend('layouts/siswa') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto py-md">
    <div class="bg-surface border border-outline-variant rounded-xl p-md shadow-sm">
        <h2 class="font-headline-md text-headline-md text-primary mb-1">Pengaturan Akun</h2>
        <p class="text-body-sm text-on-surface-variant mb-lg">Perbarui username, email, atau password akun Anda.</p>

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

        <form action="<?= base_url('siswa/profile-settings') ?>" method="POST" class="space-y-md">
            <?= csrf_field() ?>

            <div>
                <label class="block text-caption font-semibold text-on-surface-variant mb-1">NISN</label>
                <input type="text" value="<?= esc($nisn) ?>"
                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant bg-surface-container-low text-outline text-body-sm outline-none cursor-not-allowed"
                       readonly disabled>
                <p class="text-caption text-outline mt-1">NISN tidak dapat diubah.</p>
            </div>

            <div>
                <label for="username" class="block text-caption font-semibold text-on-surface-variant mb-1">Username</label>
                <input type="text" name="username" id="username"
                       value="<?= esc($username) ?>"
                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-white">
                <p class="text-caption text-outline mt-1">Username dapat diubah dan digunakan untuk login.</p>
            </div>

            <div>
                <label for="email" class="block text-caption font-semibold text-on-surface-variant mb-1">Email</label>
                <input type="email" name="email" id="email"
                       value="<?= esc($email) ?>"
                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-white">
            </div>

            <hr class="border-outline-variant/50">

            <h3 class="font-label-bold text-body-sm text-primary">Ubah Password</h3>

            <div>
                <label for="password_lama" class="block text-caption font-semibold text-on-surface-variant mb-1">Password Lama</label>
                <input type="password" name="password_lama" id="password_lama"
                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-white"
                       placeholder="Masukkan password lama">
            </div>

            <div>
                <label for="password_baru" class="block text-caption font-semibold text-on-surface-variant mb-1">Password Baru</label>
                <input type="password" name="password_baru" id="password_baru"
                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-white"
                       placeholder="Minimal 8 karakter">
            </div>

            <div>
                <label for="password_konfirmasi" class="block text-caption font-semibold text-on-surface-variant mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_konfirmasi" id="password_konfirmasi"
                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-white"
                       placeholder="Ulangi password baru">
            </div>

            <div class="flex items-center gap-sm pt-sm">
                <button type="submit"
                        class="px-5 py-2.5 bg-primary text-on-primary rounded-xl text-body-sm font-semibold hover:opacity-90 transition-opacity inline-flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span> Simpan Pengaturan
                </button>
                <a href="<?= base_url('siswa/dashboard') ?>"
                   class="px-5 py-2.5 border border-outline-variant rounded-xl text-body-sm font-semibold text-on-surface hover:bg-surface-container-low transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
