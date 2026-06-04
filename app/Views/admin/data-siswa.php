<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Data Pendaftar<?= $this->endSection() ?>
<?= $this->section('page-title') ?>Data Siswa<?= $this->endSection() ?>

<?= $this->section('css') ?>
<!-- Admin page CSS is loaded from public/css/admin.css via the layout -->
<?= $this->endSection() ?>


<?= $this->section('content') ?>

<!-- ================================
     PAGE HEADER
     ================================ -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
    <div>
        <h2 class="text-headline-md font-bold text-primary">Kelola Pendaftaran</h2>
        <p class="text-body-sm text-on-surface-variant mt-1">
            Kelola dan tinjau semua pendaftaran siswa masuk.
            Total <strong class="text-on-surface"><?= count($students) ?></strong> pendaftar terdaftar.
        </p>
    </div>
    <div class="flex gap-2 flex-nowrap whitespace-nowrap">
        <button type="button" onclick="openAddModal()"
                class="flex items-center gap-1 bg-primary text-on-primary font-semibold text-body-sm px-4 py-2 rounded-xl hover:opacity-90 transition-opacity shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add</span> Tambah Pendaftar
        </button>
        <a href="<?= base_url('admin/export/csv') ?>"
           class="flex items-center gap-1 border border-primary text-primary font-semibold text-body-sm px-4 py-2 rounded-xl hover:bg-surface-container-low transition-colors">
            <span class="material-symbols-outlined text-[18px]">download</span> Export CSV
        </a>
        <a href="<?= base_url('admin/cetak-data') ?>" target="_blank"
           class="flex items-center gap-1 border border-primary text-primary font-semibold text-body-sm px-4 py-2 rounded-xl hover:bg-surface-container-low transition-colors">
            <span class="material-symbols-outlined text-[18px]">print</span> Cetak Data
        </a>
    </div>
</div>


<!-- ================================
     FILTER BAR
     ================================ -->
<div class="filter-bar bg-white rounded-2xl border border-outline-variant shadow-sm p-4 mb-4 space-y-3">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
        <!-- Search -->
        <div class="md:col-span-8 relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
            <input id="custom-search" type="text"
                   placeholder="Cari nama, NIK, atau sekolah..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest" />
        </div>
        <!-- Status Filter -->
        <div class="md:col-span-3">
            <select id="status-filter"
                    class="w-full px-3 py-2.5 rounded-xl border border-outline-variant focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all text-body-sm outline-none bg-surface-container-lowest">
                <option value="">Semua Status Berkas</option>
                <option value="Valid">Valid</option>
                <option value="Belum Verifikasi">Belum Verifikasi</option>
                <option value="Revisi">Revisi</option>
            </select>
        </div>
        <!-- Reset -->
        <div class="md:col-span-1 flex justify-end">
            <button id="reset-filter" title="Reset filter"
                    class="p-2.5 text-primary border border-outline-variant rounded-xl hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined">filter_list_off</span>
            </button>
        </div>
    </div>

    <!-- Bulk Toolbar (hidden until rows selected) -->
    <div id="bulk-toolbar" class="hidden items-center justify-between bg-primary-container/10 px-4 py-2 rounded-xl border border-primary-container/30">
        <span class="text-primary font-semibold text-body-sm">
            <span id="selected-count">0</span> data dipilih
        </span>
        <div class="flex gap-2">
            <button id="bulk-delete-btn"
                    class="flex items-center gap-1 px-3 py-1.5 text-error font-semibold text-body-sm hover:bg-error-container/30 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-[18px]">delete</span> Hapus
            </button>
        </div>
    </div>
</div>

<!-- ================================
     DATA TABLE
     ================================ -->
<?= $this->include('components/admin/data-siswa/table') ?>

<?= $this->include('components/admin/data-siswa/modals/delete_modal') ?>

<?= $this->include('components/admin/data-siswa/modals/add_modal') ?>

<?= $this->include('components/admin/data-siswa/modals/edit_modal') ?>

<?= $this->include('components/admin/data-siswa/modals/view_modal') ?>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
    window.BASE_URL = '<?= base_url() ?>';
</script>
<?= script_tag('js/admin/data-siswa.js') ?>
<?= $this->endSection() ?>
