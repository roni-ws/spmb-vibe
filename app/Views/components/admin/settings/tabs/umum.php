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

    <!-- Google reCAPTCHA -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md md:p-lg shadow-sm">
        <h3 class="font-headline-sm text-headline-sm text-primary mb-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">security</span> Google reCAPTCHA
        </h3>
        <p class="text-caption text-on-surface-variant mb-6">Masukkan Site Key dan Secret Key dari Google reCAPTCHA v2 (Checkbox) untuk melindungi halaman Login, Registrasi, Lupa Kata Sandi, dan Atur Ulang Kata Sandi dari serangan bot.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
            <div class="flex flex-col gap-2">
                <label for="recaptcha_site_key" class="text-body-sm font-semibold text-on-surface">Site Key</label>
                <input type="text" name="recaptcha_site_key" id="recaptcha_site_key" value="<?= esc($settings['recaptcha_site_key'] ?? '') ?>"
                       class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                <p class="text-caption text-on-surface-variant">Dapatkan dari <a href="https://www.google.com/recaptcha/admin" target="_blank" class="text-primary hover:underline">Google reCAPTCHA Admin</a></p>
            </div>
            <div class="flex flex-col gap-2">
                <label for="recaptcha_secret_key" class="text-body-sm font-semibold text-on-surface">Secret Key</label>
                <input type="password" name="recaptcha_secret_key" id="recaptcha_secret_key" value="<?= esc($settings['recaptcha_secret_key'] ?? '') ?>"
                       class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
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
