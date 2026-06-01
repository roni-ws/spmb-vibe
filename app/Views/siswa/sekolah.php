<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>
Asal Sekolah (Langkah 2 dari 4)
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Stepper Visual -->
<div class="mb-lg bg-surface border border-outline-variant rounded-2xl p-sm md:p-md shadow-sm">
    <div class="flex flex-col md:flex-row justify-between items-center gap-md">
        <!-- Step 1: Completed -->
        <a href="<?= base_url('siswa/biodata') ?>" class="flex items-center gap-xs w-full md:w-auto hover:opacity-80 transition-opacity">
            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">check</span>
            </div>
            <div>
                <span class="block text-sm font-bold text-emerald-600 leading-tight">Biodata Diri</span>
                <span class="block text-[11px] text-emerald-500">Selesai</span>
            </div>
        </a>
        <div class="hidden md:block flex-grow h-px bg-emerald-200 mx-xs"></div>
        <!-- Step 2: Active -->
        <div class="flex items-center gap-xs w-full md:w-auto">
            <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm shrink-0 shadow-md">2</div>
            <div>
                <span class="block text-sm font-bold text-primary leading-tight">Asal Sekolah</span>
                <span class="block text-[11px] text-primary">Sedang Diisi</span>
            </div>
        </div>
        <div class="hidden md:block flex-grow h-px bg-outline-variant mx-xs"></div>
        <!-- Step 3: Pending -->
        <div class="flex items-center gap-xs w-full md:w-auto opacity-50">
            <div class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant text-on-surface-variant flex items-center justify-center font-bold text-sm shrink-0">3</div>
            <div>
                <span class="block text-sm font-bold text-on-surface-variant leading-tight">Data Orang Tua</span>
                <span class="block text-[11px] text-outline">Belum Diisi</span>
            </div>
        </div>
        <div class="hidden md:block flex-grow h-px bg-outline-variant mx-xs"></div>
        <!-- Step 4: Pending -->
        <div class="flex items-center gap-xs w-full md:w-auto opacity-50">
            <div class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant text-on-surface-variant flex items-center justify-center font-bold text-sm shrink-0">4</div>
            <div>
                <span class="block text-sm font-bold text-on-surface-variant leading-tight">Berkas Persyaratan</span>
                <span class="block text-[11px] text-outline">Belum Diunggah</span>
            </div>
        </div>
    </div>
    <!-- Mobile Progress Bar -->
    <div class="mt-sm md:hidden">
        <div class="h-1.5 w-full bg-surface-container-high rounded-full overflow-hidden">
            <div class="h-full bg-primary rounded-full" style="width: 50%;"></div>
        </div>
    </div>
</div>

<!-- Main Form Section -->
<div class="max-w-3xl mx-auto">
    <div class="mb-md">
        <h2 class="font-headline-sm text-headline-sm text-primary mb-xs">Informasi Sekolah Asal</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant">Tuliskan nama sekolah asal dan alamat lengkap sekolah asal Anda (SMP/MTs/Sederajat).</p>
    </div>

    <!-- Card Formulir -->
    <div class="bg-surface border border-outline-variant rounded-2xl shadow-sm p-md md:p-lg mb-md relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-tertiary-fixed-dim"></div>
        
        <form action="<?= base_url('siswa/sekolah') ?>" method="POST" class="space-y-md">
            <?= csrf_field() ?>

            <!-- Nama Sekolah Asal -->
            <div class="flex flex-col gap-xs">
                <label class="font-label-bold text-body-sm text-on-surface" for="nama_sekolah">Nama Sekolah Asal <span class="text-error">*</span></label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline-variant pointer-events-none" style="font-size: 20px;">school</span>
                    <input class="w-full pl-10 pr-sm py-3 border <?= isset($errors['nama_sekolah']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                           id="nama_sekolah" name="nama_sekolah" placeholder="Contoh: SMP Negeri 1 Jakarta" required 
                           value="<?= esc(old('nama_sekolah', $sekolah['nama_sekolah'] ?? '')) ?>" type="text"/>
                </div>
                <?php if (isset($errors['nama_sekolah'])): ?>
                    <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['nama_sekolah']) ?></span>
                <?php endif; ?>
            </div>

            <!-- Alamat Sekolah Asal -->
            <div class="flex flex-col gap-xs">
                <label class="font-label-bold text-body-sm text-on-surface" for="alamat_sekolah">Alamat Lengkap Sekolah Asal <span class="text-error">*</span></label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-sm top-4 text-outline-variant pointer-events-none" style="font-size: 20px;">location_on</span>
                    <textarea class="w-full pl-10 pr-sm py-3 border <?= isset($errors['alamat_sekolah']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all min-h-[100px]" 
                              id="alamat_sekolah" name="alamat_sekolah" placeholder="Nama jalan, kota/kabupaten, dan provinsi sekolah asal" required><?= esc(old('alamat_sekolah', $sekolah['alamat_sekolah'] ?? '')) ?></textarea>
                </div>
                <?php if (isset($errors['alamat_sekolah'])): ?>
                    <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['alamat_sekolah']) ?></span>
                <?php endif; ?>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-md border-t border-outline-variant pt-md mt-lg">
                <a href="<?= base_url('siswa/biodata') ?>" class="w-full sm:w-auto px-lg py-3 rounded-lg border border-outline text-on-surface-variant hover:bg-surface-container-high transition-colors flex items-center justify-center gap-xs font-label-bold text-body-sm text-center">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    Kembali
                </a>
                <button class="w-full sm:w-auto px-lg py-3 rounded-lg bg-primary text-on-primary font-label-bold text-body-sm hover:bg-primary-container transition-colors shadow-sm hover:shadow-md flex items-center justify-center gap-xs group" type="submit">
                    Simpan & Lanjutkan
                    <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
