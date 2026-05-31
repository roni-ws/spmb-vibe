<?php
$logoText = \App\Models\SettingsModel::getSetting('logo_text', 'SPMB Unggul Bangsa');
$logoImg  = \App\Models\SettingsModel::getSetting('logo_img', '');
?>
<!DOCTYPE html>
<html lang="id" class="light">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?= $this->renderSection('title') ?> - Admin | <?= esc($logoText) ?></title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Compiled Tailwind CSS & Theme (Local) -->
    <?= link_tag('css/landingpage.css') ?>

    <!-- DataTables CSS (Local) -->
    <link rel="stylesheet" href="<?= base_url('vendor/datatables/css/jquery.dataTables.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('vendor/datatables/css/responsive.dataTables.min.css') ?>" />

    <!-- Admin CSS -->
    <?= link_tag('css/admin.css') ?>

    <?= $this->renderSection('css') ?>
</head>

<body class="bg-background text-on-background flex overflow-hidden min-h-screen font-body-md text-body-md">

    <!-- ============================
         SIDEBAR (Desktop)
         ============================ -->
    <nav id="sidebar"
        class="hidden md:flex bg-surface-container-low text-on-surface font-label-bold text-label-bold
               h-screen w-72 rounded-r-2xl shadow-md fixed left-0 top-0 z-40 flex-col p-md">

        <!-- Logo -->
        <div class="flex items-center gap-sm mb-md">
            <?php if ($logoImg && file_exists(FCPATH . $logoImg)): ?>
                <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center shrink-0 shadow-sm border border-outline-variant/40">
                    <img src="<?= base_url($logoImg) ?>" class="w-full h-full object-contain p-1" alt="Logo">
                </div>
            <?php else: ?>
                <span class="material-symbols-outlined text-primary text-3xl">school</span>
            <?php endif; ?>
            <span class="font-headline-sm text-headline-sm text-primary leading-tight"><?= esc($logoText) ?></span>
        </div>

        <!-- Admin Profile -->
        <div class="flex items-center gap-sm mb-md pb-md border-b border-outline-variant">
            <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center shrink-0 text-lg font-bold uppercase">
                <?= mb_substr($admin_username ?? 'A', 0, 1) ?>
            </div>
            <div class="overflow-hidden">
                <p class="font-semibold text-on-surface truncate"><?= esc($admin_username ?? 'Administrator') ?></p>
                <p class="font-caption text-caption text-on-surface-variant">Panitia SPMB</p>
            </div>
        </div>

        <!-- Nav Links -->
        <?php
            $currentUri = service('uri')->getPath();
            $isDashboard = ($currentUri === 'admin/dashboard' || $currentUri === 'admin');
            $isPendaftar = str_contains($currentUri, 'pendaftar') || str_contains($currentUri, 'admin/verify') || str_contains($currentUri, 'admin/delete');
            $isExport    = str_contains($currentUri, 'export');
        ?>
        <div class="flex flex-col gap-1 flex-grow overflow-y-auto">
            <p class="font-caption text-caption text-on-surface-variant uppercase tracking-wider px-sm mb-1">Menu Utama</p>

            <a href="<?= base_url('admin/dashboard') ?>"
               class="sidebar-link flex items-center gap-sm rounded-xl px-sm py-3 transition-all
                      <?= $isDashboard ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' ?>">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' <?= $isDashboard ? '1' : '0' ?>">dashboard</span>
                Dashboard
            </a>

            <a href="<?= base_url('admin/pendaftar') ?>"
               class="sidebar-link flex items-center gap-sm rounded-xl px-sm py-3 transition-all
                      <?= $isPendaftar ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' ?>">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' <?= $isPendaftar ? '1' : '0' ?>">description</span>
                Data Pendaftar
            </a>



            <div class="mt-auto pt-md border-t border-outline-variant">
                <a href="<?= base_url('logout') ?>"
                   class="sidebar-link flex items-center gap-sm rounded-xl px-sm py-3 text-error hover:bg-error-container hover:text-on-error-container transition-all">
                    <span class="material-symbols-outlined">logout</span>
                    Keluar
                </a>
            </div>
        </div>
    </nav>

    <!-- ============================
         MAIN CONTENT WRAPPER
         ============================ -->
    <div class="flex-grow md:ml-72 flex flex-col h-screen overflow-y-auto w-full">

        <!-- TOP APP BAR -->
        <header class="bg-surface border-b border-outline-variant shadow-sm flex justify-between items-center
                       px-md md:px-lg py-3 w-full sticky top-0 z-30">
            <!-- Mobile: Hamburger + Logo -->
            <div class="flex items-center gap-sm md:hidden">
                <button id="mobile-menu-btn" class="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container transition-colors" onclick="toggleSidebar()">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <span class="font-headline-sm text-headline-sm font-bold text-primary"><?= esc($logoText) ?></span>
            </div>
            <!-- Desktop: Page title -->
            <div class="hidden md:flex items-center gap-sm">
                <span class="font-headline-md text-headline-md font-bold text-primary">
                    <?= $this->renderSection('page-title') ?>
                </span>
            </div>
            <!-- Right actions -->
            <div class="flex items-center gap-sm">
                <button class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container-low transition-colors relative">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <div class="w-9 h-9 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-sm uppercase cursor-pointer">
                    <?= mb_substr($admin_username ?? 'A', 0, 1) ?>
                </div>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="p-4 md:p-lg flex-grow w-full">
            <?= $this->renderSection('content') ?>
        </main>

        <!-- Mobile bottom padding -->
        <div class="h-20 md:hidden w-full"></div>
    </div>

    <!-- ============================
         MOBILE SIDEBAR OVERLAY
         ============================ -->
    <div id="sidebar-overlay"
         class="fixed inset-0 bg-black/40 z-30 hidden md:hidden"
         onclick="toggleSidebar()"></div>

    <!-- Mobile Sidebar -->
    <nav id="mobile-sidebar"
         class="fixed left-0 top-0 h-screen w-72 bg-surface-container-low text-on-surface
                z-50 flex flex-col p-md shadow-xl md:hidden
                -translate-x-full transition-transform duration-300">
        <div class="flex items-center gap-sm mb-md">
            <span class="material-symbols-outlined text-primary text-3xl">school</span>
            <span class="font-headline-sm text-headline-sm text-primary"><?= esc($logoText) ?></span>
        </div>
        <div class="flex items-center gap-sm mb-md pb-md border-b border-outline-variant">
            <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold uppercase">
                <?= mb_substr($admin_username ?? 'A', 0, 1) ?>
            </div>
            <div>
                <p class="font-semibold"><?= esc($admin_username ?? 'Administrator') ?></p>
                <p class="font-caption text-caption text-on-surface-variant">Panitia SPMB</p>
            </div>
        </div>
        <div class="flex flex-col gap-1 flex-grow overflow-y-auto">
            <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-sm rounded-xl px-sm py-3 bg-secondary-container text-on-secondary-container font-bold">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1">dashboard</span> Dashboard
            </a>
            <a href="<?= base_url('admin/pendaftar') ?>" class="flex items-center gap-sm rounded-xl px-sm py-3 text-on-surface-variant hover:bg-surface-container-high transition-all">
                <span class="material-symbols-outlined">description</span> Data Pendaftar
            </a>

            <a href="<?= base_url('logout') ?>" class="flex items-center gap-sm rounded-xl px-sm py-3 text-error hover:bg-error-container mt-auto">
                <span class="material-symbols-outlined">logout</span> Keluar
            </a>
        </div>
    </nav>

    <!-- ============================
         BOTTOM NAV (Mobile)
         ============================ -->
    <nav class="bg-surface border-t border-outline-variant shadow-lg fixed bottom-0 w-full z-20
                flex justify-around items-center py-2 md:hidden text-caption font-caption">
        <a href="<?= base_url('admin/dashboard') ?>" class="flex flex-col items-center gap-0.5 px-4 py-1 rounded-full transition-all <?= $isDashboard ? 'text-on-secondary-container bg-secondary-container' : 'text-on-surface-variant hover:bg-surface-container-high' ?>">
            <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' <?= $isDashboard ? '1' : '0' ?>">dashboard</span>
            Dashboard
        </a>
        <a href="<?= base_url('admin/pendaftar') ?>" class="flex flex-col items-center gap-0.5 px-4 py-1 rounded-full transition-all <?= $isPendaftar ? 'text-on-secondary-container bg-secondary-container' : 'text-on-surface-variant hover:bg-surface-container-high' ?>">
            <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' <?= $isPendaftar ? '1' : '0' ?>">description</span>
            Pendaftar
        </a>

        <a href="<?= base_url('logout') ?>" class="flex flex-col items-center gap-0.5 text-error px-4 py-1 rounded-full">
            <span class="material-symbols-outlined text-xl">logout</span>
            Keluar
        </a>
    </nav>

    <!-- jQuery + DataTables JS (Local) -->
    <script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('vendor/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('vendor/datatables/js/dataTables.responsive.min.js') ?>"></script>

    <!-- ApexCharts (Local) -->
    <script src="<?= base_url('vendor/apexcharts/apexcharts.min.js') ?>"></script>

    <!-- Admin JS (sidebar toggle) -->
    <?= script_tag('js/admin.js') ?>

    <?= $this->renderSection('scripts') ?>
</body>

</html>
