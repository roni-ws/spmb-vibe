<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Pengumuman<?= $this->endSection() ?>
<?= $this->section('page-title') ?>Pengumuman Pendaftaran<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-[800px] mx-auto">

    <!-- ==========================================
         PAGE HEADER
         ========================================== -->
    <div class="flex justify-between items-center mb-md">
        <div>
            <h1 class="font-headline-md text-headline-md font-bold text-primary mb-1">Pengumuman & WhatsApp</h1>
            <p class="text-on-surface-variant text-body-sm">Kelola tautan grup WhatsApp resmi dan publikasi pengumuman untuk calon siswa baru.</p>
        </div>
    </div>

    <div class="flex flex-col gap-md">

    <!-- ==========================================
         WHATSAPP GROUP LINK
         ========================================== -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md md:p-lg shadow-sm">
        <h3 class="font-headline-sm text-headline-sm text-primary mb-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">chat</span> Grup WhatsApp Resmi
        </h3>
        <p class="text-caption text-on-surface-variant mb-6">Ubah tautan WhatsApp Group yang aktif bagi calon siswa baru di dashboard pendaftar.</p>

        <form action="<?= base_url('admin/pengumuman/save-whatsapp') ?>" method="POST">
            <?= csrf_field() ?>

            <div class="flex flex-col gap-2">
                <label for="whatsapp_link" class="text-body-sm font-semibold text-on-surface">Tautan WhatsApp Group Pendaftar</label>
                <div class="flex gap-3">
                    <input type="url" name="whatsapp_link" id="whatsapp_link" value="<?= esc($whatsapp_link ?? '') ?>"
                           class="flex-grow rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                    <button type="submit" class="bg-primary text-on-primary font-semibold text-body-sm px-5 py-3 rounded-xl hover:opacity-90 transition-opacity flex items-center gap-1.5 shadow-sm shrink-0">
                        <span class="material-symbols-outlined text-[18px]">save</span> Simpan
                    </button>
                </div>
                <p class="text-caption text-on-surface-variant">Contoh: `https://chat.whatsapp.com/GrupSpmb` atau `https://wa.me/628...`</p>
            </div>
        </form>
    </div>

    <!-- ==========================================
         ANNOUNCEMENT MANAGEMENT CONSOLE
         ========================================== -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-md md:p-lg shadow-sm">
        <div class="flex justify-between items-center mb-md border-b border-outline-variant/60 pb-sm">
            <div>
                <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">campaign</span> Pengumuman Pendaftaran Aktif
                </h3>
                <p class="text-caption text-on-surface-variant mt-0.5">Kelola seluruh perilisan pengumuman di dashboard calon siswa.</p>
            </div>
            <button type="button" onclick="openAnnouncementModal()" class="bg-primary text-on-primary font-semibold text-body-sm px-4 py-2 rounded-xl hover:opacity-90 transition-opacity flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add_circle</span> Buat Pengumuman
            </button>
        </div>

        <div class="flex flex-col gap-sm">
            <?php if (!empty($announcements)): ?>
                <?php foreach ($announcements as $ann): ?>
                <div class="p-md rounded-xl border border-outline-variant bg-surface-container-low/50 hover:bg-surface-container-low transition-colors flex justify-between items-start gap-md">
                    <div class="space-y-1">
                        <h4 class="font-semibold text-body-sm text-primary flex items-center gap-xs">
                            <span class="material-symbols-outlined text-base text-primary">campaign</span>
                            <?= esc($ann['judul']) ?>
                        </h4>
                        <p class="text-caption text-on-surface-variant whitespace-pre-line leading-relaxed">
                            <?= esc($ann['konten']) ?>
                        </p>
                        <span class="block text-[10px] text-outline pt-1 flex items-center gap-xs">
                            <span class="material-symbols-outlined text-[12px]">schedule</span>
                            <?= date('d M Y H:i', strtotime($ann['created_at'])) ?>
                        </span>
                    </div>
                    <button type="button" onclick="confirmDeleteAnnouncement(<?= $ann['id'] ?>)" class="text-error hover:bg-error-container p-2 rounded-lg transition-colors flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">delete</span>
                    </button>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-10 text-on-surface-variant border border-dashed border-outline-variant rounded-xl bg-surface/30">
                    <span class="material-symbols-outlined text-4xl block mb-2 text-outline animate-[bounce_2s_infinite]">campaign</span>
                    Belum ada pengumuman penting yang dipublikasikan.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>

<!-- ==========================================
     MODAL: TAMBAH PENGUMUMAN BARU
     ========================================== -->
<div id="modal-announcement" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-surface border border-outline-variant rounded-2xl w-[90%] max-w-[500px] shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300">
        <!-- Header -->
        <div class="px-md py-4 bg-primary text-on-primary flex justify-between items-center">
            <h3 class="font-headline-sm text-headline-sm text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-white">campaign</span> Buat Pengumuman Baru
            </h3>
            <button type="button" onclick="closeAnnouncementModal()" class="text-white/80 hover:text-white flex items-center justify-center">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Form -->
        <form action="<?= base_url('admin/pengumuman/add') ?>" method="POST" class="p-md space-y-md">
            <?= csrf_field() ?>

            <div class="flex flex-col gap-1">
                <label for="judul" class="text-body-sm font-semibold text-on-surface">Judul Pengumuman</label>
                <input type="text" name="judul" id="judul" placeholder="Contoh: Jadwal Ujian Masuk Seleksi Akademik"
                       class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" required>
            </div>

            <div class="flex flex-col gap-1">
                <label for="konten" class="text-body-sm font-semibold text-on-surface">Isi Pengumuman</label>
                <textarea name="konten" id="konten" rows="5" placeholder="Tuliskan pengumuman lengkap di sini..."
                          class="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-body-sm outline-none focus:border-primary focus:ring-2 focus:ring-primary/20" required></textarea>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-sm border-t border-outline-variant">
                <button type="button" onclick="closeAnnouncementModal()" class="px-5 py-2.5 rounded-xl border border-outline text-on-surface-variant hover:bg-surface-container-high font-semibold text-body-sm transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary hover:opacity-90 font-semibold text-body-sm shadow-md transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">publish</span> Publikasikan
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const BASE_URL = '<?= base_url() ?>';
    const CSRF_TOKEN = '<?= csrf_token() ?>';
    const CSRF_HASH = '<?= csrf_hash() ?>';
</script>
<?= script_tag('js/admin/pengumuman.js') ?>
<?= $this->endSection() ?>
