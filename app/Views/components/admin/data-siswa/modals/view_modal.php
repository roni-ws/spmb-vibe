<!-- ================================
     VIEW STUDENT MODAL
     ================================ -->
<div id="view-modal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 p-4"
     style="display: none; align-items: center; justify-content: center; z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full animate-in fade-in zoom-in-95 duration-200"
         style="margin: auto; max-height: 90vh; display: flex; flex-direction: column; width: 100%; max-width: 640px;">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">badge</span>
                </div>
                <div>
                    <h3 class="font-semibold text-body-md text-on-surface">Detail Calon Siswa</h3>
                    <p class="text-caption text-on-surface-variant">No. Registrasi: <span id="view-no_registrasi" class="font-semibold text-primary"></span></p>
                </div>
            </div>
            <button type="button" onclick="closeViewModal()" class="p-1.5 rounded-full hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-on-surface-variant">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <div style="display: flex; flex-direction: column; overflow: hidden; margin: 0;">
            <div class="px-6 py-5 overflow-y-auto custom-scrollbar" style="flex: 1 1 auto; max-height: 65vh;">
                <div class="space-y-6">

                    <!-- ======== PAS FOTO ======== -->
                    <div class="flex justify-center">
                        <div class="relative">
                            <img id="view-pas-foto" src="" alt="Pas Foto"
                                 class="w-28 h-36 object-cover border-2 border-outline-variant rounded-xl shadow-sm hidden">
                            <div id="view-pas-foto-placeholder"
                                 class="w-28 h-36 bg-surface-container-lowest rounded-xl border-2 border-dashed border-outline-variant flex flex-col items-center justify-center gap-1 hidden">
                                <span class="material-symbols-outlined text-3xl text-outline">image</span>
                                <span class="text-caption text-outline">Belum ada foto</span>
                            </div>
                        </div>
                    </div>

                    <!-- ======== BIODATA ======== -->
                    <div>
                        <h4 class="font-semibold text-body-sm text-primary mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">person</span> Biodata
                        </h4>
                        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6">
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">NISN</span>
                                    <p id="view-nisn" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">NIK</span>
                                    <p id="view-nik" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">No. KK</span>
                                    <p id="view-no_kk" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">Nama Lengkap</span>
                                    <p id="view-nama" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">Tempat Lahir</span>
                                    <p id="view-tempat_lahir" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">Tanggal Lahir</span>
                                    <p id="view-tanggal_lahir" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">Jenis Kelamin</span>
                                    <p id="view-jenis_kelamin" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">Anak Ke-</span>
                                    <p id="view-anak_ke" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">Jumlah Saudara</span>
                                    <p id="view-jumlah_saudara" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">No. Handphone</span>
                                    <p id="view-nohp" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ======== ALAMAT ======== -->
                    <div>
                        <h4 class="font-semibold text-body-sm text-primary mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">home</span> Alamat
                        </h4>
                        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6">
                                <div class="md:col-span-2">
                                    <span class="text-caption text-on-surface-variant font-medium">Desa</span>
                                    <p id="view-alamat_desa" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">Kecamatan</span>
                                    <p id="view-alamat_kecamatan" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">Kabupaten / Kota</span>
                                    <p id="view-alamat_kabupaten" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ======== DATA ORANG TUA ======== -->
                    <div>
                        <h4 class="font-semibold text-body-sm text-primary mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">family_history</span> Data Orang Tua
                        </h4>
                        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6">
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">Nama Ayah</span>
                                    <p id="view-nama_ayah" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">Pekerjaan Ayah</span>
                                    <p id="view-pekerjaan_ayah" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">Nama Ibu</span>
                                    <p id="view-nama_ibu" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">Pekerjaan Ibu</span>
                                    <p id="view-pekerjaan_ibu" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ======== ASAL SEKOLAH ======== -->
                    <div>
                        <h4 class="font-semibold text-body-sm text-primary mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">school</span> Asal Sekolah
                        </h4>
                        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6">
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">Nama Sekolah</span>
                                    <p id="view-sekolah" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                                <div>
                                    <span class="text-caption text-on-surface-variant font-medium">Alamat Sekolah</span>
                                    <p id="view-alamat_sekolah" class="text-body-sm font-semibold text-on-surface">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ======== DOKUMEN UNGGAHAN ======== -->
                    <div>
                        <h4 class="font-semibold text-body-sm text-primary mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">folder</span> Dokumen Unggahan
                        </h4>
                        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant divide-y divide-outline-variant/50 overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-[22px] text-primary">description</span>
                                    <div>
                                        <p class="text-body-sm font-medium text-on-surface">Scan Ijazah / SKL</p>
                                        <span id="view-badge-ijazah" class="text-caption font-bold"></span>
                                    </div>
                                </div>
                                <a id="view-link-ijazah" href="#" target="_blank"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary/10 text-primary rounded-lg text-caption font-semibold hover:bg-primary/20 transition-colors hidden">
                                    <span class="material-symbols-outlined text-[16px]">open_in_new</span> Lihat
                                </a>
                            </div>
                            <div class="flex items-center justify-between px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-[22px] text-primary">description</span>
                                    <div>
                                        <p class="text-body-sm font-medium text-on-surface">Scan Kartu Keluarga</p>
                                        <span id="view-badge-kk" class="text-caption font-bold"></span>
                                    </div>
                                </div>
                                <a id="view-link-kk" href="#" target="_blank"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary/10 text-primary rounded-lg text-caption font-semibold hover:bg-primary/20 transition-colors hidden">
                                    <span class="material-symbols-outlined text-[16px]">open_in_new</span> Lihat
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- ======== VERIFIKASI BERKAS ======== -->
                    <form id="view-verify-form" method="post" action="">
                        <?= csrf_field() ?>
                        <div>
                            <h4 class="font-semibold text-body-sm text-primary mb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">verified_user</span> Verifikasi Berkas
                            </h4>
                            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6">
                                    <div>
                                        <span class="text-caption text-on-surface-variant font-medium">Status Verifikasi</span>
                                        <p class="text-body-sm text-on-surface-variant mt-0.5 mb-2">Saat ini: <span id="view-status_badge" class="inline-block px-2.5 py-0.5 rounded-full text-caption font-bold"></span></p>
                                        <select name="status_verifikasi_id" id="view-verify-status"
                                                class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-white">
                                            <option value="4">Tidak Valid – Dokumen tidak sesuai</option>
                                            <option value="3">Valid – Berkas lengkap & sah</option>
                                            <option value="2">Revisi – Perlu perbaikan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <span class="text-caption text-on-surface-variant font-medium">Finalisasi</span>
                                        <p id="view-final_badge" class="inline-block px-2.5 py-0.5 rounded-full text-caption font-bold mt-1"></p>
                                    </div>
                                    <div class="md:col-span-2">
                                        <span class="text-caption text-on-surface-variant font-medium">Catatan Verifikasi</span>
                                        <p class="text-body-sm text-on-surface-variant mt-0.5 mb-2">Pesan ini akan dikirim ke dashboard calon siswa</p>
                                        <textarea name="catatan_verifikasi" id="view-verify-catatan" rows="3"
                                                  class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none resize-none bg-white"
                                                  placeholder="Tulis catatan untuk calon siswa (wajib jika status Revisi)"></textarea>
                                    </div>
                                    <div>
                                        <span class="text-caption text-on-surface-variant font-medium">Tanggal Daftar</span>
                                        <p id="view-tgl_daftar" class="text-body-sm font-semibold text-on-surface mt-0.5">-</p>
                                    </div>
                                </div>
                                <div class="mt-4 pt-3 border-t border-outline-variant/30 flex justify-end">
                                    <button type="submit"
                                            class="px-5 py-2.5 bg-primary text-on-primary rounded-xl text-body-sm font-semibold hover:opacity-90 transition-opacity inline-flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[18px]">check</span> Simpan Verifikasi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-2 px-6 pb-6 pt-2 border-t border-outline-variant/30 shrink-0">
                <button type="button" onclick="closeViewModal()"
                        class="flex-1 py-2.5 border border-outline-variant rounded-xl text-body-sm font-semibold text-on-surface hover:bg-surface-container transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
