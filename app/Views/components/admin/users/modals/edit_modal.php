<!-- ==========================================
     MODAL 2: EDIT STAFF
     ========================================== -->
<div id="edit-modal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 p-4"
     style="display: none; align-items: center; justify-content: center; z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-in fade-in zoom-in-95 duration-200"
         style="margin: auto; max-height: 90vh; display: flex; flex-direction: column; width: 100%; max-width: 450px;">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">edit</span>
                </div>
                <div>
                    <h3 class="font-semibold text-body-md text-on-surface">Ubah Data Staff</h3>
                    <p class="text-caption text-on-surface-variant">Perbarui username, email, dan hak akses</p>
                </div>
            </div>
            <button onclick="closeEditModal()" class="p-1.5 rounded-full hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-on-surface-variant">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="edit-form" method="post" action="" style="display: flex; flex-direction: column; overflow: hidden; margin: 0;">
            <?= csrf_field() ?>
            <div class="px-6 py-5 space-y-4 overflow-y-auto custom-scrollbar" style="flex: 1 1 auto; max-height: 60vh;">
                <!-- Username -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Username</label>
                    <input type="text" name="username" id="edit-username" required minlength="3" maxlength="30" pattern="^[a-zA-Z0-9_]+$"
                           class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                </div>
                <!-- Email -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Alamat Email</label>
                    <input type="email" name="email" id="edit-email" required
                           class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                </div>
                <!-- Role -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Peran / Akses Sistem</label>
                    <select name="role" id="edit-role" required
                            class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest">
                        <option value="panitia">Panitia (Akses Terbatas ke Pendaftar)</option>
                        <option value="admin">Admin (Akses Penuh Pengaturan & User)</option>
                    </select>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-2 px-6 pb-6 pt-3 border-t border-outline-variant/30 shrink-0">
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
