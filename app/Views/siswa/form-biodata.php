<?= $this->extend('layouts/siswa') ?>

<?= $this->section('title') ?>
Biodata Diri (Langkah 1 dari 4)
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('components/siswa/form-stepper') ?>

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
                           value="<?= esc(old('nik', !str_contains($biodata['nik'] ?? '', REG_PREFIX) ? ($biodata['nik'] ?? '') : '')) ?>" title="Must be exactly 16 digits" type="text"/>
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
                    <label class="font-label-bold text-body-sm text-on-surface" for="no_hp">No. Handphone <span class="text-error">*</span></label>
                    <input class="w-full px-sm py-3 border <?= isset($errors['no_hp']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                           id="no_hp" name="no_hp" pattern="\d{10,15}" placeholder="08xxxxxxxxx" required 
                           value="<?= esc(old('no_hp', $biodata['no_hp'] ?? '')) ?>" title="Masukkan 10-15 digit nomor HP" type="text"/>
                    <?php if (isset($errors['no_hp'])): ?>
                        <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['no_hp']) ?></span>
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
                    <label class="font-label-bold text-body-sm text-on-surface" for="alamat_desa">Desa <span class="text-error">*</span></label>
                    <input class="w-full px-sm py-3 border <?= isset($errors['alamat_desa']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant mb-sm focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                           id="alamat_desa" name="alamat_desa" placeholder="Nama Desa" required 
                           value="<?= esc(old('alamat_desa', $biodata['alamat_desa'] ?? '')) ?>" type="text"/>
                    <?php if (isset($errors['alamat_desa'])): ?>
                        <span class="text-xs text-error -mt-2 mb-sm font-semibold"><?= esc($errors['alamat_desa']) ?></span>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-bold text-body-sm text-on-surface" for="alamat_kecamatan">Kecamatan <span class="text-error">*</span></label>
                        <input class="w-full px-sm py-3 border <?= isset($errors['alamat_kecamatan']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                               id="alamat_kecamatan" name="alamat_kecamatan" placeholder="Nama kecamatan" required 
                               value="<?= esc(old('alamat_kecamatan', $biodata['alamat_kecamatan'] ?? '')) ?>" type="text"/>
                        <?php if (isset($errors['alamat_kecamatan'])): ?>
                            <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['alamat_kecamatan']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="flex flex-col gap-xs">
                        <label class="font-label-bold text-body-sm text-on-surface" for="alamat_kabupaten">Kabupaten / Kota <span class="text-error">*</span></label>
                        <input class="w-full px-sm py-3 border <?= isset($errors['alamat_kabupaten']) ? 'border-error' : 'border-outline' ?> rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                               id="alamat_kabupaten" name="alamat_kabupaten" placeholder="Nama kabupaten atau kota" required 
                               value="<?= esc(old('alamat_kabupaten', $biodata['alamat_kabupaten'] ?? '')) ?>" type="text"/>
                        <?php if (isset($errors['alamat_kabupaten'])): ?>
                            <span class="text-xs text-error mt-xs font-semibold"><?= esc($errors['alamat_kabupaten']) ?></span>
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
<?= script_tag('js/siswa/form-biodata.js') ?>
<?= $this->endSection() ?>
