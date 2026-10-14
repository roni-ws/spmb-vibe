<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Dashboard Admin<?= $this->endSection() ?>
<?= $this->section('page-title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="flex flex-col gap-md">

    <!-- ================================
     WELCOME BANNER
     ================================ -->
    <section class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <div>
            <h1 class="font-headline-md text-headline-md md:text-3xl font-bold text-primary">
                Selamat Datang, <?= esc($admin_username ?? 'Admin') ?>
            </h1>
            <p class="text-on-surface-variant text-body-sm mt-1">
                <?= date('l, d F Y') ?>
                &middot;
                <span class="text-secondary font-semibold">
                    <?= number_format($stats['pending']) ?> pendaftar menunggu review
                </span>
            </p>
        </div>
    </section>

    <!-- ================================
     STAT CARDS WITH TREND
     ================================ -->
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">

        <div class="stat-card bg-surface-container-lowest rounded-2xl p-4 md:p-md border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-3">
                <span class="text-caption font-semibold text-on-surface-variant uppercase tracking-wider">Total Pendaftar</span>
                <div class="bg-primary-container text-on-primary-container p-2 rounded-xl">
                    <span class="material-symbols-outlined text-xl">group</span>
                </div>
            </div>
            <h2 class="stat-value text-headline-lg font-bold text-primary" data-target="<?= $stats['total'] ?>">0</h2>
            <div class="flex items-center gap-1 mt-2">
                <?php if ($trend_pct['total'] > 0): ?>
                    <span class="text-tertiary text-caption flex items-center">▲ <?= $trend_pct['total'] ?>%</span>
                <?php elseif ($trend_pct['total'] < 0): ?>
                    <span class="text-error text-caption flex items-center">▼ <?= abs($trend_pct['total']) ?>%</span>
                <?php else: ?>
                    <span class="text-outline text-caption flex items-center">—</span>
                <?php endif; ?>
                <span class="text-caption text-on-surface-variant">vs bulan lalu</span>
            </div>
        </div>

        <div class="stat-card bg-surface-container-lowest rounded-2xl p-4 md:p-md border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-3">
                <span class="text-caption font-semibold text-on-surface-variant uppercase tracking-wider">Berkas Valid</span>
                <div class="bg-tertiary-container text-on-tertiary-container p-2 rounded-xl">
                    <span class="material-symbols-outlined text-xl">verified</span>
                </div>
            </div>
            <h2 class="stat-value text-headline-lg font-bold text-tertiary" data-target="<?= $stats['valid'] ?>">0</h2>
            <p class="text-caption text-on-surface-variant mt-2 flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">check_circle</span>
                <?= $stats['total'] > 0 ? number_format(($stats['valid'] / $stats['total']) * 100, 1) : 0 ?>% dari total
            </p>
        </div>

        <div class="stat-card bg-surface-container-lowest rounded-2xl p-4 md:p-md border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-3">
                <span class="text-caption font-semibold text-on-surface-variant uppercase tracking-wider">Menunggu Review</span>
                <div class="bg-secondary-container text-on-secondary-container p-2 rounded-xl">
                    <span class="material-symbols-outlined text-xl">pending_actions</span>
                </div>
            </div>
            <h2 class="stat-value text-headline-lg font-bold text-secondary" data-target="<?= $stats['pending'] ?>">0</h2>
            <p class="text-caption text-secondary mt-2 flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">warning</span>
                Perlu perhatian segera
            </p>
        </div>

        <div class="stat-card bg-surface-container-lowest rounded-2xl p-4 md:p-md border border-outline-variant shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-3">
                <span class="text-caption font-semibold text-on-surface-variant uppercase tracking-wider">Tidak Valid</span>
                <div class="bg-error-container text-on-error-container p-2 rounded-xl">
                    <span class="material-symbols-outlined text-xl">cancel</span>
                </div>
            </div>
            <h2 class="stat-value text-headline-lg font-bold text-error" data-target="<?= $stats['invalid'] ?>">0</h2>
            <p class="text-caption text-on-surface-variant mt-2 flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">info</span>
                <?= $stats['total'] > 0 ? number_format(($stats['invalid'] / $stats['total']) * 100, 1) : 0 ?>% dari total
            </p>
        </div>

    </section>

    <!-- ================================
     CHARTS + SIDEBAR
     ================================ -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        <!-- Bar Chart (2/3) -->
        <div class="lg:col-span-2 bg-surface-container-lowest rounded-2xl p-4 md:p-md border border-outline-variant shadow-sm">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-headline-sm font-semibold text-on-surface">Tren Pendaftaran</h3>
                <span class="text-caption text-on-surface-variant bg-surface-container px-3 py-1 rounded-full">6 Bulan Terakhir</span>
            </div>
            <div id="apex-trend-chart" class="w-full" style="min-height: 280px;"></div>
        </div>

        <!-- Right Sidebar (1/3) -->
        <div class="flex flex-col gap-4">

            <!-- Donut Chart -->
            <div class="bg-surface-container-lowest rounded-2xl p-4 md:p-md border border-outline-variant shadow-sm">
                <h3 class="text-headline-sm font-semibold text-on-surface mb-3">Distribusi Status</h3>
                <div id="apex-donut-chart" class="w-full" style="min-height: 200px;"></div>
            </div>

            <!-- Status Info Card -->
            <div class="bg-surface-container-lowest rounded-2xl p-4 md:p-md border border-outline-variant shadow-sm">
                <h3 class="text-headline-sm font-semibold text-on-surface mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">info</span>
                    Status Pendaftaran
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 px-3 rounded-xl bg-surface-container-low">
                        <span class="text-body-sm text-on-surface-variant">Registrasi</span>
                        <span class="flex items-center gap-1.5 font-semibold text-body-sm <?= $is_open === '1' ? 'text-tertiary' : 'text-error' ?>">
                            <span class="w-2 h-2 rounded-full <?= $is_open === '1' ? 'bg-tertiary' : 'bg-error' ?>"></span>
                            <?= $is_open === '1' ? 'Dibuka' : 'Ditutup' ?>
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2 px-3 rounded-xl bg-surface-container-low">
                        <span class="text-body-sm text-on-surface-variant">Tahun Ajaran</span>
                        <span class="font-semibold text-body-sm text-on-surface"><?= esc($tahun_ajaran) ?></span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-surface-container-lowest rounded-2xl p-4 md:p-md border border-outline-variant shadow-sm">
                <h3 class="text-headline-sm font-semibold text-on-surface mb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">bolt</span>
                    Aksi Cepat
                </h3>
                <div class="grid grid-cols-2 gap-2">
                    <a href="<?= base_url('admin/pendaftar') ?>"
                       class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-surface-container-low hover:bg-primary-container hover:text-on-primary-container transition-all text-on-surface-variant">
                        <span class="material-symbols-outlined text-2xl">person_add</span>
                        <span class="text-caption font-semibold">Tambah</span>
                    </a>
                    <a href="<?= base_url('admin/export/csv') ?>"
                       class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-surface-container-low hover:bg-primary-container hover:text-on-primary-container transition-all text-on-surface-variant">
                        <span class="material-symbols-outlined text-2xl">download</span>
                        <span class="text-caption font-semibold">Export</span>
                    </a>
                    <a href="<?= base_url('admin/cetak-data') ?>"
                       class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-surface-container-low hover:bg-primary-container hover:text-on-primary-container transition-all text-on-surface-variant">
                        <span class="material-symbols-outlined text-2xl">print</span>
                        <span class="text-caption font-semibold">Cetak</span>
                    </a>
                    <a href="<?= base_url('admin/pengumuman') ?>"
                       class="flex flex-col items-center gap-1.5 p-3 rounded-xl bg-surface-container-low hover:bg-primary-container hover:text-on-primary-container transition-all text-on-surface-variant">
                        <span class="material-symbols-outlined text-2xl">campaign</span>
                        <span class="text-caption font-semibold">Pengumuman</span>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- ================================
     PENDAFTAR TERBARU — CARD GRID
     ================================ -->
    <section class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 md:px-md pt-4 md:pt-md pb-3 border-b border-outline-variant">
            <div>
                <h3 class="text-headline-sm font-semibold text-on-surface">Pendaftar Terbaru</h3>
                <p class="text-caption text-on-surface-variant mt-0.5">4 calon siswa paling baru</p>
            </div>
            <a href="<?= base_url('admin/pendaftar') ?>"
               class="text-caption font-semibold text-primary hover:underline flex items-center gap-1 transition-colors">
                Lihat Semua
                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 md:p-md">
            <?php if (!empty($recent_students)): ?>
                <?php $avatarColors = ['bg-primary-container text-on-primary-container', 'bg-secondary-container text-on-secondary-container', 'bg-tertiary-container text-on-tertiary-container', 'bg-surface-container-high text-primary']; ?>
                <?php $statusBadges = [
                    'Belum Verifikasi' => 'badge-pending',
                    'Pending'          => 'badge-pending',
                    'Revisi'           => 'badge-invalid',
                    'Tidak Valid'      => 'badge-invalid',
                    'Valid'            => 'badge-valid',
                ]; ?>
                <?php foreach ($recent_students as $i => $student): ?>
                <div class="student-card flex items-start gap-3 p-3 rounded-2xl border border-outline-variant/60 bg-surface/40 hover:bg-surface hover:border-primary/30 hover:shadow-sm transition-all group">
                    <div class="w-10 h-10 rounded-xl <?= $avatarColors[$i % 4] ?> flex items-center justify-center font-bold text-sm uppercase shrink-0">
                        <?= mb_substr($student['nama_lengkap'] ?: $student['username'], 0, 2) ?>
                    </div>
                    <div class="flex-grow min-w-0">
                        <p class="text-body-sm font-semibold text-on-surface truncate">
                            <?= esc($student['nama_lengkap'] ?: $student['username']) ?>
                        </p>
                        <p class="text-caption text-on-surface-variant">
                            <?= date('d M Y', strtotime($student['created_at'])) ?>
                        </p>
                    </div>
                    <a href="<?= base_url('admin/pendaftar') ?>"
                       class="shrink-0 text-caption font-semibold text-primary opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-0.5">
                        Detail
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-10 text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl block mb-2 text-outline">inbox</span>
                    <p class="text-body-sm">Belum ada pendaftar</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
window.DASHBOARD_DATA = {
    trendRaw: <?= json_encode(array_values($monthly_trends ?? [])) ?>,
    statsValid: <?= (int)($stats['valid'] ?? 0) ?>,
    statsPending: <?= (int)($stats['pending'] ?? 0) ?>,
    statsInvalid: <?= (int)($stats['invalid'] ?? 0) ?>
};
</script>
<script src="<?= base_url('js/admin/dashboard.js') ?>"></script>
<?= $this->endSection() ?>
