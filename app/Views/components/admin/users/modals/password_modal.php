<!-- ==========================================
     MODAL 3: UBAH PASSWORD
     ========================================== -->
<div id="password-modal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 p-4"
     style="display: none; align-items: center; justify-content: center; z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md animate-in fade-in zoom-in-95 duration-200"
         style="margin: auto; max-height: 90vh; display: flex; flex-direction: column; width: 100%; max-width: 450px;">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary">lock_reset</span>
                </div>
                <div>
                    <h3 class="font-semibold text-body-md text-on-surface">Ubah Kata Sandi</h3>
                    <p id="password-modal-subtitle" class="text-caption text-on-surface-variant">Ganti kata sandi milik staff</p>
                </div>
            </div>
            <button onclick="closePasswordModal()" class="p-1.5 rounded-full hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-on-surface-variant">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="password-form" method="post" action="" style="display: flex; flex-direction: column; overflow: hidden; margin: 0;">
            <?= csrf_field() ?>
            <div class="px-6 py-5 space-y-4 overflow-y-auto custom-scrollbar" style="flex: 1 1 auto; max-height: 60vh;">
                <!-- Password -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Kata Sandi Baru</label>
                    <input type="password" name="password" required minlength="6"
                           placeholder="Minimal 6 karakter baru"
                           class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                </div>
                <!-- Confirm Password -->
                <div>
                    <label class="block text-body-sm font-semibold text-on-surface mb-1.5">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirm" required minlength="6"
                           placeholder="Ulangi kata sandi baru"
                           class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex gap-2 px-6 pb-6 pt-3 border-t border-outline-variant/30 shrink-0">
                <button type="button" onclick="closePasswordModal()"
                        class="flex-1 py-2.5 border border-outline-variant rounded-xl text-body-sm font-semibold text-on-surface hover:bg-surface-container transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 bg-primary text-on-primary rounded-xl text-body-sm font-semibold hover:opacity-90 transition-opacity">
                    Perbarui Sandi
                </button>
            </div>
        </form>
    </div>
</div>
