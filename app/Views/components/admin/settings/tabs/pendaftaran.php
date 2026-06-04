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
     SUB-TAB 6: KOP SURAT & CETAK BUKTI
     ========================================== -->
<div id="tab-kop" class="tab-pane hidden flex flex-col gap-md">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md md:p-lg shadow-sm">
        <h3 class="font-headline-sm text-headline-sm text-primary mb-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">print</span> Pengaturan Kop Cetak Bukti Pendaftaran
        </h3>
        <p class="text-caption text-on-surface-variant mb-6">Ubah data kop surat dinamis yang akan dicetak pada lembar Bukti Pendaftaran PDF oleh calon siswa.</p>

        <div class="flex flex-col gap-md">
            <div class="flex flex-col gap-2">
                <label class="text-body-sm font-semibold text-on-surface">Kop Surat</label>
                <p class="text-caption text-on-surface-variant -mt-1">Format bebas dengan editor teks. Hasil akan muncul di semua halaman cetak (Kartu Bukti Pendaftaran, Arsip, Cetak Data).</p>
                <!-- Quill Editor -->
                <div id="quill-editor" class="bg-surface border border-outline-variant rounded-xl" style="min-height: 250px;"><?= old('kop_content', $settings['kop_content'] ?? '') ?></div>
                <textarea name="kop_content" id="kop-content-hidden" class="hidden"><?= old('kop_content', $settings['kop_content'] ?? '') ?></textarea>
            </div>

            <!-- Nama Ketua Panitia -->
            <div class="flex flex-col gap-2">
                <label for="ketua_panitia" class="text-body-sm font-semibold text-on-surface">Nama Ketua Panitia</label>
                <input type="text" name="ketua_panitia" id="ketua_panitia" value="<?= esc($settings['ketua_panitia'] ?? '') ?>"
                       placeholder="Contoh: Drs. H. Ahmad Fauzi, M.Pd."
                       class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                <p class="text-caption text-on-surface-variant">Nama ini akan muncul di lembar tanda tangan pada Kartu Bukti Pendaftaran dan Cetak Arsip.</p>
            </div>

            <!-- Kota Tanda Tangan -->
            <div class="flex flex-col gap-2">
                <label for="kota_ttd" class="text-body-sm font-semibold text-on-surface">Kota Tanda Tangan</label>
                <input type="text" name="kota_ttd" id="kota_ttd" value="<?= esc($settings['kota_ttd'] ?? 'Jakarta') ?>"
                       placeholder="Contoh: Jakarta"
                       class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                <p class="text-caption text-on-surface-variant">Nama kota/kabupaten yang akan muncul di lembar tanda tangan cetak (contoh: Jakarta, Lamongan, Surabaya).</p>
            </div>
        </div>
    </div>
</div>
