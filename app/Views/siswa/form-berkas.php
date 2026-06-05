<?= $this->extend('layouts/siswa') ?>

<?= $this->section('title') ?>
Kelengkapan Berkas (Langkah 4 dari 4)
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('components/siswa/form-stepper') ?>

<!-- Main Form Section -->
<div class="max-w-3xl mx-auto">
    <div class="mb-md">
        <h2 class="font-headline-sm text-headline-sm text-primary mb-xs">Unggah Berkas Administrasi</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant">Harap unggah hasil scan dokumen persyaratan resmi dalam format PDF atau Gambar (JPG/JPEG/PNG) dengan batas ukuran maksimal 2MB per file.</p>
    </div>

    <!-- Card Formulir -->
    <div class="bg-surface border border-outline-variant rounded-2xl shadow-sm p-md md:p-lg mb-md relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-tertiary-fixed-dim"></div>
        
        <form action="<?= base_url('siswa/berkas') ?>" method="POST" enctype="multipart/form-data" class="space-y-lg">
            <?= csrf_field() ?>

            <!-- Unggah Ijazah / SKL -->
            <div class="flex flex-col gap-sm p-sm rounded-xl bg-surface-container-low border border-outline-variant/40">
                <div class="flex justify-between items-start flex-wrap gap-xs">
                    <div>
                        <h3 class="font-label-bold text-body-sm text-on-surface">Scan Ijazah / Surat Keterangan Lulus (SKL) <span class="text-error">*</span></h3>
                        <p class="text-caption text-on-surface-variant">Pastikan tulisan terbaca jelas. Format: PDF/JPG/PNG max 2MB.</p>
                    </div>
                    <?php if ($berkas && !empty($berkas['fc_ijazah_skl'])): ?>
                        <div class="inline-flex items-center gap-xs px-3 py-1 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-full font-label-bold text-[11px] shadow-sm select-none">
                            <span class="material-symbols-outlined text-[16px] text-emerald-700">check_circle</span> Sudah Terunggah
                        </div>
                    <?php else: ?>
                        <div class="inline-flex items-center gap-xs px-3 py-1 bg-amber-100 border border-amber-300 text-amber-800 rounded-full font-label-bold text-[11px] shadow-sm select-none">
                            <span class="material-symbols-outlined text-[16px] text-amber-600">pending</span> Wajib Diunggah
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col gap-xs mt-1">
                    <input class="w-full text-body-sm file:mr-md file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-body-sm file:font-semibold file:bg-primary-fixed file:text-on-primary-fixed hover:file:bg-primary-fixed-dim cursor-pointer" 
                           id="fc_ijazah_skl" name="fc_ijazah_skl" type="file" <?= ($berkas && !empty($berkas['fc_ijazah_skl'])) ? '' : 'required' ?> />
                    <?php if (isset($errors['fc_ijazah_skl'])): ?>
                        <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['fc_ijazah_skl']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Unggah Kartu Keluarga (KK) -->
            <div class="flex flex-col gap-sm p-sm rounded-xl bg-surface-container-low border border-outline-variant/40">
                <div class="flex justify-between items-start flex-wrap gap-xs">
                    <div>
                        <h3 class="font-label-bold text-body-sm text-on-surface">Scan Kartu Keluarga (KK) <span class="text-error">*</span></h3>
                        <p class="text-caption text-on-surface-variant">Seluruh anggota keluarga harus terlihat jelas. Format: PDF/JPG/PNG max 2MB.</p>
                    </div>
                    <?php if ($berkas && !empty($berkas['fc_kk'])): ?>
                        <div class="inline-flex items-center gap-xs px-3 py-1 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-full font-label-bold text-[11px] shadow-sm select-none">
                            <span class="material-symbols-outlined text-[16px] text-emerald-700">check_circle</span> Sudah Terunggah
                        </div>
                    <?php else: ?>
                        <div class="inline-flex items-center gap-xs px-3 py-1 bg-amber-100 border border-amber-300 text-amber-800 rounded-full font-label-bold text-[11px] shadow-sm select-none">
                            <span class="material-symbols-outlined text-[16px] text-amber-600">pending</span> Wajib Diunggah
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col gap-xs mt-1">
                    <input class="w-full text-body-sm file:mr-md file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-body-sm file:font-semibold file:bg-primary-fixed file:text-on-primary-fixed hover:file:bg-primary-fixed-dim cursor-pointer" 
                           id="fc_kk" name="fc_kk" type="file" <?= ($berkas && !empty($berkas['fc_kk'])) ? '' : 'required' ?> />
                    <?php if (isset($errors['fc_kk'])): ?>
                        <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['fc_kk']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Unggah Pas Foto 3x4 -->
            <div class="flex flex-col gap-sm p-sm rounded-xl bg-surface-container-low border border-outline-variant/40">
                <div class="flex justify-between items-start flex-wrap gap-xs">
                    <div>
                        <h3 class="font-label-bold text-body-sm text-on-surface">Pas Foto 3x4 <span class="text-error">*</span></h3>
                        <p class="text-caption text-on-surface-variant">Foto resmi terbaru (berwarna). Format: JPG/JPEG/PNG max 2MB.</p>
                    </div>
                    <?php if ($berkas && !empty($berkas['pas_foto'])): ?>
                        <div class="inline-flex items-center gap-xs px-3 py-1 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-full font-label-bold text-[11px] shadow-sm select-none">
                            <span class="material-symbols-outlined text-[16px] text-emerald-700">check_circle</span> Sudah Terunggah
                        </div>
                    <?php else: ?>
                        <div class="inline-flex items-center gap-xs px-3 py-1 bg-amber-100 border border-amber-300 text-amber-800 rounded-full font-label-bold text-[11px] shadow-sm select-none">
                            <span class="material-symbols-outlined text-[16px] text-amber-600">pending</span> Wajib Diunggah
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col gap-xs mt-1">
                    <input class="w-full text-body-sm file:mr-md file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-body-sm file:font-semibold file:bg-primary-fixed file:text-on-primary-fixed hover:file:bg-primary-fixed-dim cursor-pointer" 
                           id="pas_foto" name="pas_foto" type="file" <?= ($berkas && !empty($berkas['pas_foto'])) ? '' : 'required' ?> accept=".jpg,.jpeg,.png" />
                    <?php if (isset($errors['pas_foto'])): ?>
                        <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['pas_foto']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <?= $this->include('components/siswa/form-actions') ?>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?= script_tag('js/siswa/form-berkas.js') ?>
<?= $this->endSection() ?>
