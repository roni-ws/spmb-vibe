<?= $this->extend('layouts/siswa') ?>

<?= $this->section('title') ?>
Data Orang Tua (Langkah 3 dari 4)
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('components/siswa/form-stepper') ?>

<!-- Main Form Section -->
<div class="max-w-3xl mx-auto">
    <div class="mb-md">
        <h2 class="font-headline-sm text-headline-sm text-primary mb-xs">Data Orang Tua / Wali</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant">Lengkapi informasi nama dan pekerjaan dari kedua orang tua kandung Anda.</p>
    </div>

    <!-- Card Formulir -->
    <div class="bg-surface border border-outline-variant rounded-2xl shadow-sm p-md md:p-lg mb-md relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-tertiary-fixed-dim"></div>
        
        <form action="<?= base_url('siswa/orang-tua') ?>" method="POST" class="space-y-md">
            <?= csrf_field() ?>

            <!-- Data Ayah -->
            <div class="pb-sm border-b border-surface-variant space-y-md">
                <h3 class="font-label-bold text-body-md text-primary flex items-center gap-xs">
                    <span class="material-symbols-outlined">person</span> Data Ayah Kandung
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-bold text-body-sm text-on-surface" for="nama_ayah">Nama Lengkap Ayah <span class="text-error">*</span></label>
                        <input class="w-full px-sm py-3 border <?= isset($errors['nama_ayah']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                               id="nama_ayah" name="nama_ayah" placeholder="Sesuai Akta / KK" required 
                               value="<?= esc(old('nama_ayah', $orang_tua['nama_ayah'] ?? '')) ?>" type="text"/>
                        <?php if (isset($errors['nama_ayah'])): ?>
                            <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['nama_ayah']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="flex flex-col gap-xs">
                        <label class="font-label-bold text-body-sm text-on-surface" for="pekerjaan_ayah">Pekerjaan Ayah <span class="text-error">*</span></label>
                        <input class="w-full px-sm py-3 border <?= isset($errors['pekerjaan_ayah']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                               id="pekerjaan_ayah" name="pekerjaan_ayah" placeholder="Contoh: PNS, Karyawan Swasta, Wiraswasta" required 
                               value="<?= esc(old('pekerjaan_ayah', $orang_tua['pekerjaan_ayah'] ?? '')) ?>" type="text"/>
                        <?php if (isset($errors['pekerjaan_ayah'])): ?>
                            <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['pekerjaan_ayah']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Data Ibu -->
            <div class="space-y-md pt-sm">
                <h3 class="font-label-bold text-body-md text-primary flex items-center gap-xs">
                    <span class="material-symbols-outlined">person</span> Data Ibu Kandung
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-bold text-body-sm text-on-surface" for="nama_ibu">Nama Lengkap Ibu <span class="text-error">*</span></label>
                        <input class="w-full px-sm py-3 border <?= isset($errors['nama_ibu']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                               id="nama_ibu" name="nama_ibu" placeholder="Sesuai Akta / KK" required 
                               value="<?= esc(old('nama_ibu', $orang_tua['nama_ibu'] ?? '')) ?>" type="text"/>
                        <?php if (isset($errors['nama_ibu'])): ?>
                            <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['nama_ibu']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="flex flex-col gap-xs">
                        <label class="font-label-bold text-body-sm text-on-surface" for="pekerjaan_ibu">Pekerjaan Ibu <span class="text-error">*</span></label>
                        <input class="w-full px-sm py-3 border <?= isset($errors['pekerjaan_ibu']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                               id="pekerjaan_ibu" name="pekerjaan_ibu" placeholder="Contoh: Ibu Rumah Tangga, PNS, Guru" required 
                               value="<?= esc(old('pekerjaan_ibu', $orang_tua['pekerjaan_ibu'] ?? '')) ?>" type="text"/>
                        <?php if (isset($errors['pekerjaan_ibu'])): ?>
                            <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['pekerjaan_ibu']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <?= $this->include('components/siswa/form-actions') ?>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?= script_tag('js/siswa/form-orang-tua.js') ?>
<?= $this->endSection() ?>
