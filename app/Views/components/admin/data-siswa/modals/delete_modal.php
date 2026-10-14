<!-- ================================
     DELETE CONFIRM MODAL (Single)
     ================================ -->
<div id="delete-modal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 p-4"
     style="display: none; align-items: center; justify-content: center; z-index: 9999;">
    <form id="delete-form" method="post" action="" class="bg-white rounded-2xl shadow-2xl w-full max-w-sm"
         style="margin: auto; max-height: 90vh; display: flex; flex-direction: column; width: 100%; max-width: 380px;">
        <?= csrf_field() ?>
        <div class="px-6 py-5 text-center flex-grow overflow-y-auto">
            <div class="w-14 h-14 rounded-full bg-error-container flex items-center justify-center mx-auto mb-4 shrink-0">
                <span class="material-symbols-outlined text-error text-2xl">delete_forever</span>
            </div>
            <h3 class="font-semibold text-body-lg text-on-surface mb-1">Hapus Pendaftar?</h3>
            <p class="text-body-sm text-on-surface mb-3 font-medium">
                Apakah Anda yakin ingin menghapus calon siswa ini?
            </p>
            <p class="text-caption text-on-surface-variant bg-surface-container-low/60 p-3 rounded-xl border border-outline-variant/30 leading-relaxed">
                Data <strong id="delete-student-name" class="text-on-surface"></strong> akan dihapus secara permanen beserta semua berkas terkait. Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
        <div class="flex gap-3 px-6 pb-6 shrink-0">
            <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 py-2.5 border border-outline-variant rounded-xl text-body-sm font-semibold hover:bg-surface-container transition-colors">
                Batal
            </button>
            <button type="submit"
                    class="flex-1 py-2.5 bg-error text-on-error rounded-xl text-body-sm font-semibold hover:opacity-90 transition-opacity">
                Ya, Hapus
            </button>
        </div>
    </form>
</div>

<!-- ================================
     BULK DELETE CONFIRM MODAL
     ================================ -->
<div id="bulk-delete-modal"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 p-4"
     style="display: none; align-items: center; justify-content: center; z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm"
         style="margin: auto; max-height: 90vh; display: flex; flex-direction: column; width: 100%; max-width: 380px;">
        <div class="px-6 py-5 text-center flex-grow overflow-y-auto">
            <div class="w-14 h-14 rounded-full bg-error-container flex items-center justify-center mx-auto mb-4 shrink-0">
                <span class="material-symbols-outlined text-error text-2xl">delete_forever</span>
            </div>
            <h3 class="font-semibold text-body-lg text-on-surface mb-1">Hapus Pendaftar Terpilih?</h3>
            <p class="text-body-sm text-on-surface mb-3 font-medium">
                Apakah Anda yakin ingin menghapus <strong id="bulk-delete-count" class="text-on-surface">0</strong> pendaftar terpilih?
            </p>
            <p class="text-caption text-on-surface-variant bg-surface-container-low/60 p-3 rounded-xl border border-outline-variant/30 leading-relaxed">
                Semua data akan dihapus secara permanen beserta berkas terkait. Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
        <div class="flex gap-3 px-6 pb-6 shrink-0">
            <button type="button" onclick="closeBulkDeleteModal()"
                    class="flex-1 py-2.5 border border-outline-variant rounded-xl text-body-sm font-semibold hover:bg-surface-container transition-colors">
                Batal
            </button>
            <button type="button" id="bulk-confirm-delete-btn"
                    class="flex-1 py-2.5 bg-error text-on-error rounded-xl text-body-sm font-semibold hover:opacity-90 transition-opacity">
                Ya, Hapus Semua
            </button>
        </div>
    </div>
</div>
