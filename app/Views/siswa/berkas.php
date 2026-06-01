<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>
Kelengkapan Berkas (Langkah 4 dari 4)
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
        <!-- Step 2: Completed -->
        <a href="<?= base_url('siswa/sekolah') ?>" class="flex items-center gap-xs w-full md:w-auto hover:opacity-80 transition-opacity">
            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">check</span>
            </div>
            <div>
                <span class="block text-sm font-bold text-emerald-600 leading-tight">Asal Sekolah</span>
                <span class="block text-[11px] text-emerald-500">Selesai</span>
            </div>
        </a>
        <div class="hidden md:block flex-grow h-px bg-emerald-200 mx-xs"></div>
        <!-- Step 3: Completed -->
        <a href="<?= base_url('siswa/orang-tua') ?>" class="flex items-center gap-xs w-full md:w-auto hover:opacity-80 transition-opacity">
            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">check</span>
            </div>
            <div>
                <span class="block text-sm font-bold text-emerald-600 leading-tight">Data Orang Tua</span>
                <span class="block text-[11px] text-emerald-500">Selesai</span>
            </div>
        </a>
        <div class="hidden md:block flex-grow h-px bg-emerald-200 mx-xs"></div>
        <!-- Step 4: Active -->
        <div class="flex items-center gap-xs w-full md:w-auto">
            <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm shrink-0 shadow-md">4</div>
            <div>
                <span class="block text-sm font-bold text-primary leading-tight">Berkas Persyaratan</span>
                <span class="block text-[11px] text-primary">Sedang Diisi</span>
            </div>
        </div>
    </div>
    <!-- Mobile Progress Bar -->
    <div class="mt-sm md:hidden">
        <div class="h-1.5 w-full bg-surface-container-high rounded-full overflow-hidden">
            <div class="h-full bg-primary rounded-full" style="width: 100%;"></div>
        </div>
    </div>
</div>

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
            <div class="flex flex-col sm:flex-row justify-between items-center gap-md border-t border-outline-variant pt-md mt-lg">
                <a href="<?= base_url('siswa/orang-tua') ?>" class="w-full sm:w-auto rounded-lg border border-outline text-on-surface-variant hover:bg-surface-container-high transition-colors flex items-center justify-center gap-xs font-label-bold text-body-sm text-center"
                   style="padding: 12px 36px !important; min-width: 160px;">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    Kembali
                </a>
                <button class="w-full sm:w-auto rounded-lg bg-primary text-on-primary font-label-bold text-body-sm hover:bg-primary-container transition-colors shadow-sm hover:shadow-md flex items-center justify-center gap-xs group" 
                        style="padding: 12px 36px !important; min-width: 160px; cursor: pointer;"
                        type="submit">
                    Simpan & Selesai
                    <span class="material-symbols-outlined text-[20px]">check</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
