<!-- ================================
     ADD STUDENT MODAL
     ================================ -->
<div id="add-modal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 p-4"
     style="display: none; align-items: center; justify-content: center; z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full animate-in fade-in zoom-in-95 duration-200"
         style="margin: auto; max-height: 90vh; display: flex; flex-direction: column; width: 100%; max-width: 720px;">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">person_add</span>
                </div>
                <div>
                    <h3 class="font-semibold text-body-md text-on-surface">Tambah Calon Siswa</h3>
                    <p class="text-caption text-on-surface-variant">Pendaftaran bertahap (Langkah <span id="add-step-indicator-text">1</span> dari 4)</p>
                </div>
            </div>
            <button type="button" onclick="closeAddModal()" class="p-1.5 rounded-full hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-on-surface-variant">close</span>
            </button>
        </div>

        <!-- Stepper UI -->
        <div class="px-6 pt-4 shrink-0">
            <div class="flex items-center justify-between relative">
                <!-- Line background -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-surface-container-low rounded-full -z-10"></div>
                <!-- Line active progress -->
                <div id="add-stepper-progress" class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-primary rounded-full transition-all duration-300 -z-10" style="width: 0%;"></div>
                
                <!-- Step 1 -->
                <div class="flex flex-col items-center gap-1 bg-white px-2">
                    <div id="add-step-circle-1" class="w-8 h-8 rounded-full flex items-center justify-center text-caption font-bold bg-primary text-on-primary border-2 border-primary transition-colors">1</div>
                    <span class="text-[10px] font-semibold text-primary">Biodata Diri</span>
                </div>
                <!-- Step 2 -->
                <div class="flex flex-col items-center gap-1 bg-white px-2">
                    <div id="add-step-circle-2" class="w-8 h-8 rounded-full flex items-center justify-center text-caption font-bold bg-surface-container-high text-on-surface-variant border-2 border-outline-variant transition-colors">2</div>
                    <span class="text-[10px] font-medium text-on-surface-variant">Asal Sekolah</span>
                </div>
                <!-- Step 3 -->
                <div class="flex flex-col items-center gap-1 bg-white px-2">
                    <div id="add-step-circle-3" class="w-8 h-8 rounded-full flex items-center justify-center text-caption font-bold bg-surface-container-high text-on-surface-variant border-2 border-outline-variant transition-colors">3</div>
                    <span class="text-[10px] font-medium text-on-surface-variant">Data Orang Tua</span>
                </div>
                <!-- Step 4 -->
                <div class="flex flex-col items-center gap-1 bg-white px-2">
                    <div id="add-step-circle-4" class="w-8 h-8 rounded-full flex items-center justify-center text-caption font-bold bg-surface-container-high text-on-surface-variant border-2 border-outline-variant transition-colors">4</div>
                    <span class="text-[10px] font-medium text-on-surface-variant">Berkas</span>
                </div>
            </div>
        </div>

        <!-- Modal Body -->
        <form id="add-form" method="post" action="<?= base_url('admin/add-student') ?>" enctype="multipart/form-data" style="display: flex; flex-direction: column; overflow: hidden; margin: 0;">
            <?= csrf_field() ?>
            <div class="px-6 py-5 overflow-y-auto custom-scrollbar" style="flex: 1 1 auto; max-height: 55vh;">
                <!-- ================================
                     STEP 1: BIODATA DIRI & ALAMAT
                     ================================ -->
                <div id="add-step-content-1" class="space-y-md">
                    <!-- ======== BIODATA DIRI ======== -->
                    <div class="space-y-md">
                        <h4 class="font-label-bold text-body-md text-primary pb-xs border-b border-surface-variant/30 flex items-center gap-xs">
                            <span class="material-symbols-outlined">person</span> Biodata Diri
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                            <!-- NISN -->
                            <div class="flex flex-col gap-xs">
                                <label class="font-label-bold text-body-sm text-on-surface" for="add-nisn">Nomor Induk Siswa Nasional (NISN) <span class="text-error">*</span></label>
                                <input class="w-full px-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                       id="add-nisn" name="nisn" pattern="\d{10}" placeholder="NISN 10 digit resmi" required type="text"/>
                            </div>

                            <!-- NIK -->
                            <div class="flex flex-col gap-xs">
                                <label class="font-label-bold text-body-sm text-on-surface" for="add-nik">Nomor Induk Kependudukan (NIK) <span class="text-error">*</span></label>
                                <input class="w-full px-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                       id="add-nik" name="nik" pattern="\d{16}" placeholder="NIK 16 digit sesuai KK" required type="text"/>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                            <!-- Nama Lengkap -->
                            <div class="flex flex-col gap-xs">
                                <label class="font-label-bold text-body-sm text-on-surface" for="add-nama">Nama Lengkap <span class="text-error">*</span></label>
                                <input class="w-full px-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                       id="add-nama" name="nama_lengkap" placeholder="Nama lengkap sesuai dokumen resmi" required type="text"/>
                            </div>

                            <!-- No. KK -->
                            <div class="flex flex-col gap-xs">
                                <label class="font-label-bold text-body-sm text-on-surface" for="add-no_kk">Nomor Kartu Keluarga (KK) <span class="text-error">*</span></label>
                                <input class="w-full px-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                       id="add-no_kk" name="no_kk" pattern="\d{16}" placeholder="Nomor KK 16 digit sesuai KK resmi" required type="text"/>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                            <!-- Tempat Lahir -->
                            <div class="flex flex-col gap-xs">
                                <label class="font-label-bold text-body-sm text-on-surface" for="add-tempat_lahir">Tempat Lahir <span class="text-error">*</span></label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline-variant pointer-events-none" style="font-size: 20px;">location_on</span>
                                    <input class="w-full pl-10 pr-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                           id="add-tempat_lahir" name="tempat_lahir" placeholder="Kota atau Kabupaten Lahir" required type="text"/>
                                </div>
                            </div>

                            <!-- No. HP -->
                            <div class="flex flex-col gap-xs">
                                <label class="font-label-bold text-body-sm text-on-surface" for="add-nohp">No. Handphone <span class="text-error">*</span></label>
                                <input class="w-full px-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                       id="add-nohp" name="no_hp" pattern="\d{10,15}" placeholder="08xxxxxxxxx" required type="text"/>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                            <!-- Tanggal Lahir -->
                            <div class="flex flex-col gap-xs">
                                <label class="font-label-bold text-body-sm text-on-surface" for="add-tanggal_lahir">Tanggal Lahir <span class="text-error">*</span></label>
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline-variant pointer-events-none" style="font-size: 20px;">calendar_today</span>
                                    <input class="w-full pl-10 pr-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface text-on-surface-variant focus:text-on-surface focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                           id="add-tanggal_lahir" name="tanggal_lahir" required type="date"/>
                                </div>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="flex flex-col gap-xs">
                                <label class="font-label-bold text-body-sm text-on-surface">Jenis Kelamin <span class="text-error">*</span></label>
                                <div class="flex flex-col sm:flex-row gap-sm">
                                    <label class="relative flex-1 cursor-pointer group">
                                        <input class="peer sr-only" name="jenis_kelamin" required type="radio" value="L" checked />
                                        <div class="w-full px-md py-3 border border-outline rounded-lg flex items-center justify-center gap-sm bg-surface transition-all peer-checked:border-primary peer-checked:bg-surface-container-low peer-checked:shadow-[0_0_0_1px_#002045] group-hover:border-primary">
                                            <span class="material-symbols-outlined text-outline-variant peer-checked:text-primary transition-colors">male</span>
                                            <span class="font-label-bold text-body-sm text-on-surface-variant peer-checked:text-primary transition-colors">Laki-laki</span>
                                        </div>
                                    </label>
                                    <label class="relative flex-1 cursor-pointer group">
                                        <input class="peer sr-only" name="jenis_kelamin" required type="radio" value="P" />
                                        <div class="w-full px-md py-3 border border-outline rounded-lg flex items-center justify-center gap-sm bg-surface transition-all peer-checked:border-primary peer-checked:bg-surface-container-low peer-checked:shadow-[0_0_0_1px_#002045] group-hover:border-primary">
                                            <span class="material-symbols-outlined text-outline-variant peer-checked:text-primary transition-colors">female</span>
                                            <span class="font-label-bold text-body-sm text-on-surface-variant peer-checked:text-primary transition-colors">Perempuan</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                            <!-- Anak Ke -->
                            <div class="flex flex-col gap-xs">
                                <label class="font-label-bold text-body-sm text-on-surface" for="add-anak_ke">Anak Ke- <span class="text-error">*</span></label>
                                <input class="w-full px-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                       id="add-anak_ke" name="anak_ke" min="1" placeholder="Contoh: 1" required type="number"/>
                            </div>

                            <!-- Jumlah Saudara -->
                            <div class="flex flex-col gap-xs">
                                <label class="font-label-bold text-body-sm text-on-surface" for="add-jumlah_saudara">Jumlah Saudara Kandung <span class="text-error">*</span></label>
                                <input class="w-full px-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                       id="add-jumlah_saudara" name="jumlah_saudara" min="0" placeholder="Jumlah saudara kandung (tulis 0 jika tunggal)" required type="number"/>
                            </div>
                        </div>
                    </div>

                    <!-- ======== ALAMAT DOMISILI ======== -->
                    <div class="flex flex-col gap-xs pt-sm border-t border-surface-variant/30">
                        <h4 class="font-label-bold text-body-md text-primary mb-xs flex items-center gap-xs">
                            <span class="material-symbols-outlined">home</span> Alamat Domisili Saat Ini
                        </h4>
                        
                        <div class="flex flex-col gap-xs">
                            <label class="font-label-bold text-body-sm text-on-surface" for="add-alamat_desa">Desa <span class="text-error">*</span></label>
                            <input class="w-full px-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                   id="add-alamat_desa" name="alamat_desa" placeholder="Nama Desa" required type="text"/>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md mt-sm">
                            <div class="flex flex-col gap-xs">
                                <label class="font-label-bold text-body-sm text-on-surface" for="add-alamat_kecamatan">Kecamatan <span class="text-error">*</span></label>
                                <input class="w-full px-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                       id="add-alamat_kecamatan" name="alamat_kecamatan" placeholder="Nama kecamatan" required type="text"/>
                            </div>

                            <div class="flex flex-col gap-xs">
                                <label class="font-label-bold text-body-sm text-on-surface" for="add-alamat_kabupaten">Kabupaten / Kota <span class="text-error">*</span></label>
                                <input class="w-full px-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                       id="add-alamat_kabupaten" name="alamat_kabupaten" placeholder="Nama kabupaten atau kota" required type="text"/>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================================
                     STEP 2: ASAL SEKOLAH
                     ================================ -->
                <div id="add-step-content-2" class="space-y-md hidden">
                    <div class="space-y-md">
                        <h4 class="font-label-bold text-body-md text-primary pb-xs border-b border-surface-variant/30 flex items-center gap-xs">
                            <span class="material-symbols-outlined">school</span> Informasi Sekolah Asal
                        </h4>
                        
                        <!-- Nama Sekolah Asal -->
                        <div class="flex flex-col gap-xs">
                            <label class="font-label-bold text-body-sm text-on-surface" for="add-sekolah">Nama Sekolah Asal <span class="text-error">*</span></label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline-variant pointer-events-none" style="font-size: 20px;">school</span>
                                <input class="w-full pl-10 pr-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                       id="add-sekolah" name="nama_sekolah" placeholder="Contoh: SMP Negeri 1 Jakarta" required type="text"/>
                            </div>
                        </div>

                        <!-- Alamat Sekolah Asal -->
                        <div class="flex flex-col gap-xs">
                            <label class="font-label-bold text-body-sm text-on-surface" for="add-alamat_sekolah">Alamat Lengkap Sekolah Asal <span class="text-error">*</span></label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-sm top-4 text-outline-variant pointer-events-none" style="font-size: 20px;">location_on</span>
                                <textarea class="w-full pl-10 pr-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all min-h-[100px]" 
                                          id="add-alamat_sekolah" name="alamat_sekolah" placeholder="Nama jalan, kota/kabupaten, dan provinsi sekolah asal" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================================
                     STEP 3: DATA ORANG TUA
                     ================================ -->
                <div id="add-step-content-3" class="space-y-md hidden">
                    <!-- Data Ayah -->
                    <div class="pb-sm border-b border-surface-variant/30 space-y-md">
                        <h4 class="font-label-bold text-body-md text-primary flex items-center gap-xs">
                            <span class="material-symbols-outlined">person</span> Data Ayah Kandung
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                            <div class="flex flex-col gap-xs">
                                <label class="font-label-bold text-body-sm text-on-surface" for="add-nama_ayah">Nama Lengkap Ayah <span class="text-error">*</span></label>
                                <input class="w-full px-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                       id="add-nama_ayah" name="nama_ayah" placeholder="Sesuai Akta / KK" required type="text"/>
                            </div>

                            <div class="flex flex-col gap-xs">
                                <label class="font-label-bold text-body-sm text-on-surface" for="add-pekerjaan_ayah">Pekerjaan Ayah <span class="text-error">*</span></label>
                                <input class="w-full px-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                       id="add-pekerjaan_ayah" name="pekerjaan_ayah" placeholder="Contoh: PNS, Karyawan Swasta, Wiraswasta" required type="text"/>
                            </div>
                        </div>
                    </div>

                    <!-- Data Ibu -->
                    <div class="space-y-md pt-sm">
                        <h4 class="font-label-bold text-body-md text-primary flex items-center gap-xs">
                            <span class="material-symbols-outlined">person</span> Data Ibu Kandung
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                            <div class="flex flex-col gap-xs">
                                <label class="font-label-bold text-body-sm text-on-surface" for="add-nama_ibu">Nama Lengkap Ibu <span class="text-error">*</span></label>
                                <input class="w-full px-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                       id="add-nama_ibu" name="nama_ibu" placeholder="Sesuai Akta / KK" required type="text"/>
                            </div>

                            <div class="flex flex-col gap-xs">
                                <label class="font-label-bold text-body-sm text-on-surface" for="add-pekerjaan_ibu">Pekerjaan Ibu <span class="text-error">*</span></label>
                                <input class="w-full px-sm py-3 border border-outline rounded-lg bg-surface font-body-md text-body-md text-on-surface placeholder:text-outline-variant focus:border-primary focus:ring-2 focus:ring-tertiary-fixed-dim/30 transition-all" 
                                       id="add-pekerjaan_ibu" name="pekerjaan_ibu" placeholder="Contoh: Ibu Rumah Tangga, PNS, Guru" required type="text"/>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================================
                     STEP 4: BERKAS PERSYARATAN
                     ================================ -->
                <div id="add-step-content-4" class="space-y-md hidden">
                    <div class="space-y-md">
                        <h4 class="font-label-bold text-body-md text-primary pb-xs border-b border-surface-variant/30 flex items-center gap-xs">
                            <span class="material-symbols-outlined">folder</span> Unggah Berkas Administrasi (Opsional)
                        </h4>
                        
                        <div class="space-y-md">
                            <!-- Unggah Ijazah / SKL -->
                            <div class="flex flex-col gap-sm p-sm rounded-xl bg-surface-container-low border border-outline-variant/40">
                                <div class="flex justify-between items-start flex-wrap gap-xs">
                                    <div>
                                        <h3 class="font-label-bold text-body-sm text-on-surface">Scan Ijazah / Surat Keterangan Lulus (SKL)</h3>
                                        <p class="text-caption text-on-surface-variant">Pastikan tulisan terbaca jelas. Format: PDF/JPG/PNG max 2MB.</p>
                                    </div>
                                    <div class="inline-flex items-center gap-xs px-3 py-1 bg-amber-100 border border-amber-300 text-amber-800 rounded-full font-label-bold text-[11px] shadow-sm select-none">
                                        <span class="material-symbols-outlined text-[16px] text-amber-600">pending</span> Opsional
                                    </div>
                                </div>

                                <div class="flex flex-col gap-xs mt-1">
                                    <input class="w-full text-body-sm file:mr-md file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-body-sm file:font-semibold file:bg-primary-fixed file:text-on-primary-fixed hover:file:bg-primary-fixed-dim cursor-pointer" 
                                           id="add-fc_ijazah_skl" name="fc_ijazah_skl" type="file" />
                                </div>
                            </div>

                            <!-- Unggah Kartu Keluarga (KK) -->
                            <div class="flex flex-col gap-sm p-sm rounded-xl bg-surface-container-low border border-outline-variant/40">
                                <div class="flex justify-between items-start flex-wrap gap-xs">
                                    <div>
                                        <h3 class="font-label-bold text-body-sm text-on-surface">Scan Kartu Keluarga (KK)</h3>
                                        <p class="text-caption text-on-surface-variant">Seluruh anggota keluarga harus terlihat jelas. Format: PDF/JPG/PNG max 2MB.</p>
                                    </div>
                                    <div class="inline-flex items-center gap-xs px-3 py-1 bg-amber-100 border border-amber-300 text-amber-800 rounded-full font-label-bold text-[11px] shadow-sm select-none">
                                        <span class="material-symbols-outlined text-[16px] text-amber-600">pending</span> Opsional
                                    </div>
                                </div>

                                <div class="flex flex-col gap-xs mt-1">
                                    <input class="w-full text-body-sm file:mr-md file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-body-sm file:font-semibold file:bg-primary-fixed file:text-on-primary-fixed hover:file:bg-primary-fixed-dim cursor-pointer" 
                                           id="add-fc_kk" name="fc_kk" type="file" />
                                </div>
                            </div>

                            <!-- Unggah Pas Foto 3x4 -->
                            <div class="flex flex-col gap-sm p-sm rounded-xl bg-surface-container-low border border-outline-variant/40">
                                <div class="flex justify-between items-start flex-wrap gap-xs">
                                    <div>
                                        <h3 class="font-label-bold text-body-sm text-on-surface">Pas Foto 3x4</h3>
                                        <p class="text-caption text-on-surface-variant">Foto resmi terbaru (berwarna). Format: JPG/JPEG/PNG max 2MB.</p>
                                    </div>
                                    <div class="inline-flex items-center gap-xs px-3 py-1 bg-amber-100 border border-amber-300 text-amber-800 rounded-full font-label-bold text-[11px] shadow-sm select-none">
                                        <span class="material-symbols-outlined text-[16px] text-amber-600">pending</span> Opsional
                                    </div>
                                </div>

                                <div class="flex flex-col gap-xs mt-1">
                                    <input class="w-full text-body-sm file:mr-md file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-body-sm file:font-semibold file:bg-primary-fixed file:text-on-primary-fixed hover:file:bg-primary-fixed-dim cursor-pointer" 
                                           id="add-pas_foto" name="pas_foto" type="file" accept=".jpg,.jpeg,.png" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="flex flex-col sm:flex-row justify-end items-center gap-sm px-6 pb-6 pt-4 border-t border-outline-variant/30 shrink-0 bg-white">
                <button type="button" id="add-btn-prev" onclick="addPrevStep()" 
                        class="w-full sm:w-auto rounded-lg border border-outline text-on-surface-variant hover:bg-surface-container-high transition-colors flex items-center justify-center gap-xs font-label-bold text-body-sm text-center hidden"
                        style="padding: 10px 24px !important; min-width: 120px;">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Sebelumnya
                </button>
                <button type="button" id="add-btn-cancel" onclick="closeAddModal()" 
                        class="w-full sm:w-auto rounded-lg border border-outline text-on-surface-variant hover:bg-surface-container-high transition-colors flex items-center justify-center gap-xs font-label-bold text-body-sm text-center"
                        style="padding: 10px 24px !important; min-width: 120px;">
                    Batal
                </button>
                <button type="button" id="add-btn-next" onclick="addNextStep()" 
                        class="w-full sm:w-auto rounded-lg bg-primary text-on-primary font-label-bold text-body-sm hover:bg-primary-container transition-colors shadow-sm hover:shadow-md flex items-center justify-center gap-xs group"
                        style="padding: 10px 24px !important; min-width: 120px; cursor: pointer;">
                    Selanjutnya
                    <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </button>
                <button type="submit" id="add-btn-submit" 
                        class="w-full sm:w-auto rounded-lg bg-primary text-on-primary font-label-bold text-body-sm hover:bg-primary-container transition-colors shadow-sm hover:shadow-md flex items-center justify-center gap-xs group hidden"
                        style="padding: 10px 24px !important; min-width: 120px; cursor: pointer;">
                    Simpan Data
                    <span class="material-symbols-outlined text-[18px]">check</span>
                </button>
            </div>
        </form>
    </div>
</div>
