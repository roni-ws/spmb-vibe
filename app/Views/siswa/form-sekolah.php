<?= $this->extend('layouts/siswa') ?>

<?= $this->section('title') ?>
Asal Sekolah (Langkah 2 dari 4)
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('components/siswa/form-stepper') ?>

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
            <?= $this->include('components/siswa/form-actions') ?>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?= script_tag('js/siswa/form-sekolah.js') ?>
<?= $this->endSection() ?>
