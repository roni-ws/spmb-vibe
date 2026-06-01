<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>
Biodata Diri (Langkah 1 dari 4)
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Stepper Visual -->
<div class="mb-lg bg-surface border border-outline-variant rounded-2xl p-sm md:p-md shadow-sm">
    <div class="flex flex-col md:flex-row justify-between items-center gap-md">
        <!-- Step 1: Active -->
        <div class="flex items-center gap-xs w-full md:w-auto">
            <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm shrink-0 shadow-md">1</div>
            <div>
                <span class="block text-sm font-bold text-primary leading-tight">Biodata Diri</span>
                <span class="block text-[11px] text-primary">Sedang Diisi</span>
            </div>
        </div>
        <div class="hidden md:block flex-grow h-px bg-outline-variant mx-xs"></div>
        <!-- Step 2: Pending -->
        <div class="flex items-center gap-xs w-full md:w-auto opacity-50">
            <div class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant text-on-surface-variant flex items-center justify-center font-bold text-sm shrink-0">2</div>
            <div>
                <span class="block text-sm font-bold text-on-surface-variant leading-tight">Asal Sekolah</span>
                <span class="block text-[11px] text-outline">Belum Diisi</span>
            </div>
        </div>
        <div class="hidden md:block flex-grow h-px bg-outline-variant mx-xs"></div>
        <!-- Step 3: Pending -->
        <div class="flex items-center gap-xs w-full md:w-auto opacity-50">
            <div class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant text-on-surface-variant flex items-center justify-center font-bold text-sm shrink-0">3</div>
            <div>
                <span class="block text-sm font-bold text-on-surface-variant leading-tight">Data Orang Tua</span>
                <span class="block text-[11px] text-outline">Belum Diisi</span>
            </div>
        </div>
        <div class="hidden md:block flex-grow h-px bg-outline-variant mx-xs"></div>
        <!-- Step 4: Pending -->
        <div class="flex items-center gap-xs w-full md:w-auto opacity-50">
            <div class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant text-on-surface-variant flex items-center justify-center font-bold text-sm shrink-0">4</div>
            <div>
                <span class="block text-sm font-bold text-on-surface-variant leading-tight">Berkas Persyaratan</span>
                <span class="block text-[11px] text-outline">Belum Diunggah</span>
            </div>
        </div>
    </div>
    <!-- Mobile Progress Bar -->
    <div class="mt-sm md:hidden">
        <div class="h-1.5 w-full bg-surface-container-high rounded-full overflow-hidden">
            <div class="h-full bg-primary rounded-full" style="width: 25%;"></div>
        </div>
    </div>
</div>

<!-- Main Form Section -->
<div class="max-w-3xl mx-auto">
    <div class="mb-md">
        <h2 class="font-headline-sm text-headline-sm text-primary mb-xs">Lengkapi Biodata Diri Anda</h2>
        <p class="font-body-sm text-body-sm text-on-surface-variant">Harap masukkan data pribadi resmi Anda sesuai dengan dokumen Kartu Keluarga dan KTP/KIA.</p>
    </div>

    <!-- Card Formulir -->
    <div class="bg-surface border border-outline-variant rounded-2xl shadow-sm p-md md:p-lg mb-md relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-tertiary-fixed-dim"></div>
        
        <form action="<?= base_url('siswa/biodata') ?>" method="POST" class="space-y-md">
            <?= csrf_field() ?>

            <!-- NIK & Nama Lengkap -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div class="flex flex-col gap-xs">
                    <label class="font-label-bold text-body-sm text-on-surface" for="nik">Nomor Induk Kependudukan (NIK) <span class="text-error">*</span></label>
                    <input class="w-full px-sm py-3 border <?= isset($errors['nik']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                           id="nik" name="nik" pattern="\d{16}" placeholder="NIK 16 digit sesuai KK" required 
                           value="<?= esc(old('nik', !str_contains($biodata['nik'] ?? '', 'REG-') ? ($biodata['nik'] ?? '') : '')) ?>" title="Must be exactly 16 digits" type="text"/>
                    <?php if (isset($errors['nik'])): ?>
                        <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['nik']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col gap-xs">
                    <label class="font-label-bold text-body-sm text-on-surface" for="nama_lengkap">Nama Lengkap <span class="text-error">*</span></label>
                    <input class="w-full px-sm py-3 border <?= isset($errors['nama_lengkap']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                           id="nama_lengkap" name="nama_lengkap" placeholder="Nama lengkap sesuai dokumen resmi" required 
                           value="<?= esc(old('nama_lengkap', $biodata['nama_lengkap'] ?? '')) ?>" type="text"/>
                    <?php if (isset($errors['nama_lengkap'])): ?>
                        <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['nama_lengkap']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Nomor Kartu Keluarga (KK) -->
            <div class="flex flex-col gap-xs">
                <label class="font-label-bold text-body-sm text-on-surface" for="no_kk">Nomor Kartu Keluarga (KK) <span class="text-error">*</span></label>
                <input class="w-full px-sm py-3 border <?= isset($errors['no_kk']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                       id="no_kk" name="no_kk" pattern="\d{16}" placeholder="Nomor KK 16 digit sesuai KK resmi" required 
                       value="<?= esc(old('no_kk', $biodata['no_kk'] ?? '')) ?>" title="Must be exactly 16 digits" type="text"/>
                <?php if (isset($errors['no_kk'])): ?>
                    <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['no_kk']) ?></span>
                <?php endif; ?>
            </div>

            <!-- Tempat & Tanggal Lahir -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div class="flex flex-col gap-xs">
                    <label class="font-label-bold text-body-sm text-on-surface" for="tempat_lahir">Tempat Lahir <span class="text-error">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline-variant pointer-events-none" style="font-size: 20px;">location_on</span>
                        <input class="w-full pl-10 pr-sm py-3 border <?= isset($errors['tempat_lahir']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                               id="tempat_lahir" name="tempat_lahir" placeholder="Kota atau Kabupaten Lahir" required 
                               value="<?= esc(old('tempat_lahir', $biodata['tempat_lahir'] ?? '')) ?>" type="text"/>
                    </div>
                    <?php if (isset($errors['tempat_lahir'])): ?>
                        <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['tempat_lahir']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col gap-xs">
                    <label class="font-label-bold text-body-sm text-on-surface" for="tanggal_lahir">Tanggal Lahir <span class="text-error">*</span></label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline-variant pointer-events-none" style="font-size: 20px;">calendar_today</span>
                        <input class="w-full pl-10 pr-sm py-3 border <?= isset($errors['tanggal_lahir']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface text-on-surface-variant focus:text-on-surface focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                               id="tanggal_lahir" name="tanggal_lahir" required 
                               value="<?= esc(old('tanggal_lahir', $biodata['tanggal_lahir'] ?? '')) ?>" type="date"/>
                    </div>
                    <?php if (isset($errors['tanggal_lahir'])): ?>
                        <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['tanggal_lahir']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Jenis Kelamin -->
            <div class="flex flex-col gap-xs">
                <label class="font-label-bold text-body-sm text-on-surface">Jenis Kelamin <span class="text-error">*</span></label>
                <div class="flex flex-col sm:flex-row gap-sm">
                    <?php 
                        $jk = old('jenis_kelamin', $biodata['jenis_kelamin'] ?? 'L');
                    ?>
                    <label class="relative flex-1 cursor-pointer group">
                        <input class="peer sr-only" name="jenis_kelamin" required type="radio" value="L" <?= $jk === 'L' ? 'checked' : '' ?>/>
                        <div class="w-full px-md py-3 border border-outline rounded-lg flex items-center justify-center gap-sm bg-surface transition-all peer-checked:border-primary peer-checked:bg-surface-container-low peer-checked:shadow-[0_0_0_1px_#002045] group-hover:border-primary">
                            <span class="material-symbols-outlined text-outline-variant peer-checked:text-primary transition-colors">male</span>
                            <span class="font-label-bold text-body-sm text-on-surface-variant peer-checked:text-primary transition-colors">Laki-laki</span>
                        </div>
                    </label>
                    <label class="relative flex-1 cursor-pointer group">
                        <input class="peer sr-only" name="jenis_kelamin" required type="radio" value="P" <?= $jk === 'P' ? 'checked' : '' ?>/>
                        <div class="w-full px-md py-3 border border-outline rounded-lg flex items-center justify-center gap-sm bg-surface transition-all peer-checked:border-primary peer-checked:bg-surface-container-low peer-checked:shadow-[0_0_0_1px_#002045] group-hover:border-primary">
                            <span class="material-symbols-outlined text-outline-variant peer-checked:text-primary transition-colors">female</span>
                            <span class="font-label-bold text-body-sm text-on-surface-variant peer-checked:text-primary transition-colors">Perempuan</span>
                        </div>
                    </label>
                </div>
                <?php if (isset($errors['jenis_kelamin'])): ?>
                    <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['jenis_kelamin']) ?></span>
                <?php endif; ?>
            </div>

            <!-- Status Keluarga (Anak Ke & Jumlah Saudara) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div class="flex flex-col gap-xs">
                    <label class="font-label-bold text-body-sm text-on-surface" for="anak_ke">Anak Ke- <span class="text-error">*</span></label>
                    <input class="w-full px-sm py-3 border <?= isset($errors['anak_ke']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                           id="anak_ke" name="anak_ke" min="1" placeholder="Contoh: 1" required 
                           value="<?= esc(old('anak_ke', $biodata['anak_ke'] ?? '')) ?>" type="number"/>
                    <?php if (isset($errors['anak_ke'])): ?>
                        <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['anak_ke']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col gap-xs">
                    <label class="font-label-bold text-body-sm text-on-surface" for="jumlah_saudara">Jumlah Saudara Kandung <span class="text-error">*</span></label>
                    <input class="w-full px-sm py-3 border <?= isset($errors['jumlah_saudara']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                           id="jumlah_saudara" name="jumlah_saudara" min="0" placeholder="Jumlah saudara kandung (tulis 0 jika tunggal)" required 
                           value="<?= esc(old('jumlah_saudara', $biodata['jumlah_saudara'] ?? '')) ?>" type="number"/>
                    <?php if (isset($errors['jumlah_saudara'])): ?>
                        <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['jumlah_saudara']) ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Alamat Lengkap Section -->
            <div class="flex flex-col gap-xs pt-sm border-t border-surface-variant">
                <h3 class="font-label-bold text-body-md text-primary mb-xs">Alamat Domisili Saat Ini</h3>
                
                <div class="flex flex-col gap-xs">
                    <label class="font-label-bold text-body-sm text-on-surface" for="alamat_desa">Alamat Jalan / RT / RW / Desa <span class="text-error">*</span></label>
                    <input class="w-full px-sm py-3 border <?= isset($errors['alamat_desa']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant mb-sm focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                           id="alamat_desa" name="alamat_desa" placeholder="Nama jalan, nomor rumah, RT/RW, Dusun/Desa" required 
                           value="<?= esc(old('alamat_desa', $biodata['alamat_desa'] ?? '')) ?>" type="text"/>
                    <?php if (isset($errors['alamat_desa'])): ?>
                        <span class="text-xs text-error -mt-2 mb-sm font-semibold"><?= esc($errors['alamat_desa']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-bold text-body-sm text-on-surface" for="alamat_kabupaten">Kota / Kabupaten <span class="text-error">*</span></label>
                        <input class="w-full px-sm py-3 border <?= isset($errors['alamat_kabupaten']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                               id="alamat_kabupaten" name="alamat_kabupaten" placeholder="Kota atau Kabupaten" required 
                               value="<?= esc(old('alamat_kabupaten', $biodata['alamat_kabupaten'] ?? '')) ?>" type="text"/>
                        <?php if (isset($errors['alamat_kabupaten'])): ?>
                            <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['alamat_kabupaten']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="flex flex-col gap-xs">
                        <label class="font-label-bold text-body-sm text-on-surface" for="alamat_kecamatan">Provinsi <span class="text-error">*</span></label>
                        <input class="w-full px-sm py-3 border <?= isset($errors['alamat_kecamatan']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                               id="alamat_kecamatan" name="alamat_kecamatan" placeholder="Provinsi" required 
                               value="<?= esc(old('alamat_kecamatan', $biodata['alamat_kecamatan'] ?? '')) ?>" type="text"/>
                        <?php if (isset($errors['alamat_kecamatan'])): ?>
                            <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['alamat_kecamatan']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-md border-t border-outline-variant pt-md mt-lg">
                <a href="<?= base_url('siswa/dashboard') ?>" class="w-full sm:w-auto rounded-lg border border-outline text-on-surface-variant hover:bg-surface-container-high transition-colors flex items-center justify-center gap-xs font-label-bold text-body-sm text-center"
                   style="padding: 12px 36px !important; min-width: 160px;">
                    Batal
                </a>
                <button class="w-full sm:w-auto rounded-lg bg-primary text-on-primary font-label-bold text-body-sm hover:bg-primary-container transition-colors shadow-sm hover:shadow-md flex items-center justify-center gap-xs group" 
                        style="padding: 12px 36px !important; min-width: 160px; cursor: pointer;"
                        type="submit">
                    Simpan & Lanjutkan
                    <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
