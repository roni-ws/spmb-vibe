<!-- ==========================================
     SUB-TAB 3: GALERI FOTO
     ========================================== -->
<div id="tab-galeri" class="tab-pane hidden flex flex-col gap-md">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md md:p-lg shadow-sm">
        <h3 class="font-headline-sm text-headline-sm text-primary mb-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">photo_library</span> Galeri Foto Halaman Depan
        </h3>
        <p class="text-caption text-on-surface-variant mb-6">Unggah hingga 4 foto kegiatan sekolah yang akan ditampilkan dalam grid 2×2 di bagian persyaratan halaman depan.</p>

        <div class="flex flex-col gap-6">
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="flex flex-col gap-2">
                    <label class="text-body-sm font-semibold text-on-surface">Foto Galeri <?= $i ?></label>
                    <div class="flex flex-wrap items-center gap-md p-4 rounded-xl border border-dashed border-outline-variant bg-surface/50">
                        <div class="w-36 h-24 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center shrink-0 border border-outline-variant">
                            <?php $galleryKey = "gallery_img_{$i}"; ?>
                            <?php if (!empty($settings[$galleryKey]) && file_exists(FCPATH . $settings[$galleryKey])): ?>
                                <img id="gallery<?= $i ?>-preview" src="<?= base_url($settings[$galleryKey]) ?>" class="w-full h-full object-cover" alt="Gallery <?= $i ?>">
                            <?php else: ?>
                                <div id="gallery<?= $i ?>-preview-placeholder" class="text-center text-outline flex flex-col items-center justify-center h-full">
                                    <span class="material-symbols-outlined text-3xl">add_photo_alternate</span>
                                    <span class="text-[9px]">Kosong</span>
                                </div>
                                <img id="gallery<?= $i ?>-preview" class="w-full h-full object-cover hidden" alt="Gallery <?= $i ?> Preview">
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow flex flex-col gap-1.5 min-w-[200px]">
                            <input type="file" name="gallery_file_<?= $i ?>" id="gallery_file_<?= $i ?>" accept="image/png,image/jpg,image/jpeg"
                                   class="text-caption text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-caption file:font-semibold file:bg-primary-container file:text-on-primary-container hover:file:opacity-90 file:cursor-pointer"
                                   onchange="previewImage(this, 'gallery<?= $i ?>-preview', 'gallery<?= $i ?>-preview-placeholder')">
                            <p class="text-[11px] text-on-surface-variant">Format: PNG, JPG, JPEG. Maksimum 2MB. Biarkan kosong jika tidak ingin mengubah.</p>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>
