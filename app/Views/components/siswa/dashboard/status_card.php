<!-- Main Status Card -->
<div class="md:col-span-8 bg-surface border border-outline-variant rounded-xl p-md shadow-[0_4px_12px_rgba(0,32,69,0.04)] relative overflow-hidden flex flex-col justify-between min-h-[220px] order-1">
    <!-- Decorative background element -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-secondary-container opacity-20 rounded-bl-full transform translate-x-8 -translate-y-8 pointer-events-none"></div>
    
    <div class="flex justify-between items-start mb-lg relative z-10">
        <div>
            <h2 class="font-headline-md text-headline-md text-primary mb-xs">Status Pendaftaran</h2>
            
            <!-- Dynamic Status Badge -->
            <?php if ($status_verifikasi === 'Valid'): ?>
                <div class="inline-flex items-center gap-xs px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full font-label-bold text-caption border border-emerald-300">
                    <span class="material-symbols-outlined text-[16px]">verified</span>
                    Verifikasi Berkas: Valid / Terverifikasi
                </div>
            <?php elseif ($is_finalized === 1): ?>
                <div class="inline-flex items-center gap-xs px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-label-bold text-caption border border-blue-300">
                    <span class="material-symbols-outlined text-[16px]">pending_actions</span>
                    Verifikasi Berkas: Menunggu Verifikasi Panitia
                </div>
            <?php elseif ($status_verifikasi === 'Revisi'): ?>
                <div class="inline-flex items-center gap-xs px-3 py-1 bg-red-100 text-red-800 rounded-full font-label-bold text-caption border border-red-300">
                    <span class="material-symbols-outlined text-[16px]">gpp_bad</span>
                    Verifikasi Berkas: Butuh Revisi
                </div>
            <?php elseif ($status_verifikasi === 'Tidak Valid'): ?>
                <div class="inline-flex items-center gap-xs px-3 py-1 bg-red-100 text-red-800 rounded-full font-label-bold text-caption border border-red-300">
                    <span class="material-symbols-outlined text-[16px]">gpp_bad</span>
                    Verifikasi Berkas: Tidak Valid
                </div>
            <?php else: ?>
                <div class="inline-flex items-center gap-xs px-3 py-1 bg-amber-100 text-amber-800 rounded-full font-label-bold text-caption border border-amber-300">
                    <span class="material-symbols-outlined text-[16px]">pending_actions</span>
                    Verifikasi Berkas: Sedang Diproses (Pending)
                </div>
            <?php endif; ?>
        </div>
        
        <div class="w-12 h-12 rounded-full bg-surface-container-low flex items-center justify-center text-primary border border-outline-variant">
            <span class="material-symbols-outlined text-[24px]">assignment</span>
        </div>
    </div>
    
    <!-- Progress Steps -->
    <div class="relative z-10 mt-auto">
        <div class="flex justify-between text-body-sm text-on-surface-variant mb-2">
            <span class="font-semibold text-primary">Langkah <?= esc($progress_step) ?> dari 4</span>
            <span class="font-bold text-primary"><?= esc($progress_percent) ?>% Selesai</span>
        </div>
        
        <div class="w-full h-3 bg-surface-container-high rounded-full overflow-hidden">
            <div class="h-full bg-primary rounded-full relative transition-all duration-500 ease-out" style="width: <?= esc($progress_percent) ?>%;">
                <!-- Shimmer effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full animate-[shimmer_2s_infinite]"></div>
            </div>
        </div>
        
        <!-- Step Checklist Details -->
        <div class="mt-4 border-t border-outline-variant/60 pt-sm relative z-10">
            <?php if ($can_edit): ?>
                <p class="text-[11px] font-semibold text-primary mb-sm flex items-center gap-xs">
                    <span class="material-symbols-outlined text-[15px] text-primary">info</span>
                    Panduan: Silakan klik tombol langkah berwarna kuning di bawah ini untuk mengisi data yang belum lengkap.
                </p>
            <?php endif; ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-sm text-[11px]">
                <!-- Step 1: Akun -->
                <div class="flex items-center justify-between p-2 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-sm">
                    <div class="flex items-center gap-xs font-semibold">
                        <span class="material-symbols-outlined text-emerald-500 text-base">check_circle</span>
                        Akun Terdaftar
                    </div>
                    <span class="font-bold text-[9px] uppercase px-1.5 py-0.5 bg-emerald-200 text-emerald-900 rounded">Selesai</span>
                </div>

                <!-- Step 2: Biodata -->
                <?php if ($can_edit): ?>
                    <a href="<?= base_url('siswa/biodata') ?>" 
                       class="flex items-center justify-between p-2 rounded-lg transition-all shadow-sm group hover:scale-[1.02] hover:border-primary
                              <?= $has_biodata ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' : 'animate-[pulse_3s_infinite]' ?>"
                       <?= !$has_biodata ? 'style="background-color: #fef3c7 !important; border: 1px solid #f59e0b !important; color: #78350f !important;"' : '' ?>>
                        <div class="flex items-center gap-xs font-semibold">
                            <span class="material-symbols-outlined text-base" style="color: <?= $has_biodata ? '#10b981' : '#b45309' ?> !important; font-weight: bold;">
                                <?= $has_biodata ? 'check_circle' : 'edit_square' ?>
                            </span>
                            Biodata Diri
                        </div>
                        <?php if ($has_biodata): ?>
                            <span class="font-bold text-[9px] uppercase px-1.5 py-0.5 bg-emerald-200 text-emerald-900 rounded">Selesai</span>
                        <?php else: ?>
                            <span class="font-bold text-[9px] uppercase px-1.5 py-0.5 rounded text-white transition-colors" style="background-color: #d97706 !important;">Isi ➜</span>
                        <?php endif; ?>
                    </a>
                <?php else: ?>
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200 text-slate-700 shadow-sm">
                        <div class="flex items-center gap-xs font-semibold">
                            <span class="material-symbols-outlined text-base <?= $has_biodata ? 'text-emerald-500' : 'text-slate-400' ?>">
                                <?= $has_biodata ? 'check_circle' : 'radio_button_unchecked' ?>
                            </span>
                            Biodata Diri
                        </div>
                        <span class="font-bold text-[9px] uppercase px-1.5 py-0.5 bg-slate-200 text-slate-800 rounded"><?= $has_biodata ? 'Selesai' : 'Kosong' ?></span>
                    </div>
                <?php endif; ?>

                <!-- Step 3: Asal Sekolah -->
                <?php if ($can_edit): ?>
                    <a href="<?= base_url('siswa/sekolah') ?>" 
                       class="flex items-center justify-between p-2 rounded-lg transition-all shadow-sm group hover:scale-[1.02] hover:border-primary
                              <?= $has_sekolah ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' : '' ?>"
                       <?= !$has_sekolah ? 'style="background-color: #fef3c7 !important; border: 1px solid #f59e0b !important; color: #78350f !important;"' : '' ?>>
                        <div class="flex items-center gap-xs font-semibold">
                            <span class="material-symbols-outlined text-base" style="color: <?= $has_sekolah ? '#10b981' : '#b45309' ?> !important; font-weight: bold;">
                                <?= $has_sekolah ? 'check_circle' : 'school' ?>
                            </span>
                            Asal Sekolah
                        </div>
                        <?php if ($has_sekolah): ?>
                            <span class="font-bold text-[9px] uppercase px-1.5 py-0.5 bg-emerald-200 text-emerald-900 rounded">Selesai</span>
                        <?php else: ?>
                            <span class="font-bold text-[9px] uppercase px-1.5 py-0.5 rounded text-white transition-colors" style="background-color: #d97706 !important;">Isi ➜</span>
                        <?php endif; ?>
                    </a>
                <?php else: ?>
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200 text-slate-700 shadow-sm">
                        <div class="flex items-center gap-xs font-semibold">
                            <span class="material-symbols-outlined text-base <?= $has_sekolah ? 'text-emerald-500' : 'text-slate-400' ?>">
                                <?= $has_sekolah ? 'check_circle' : 'radio_button_unchecked' ?>
                            </span>
                            Asal Sekolah
                        </div>
                        <span class="font-bold text-[9px] uppercase px-1.5 py-0.5 bg-slate-200 text-slate-800 rounded"><?= $has_sekolah ? 'Selesai' : 'Kosong' ?></span>
                    </div>
                <?php endif; ?>

                <!-- Step 4: Berkas -->
                <?php if ($can_edit): ?>
                    <a href="<?= base_url('siswa/berkas') ?>" 
                       class="flex items-center justify-between p-2 rounded-lg transition-all shadow-sm group hover:scale-[1.02] hover:border-primary
                              <?= $has_berkas ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' : '' ?>"
                       <?= !$has_berkas ? 'style="background-color: #fef3c7 !important; border: 1px solid #f59e0b !important; color: #78350f !important;"' : '' ?>>
                        <div class="flex items-center gap-xs font-semibold">
                            <span class="material-symbols-outlined text-base" style="color: <?= $has_berkas ? '#10b981' : '#b45309' ?> !important; font-weight: bold;">
                                <?= $has_berkas ? 'check_circle' : 'upload_file' ?>
                            </span>
                            Berkas Dokumen
                        </div>
                        <?php if ($has_berkas): ?>
                            <span class="font-bold text-[9px] uppercase px-1.5 py-0.5 bg-emerald-200 text-emerald-900 rounded">Selesai</span>
                        <?php else: ?>
                            <span class="font-bold text-[9px] uppercase px-1.5 py-0.5 rounded text-white transition-colors" style="background-color: #d97706 !important;">Isi ➜</span>
                        <?php endif; ?>
                    </a>
                <?php else: ?>
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 border border-slate-200 text-slate-700 shadow-sm">
                        <div class="flex items-center gap-xs font-semibold">
                            <span class="material-symbols-outlined text-base <?= $has_berkas ? 'text-emerald-500' : 'text-slate-400' ?>">
                                <?= $has_berkas ? 'check_circle' : 'radio_button_unchecked' ?>
                            </span>
                            Berkas Dokumen
                        </div>
                        <span class="font-bold text-[9px] uppercase px-1.5 py-0.5 bg-slate-200 text-slate-800 rounded"><?= $has_berkas ? 'Selesai' : 'Kosong' ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Finalisasi Pendaftaran Card -->
<div class="md:col-span-8 order-3">
    <?php if ($can_edit): ?>
        <div class="bg-surface-container-low border-2 border-dashed border-amber-300 rounded-xl p-md shadow-sm flex flex-col justify-between">
            <div class="flex gap-sm items-start mb-sm">
                <span class="material-symbols-outlined text-amber-600 text-2xl shrink-0">warning</span>
                <div>
                    <h3 class="font-label-bold text-body-sm text-amber-900 mb-1"><?= $needs_revision ? 'Finalisasi Ulang Diperlukan' : 'Finalisasi Pendaftaran Diperlukan' ?></h3>
                    <p class="font-caption text-caption text-amber-800 leading-relaxed">
                        <?php if ($status_verifikasi === 'Tidak Valid'): ?>
                            Data Anda dinyatakan **Tidak Valid** oleh panitia. Perbaiki data/formulir sesuai petunjuk, lalu lakukan **Finalisasi Ulang** agar berkas dapat diverifikasi kembali.
                        <?php elseif ($needs_revision): ?>
                            Perbaiki data sesuai catatan panitia, lalu lakukan **Finalisasi Ulang** agar berkas dapat diverifikasi kembali.
                        <?php else: ?>
                            Harap periksa kembali semua data biodata diri, data sekolah, dan dokumen berkas Anda. Jika sudah yakin benar, Anda wajib melakukan **Finalisasi Pendaftaran** agar berkas dapat divalidasi oleh panitia. Setelah difinalisasi, data Anda akan dikunci dan **TIDAK dapat diubah lagi**.
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <form action="<?= base_url('siswa/finalize') ?>" method="POST" class="mt-xs">
                <?= csrf_field() ?>
                <label class="flex items-center gap-xs text-[11px] text-slate-700 cursor-pointer mb-sm">
                    <input type="checkbox" required class="rounded border-slate-300 text-primary focus:ring-primary" />
                    Saya menyatakan bahwa data yang diisi adalah benar dan siap melakukan finalisasi.
                </label>
                <button type="submit" class="inline-flex items-center gap-xs px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-label-bold text-caption rounded-lg transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-sm">lock</span> Lakukan Finalisasi Pendaftaran
                </button>
            </form>
        </div>
    <?php else: ?>
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-md shadow-sm flex items-start gap-sm">
            <span class="material-symbols-outlined text-emerald-600 text-2xl shrink-0">check_circle</span>
            <div>
                <h3 class="font-label-bold text-body-sm text-emerald-900 mb-1">Pendaftaran Telah Difinalisasi</h3>
                <p class="font-caption text-caption text-emerald-800 leading-relaxed">
                    Terima kasih! Anda telah melakukan finalisasi pendaftaran. Berkas pendaftaran Anda telah terkunci secara aman dan sedang menunggu proses verifikasi dan validasi lengkap oleh panitia PPDB <?= esc($logoText) ?>.
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Dynamic Verification Feedback Card (If present in database) -->
<?php if (!empty($catatan_verifikasi) && $status_verifikasi !== 'Valid'): ?>
    <div class="md:col-span-12 order-4">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-md flex gap-sm items-start shadow-[0_2px_8px_rgba(30,144,255,0.05)]">
            <span class="material-symbols-outlined text-blue-600 text-2xl shrink-0">info</span>
            <div>
                <h3 class="font-label-bold text-body-sm text-blue-900 mb-1">Catatan Dari Panitia Pendaftaran:</h3>
                <p class="font-caption text-body-sm text-blue-800 leading-relaxed">
                    "<?= esc($catatan_verifikasi) ?>"
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>
