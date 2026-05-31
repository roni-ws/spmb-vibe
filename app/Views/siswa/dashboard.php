<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>
Dashboard Calon Siswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Alert Messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="mb-md p-sm bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-xl flex items-start gap-xs shadow-sm">
        <span class="material-symbols-outlined text-[20px] text-emerald-700">check_circle</span>
        <div>
            <p class="font-label-bold text-body-sm"><?= esc(session()->getFlashdata('success')) ?></p>
        </div>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="mb-md p-sm bg-error-container text-on-error-container border border-error/20 rounded-xl flex items-start gap-xs shadow-sm">
        <span class="material-symbols-outlined text-[20px] text-error">error</span>
        <div>
            <p class="font-label-bold text-body-sm"><?= esc(session()->getFlashdata('error')) ?></p>
        </div>
    </div>
<?php endif; ?>

<!-- Welcome Section -->
<section class="mb-lg">
    <h1 class="font-display-lg-mobile text-display-lg-mobile md:font-display-lg md:text-display-lg text-primary mb-xs">
        Halo, <?= esc($nama_lengkap) ?>!
    </h1>
    <p class="font-body-lg text-body-lg text-on-surface-variant">
        Berikut adalah ringkasan status pendaftaran Anda di SMA Unggul Bangsa.
    </p>
</section>

<!-- Bento Grid Layout -->
<div class="grid grid-cols-1 md:grid-cols-12 gap-md">
    <!-- Pengumuman Kelulusan Section -->
    <?php if ($status_kelulusan === 'Lulus'): ?>
        <div class="md:col-span-12 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl p-md md:p-lg border border-emerald-400 shadow-lg relative overflow-hidden">
            <!-- Decorative icon background -->
            <div class="absolute -right-6 -bottom-6 text-white/10 select-none pointer-events-none">
                <span class="material-symbols-outlined" style="font-size: 150px;">workspace_premium</span>
            </div>
            <div class="flex items-start gap-md relative z-10">
                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center shrink-0 shadow-inner">
                    <span class="material-symbols-outlined text-3xl text-white">celebration</span>
                </div>
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-white mb-xs">Selamat! Anda Dinyatakan LULUS</h2>
                    <p class="font-body-lg text-body-md text-emerald-50 leading-relaxed max-w-xl">
                        Berdasarkan hasil seleksi akademik, berkas pendaftaran, dan rapat pleno panitia PPDB SMA Unggul Bangsa, Anda dinyatakan **LULUS** sebagai siswa baru.
                    </p>
                    <div class="mt-sm flex gap-sm">
                        <a href="<?= base_url('siswa/download-form') ?>" class="inline-flex items-center gap-xs px-4 py-2 bg-white text-emerald-700 font-label-bold text-caption rounded-lg hover:bg-emerald-50 transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-sm">print</span> Cetak Bukti Kelulusan / Formulir
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php elseif ($status_kelulusan === 'Tidak Lulus'): ?>
        <div class="md:col-span-12 bg-surface border border-red-200 rounded-2xl p-md md:p-lg shadow-[0_4px_12px_rgba(186,26,26,0.04)]">
            <div class="flex items-start gap-md">
                <div class="w-14 h-14 bg-red-50 text-red-600 rounded-full flex items-center justify-center shrink-0 border border-red-200">
                    <span class="material-symbols-outlined text-3xl">sentiment_dissatisfied</span>
                </div>
                <div>
                    <h2 class="font-headline-sm text-headline-sm text-red-700 mb-xs">Pengumuman Kelulusan</h2>
                    <p class="font-body-lg text-body-md text-on-surface-variant leading-relaxed max-w-xl">
                        Mohon maaf, berdasarkan hasil keputusan panitia PPDB SMA Unggul Bangsa, Anda dinyatakan **BELUM LULUS** pada seleksi penerimaan tahun ajaran ini. Tetap semangat, perjalanan Anda masih panjang dan luas!
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Status Card -->
    <div class="md:col-span-8 bg-surface border border-outline-variant rounded-xl p-md shadow-[0_4px_12px_rgba(0,32,69,0.04)] relative overflow-hidden flex flex-col justify-between min-h-[220px]">
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
                <?php elseif ($status_verifikasi === 'Tidak Valid' || $status_verifikasi === 'Revisi'): ?>
                    <div class="inline-flex items-center gap-xs px-3 py-1 bg-red-100 text-red-800 rounded-full font-label-bold text-caption border border-red-300">
                        <span class="material-symbols-outlined text-[16px]">gpp_bad</span>
                        Verifikasi Berkas: Butuh Revisi
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
            <div class="grid grid-cols-2 md:grid-cols-4 gap-xs mt-3 text-[11px] text-slate-500">
                <div class="flex items-center gap-xs">
                    <span class="material-symbols-outlined text-emerald-500 text-sm">check_circle</span> Akun Dibuat
                </div>
                <div class="flex items-center gap-xs">
                    <span class="material-symbols-outlined <?= $has_biodata ? 'text-emerald-500' : 'text-slate-300' ?> text-sm">
                        <?= $has_biodata ? 'check_circle' : 'radio_button_unchecked' ?>
                    </span> Biodata Diri
                </div>
                <div class="flex items-center gap-xs">
                    <span class="material-symbols-outlined <?= $has_sekolah ? 'text-emerald-500' : 'text-slate-300' ?> text-sm">
                        <?= $has_sekolah ? 'check_circle' : 'radio_button_unchecked' ?>
                    </span> Asal Sekolah
                </div>
                <div class="flex items-center gap-xs">
                    <span class="material-symbols-outlined <?= $has_berkas ? 'text-emerald-500' : 'text-slate-300' ?> text-sm">
                        <?= $has_berkas ? 'check_circle' : 'radio_button_unchecked' ?>
                    </span> Berkas Diunggah
                </div>
            </div>
        </div>
    </div>

    <!-- Action Cards Container -->
    <div class="md:col-span-4 flex flex-col gap-md">
        <!-- Download Form Action -->
        <a class="group flex items-center gap-sm p-sm bg-surface border border-outline-variant rounded-xl shadow-[0_4px_12px_rgba(0,32,69,0.04)] hover:shadow-[0_8px_20px_rgba(0,32,69,0.08)] hover:border-primary transition-all cursor-pointer"
            href="<?= base_url('siswa/download-form') ?>">
            <div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-on-primary transition-colors shrink-0">
                <span class="material-symbols-outlined">download</span>
            </div>
            <div class="flex-grow">
                <h3 class="font-label-bold text-body-sm text-on-surface group-hover:text-primary transition-colors">
                    Cetak Bukti Pendaftaran
                </h3>
                <p class="font-caption text-caption text-on-surface-variant">Dokumen Bukti PDF</p>
            </div>
            <span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors">chevron_right</span>
        </a>
        
        <!-- WhatsApp Group Action -->
        <a class="group flex items-center gap-sm p-sm bg-surface border border-outline-variant rounded-xl shadow-[0_4px_12px_rgba(0,32,69,0.04)] hover:shadow-[0_8px_20px_rgba(0,32,69,0.08)] hover:border-[#25D366] transition-all cursor-pointer"
            href="https://wa.me/6281234567890" target="_blank">
            <div class="w-10 h-10 rounded-lg bg-[#25D366]/10 flex items-center justify-center text-[#25D366] group-hover:bg-[#25D366] group-hover:text-white transition-colors shrink-0">
                <span class="material-symbols-outlined font-bold">chat</span>
            </div>
            <div class="flex-grow">
                <h3 class="font-label-bold text-body-sm text-on-surface group-hover:text-[#25D366] transition-colors">
                    Grup WhatsApp Resmi
                </h3>
                <p class="font-caption text-caption text-on-surface-variant">Info & Koordinasi Panitia</p>
            </div>
            <span class="material-symbols-outlined text-outline-variant group-hover:text-[#25D366] transition-colors">chevron_right</span>
        </a>
    </div>

    <!-- Finalisasi Pendaftaran Card -->
    <div class="md:col-span-8">
        <?php if ($is_finalized === 0): ?>
            <div class="bg-surface-container-low border-2 border-dashed border-amber-300 rounded-xl p-md shadow-sm flex flex-col justify-between">
                <div class="flex gap-sm items-start mb-sm">
                    <span class="material-symbols-outlined text-amber-600 text-2xl shrink-0">warning</span>
                    <div>
                        <h3 class="font-label-bold text-body-sm text-amber-900 mb-1">Finalisasi Pendaftaran Diperlukan</h3>
                        <p class="font-caption text-caption text-amber-800 leading-relaxed">
                            Harap periksa kembali semua data biodata diri, data sekolah, dan dokumen berkas Anda. Jika sudah yakin benar, Anda wajib melakukan **Finalisasi Pendaftaran** agar berkas dapat divalidasi oleh panitia. Setelah difinalisasi, data Anda akan dikunci dan **TIDAK dapat diubah lagi**.
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
                        Terima kasih! Anda telah melakukan finalisasi pendaftaran. Berkas pendaftaran Anda telah terkunci secara aman dan sedang menunggu proses verifikasi dan validasi lengkap oleh panitia PPDB SMA Unggul Bangsa.
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Dynamic Verification Feedback Card (If present in database) -->
    <?php if (!empty($catatan_verifikasi)): ?>
        <div class="md:col-span-12">
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

    <!-- Announcements Section -->
    <div class="md:col-span-12 mt-md">
        <div class="flex items-center justify-between mb-sm border-b border-outline-variant pb-2">
            <h2 class="font-headline-sm text-headline-sm text-primary flex items-center gap-xs">
                <span class="material-symbols-outlined text-2xl shrink-0 text-primary">campaign</span> Pengumuman Penting
            </h2>
            <button class="text-body-sm font-label-bold text-primary hover:underline">Lihat Semua</button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-sm">
            <!-- Announcement Item 1 -->
            <div class="p-sm bg-surface-container-low rounded-lg border border-outline-variant hover:bg-surface-container transition-colors">
                <div class="flex items-start gap-sm">
                    <div class="w-2 h-2 mt-2 rounded-full bg-secondary-container flex-shrink-0"></div>
                    <div>
                        <h3 class="font-label-bold text-body-sm text-on-surface mb-1">Jadwal Ujian Masuk Seleksi Akademik</h3>
                        <p class="font-caption text-caption text-on-surface-variant mb-2">
                            Ujian seleksi potensi akademik dan wawancara online akan diselenggarakan pada hari Selasa depan. Harap cetak bukti pendaftaran dan kartu ujian Anda terlebih dahulu.
                        </p>
                        <span class="font-caption text-[10px] text-outline">2 jam yang lalu</span>
                    </div>
                </div>
            </div>
            
            <!-- Announcement Item 2 -->
            <div class="p-sm bg-surface-container-low rounded-lg border border-outline-variant hover:bg-surface-container transition-colors">
                <div class="flex items-start gap-sm">
                    <div class="w-2 h-2 mt-2 rounded-full bg-outline-variant flex-shrink-0"></div>
                    <div>
                        <h3 class="font-label-bold text-body-sm text-on-surface mb-1">Panduan Unggah Berkas Persyaratan</h3>
                        <p class="font-caption text-caption text-on-surface-variant mb-2">
                            Pastikan semua dokumen hasil scan (Ijazah/SKL, Kartu Keluarga, Rapor) terbaca dengan sangat jelas. Format file yang diterima hanya berupa gambar/PDF dengan batas ukuran maksimal 2MB per file.
                        </p>
                        <span class="font-caption text-[10px] text-outline">Kemarin</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
