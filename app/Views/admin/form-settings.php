<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Pengaturan Frontend<?= $this->endSection() ?>
<?= $this->section('page-title') ?>Pengaturan Halaman Utama<?= $this->endSection() ?>

<?= $this->section('css') ?>
<link href="<?= base_url('vendor/quill/quill.snow.css') ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-[1000px] mx-auto">

    <!-- ==========================================
         PAGE HEADER
         ========================================== -->
    <div class="flex justify-between items-center mb-md">
        <div>
            <h1 class="font-headline-md text-headline-md font-bold text-primary mb-1">Pengaturan Website</h1>
            <p class="text-on-surface-variant text-body-sm">Kelola isi konten halaman depan, alur pendaftaran, dan informasi kontak sekolah secara real-time.</p>
        </div>
    </div>

    <!-- ==========================================
         SUB-TAB NAVIGATION
         ========================================== -->
    <nav class="flex flex-wrap gap-2 mb-md border-b border-outline-variant pb-2">
        <button type="button" onclick="switchSubTab('tab-branding')" id="btn-tab-branding"
                class="tab-btn active px-4 py-2.5 rounded-xl text-label-bold font-semibold transition-all flex items-center gap-2 border-b-2 border-primary text-primary">
            <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
            Branding & Status
        </button>
        <button type="button" onclick="switchSubTab('tab-hero')" id="btn-tab-hero"
                class="tab-btn px-4 py-2.5 rounded-xl text-label-bold font-semibold transition-all flex items-center gap-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface hover:bg-surface-container">
            <span class="material-symbols-outlined text-[18px]">image</span>
            Hero Banner
        </button>
        <button type="button" onclick="switchSubTab('tab-alur')" id="btn-tab-alur"
                class="tab-btn px-4 py-2.5 rounded-xl text-label-bold font-semibold transition-all flex items-center gap-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface hover:bg-surface-container">
            <span class="material-symbols-outlined text-[18px]">route</span>
            Alur Pendaftaran
        </button>
        <button type="button" onclick="switchSubTab('tab-footer')" id="btn-tab-footer"
                class="tab-btn px-4 py-2.5 rounded-xl text-label-bold font-semibold transition-all flex items-center gap-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface hover:bg-surface-container">
            <span class="material-symbols-outlined text-[18px]">contact_support</span>
            Persyaratan & Footer
        </button>
        <button type="button" onclick="switchSubTab('tab-pengumuman')" id="btn-tab-pengumuman"
                class="tab-btn px-4 py-2.5 rounded-xl text-label-bold font-semibold transition-all flex items-center gap-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface hover:bg-surface-container">
            <span class="material-symbols-outlined text-[18px]">campaign</span>
            Pengumuman & WA
        </button>
        <button type="button" onclick="switchSubTab('tab-kop')" id="btn-tab-kop"
                class="tab-btn px-4 py-2.5 rounded-xl text-label-bold font-semibold transition-all flex items-center gap-2 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface hover:bg-surface-container">
            <span class="material-symbols-outlined text-[18px]">print</span>
            Kop & Cetak Bukti
        </button>
    </nav>

    <!-- ==========================================
         MAIN CONFIGURATION FORM
         ========================================== -->
    <form id="settings-form" action="<?= base_url('admin/settings/save') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <?= $this->include('components/admin/settings/tabs/umum') ?>
        <?= $this->include('components/admin/settings/tabs/pendaftaran') ?>
        <?= $this->include('components/admin/settings/tabs/pengumuman') ?>

        <!-- ==========================================

             SUBMIT TOOLBAR (Sticky at Bottom)
             ========================================== -->
        <div class="sticky bottom-6 mt-md p-4 rounded-2xl bg-surface border border-outline-variant/80 shadow-lg flex justify-between items-center backdrop-blur-md bg-opacity-95 z-10 animate-slide-up">
            <span class="text-caption text-on-surface-variant hidden sm:inline flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px] text-primary">info</span> Pastikan data yang dimasukkan sudah benar sebelum disimpan.
            </span>
            <div class="flex gap-3 ml-auto w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto bg-primary text-on-primary font-semibold text-body-sm px-6 py-3 rounded-xl hover:opacity-90 shadow-md transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span> Simpan Perubahan
                </button>
            </div>
        </div>

    </form>
</div>



<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const BASE_URL = '<?= base_url() ?>';
    const CSRF_TOKEN = '<?= csrf_token() ?>';
    const CSRF_HASH = '<?= csrf_hash() ?>';
</script>
<script src="<?= base_url('vendor/quill/quill.min.js') ?>"></script>
<?= script_tag('js/admin/form-settings.js') ?>
<?= $this->endSection() ?>
