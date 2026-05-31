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

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary":                    "#002045",
                        "on-primary":                 "#ffffff",
                        "primary-container":          "#1a365d",
                        "on-primary-container":       "#86a0cd",
                        "primary-fixed":              "#d6e3ff",
                        "primary-fixed-dim":          "#adc7f7",
                        "inverse-primary":            "#adc7f7",
                        "secondary":                  "#7d5700",
                        "on-secondary":               "#ffffff",
                        "secondary-container":        "#ffc250",
                        "on-secondary-container":     "#725000",
                        "tertiary":                   "#002625",
                        "on-tertiary":                "#ffffff",
                        "tertiary-container":         "#003d3c",
                        "on-tertiary-container":      "#4cadab",
                        "error":                      "#ba1a1a",
                        "on-error":                   "#ffffff",
                        "error-container":            "#ffdad6",
                        "on-error-container":         "#93000a",
                        "background":                 "#f9f9ff",
                        "on-background":              "#121c2c",
                        "surface":                    "#f9f9ff",
                        "on-surface":                 "#121c2c",
                        "surface-variant":            "#d9e3f9",
                        "on-surface-variant":         "#43474e",
                        "surface-container-lowest":   "#ffffff",
                        "surface-container-low":      "#f0f3ff",
                        "surface-container":          "#e7eeff",
                        "surface-container-high":     "#dee8ff",
                        "surface-container-highest":  "#d9e3f9",
                        "surface-dim":                "#d0daf0",
                        "surface-bright":             "#f9f9ff",
                        "outline":                    "#74777f",
                        "outline-variant":            "#c4c6cf",
                        "inverse-surface":            "#273141",
                        "inverse-on-surface":         "#ebf1ff",
                        "surface-tint":               "#455f88",
                    },
                    spacing: {
                        "xs":  "4px",
                        "sm":  "12px",
                        "md":  "24px",
                        "lg":  "48px",
                        "xl":  "80px",
                        "gutter": "24px",
                        "container-max": "1200px",
                    },
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        "lg":   "0.5rem",
                        "xl":   "0.75rem",
                        "2xl":  "1rem",
                        "full": "9999px",
                    },
                    fontFamily: {
                        "headline-md":      ["Montserrat"],
                        "headline-sm":      ["Montserrat"],
                        "display-lg":       ["Montserrat"],
                        "body-md":          ["Inter"],
                        "body-sm":          ["Inter"],
                        "body-lg":          ["Inter"],
                        "caption":          ["Inter"],
                        "label-bold":       ["Inter"],
                    },
                    fontSize: {
                        "display-lg":   ["48px", { lineHeight: "1.2", letterSpacing: "-0.02em", fontWeight: "700" }],
                        "headline-md":  ["24px", { lineHeight: "1.4", fontWeight: "600" }],
                        "headline-sm":  ["20px", { lineHeight: "1.4", fontWeight: "600" }],
                        "body-lg":      ["18px", { lineHeight: "1.6", fontWeight: "400" }],
                        "body-md":      ["16px", { lineHeight: "1.6", fontWeight: "400" }],
                        "body-sm":      ["14px", { lineHeight: "1.5", fontWeight: "400" }],
                        "caption":      ["12px", { lineHeight: "1.4", fontWeight: "400" }],
                        "label-bold":   ["14px", { lineHeight: "1.2", letterSpacing: "0.05em", fontWeight: "600" }],
                    }
                }
            }
        }
    </script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" />

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

            <a href="<?= base_url('admin/export-csv') ?>"
               class="sidebar-link flex items-center gap-sm rounded-xl px-sm py-3 transition-all text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high">
                <span class="material-symbols-outlined">download</span>
                Export CSV
            </a>

            <a href="<?= base_url('admin/export-print') ?>"
               class="sidebar-link flex items-center gap-sm rounded-xl px-sm py-3 transition-all text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high">
                <span class="material-symbols-outlined">print</span>
                Cetak Data
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
            <a href="<?= base_url('admin/export-csv') ?>" class="flex items-center gap-sm rounded-xl px-sm py-3 text-on-surface-variant hover:bg-surface-container-high transition-all">
                <span class="material-symbols-outlined">download</span> Export CSV
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
        <a href="<?= base_url('admin/export-csv') ?>" class="flex flex-col items-center gap-0.5 text-on-surface-variant px-4 py-1 rounded-full hover:bg-surface-container-high transition-all">
            <span class="material-symbols-outlined text-xl">download</span>
            Export
        </a>
        <a href="<?= base_url('logout') ?>" class="flex flex-col items-center gap-0.5 text-error px-4 py-1 rounded-full">
            <span class="material-symbols-outlined text-xl">logout</span>
            Keluar
        </a>
    </nav>

    <!-- jQuery + DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.0/dist/apexcharts.min.js"></script>

    <!-- Admin JS (sidebar toggle) -->
    <?= script_tag('js/admin.js') ?>

    <?= $this->renderSection('scripts') ?>
</body>

</html>
