<!-- ================================
     EDIT STUDENT MODAL
     ================================ -->
<div id="edit-modal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 p-4"
     style="display: none; align-items: center; justify-content: center; z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full animate-in fade-in zoom-in-95 duration-200"
         style="margin: auto; max-height: 90vh; display: flex; flex-direction: column; width: 100%; max-width: 720px;">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">edit</span>
                </div>
                <div>
                    <h3 class="font-semibold text-body-md text-on-surface">Edit Data Calon Siswa</h3>
                    <p id="edit-modal-subtitle" class="text-caption text-on-surface-variant">Ubah seluruh data calon siswa</p>
                    <p class="text-caption text-on-surface-variant mt-1">No. Registrasi: <span id="edit-no_registrasi" class="font-semibold text-primary"></span></p>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" class="p-1.5 rounded-full hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-on-surface-variant">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="edit-form" method="post" action="" style="display: flex; flex-direction: column; overflow: hidden; margin: 0;">
            <?= csrf_field() ?>
            <div class="px-6 py-5 overflow-y-auto custom-scrollbar" style="flex: 1 1 auto; max-height: 65vh;">
                <div class="space-y-6">
                    <!-- ======== BIODATA ======== -->
                    <div>
                        <h4 class="font-semibold text-body-sm text-primary mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">person</span> Biodata
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="edit-nama" required
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">NIK</label>
                                <input type="text" name="nik" id="edit-nik" required
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">No. KK</label>
                                <input type="text" name="no_kk" id="edit-no_kk"
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" id="edit-tempat_lahir" required
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="edit-tanggal_lahir" required
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="edit-jenis_kelamin" required
                                        class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Anak Ke-</label>
                                <input type="number" name="anak_ke" id="edit-anak_ke" required min="1"
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Jumlah Saudara Kandung</label>
                                <input type="number" name="jumlah_saudara" id="edit-jumlah_saudara" required min="0"
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">No. Handphone</label>
                                <input type="text" name="no_hp" id="edit-nohp"
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                        </div>
                    </div>

                    <!-- ======== ALAMAT ======== -->
                    <div>
                        <h4 class="font-semibold text-body-sm text-primary mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">home</span> Alamat
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Desa</label>
                                <input type="text" name="alamat_desa" id="edit-alamat_desa" required
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Kecamatan</label>
                                <input type="text" name="alamat_kecamatan" id="edit-alamat_kecamatan" required
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Kabupaten / Kota</label>
                                <input type="text" name="alamat_kabupaten" id="edit-alamat_kabupaten" required
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                        </div>
                    </div>

                    <!-- ======== DATA ORANG TUA ======== -->
                    <div>
                        <h4 class="font-semibold text-body-sm text-primary mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">family_history</span> Data Orang Tua
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Nama Lengkap Ayah</label>
                                <input type="text" name="nama_ayah" id="edit-nama_ayah" required
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Pekerjaan Ayah</label>
                                <input type="text" name="pekerjaan_ayah" id="edit-pekerjaan_ayah" required
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Nama Lengkap Ibu</label>
                                <input type="text" name="nama_ibu" id="edit-nama_ibu" required
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Pekerjaan Ibu</label>
                                <input type="text" name="pekerjaan_ibu" id="edit-pekerjaan_ibu" required
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                        </div>
                    </div>

                    <!-- ======== ASAL SEKOLAH ======== -->
                    <div>
                        <h4 class="font-semibold text-body-sm text-primary mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">school</span> Asal Sekolah
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Nama Sekolah</label>
                                <input type="text" name="nama_sekolah" id="edit-sekolah" required
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                            <div>
                                <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Alamat Sekolah</label>
                                <input type="text" name="alamat_sekolah" id="edit-alamat_sekolah" required
                                       class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-2 px-6 pb-6 pt-2 border-t border-outline-variant/30 shrink-0">
                <button type="button" onclick="closeEditModal()"
                        class="flex-1 py-2.5 border border-outline-variant rounded-xl text-body-sm font-semibold text-on-surface hover:bg-surface-container transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-primary text-on-primary rounded-xl text-body-sm font-semibold hover:opacity-90 transition-opacity">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
