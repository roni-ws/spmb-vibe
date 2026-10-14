<!-- ==========================================
     MODAL 4: HAPUS CONFIRM MODAL
     ========================================== -->
<div id="delete-modal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 p-4"
     style="display: none; align-items: center; justify-content: center; z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm animate-in fade-in zoom-in-95 duration-200"
         style="margin: auto; max-height: 90vh; display: flex; flex-direction: column; width: 100%; max-width: 380px;">
        <div class="px-6 py-5 text-center flex-grow overflow-y-auto">
            <div class="w-14 h-14 rounded-full bg-error-container flex items-center justify-center mx-auto mb-4 shrink-0 shadow-sm border border-on-error-container/10">
                <span class="material-symbols-outlined text-error text-2xl animate-pulse">delete_forever</span>
            </div>
            <h3 class="font-semibold text-body-lg text-on-surface mb-1">Hapus Akun Staff?</h3>
            <p class="text-body-sm text-on-surface-variant">
                Akun staf <strong id="delete-username" class="text-on-surface"></strong> akan dihapus permanen dari database. Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
        <form id="delete-form" method="post" action="" class="flex gap-3 px-6 pb-6 shrink-0">
            <?= csrf_field() ?>
            <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 py-2.5 border border-outline-variant rounded-xl text-body-sm font-semibold hover:bg-surface-container transition-colors">
                Batal
            </button>
            <button type="submit"
                    class="flex-1 py-2.5 bg-error text-on-error rounded-xl text-body-sm font-semibold hover:opacity-90 transition-opacity shadow-md">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>
