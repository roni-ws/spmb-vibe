<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Dashboard Admin<?= $this->endSection() ?>
<?= $this->section('page-title') ?>Overview<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ================================
     WELCOME BANNER
     ================================ -->
<section class="mb-md">
    <h1 class="font-headline-md text-headline-md md:text-3xl font-bold text-primary mb-1">
        Selamat Datang, <?= esc($admin_username ?? 'Admin') ?> 👋
    </h1>
    <p class="text-on-surface-variant text-body-sm">
        Berikut ringkasan status penerimaan siswa baru saat ini.
    </p>
</section>

<!-- ================================
     STATS CARDS
     ================================ -->
<section class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-md">

    <!-- Total Pendaftar -->
    <div class="bg-surface-container-lowest rounded-2xl p-md border border-outline-variant shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between min-h-[120px]">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-body-sm text-on-surface-variant">Total Pendaftar</p>
                <h2 id="stat-total" class="text-headline-md font-bold text-primary mt-1">
                    <?= number_format($stats['total']) ?>
                </h2>
            </div>
            <div class="bg-primary-container text-on-primary-container p-2 rounded-xl">
                <span class="material-symbols-outlined">group</span>
            </div>
        </div>
        <p class="text-caption text-on-surface-variant flex items-center gap-1 mt-2">
            <span class="material-symbols-outlined text-sm text-secondary">bar_chart</span>
            Semua pendaftar terdaftar
        </p>
    </div>

    <!-- Berkas Valid -->
    <div class="bg-surface-container-lowest rounded-2xl p-md border border-outline-variant shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between min-h-[120px]">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-body-sm text-on-surface-variant">Berkas Valid</p>
                <h2 id="stat-valid" class="text-headline-md font-bold text-tertiary mt-1">
                    <?= number_format($stats['valid']) ?>
                </h2>
            </div>
            <div class="bg-tertiary-container text-on-tertiary-container p-2 rounded-xl">
                <span class="material-symbols-outlined">verified</span>
            </div>
        </div>
        <p class="text-caption text-on-surface-variant flex items-center gap-1 mt-2">
            <span class="material-symbols-outlined text-sm">check_circle</span>
            <?= $stats['total'] > 0 ? number_format(($stats['valid'] / $stats['total']) * 100, 1) : 0 ?>% dari total
        </p>
    </div>

    <!-- Menunggu Review -->
    <div class="bg-surface-container-lowest rounded-2xl p-md border border-outline-variant shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between min-h-[120px]">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-body-sm text-on-surface-variant">Menunggu Review</p>
                <h2 id="stat-pending" class="text-headline-md font-bold text-secondary mt-1">
                    <?= number_format($stats['pending']) ?>
                </h2>
            </div>
            <div class="bg-secondary-container text-on-secondary-container p-2 rounded-xl">
                <span class="material-symbols-outlined">pending_actions</span>
            </div>
        </div>
        <p class="text-caption text-secondary flex items-center gap-1 mt-2">
            <span class="material-symbols-outlined text-sm">warning</span>
            Perlu perhatian
        </p>
    </div>

    <!-- Tidak Valid -->
    <div class="bg-surface-container-lowest rounded-2xl p-md border border-outline-variant shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between min-h-[120px]">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-body-sm text-on-surface-variant">Tidak Valid / Revisi</p>
                <h2 id="stat-invalid" class="text-headline-md font-bold text-error mt-1">
                    <?= number_format($stats['invalid']) ?>
                </h2>
            </div>
            <div class="bg-error-container text-on-error-container p-2 rounded-xl">
                <span class="material-symbols-outlined">cancel</span>
            </div>
        </div>
        <p class="text-caption text-on-surface-variant flex items-center gap-1 mt-2">
            <span class="material-symbols-outlined text-sm">info</span>
            <?= $stats['total'] > 0 ? number_format(($stats['invalid'] / $stats['total']) * 100, 1) : 0 ?>% dari total
        </p>
    </div>

</section>

<!-- ================================
     CHART + RECENT ACTIVITY
     ================================ -->
<section class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-md">

    <!-- ApexCharts Bar Chart -->
    <div class="lg:col-span-2 bg-surface-container-lowest rounded-2xl p-md border border-outline-variant shadow-sm flex flex-col">
        <div class="flex justify-between items-center mb-md">
            <h3 class="text-headline-sm font-semibold text-on-surface">Tren Pendaftaran</h3>
            <span class="text-caption text-on-surface-variant bg-surface-container px-3 py-1 rounded-full">6 Bulan Terakhir</span>
        </div>
        <div id="apex-trend-chart" class="w-full min-h-[280px]"></div>
    </div>

    <!-- Status Donut Chart + Recent Activity -->
    <div class="flex flex-col gap-4">

        <!-- Donut Chart Status -->
        <div class="bg-surface-container-lowest rounded-2xl p-md border border-outline-variant shadow-sm">
            <h3 class="text-headline-sm font-semibold text-on-surface mb-sm">Distribusi Status</h3>
            <div id="apex-donut-chart" class="w-full min-h-[200px]"></div>
        </div>

        <!-- Recent Registrations -->
        <div class="bg-surface-container-lowest rounded-2xl p-md border border-outline-variant shadow-sm flex flex-col flex-grow">
            <div class="flex justify-between items-center mb-sm">
                <h3 class="text-headline-sm font-semibold text-on-surface">Pendaftar Terbaru</h3>
                <a href="<?= base_url('admin/pendaftar') ?>" class="text-caption text-primary hover:underline">Lihat Semua</a>
            </div>
            <div class="flex flex-col gap-sm overflow-y-auto max-h-[260px] pr-1">
                <?php if (!empty($recent_students)): ?>
                    <?php foreach ($recent_students as $s): ?>
                    <div class="flex items-center gap-sm p-2 rounded-xl hover:bg-surface-container-low transition-colors">
                        <div class="w-9 h-9 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-sm uppercase shrink-0">
                            <?= mb_substr($s['nama_lengkap'] ?: $s['username'], 0, 1) ?>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-body-sm font-semibold text-on-surface truncate">
                                <?= esc($s['nama_lengkap'] ?: $s['username']) ?>
                            </p>
                            <p class="text-caption text-on-surface-variant">
                                <?= date('d M Y', strtotime($s['created_at'])) ?>
                            </p>
                        </div>
                        <span class="material-symbols-outlined text-on-surface-variant text-sm ml-auto">person_add</span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-6 text-on-surface-variant text-body-sm">
                        <span class="material-symbols-outlined text-4xl mb-2 block">inbox</span>
                        Belum ada pendaftar
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</section>

<!-- ================================
     SHORTCUT — DATA PENDAFTAR
     ================================ -->
<section class="bg-surface-container-lowest rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
    <div class="flex flex-wrap justify-between items-center p-md gap-2 border-b border-outline-variant">
        <div>
            <h3 class="text-headline-sm font-semibold text-on-surface">Pendaftar Terbaru</h3>
            <p class="text-caption text-on-surface-variant mt-0.5">4 pendaftar paling baru</p>
        </div>
        <a href="<?= base_url('admin/pendaftar') ?>"
           class="flex items-center gap-1 bg-primary text-on-primary font-semibold text-body-sm px-4 py-2 rounded-xl hover:opacity-90 transition-opacity">
            <span class="material-symbols-outlined text-[18px]">open_in_new</span> Lihat Semua Data
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low">
                    <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Calon Siswa</th>
                    <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider hidden md:table-cell">Tgl Daftar</th>
                    <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($recent_students)): ?>
                    <?php $colors = ['bg-primary-container text-on-primary-container','bg-secondary-container text-on-secondary-container','bg-tertiary-container text-on-tertiary-container','bg-surface-container-high text-primary']; ?>
                    <?php foreach ($recent_students as $i => $s): ?>
                    <tr class="border-t border-outline-variant hover:bg-surface-container-lowest transition-colors">
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full <?= $colors[$i % 4] ?> flex items-center justify-center text-xs font-bold uppercase shrink-0">
                                    <?= mb_substr($s['nama_lengkap'] ?: $s['username'], 0, 2) ?>
                                </div>
                                <span class="text-body-sm font-semibold text-on-surface">
                                    <?= esc($s['nama_lengkap'] ?: $s['username']) ?>
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-body-sm text-on-surface-variant hidden md:table-cell">
                            <?= date('d M Y', strtotime($s['created_at'])) ?>
                        </td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-caption font-semibold bg-secondary-container text-on-secondary-container">
                                <span class="material-symbols-outlined text-[13px]">pending_actions</span> Pending
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right">
                            <a href="<?= base_url('admin/pendaftar') ?>"
                               class="text-primary text-caption font-semibold hover:underline inline-flex items-center gap-1">
                                Detail <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-on-surface-variant text-body-sm">
                            <span class="material-symbols-outlined text-4xl block mb-2 text-outline">inbox</span>
                            Belum ada pendaftar
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>


<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
(function () {
    // ── Trend Data (dari PHP) ──────────────────────────────────────
    const trendRaw = <?= json_encode(array_values($monthly_trends ?? [])) ?>;
    const trendLabels = trendRaw.map(d => d.name);
    const trendData   = trendRaw.map(d => d.count);

    // ── Stats (dari PHP) ──────────────────────────────────────────
    const statsValid   = <?= (int)($stats['valid']   ?? 0) ?>;
    const statsPending = <?= (int)($stats['pending'] ?? 0) ?>;
    const statsInvalid = <?= (int)($stats['invalid'] ?? 0) ?>;

    // ========================================
    // 1. ApexCharts — Bar Chart Tren Pendaftaran
    // ========================================
    const trendOptions = {
        chart: {
            type: 'bar',
            height: 280,
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif',
            animations: { enabled: true, easing: 'easeinout', speed: 600 }
        },
        series: [{
            name: 'Pendaftar',
            data: trendData
        }],
        xaxis: {
            categories: trendLabels,
            labels: { style: { colors: '#43474e', fontSize: '12px' } },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                style: { colors: '#43474e', fontSize: '12px' },
                formatter: v => Math.floor(v)
            },
            min: 0
        },
        plotOptions: {
            bar: {
                borderRadius: 6,
                columnWidth: '55%',
                dataLabels: { position: 'top' }
            }
        },
        dataLabels: {
            enabled: true,
            offsetY: -18,
            style: { fontSize: '11px', colors: ['#002045'], fontWeight: 600 },
            formatter: v => v > 0 ? v : ''
        },
        colors: ['#002045'],
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.5,
                gradientToColors: ['#1a365d'],
                opacityFrom: 1,
                opacityTo: 0.75,
                stops: [0, 100]
            }
        },
        grid: {
            borderColor: '#e7eeff',
            strokeDashArray: 4,
            xaxis: { lines: { show: false } }
        },
        tooltip: {
            y: { formatter: v => v + ' pendaftar' },
            theme: 'light'
        }
    };

    const trendChart = new ApexCharts(document.querySelector('#apex-trend-chart'), trendOptions);
    trendChart.render();

    // ========================================
    // 2. ApexCharts — Donut Chart Distribusi Status
    // ========================================
    const donutOptions = {
        chart: {
            type: 'donut',
            height: 200,
            fontFamily: 'Inter, sans-serif',
            animations: { enabled: true, easing: 'easeinout', speed: 600 }
        },
        series: [statsValid, statsPending, statsInvalid],
        labels: ['Valid', 'Pending', 'Tidak Valid'],
        colors: ['#002625', '#7d5700', '#ba1a1a'],
        dataLabels: { enabled: false },
        legend: {
            position: 'bottom',
            fontSize: '12px',
            labels: { colors: '#43474e' }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '13px',
                            fontWeight: 600,
                            color: '#43474e',
                            formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                        }
                    }
                }
            }
        },
        tooltip: {
            y: { formatter: v => v + ' pendaftar' },
            theme: 'light'
        }
    };

    const donutChart = new ApexCharts(document.querySelector('#apex-donut-chart'), donutOptions);
    donutChart.render();

    // ========================================
    // 3. DataTables
    // ========================================
    $(document).ready(function () {
        $('#pendaftar-table').DataTable({
            responsive: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json'
            },
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: [6] }
            ],
            order: [[3, 'desc']]
        });
    });
})();
</script>
<?= $this->endSection() ?>
