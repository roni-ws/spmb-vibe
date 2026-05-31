<?= $this->extend('layouts/frontend') ?>

<?= $this->section('title') ?>SPMB SMA Unggul Bangsa | Penerimaan Siswa Baru<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="flex-grow">
        <!-- Hero Section -->
        <section id="home" class="relative bg-surface-container-lowest py-24 md:py-32 overflow-hidden">
            <?php
            $heroImg = \App\Models\SettingsModel::getSetting('hero_img', '');
            $heroSrc = ($heroImg && file_exists(FCPATH . $heroImg)) ? base_url($heroImg) : base_url('images/school_campus.png');
            ?>
            <div class="absolute inset-0 z-0">
                <img alt="Siswa berjalan di kampus modern"
                    class="w-full h-full object-cover opacity-20 object-center"
                    src="<?= $heroSrc ?>" />
                <div
                    class="absolute inset-0 bg-gradient-to-r from-surface-container-lowest via-surface-container-lowest/90 to-transparent">
                </div>
            </div>
            <div class="max-w-[1200px] mx-auto px-gutter relative z-10">
                <div class="max-w-2xl">
                    <!-- Dynamic Admission Status Badge -->
                    <?php if ($is_open): ?>
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-surface-container-high text-on-surface-variant font-label-bold text-label-bold mb-6 border border-primary-fixed-dim shadow-sm animate-pulse">
                            <span class="w-3.5 h-3.5 rounded-full bg-tertiary-fixed-dim animate-ping absolute"></span>
                            <span class="w-3.5 h-3.5 rounded-full bg-tertiary-fixed-dim relative"></span>
                            Pendaftaran Dibuka TA <?= esc($tahun_ajaran) ?>
                        </div>
                    <?php else: ?>
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-error-container text-on-error-container font-label-bold text-label-bold mb-6 border border-error/20">
                            <span class="w-3.5 h-3.5 rounded-full bg-error"></span>
                            Pendaftaran Saat Ini Ditutup
                        </div>
                    <?php endif; ?>
                    
                    <h1 class="font-display-lg text-display-lg-mobile md:text-display-lg text-primary mb-6 leading-tight"><?= esc($frontend_title) ?></h1>
                    <p class="font-body-lg text-body-lg text-on-surface-variant mb-8"><?= esc($frontend_subtitle) ?></p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <?php if ($is_open): ?>
                            <a href="/register"
                                class="bg-primary text-on-primary font-label-bold text-label-bold px-8 py-4 rounded-lg hover:opacity-90 transition-opacity flex items-center justify-center gap-2 shadow-lg hover:scale-[1.02] transform duration-100">
                                Daftar Sekarang
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        <?php else: ?>
                            <button disabled
                                class="bg-outline text-white font-label-bold text-label-bold px-8 py-4 rounded-lg cursor-not-allowed opacity-60 flex items-center justify-center gap-2">
                                Pendaftaran Ditutup
                                <span class="material-symbols-outlined text-sm">lock</span>
                            </button>
                        <?php endif; ?>
                        <a href="#syarat"
                            class="border border-primary text-primary font-label-bold text-label-bold px-8 py-4 rounded-lg hover:bg-surface-container-low transition-colors flex items-center justify-center">
                            Lihat Persyaratan
                        </a>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Admission Flow -->
        <section id="alur" class="py-20 bg-surface">
            <div class="max-w-[1200px] mx-auto px-gutter">
                <div class="text-center mb-16">
                    <h2 class="font-headline-md text-headline-md text-primary mb-4">Alur Pendaftaran SPMB</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl mx-auto">Proses pendaftaran kami rancang dengan mudah, cepat, dan transparan dari tahap pembuatan akun hingga pengumuman akhir.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
                    <!-- Connector Line (Desktop) -->
                    <div class="hidden md:block absolute top-12 left-[12.5%] right-[12.5%] h-px bg-outline-variant z-0">
                    </div>
                    <?php for($i = 1; $i <= 4; $i++): 
                        $stepTitle = \App\Models\SettingsModel::getSetting("step{$i}_title");
                        $stepDesc = \App\Models\SettingsModel::getSetting("step{$i}_desc");
                        $stepIcon = \App\Models\SettingsModel::getSetting("step{$i}_icon");
                        
                        // Default Fallbacks
                        if ($i === 1) {
                            $stepTitle = $stepTitle ?: "1. Registrasi Akun";
                            $stepDesc = $stepDesc ?: "Buat akun pendaftar secara online menggunakan username, email aktif, dan password Anda.";
                            $stepIcon = $stepIcon ?: "app_registration";
                        } elseif ($i === 2) {
                            $stepTitle = $stepTitle ?: "2. Isi Formulir";
                            $stepDesc = $stepDesc ?: "Masuk ke sistem, lengkapi formulir biodata pribadi, data kedua orang tua/wali, serta asal sekolah.";
                            $stepIcon = $stepIcon ?: "description";
                        } elseif ($i === 3) {
                            $stepTitle = $stepTitle ?: "3. Upload & Verifikasi";
                            $stepDesc = $stepDesc ?: "Unggah pas foto, ijazah/SKL, dan Kartu Keluarga (KK). Pantau proses verifikasi berkas oleh admin.";
                            $stepIcon = $stepIcon ?: "fact_check";
                        } else {
                            $stepTitle = $stepTitle ?: "4. Hasil & Dashboard";
                            $stepDesc = $stepDesc ?: "Unduh formulir pendaftaran PDF, gabung grup WhatsApp resmi, dan cek hasil pengumuman kelulusan.";
                            $stepIcon = $stepIcon ?: "celebration";
                        }
                        
                        $isLast = ($i === 4);
                        $bgClass = $isLast ? 'bg-secondary-container border border-secondary-container/50' : 'bg-surface-container-high border border-outline-variant/30';
                        $iconClass = $isLast ? 'text-on-secondary-container' : 'text-primary';
                    ?>
                        <!-- Step <?= $i ?> -->
                        <div class="relative z-10 flex flex-col items-center text-center group">
                            <div class="w-24 h-24 rounded-full <?= $bgClass ?> flex items-center justify-center mb-6 shadow-md group-hover:scale-105 transition-transform duration-200">
                                <span class="material-symbols-outlined text-4xl <?= $iconClass ?>"><?= esc($stepIcon) ?></span>
                            </div>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface mb-2"><?= esc($stepTitle) ?></h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant"><?= esc($stepDesc) ?></p>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </section>
        
        <!-- Requirements Bento Grid -->
        <section id="syarat" class="py-20 bg-surface-container-low">
            <div class="max-w-[1200px] mx-auto px-gutter">
                <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div>
                        <h2 class="font-headline-md text-headline-md text-primary mb-4">Persyaratan Pendaftaran</h2>
                        <p class="font-body-md text-body-md text-on-surface-variant w-full">Siapkan berkas-berkas wajib berikut agar mempermudah dan mempercepat proses verifikasi data Anda.</p>
                    </div>
                    <button
                        class="text-primary font-label-bold text-label-bold flex items-center gap-1 hover:underline">
                        Unduh Panduan Lengkap <span class="material-symbols-outlined text-sm">download</span>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Required Documents -->
                    <div
                        class="md:col-span-2 bg-surface rounded-xl p-8 shadow-[0_4px_12px_rgba(0,32,69,0.04)] border border-outline-variant">
                        <h3 class="font-headline-sm text-headline-sm text-on-surface mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary-fixed-dim"
                                data-icon="folder_open">folder_open</span>
                            Dokumen Persyaratan (Wajib)
                        </h3>
                        <ul class="space-y-4">
                            <?php for($i = 1; $i <= 3; $i++): 
                                $reqTitle = \App\Models\SettingsModel::getSetting("req{$i}_title");
                                $reqDesc = \App\Models\SettingsModel::getSetting("req{$i}_desc");
                                $reqIcon = \App\Models\SettingsModel::getSetting("req{$i}_icon");
                                
                                // Default Fallbacks
                                if ($i === 1) {
                                    $reqTitle = $reqTitle ?: "Pas Foto Terbaru";
                                    $reqDesc = $reqDesc ?: "Pas foto berwarna ukuran 3x4 cm dengan latar belakang merah (format JPG/PNG, ukuran maks. 2MB, diambil dalam 3 bulan terakhir).";
                                    $reqIcon = $reqIcon ?: "portrait";
                                } elseif ($i === 2) {
                                    $reqTitle = $reqTitle ?: "Ijazah SMP / SKL";
                                    $reqDesc = $reqDesc ?: "Salinan ijazah asli atau Surat Keterangan Lulus (SKL) sementara jika ijazah resmi belum diterbitkan sekolah (format PDF/Gambar).";
                                    $reqIcon = $reqIcon ?: "school";
                                } else {
                                    $reqTitle = $reqTitle ?: "Kartu Keluarga (KK)";
                                    $reqDesc = $reqDesc ?: "Salinan dokumen Kartu Keluarga resmi dari Dinas Kependudukan dan Catatan Sipil setempat.";
                                    $reqIcon = $reqIcon ?: "family_history";
                                }
                            ?>
                                <li class="flex items-start gap-4 pb-4 border-b border-outline-variant/50 last:border-0 last:pb-0">
                                    <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-primary"><?= esc($reqIcon) ?></span>
                                    </div>
                                    <div>
                                        <h4 class="font-label-bold text-label-bold text-on-surface"><?= esc($reqTitle) ?></h4>
                                        <p class="font-body-sm text-body-sm text-on-surface-variant"><?= esc($reqDesc) ?></p>
                                    </div>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                    <!-- Academic & General Criteria -->
                    <div class="bg-primary text-on-primary rounded-xl p-8 shadow-lg flex flex-col justify-between">
                        <div>
                            <h3 class="font-headline-sm text-headline-sm mb-6 flex items-center gap-2">
                                <span class="material-symbols-outlined text-tertiary-fixed-dim"
                                    data-icon="verified_user">verified_user</span>
                                Ketentuan Umum
                            </h3>
                            <p class="font-body-md text-body-md text-primary-fixed mb-6">Setiap calon siswa harus memenuhi kriteria umum penerimaan berikut:</p>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-2 font-body-sm text-body-sm text-on-primary">
                                    <span class="material-symbols-outlined text-tertiary-fixed-dim text-sm mt-0.5"
                                        data-icon="check_circle">check_circle</span>
                                    Lulusan SMP/MTs/Sederajat tahun ajaran berjalan atau 1 tahun sebelumnya.
                                </li>
                                <li class="flex items-start gap-2 font-body-sm text-body-sm text-on-primary">
                                    <span class="material-symbols-outlined text-tertiary-fixed-dim text-sm mt-0.5"
                                        data-icon="check_circle">check_circle</span>
                                    Berusia maksimal 21 tahun pada tanggal 1 Juli tahun pelajaran berjalan.
                                </li>
                                <li class="flex items-start gap-2 font-body-sm text-body-sm text-on-primary">
                                    <span class="material-symbols-outlined text-tertiary-fixed-dim text-sm mt-0.5"
                                        data-icon="check_circle">check_circle</span>
                                    Memiliki kelakuan baik, sehat jasmani & rohani, serta tidak sedang terlibat tindak kriminal/narkoba.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?= $this->endSection() ?>
