<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Pengaturan Frontend<?= $this->endSection() ?>
<?= $this->section('page-title') ?>Pengaturan Halaman Utama<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-[1000px] mx-auto">

    <!-- ==========================================
         PAGE HEADER
         ========================================== -->
    <div class="flex justify-between items-center mb-md">
        <div>
            <h1 class="font-headline-md text-headline-md font-bold text-primary mb-1">Pengaturan Website</h1>
            <p class="text-on-surface-variant text-body-sm">Kelola isi konten halaman depan, alur pendaftaran, dan informasi kontak sekolah secara real-time.</p>
        </div>
    </div>

    <!-- Alert Success / Errors -->
    <?php if (session()->has('success')): ?>
        <div class="mb-6 p-4 rounded-xl bg-tertiary-container text-on-tertiary-container border border-on-tertiary-container/20 flex items-center gap-3 text-body-sm font-semibold animate-fade-in">
            <span class="material-symbols-outlined">check_circle</span>
            <?= session('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->has('errors')): ?>
        <div class="mb-6 p-4 rounded-xl bg-error-container text-on-error-container border border-on-error-container/20 flex flex-col gap-1 text-body-sm animate-fade-in">
            <div class="flex items-center gap-3 font-semibold mb-1">
                <span class="material-symbols-outlined">error</span>
                Terdapat kesalahan dalam pengisian form:
            </div>
            <ul class="list-disc list-inside pl-4 text-caption">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- ==========================================
         SUB-TAB NAVIGATION
         ========================================== -->
    <nav class="flex flex-wrap gap-2 mb-md border-b border-outline-variant pb-2">
        <button type="button" onclick="switchSubTab('tab-branding')" id="btn-tab-branding"
                class="tab-btn active px-4 py-2.5 rounded-xl text-label-bold font-semibold transition-all flex items-center gap-2 border-b-2 border-transparent">
            <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
            Branding & Status
        </button>
        <button type="button" onclick="switchSubTab('tab-hero')" id="btn-tab-hero"
                class="tab-btn px-4 py-2.5 rounded-xl text-label-bold font-semibold transition-all flex items-center gap-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface hover:bg-surface-container">
            <span class="material-symbols-outlined text-[18px]">image</span>
            Hero Banner
        </button>
        <button type="button" onclick="switchSubTab('tab-alur')" id="btn-tab-alur"
                class="tab-btn px-4 py-2.5 rounded-xl text-label-bold font-semibold transition-all flex items-center gap-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface hover:bg-surface-container">
            <span class="material-symbols-outlined text-[18px]">route</span>
            Alur Pendaftaran
        </button>
        <button type="button" onclick="switchSubTab('tab-footer')" id="btn-tab-footer"
                class="tab-btn px-4 py-2.5 rounded-xl text-label-bold font-semibold transition-all flex items-center gap-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface hover:bg-surface-container">
            <span class="material-symbols-outlined text-[18px]">contact_support</span>
            Persyaratan & Footer
        </button>
        <button type="button" onclick="switchSubTab('tab-pengumuman')" id="btn-tab-pengumuman"
                class="tab-btn px-4 py-2.5 rounded-xl text-label-bold font-semibold transition-all flex items-center gap-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface hover:bg-surface-container">
            <span class="material-symbols-outlined text-[18px]">campaign</span>
            Pengumuman & WA
        </button>
    </nav>

    <!-- ==========================================
         MAIN CONFIGURATION FORM
         ========================================== -->
    <form action="<?= base_url('admin/settings/save') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- ==========================================
             SUB-TAB 1: BRANDING & STATUS
             ========================================== -->
        <div id="tab-branding" class="tab-pane flex flex-col gap-md">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md md:p-lg shadow-sm">
                <h3 class="font-headline-sm text-headline-sm text-primary mb-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">school</span> Identitas & Status Sekolah
                </h3>
                <p class="text-caption text-on-surface-variant mb-6">Sesuaikan nama identitas, logo, serta kontrol pembukaan registrasi siswa baru.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <!-- Status Pendaftaran -->
                    <div class="flex flex-col gap-2">
                        <label for="is_open" class="text-body-sm font-semibold text-on-surface">Status Pendaftaran</label>
                        <select name="is_open" id="is_open" class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                            <option value="1" <?= $settings['is_open'] == '1' ? 'selected' : '' ?>>🟢 Pendaftaran Dibuka (Open)</option>
                            <option value="0" <?= $settings['is_open'] == '0' ? 'selected' : '' ?>>🔴 Pendaftaran Ditutup (Closed)</option>
                        </select>
                    </div>

                    <!-- Tahun Ajaran -->
                    <div class="flex flex-col gap-2">
                        <label for="tahun_ajaran" class="text-body-sm font-semibold text-on-surface">Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" id="tahun_ajaran" value="<?= esc($settings['tahun_ajaran']) ?>"
                               class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" required>
                    </div>

                    <!-- Nama Sekolah / Teks Logo -->
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label for="logo_text" class="text-body-sm font-semibold text-on-surface">Nama Sekolah / Teks Branding Header</label>
                        <input type="text" name="logo_text" id="logo_text" value="<?= esc($settings['logo_text']) ?>"
                               class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" required>
                        <p class="text-caption text-on-surface-variant">Teks ini akan muncul sebagai branding utama pada bilah navigasi (header) halaman depan.</p>
                    </div>

                    <!-- Upload Logo Sekolah -->
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label class="text-body-sm font-semibold text-on-surface">Logo Sekolah (Header)</label>
                        <div class="flex flex-wrap items-center gap-md p-4 rounded-xl border border-dashed border-outline-variant bg-surface/50">
                            <div class="w-20 h-20 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center shrink-0 border border-outline-variant">
                                <?php if (!empty($settings['logo_img']) && file_exists(FCPATH . $settings['logo_img'])): ?>
                                    <img id="logo-preview" src="<?= base_url($settings['logo_img']) ?>" class="w-full h-full object-contain p-1" alt="Logo Sekolah">
                                <?php else: ?>
                                    <div id="logo-preview-placeholder" class="text-center text-outline flex flex-col items-center">
                                        <span class="material-symbols-outlined text-3xl">school</span>
                                        <span class="text-[9px]">Tanpa Logo</span>
                                    </div>
                                    <img id="logo-preview" class="w-full h-full object-contain p-1 hidden" alt="Logo Sekolah Preview">
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow flex flex-col gap-1.5 min-w-[200px]">
                                <input type="file" name="logo_file" id="logo_file" accept="image/png,image/jpg,image/jpeg"
                                       class="text-caption text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-caption file:font-semibold file:bg-primary-container file:text-on-primary-container hover:file:opacity-90 file:cursor-pointer"
                                       onchange="previewImage(this, 'logo-preview', 'logo-preview-placeholder')">
                                <p class="text-[11px] text-on-surface-variant">Format: PNG, JPG, JPEG. Maksimum file 2MB. Logo ini akan menggantikan ikon sekolah bawaan di bagian navigasi teratas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==========================================
             SUB-TAB 2: HERO BANNER
             ========================================== -->
        <div id="tab-hero" class="tab-pane hidden flex flex-col gap-md">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md md:p-lg shadow-sm">
                <h3 class="font-headline-sm text-headline-sm text-primary mb-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">image</span> Header Promosi Utama (Hero Section)
                </h3>
                <p class="text-caption text-on-surface-variant mb-6">Ubah tulisan promosi pembuka serta banner grafis utama yang menyapa calon siswa baru saat membuka situs.</p>

                <div class="flex flex-col gap-md">
                    <!-- Judul Hero -->
                    <div class="flex flex-col gap-2">
                        <label for="frontend_title" class="text-body-sm font-semibold text-on-surface">Judul Utama Halaman Depan (Title)</label>
                        <input type="text" name="frontend_title" id="frontend_title" value="<?= esc($settings['frontend_title']) ?>"
                               class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" required>
                    </div>

                    <!-- Sub-Judul Hero -->
                    <div class="flex flex-col gap-2">
                        <label for="frontend_subtitle" class="text-body-sm font-semibold text-on-surface">Sub-Judul / Deskripsi Singkat (Subtitle)</label>
                        <textarea name="frontend_subtitle" id="frontend_subtitle" rows="3"
                                  class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" required><?= esc($settings['frontend_subtitle']) ?></textarea>
                    </div>

                    <!-- Upload Hero Image -->
                    <div class="flex flex-col gap-2">
                        <label class="text-body-sm font-semibold text-on-surface">Gambar Ilustrasi Utama (Hero Image)</label>
                        <div class="flex flex-wrap items-center gap-md p-4 rounded-xl border border-dashed border-outline-variant bg-surface/50">
                            <div class="w-36 h-20 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center shrink-0 border border-outline-variant">
                                <?php if (!empty($settings['hero_img']) && file_exists(FCPATH . $settings['hero_img'])): ?>
                                    <img id="hero-preview" src="<?= base_url($settings['hero_img']) ?>" class="w-full h-full object-cover" alt="Hero Image">
                                <?php else: ?>
                                    <div id="hero-preview-placeholder" class="text-center text-outline flex flex-col items-center justify-center h-full">
                                        <span class="material-symbols-outlined text-3xl">image</span>
                                        <span class="text-[9px]">Ilustrasi Default</span>
                                    </div>
                                    <img id="hero-preview" class="w-full h-full object-cover hidden" alt="Hero Image Preview">
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow flex flex-col gap-1.5 min-w-[200px]">
                                <input type="file" name="hero_file" id="hero_file" accept="image/png,image/jpg,image/jpeg"
                                       class="text-caption text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-caption file:font-semibold file:bg-primary-container file:text-on-primary-container hover:file:opacity-90 file:cursor-pointer"
                                       onchange="previewImage(this, 'hero-preview', 'hero-preview-placeholder')">
                                <p class="text-[11px] text-on-surface-variant">Format: PNG, JPG, JPEG. Maksimum file 4MB. Direkomendasikan gambar dengan rasio lebar (landscape) atau ilustrasi SVG modern.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==========================================
             SUB-TAB 3: ALUR PENDAFTARAN
             ========================================== -->
        <div id="tab-alur" class="tab-pane hidden flex flex-col gap-md">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md md:p-lg shadow-sm">
                <h3 class="font-headline-sm text-headline-sm text-primary mb-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">route</span> Alur Pendaftaran (Langkah 1 s.d 4)
                </h3>
                <p class="text-caption text-on-surface-variant mb-6">Ubah judul, detail instruksi, serta ikon Material Symbols untuk ke-empat alur alur pendaftaran siswa baru.</p>

                <div class="flex flex-col gap-8">
                    <!-- Step 1 -->
                    <div class="p-4 rounded-2xl bg-surface border border-outline-variant/60 flex flex-col gap-3">
                        <div class="flex items-center justify-between border-b border-outline-variant/30 pb-2">
                            <span class="text-body-sm font-bold text-primary flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px] font-bold">looks_one</span> LANGKAH PERTAMA
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-sm">
                            <div class="flex flex-col gap-1 md:col-span-2">
                                <label for="step1_title" class="text-caption font-semibold text-on-surface-variant">Judul Langkah</label>
                                <input type="text" name="step1_title" id="step1_title" value="<?= esc($settings['step1_title']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="step1_icon" class="text-caption font-semibold text-on-surface-variant">Ikon (Material Name)</label>
                                <input type="text" name="step1_icon" id="step1_icon" value="<?= esc($settings['step1_icon']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                            </div>
                            <div class="flex flex-col gap-1 md:col-span-3">
                                <label for="step1_desc" class="text-caption font-semibold text-on-surface-variant">Deskripsi Alur</label>
                                <input type="text" name="step1_desc" id="step1_desc" value="<?= esc($settings['step1_desc']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="p-4 rounded-2xl bg-surface border border-outline-variant/60 flex flex-col gap-3">
                        <div class="flex items-center justify-between border-b border-outline-variant/30 pb-2">
                            <span class="text-body-sm font-bold text-primary flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px] font-bold">looks_two</span> LANGKAH KEDUA
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-sm">
                            <div class="flex flex-col gap-1 md:col-span-2">
                                <label for="step2_title" class="text-caption font-semibold text-on-surface-variant">Judul Langkah</label>
                                <input type="text" name="step2_title" id="step2_title" value="<?= esc($settings['step2_title']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="step2_icon" class="text-caption font-semibold text-on-surface-variant">Ikon (Material Name)</label>
                                <input type="text" name="step2_icon" id="step2_icon" value="<?= esc($settings['step2_icon']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                            </div>
                            <div class="flex flex-col gap-1 md:col-span-3">
                                <label for="step2_desc" class="text-caption font-semibold text-on-surface-variant">Deskripsi Alur</label>
                                <input type="text" name="step2_desc" id="step2_desc" value="<?= esc($settings['step2_desc']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="p-4 rounded-2xl bg-surface border border-outline-variant/60 flex flex-col gap-3">
                        <div class="flex items-center justify-between border-b border-outline-variant/30 pb-2">
                            <span class="text-body-sm font-bold text-primary flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px] font-bold">looks_3</span> LANGKAH KETIGA
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-sm">
                            <div class="flex flex-col gap-1 md:col-span-2">
                                <label for="step3_title" class="text-caption font-semibold text-on-surface-variant">Judul Langkah</label>
                                <input type="text" name="step3_title" id="step3_title" value="<?= esc($settings['step3_title']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="step3_icon" class="text-caption font-semibold text-on-surface-variant">Ikon (Material Name)</label>
                                <input type="text" name="step3_icon" id="step3_icon" value="<?= esc($settings['step3_icon']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                            </div>
                            <div class="flex flex-col gap-1 md:col-span-3">
                                <label for="step3_desc" class="text-caption font-semibold text-on-surface-variant">Deskripsi Alur</label>
                                <input type="text" name="step3_desc" id="step3_desc" value="<?= esc($settings['step3_desc']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="p-4 rounded-2xl bg-surface border border-outline-variant/60 flex flex-col gap-3">
                        <div class="flex items-center justify-between border-b border-outline-variant/30 pb-2">
                            <span class="text-body-sm font-bold text-primary flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px] font-bold">looks_4</span> LANGKAH KEEMPAT
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-sm">
                            <div class="flex flex-col gap-1 md:col-span-2">
                                <label for="step4_title" class="text-caption font-semibold text-on-surface-variant">Judul Langkah</label>
                                <input type="text" name="step4_title" id="step4_title" value="<?= esc($settings['step4_title']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="step4_icon" class="text-caption font-semibold text-on-surface-variant">Ikon (Material Name)</label>
                                <input type="text" name="step4_icon" id="step4_icon" value="<?= esc($settings['step4_icon']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                            </div>
                            <div class="flex flex-col gap-1 md:col-span-3">
                                <label for="step4_desc" class="text-caption font-semibold text-on-surface-variant">Deskripsi Alur</label>
                                <input type="text" name="step4_desc" id="step4_desc" value="<?= esc($settings['step4_desc']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==========================================
             SUB-TAB 4: PERSYARATAN & FOOTER
             ========================================== -->
        <div id="tab-footer" class="tab-pane hidden flex flex-col gap-md">
            
            <!-- Persyaratan Berkas -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md md:p-lg shadow-sm">
                <h3 class="font-headline-sm text-headline-sm text-primary mb-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">fact_check</span> Persyaratan Kelengkapan Berkas (Calon Siswa)
                </h3>
                <p class="text-caption text-on-surface-variant mb-6">Ubah instruksi dokumen pendaftaran yang wajib diunggah oleh calon siswa baru.</p>

                <div class="flex flex-col gap-6">
                    <!-- Persyaratan 1 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-sm p-4 rounded-xl border border-outline-variant bg-surface/40">
                        <div class="flex flex-col gap-1 md:col-span-2">
                            <label for="req1_title" class="text-caption font-semibold text-on-surface-variant">Persyaratan 1</label>
                            <input type="text" name="req1_title" id="req1_title" value="<?= esc($settings['req1_title']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label for="req1_icon" class="text-caption font-semibold text-on-surface-variant">Ikon</label>
                            <input type="text" name="req1_icon" id="req1_icon" value="<?= esc($settings['req1_icon']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                        </div>
                        <div class="flex flex-col gap-1 md:col-span-3">
                            <label for="req1_desc" class="text-caption font-semibold text-on-surface-variant">Detail Deskripsi Persyaratan</label>
                            <input type="text" name="req1_desc" id="req1_desc" value="<?= esc($settings['req1_desc']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                        </div>
                    </div>

                    <!-- Persyaratan 2 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-sm p-4 rounded-xl border border-outline-variant bg-surface/40">
                        <div class="flex flex-col gap-1 md:col-span-2">
                            <label for="req2_title" class="text-caption font-semibold text-on-surface-variant">Persyaratan 2</label>
                            <input type="text" name="req2_title" id="req2_title" value="<?= esc($settings['req2_title']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label for="req2_icon" class="text-caption font-semibold text-on-surface-variant">Ikon</label>
                            <input type="text" name="req2_icon" id="req2_icon" value="<?= esc($settings['req2_icon']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                        </div>
                        <div class="flex flex-col gap-1 md:col-span-3">
                            <label for="req2_desc" class="text-caption font-semibold text-on-surface-variant">Detail Deskripsi Persyaratan</label>
                            <input type="text" name="req2_desc" id="req2_desc" value="<?= esc($settings['req2_desc']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                        </div>
                    </div>

                    <!-- Persyaratan 3 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-sm p-4 rounded-xl border border-outline-variant bg-surface/40">
                        <div class="flex flex-col gap-1 md:col-span-2">
                            <label for="req3_title" class="text-caption font-semibold text-on-surface-variant">Persyaratan 3</label>
                            <input type="text" name="req3_title" id="req3_title" value="<?= esc($settings['req3_title']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label for="req3_icon" class="text-caption font-semibold text-on-surface-variant">Ikon</label>
                            <input type="text" name="req3_icon" id="req3_icon" value="<?= esc($settings['req3_icon']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                        </div>
                        <div class="flex flex-col gap-1 md:col-span-3">
                            <label for="req3_desc" class="text-caption font-semibold text-on-surface-variant">Detail Deskripsi Persyaratan</label>
                            <input type="text" name="req3_desc" id="req3_desc" value="<?= esc($settings['req3_desc']) ?>" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-3 py-2 text-body-sm outline-none focus:border-primary">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Konfigurasi Footer & Informasi Kontak -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md md:p-lg shadow-sm">
                <h3 class="font-headline-sm text-headline-sm text-primary mb-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">contact_support</span> Informasi Kontak & Keterangan Footer
                </h3>
                <p class="text-caption text-on-surface-variant mb-6">Ubah isian detail telepon, email, alamat kantor panitia pendaftaran, serta slogan/deskripsi singkat sekolah di bagian kaki halaman depan (footer).</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <!-- Nomor Telepon Panitia -->
                    <div class="flex flex-col gap-2">
                        <label for="footer_phone" class="text-body-sm font-semibold text-on-surface">Nomor Telepon Panitia</label>
                        <input type="text" name="footer_phone" id="footer_phone" value="<?= esc($settings['footer_phone']) ?>"
                               class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" required>
                    </div>

                    <!-- Email Panitia -->
                    <div class="flex flex-col gap-2">
                        <label for="footer_email" class="text-body-sm font-semibold text-on-surface">Email Hubungan Masyarakat</label>
                        <input type="email" name="footer_email" id="footer_email" value="<?= esc($settings['footer_email']) ?>"
                               class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" required>
                    </div>

                    <!-- Alamat Fisik Kantor -->
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label for="footer_address" class="text-body-sm font-semibold text-on-surface">Alamat Fisik Sekolah / Sekretariat SPMB</label>
                        <input type="text" name="footer_address" id="footer_address" value="<?= esc($settings['footer_address']) ?>"
                               class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" required>
                    </div>

                    <!-- Slogan / Deskripsi Footer -->
                    <div class="flex flex-col gap-2 md:col-span-2">
                        <label for="footer_desc" class="text-body-sm font-semibold text-on-surface">Deskripsi / Slogan Singkat Sekolah di Footer</label>
                        <textarea name="footer_desc" id="footer_desc" rows="3" maxlength="500"
                                  class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" required><?= esc($settings['footer_desc']) ?></textarea>
                        <p class="text-caption text-on-surface-variant">Deskripsi singkat mengenai sekolah Anda yang akan ditampilkan tepat di bawah nama sekolah di bagian kiri footer.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==========================================
             SUB-TAB 5: PENGUMUMAN & WHATSAPP
             ========================================== -->
        <!-- ==========================================
             SUB-TAB 5: PENGUMUMAN & WHATSAPP
             ========================================== -->
        <div id="tab-pengumuman" class="tab-pane hidden flex flex-col gap-md">
            
            <!-- WhatsApp Group Link -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md md:p-lg shadow-sm">
                <h3 class="font-headline-sm text-headline-sm text-primary mb-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">chat</span> Grup WhatsApp Resmi
                </h3>
                <p class="text-caption text-on-surface-variant mb-6">Ubah tautan WhatsApp Group yang aktif bagi calon siswa baru di dashboard pendaftar.</p>

                <div class="flex flex-col gap-md">
                    <div class="flex flex-col gap-2">
                        <label for="whatsapp_link" class="text-body-sm font-semibold text-on-surface">Tautan WhatsApp Group Pendaftar</label>
                        <input type="url" name="whatsapp_link" id="whatsapp_link" value="<?= esc($settings['whatsapp_link'] ?? '') ?>"
                               class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                        <p class="text-caption text-on-surface-variant">Contoh: `https://chat.whatsapp.com/GrupSpmb` atau `https://wa.me/628...`</p>
                    </div>
                </div>
            </div>

            <!-- Announcement Management Console -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md md:p-lg shadow-sm">
                <div class="flex justify-between items-center mb-md border-b border-outline-variant/60 pb-sm">
                    <div>
                        <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">campaign</span> Pengumuman Pendaftaran Aktif
                        </h3>
                        <p class="text-caption text-on-surface-variant mt-0.5">Kelola seluruh perilisan pengumuman di dashboard calon siswa.</p>
                    </div>
                    <button type="button" onclick="openAnnouncementModal()" class="bg-primary text-on-primary font-semibold text-body-sm px-4 py-2 rounded-xl hover:opacity-90 transition-opacity flex items-center gap-1.5 shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span> Buat Pengumuman
                    </button>
                </div>

                <div class="flex flex-col gap-sm">
                    <?php if (!empty($announcements)): ?>
                        <?php foreach ($announcements as $ann): ?>
                        <div class="p-md rounded-xl border border-outline-variant bg-surface-container-low/50 hover:bg-surface-container-low transition-colors flex justify-between items-start gap-md">
                            <div class="space-y-1">
                                <h4 class="font-semibold text-body-sm text-primary flex items-center gap-xs">
                                    <span class="material-symbols-outlined text-base text-primary">campaign</span>
                                    <?= esc($ann['judul']) ?>
                                </h4>
                                <p class="text-caption text-on-surface-variant whitespace-pre-line leading-relaxed">
                                    <?= esc($ann['konten']) ?>
                                </p>
                                <span class="block text-[10px] text-outline pt-1 flex items-center gap-xs">
                                    <span class="material-symbols-outlined text-[12px]">schedule</span>
                                    <?= date('d M Y H:i', strtotime($ann['created_at'])) ?>
                                </span>
                            </div>
                            <button type="button" onclick="confirmDeleteAnnouncement(<?= $ann['id'] ?>)" class="text-error hover:bg-error-container p-2 rounded-lg transition-colors flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-10 text-on-surface-variant border border-dashed border-outline-variant rounded-xl bg-surface/30">
                            <span class="material-symbols-outlined text-4xl block mb-2 text-outline animate-[bounce_2s_infinite]">campaign</span>
                            Belum ada pengumuman penting yang dipublikasikan.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ==========================================
             SUBMIT TOOLBAR (Sticky at Bottom)
             ========================================== -->
        <div class="sticky bottom-6 mt-md p-4 rounded-2xl bg-surface border border-outline-variant/80 shadow-lg flex justify-between items-center backdrop-blur-md bg-opacity-95 z-10 animate-slide-up">
            <span class="text-caption text-on-surface-variant hidden sm:inline flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px] text-primary">info</span> Pastikan data yang dimasukkan sudah benar sebelum disimpan.
            </span>
            <div class="flex gap-3 ml-auto w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto bg-primary text-on-primary font-semibold text-body-sm px-6 py-3 rounded-xl hover:opacity-90 shadow-md transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<!-- ==========================================
     MODAL: TAMBAH PENGUMUMAN BARU
     ========================================== -->
<div id="modal-announcement" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-surface border border-outline-variant rounded-2xl w-[90%] max-w-[500px] shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300">
        <!-- Header -->
        <div class="px-md py-4 bg-primary text-on-primary flex justify-between items-center">
            <h3 class="font-headline-sm text-headline-sm text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-white">campaign</span> Buat Pengumuman Baru
            </h3>
            <button type="button" onclick="closeAnnouncementModal()" class="text-white/80 hover:text-white flex items-center justify-center">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <!-- Form -->
        <form action="<?= base_url('admin/settings/announcement/add') ?>" method="POST" class="p-md space-y-md">
            <?= csrf_field() ?>
            
            <div class="flex flex-col gap-1">
                <label for="judul" class="text-body-sm font-semibold text-on-surface">Judul Pengumuman</label>
                <input type="text" name="judul" id="judul" placeholder="Contoh: Jadwal Ujian Masuk Seleksi Akademik" 
                       class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" required>
            </div>
            
            <div class="flex flex-col gap-1">
                <label for="konten" class="text-body-sm font-semibold text-on-surface">Isi Pengumuman</label>
                <textarea name="konten" id="konten" rows="5" placeholder="Tuliskan pengumuman lengkap di sini..." 
                          class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" required></textarea>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-sm border-t border-outline-variant">
                <button type="button" onclick="closeAnnouncementModal()" class="px-5 py-2.5 rounded-xl border border-outline text-on-surface-variant hover:bg-surface-container-high font-semibold text-body-sm transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary hover:opacity-90 font-semibold text-body-sm shadow-md transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">publish</span> Publikasikan
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    /**
     * Switch Sub-Tab without reloading the page
     * @param {string} tabId Target tab ID to display
     */
    function switchSubTab(tabId) {
        // Hide all panes
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
        
        // Show target pane
        document.getElementById(tabId).classList.remove('hidden');
        
        // Deactivate all tab buttons styling
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active', 'border-primary', 'text-primary', 'bg-surface-container-high');
            btn.classList.add('text-on-surface-variant', 'border-transparent', 'hover:text-on-surface', 'hover:bg-surface-container');
        });
        
        // Activate target button styling
        const activeBtn = document.getElementById('btn-' + tabId);
        if (activeBtn) {
            activeBtn.classList.add('active', 'border-primary', 'text-primary');
            activeBtn.classList.remove('text-on-surface-variant', 'border-transparent', 'hover:text-on-surface', 'hover:bg-surface-container');
        }
    }

    /**
     * Preview uploaded images in the file browser
     */
    function previewImage(input, previewId, placeholderId) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(previewId);
                img.src = e.target.result;
                img.classList.remove('hidden');
                
                const placeholder = document.getElementById(placeholderId);
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            }
            reader.readAsDataURL(file);
        }
    }

    // =========================================================================
    // MODAL HANDLERS FOR ANNOUNCEMENTS
    // =========================================================================
    function openAnnouncementModal() {
        const modal = document.getElementById('modal-announcement');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('opacity-100');
            modal.querySelector('.bg-surface').classList.remove('scale-95');
            modal.querySelector('.bg-surface').classList.add('scale-100');
        }, 10);
    }

    function closeAnnouncementModal() {
        const modal = document.getElementById('modal-announcement');
        modal.classList.remove('opacity-100');
        modal.querySelector('.bg-surface').classList.add('scale-95');
        modal.querySelector('.bg-surface').classList.remove('scale-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            // Reset input values
            document.getElementById('judul').value = '';
            document.getElementById('konten').value = '';
        }, 300);
    }

    function confirmDeleteAnnouncement(id) {
        if (confirm('Apakah Anda yakin ingin menghapus pengumuman pendaftaran ini secara permanen? Calon siswa tidak akan melihat pengumuman ini lagi.')) {
            window.location.href = '<?= base_url('admin/settings/announcement/delete') ?>/' + id;
        }
    }

    // Set initial active tab styling & parse tab from URL
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab');
        if (activeTab && document.getElementById('tab-' + activeTab)) {
            switchSubTab('tab-' + activeTab);
        } else {
            const firstTabBtn = document.getElementById('btn-tab-branding');
            if (firstTabBtn) {
                firstTabBtn.classList.add('border-primary', 'text-primary');
            }
        }
    });
</script>
<?= $this->endSection() ?>
