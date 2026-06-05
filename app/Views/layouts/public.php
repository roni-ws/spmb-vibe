<?php
$logoText ??= 'SPMB Unggul Bangsa';
$logoImg ??= '';
$is_open ??= true;
?>
<!DOCTYPE html>
<html lang="id" class="light">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?= $this->renderSection('title') ?> - SPMB <?= esc($logoText) ?></title>
    
    <!-- Google Fonts & Material Symbols -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;family=Montserrat:wght@600;700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
        
    <!-- Custom CSS (Compiled Tailwind & Material Design style) -->
    <?= link_tag('css/landing-page.css') ?>
    
    <!-- Render individual view custom CSS section -->
    <?= $this->renderSection('css') ?>
</head>

<body
    class="bg-background text-on-surface antialiased selection:bg-primary-container selection:text-on-primary-container min-h-screen flex flex-col">
    
    <!-- TopAppBar -->
    <header
        class="bg-surface dark:bg-on-surface border-b border-outline-variant dark:border-outline shadow-sm flex justify-between items-center px-gutter w-full h-16 sticky top-0 z-50 shrink-0">
        <?php
        $containerClass = 'flex items-center gap-sm';
        $iconClass      = 'material-symbols-outlined text-primary dark:text-tertiary-fixed-dim text-3xl animate-bounce';
        $iconExtra      = 'data-icon="school" data-weight="fill"';
        $textClass      = 'font-headline-md text-headline-md font-bold text-primary dark:text-white tracking-tight';
        ?>
        <?php include __DIR__ . '/../components/shared/logo-display.php'; ?>
        <!-- Web Menu Links -->
        <div class="hidden md:flex items-center gap-md">
            <a class="font-label-bold text-label-bold text-slate-700 dark:text-slate-300 hover:text-primary hover:dark:text-white hover:bg-surface-container-low hover:dark:bg-slate-800 transition-colors px-4 py-2 rounded-lg"
                href="<?= base_url() ?>">Beranda</a>
            <a class="font-label-bold text-label-bold text-slate-700 dark:text-slate-300 hover:text-primary hover:dark:text-white hover:bg-surface-container-low hover:dark:bg-slate-800 transition-colors px-4 py-2 rounded-lg"
                href="<?= base_url('#alur') ?>">Alur</a>
            <a class="font-label-bold text-label-bold text-slate-700 dark:text-slate-300 hover:text-primary hover:dark:text-white hover:bg-surface-container-low hover:dark:bg-slate-800 transition-colors px-4 py-2 rounded-lg"
                href="<?= base_url('#syarat') ?>">Persyaratan</a>
            <a class="font-label-bold text-label-bold text-slate-700 dark:text-slate-300 hover:text-primary hover:dark:text-white hover:bg-surface-container-low hover:dark:bg-slate-800 transition-colors px-4 py-2 rounded-lg"
                href="<?= base_url('#kontak') ?>">Kontak</a>
        </div>
        <!-- Web Action Buttons -->
        <div class="hidden md:flex items-center gap-2">
            <a href="<?= base_url('login') ?>" class="border border-primary text-primary dark:border-slate-300 dark:text-slate-200 font-label-bold text-label-bold px-4 py-2 rounded-lg hover:bg-surface-container-low hover:dark:bg-slate-800 transition-colors text-sm">
                Login
            </a>
            <?php if ($is_open): ?>
                <a href="<?= base_url('register') ?>" class="bg-primary text-on-primary dark:bg-white dark:text-primary font-label-bold text-label-bold px-4 py-2 rounded-lg hover:opacity-90 transition-opacity text-sm shadow-md">
                    Daftar
                </a>
            <?php endif; ?>
        </div>
        <!-- Mobile Hamburger Button -->
        <button onclick="toggleMobileMenu()" class="md:hidden flex items-center justify-center p-2 rounded-lg text-slate-700 dark:text-slate-300 hover:bg-surface-container-low hover:dark:bg-slate-800 focus:outline-none transition-colors">
            <span id="hamburger-icon" class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 0;">menu</span>
        </button>
    </header>

    <!-- Mobile Navigation Drawer (Collapsible) -->
    <div id="mobile-menu" class="hidden md:hidden bg-surface dark:bg-slate-900 border-b border-outline-variant dark:border-outline/50 px-gutter py-md flex flex-col gap-sm shadow-md sticky top-16 z-40 transition-all duration-300">
        <a class="font-label-bold text-label-bold text-slate-700 dark:text-slate-300 hover:text-primary hover:dark:text-white hover:bg-surface-container-low hover:dark:bg-slate-800/50 transition-colors px-4 py-2.5 rounded-lg flex items-center gap-sm"
            href="<?= base_url() ?>" onclick="toggleMobileMenu()">
            <span class="material-symbols-outlined text-lg">home</span>
            Beranda
        </a>
        <a class="font-label-bold text-label-bold text-slate-700 dark:text-slate-300 hover:text-primary hover:dark:text-white hover:bg-surface-container-low hover:dark:bg-slate-800/50 transition-colors px-4 py-2.5 rounded-lg flex items-center gap-sm"
            href="<?= base_url('#alur') ?>" onclick="toggleMobileMenu()">
            <span class="material-symbols-outlined text-lg">route</span>
            Alur
        </a>
        <a class="font-label-bold text-label-bold text-slate-700 dark:text-slate-300 hover:text-primary hover:dark:text-white hover:bg-surface-container-low hover:dark:bg-slate-800/50 transition-colors px-4 py-2.5 rounded-lg flex items-center gap-sm"
            href="<?= base_url('#syarat') ?>" onclick="toggleMobileMenu()">
            <span class="material-symbols-outlined text-lg">verified</span>
            Persyaratan
        </a>
        <a class="font-label-bold text-label-bold text-slate-700 dark:text-slate-300 hover:text-primary hover:dark:text-white hover:bg-surface-container-low hover:dark:bg-slate-800/50 transition-colors px-4 py-2.5 rounded-lg flex items-center gap-sm"
            href="<?= base_url('#kontak') ?>" onclick="toggleMobileMenu()">
            <span class="material-symbols-outlined text-lg">contact_support</span>
            Kontak
        </a>
        <div class="h-px bg-outline-variant/50 dark:bg-outline/30 my-1"></div>
        <div class="flex gap-sm mt-1">
            <a href="<?= base_url('login') ?>" onclick="toggleMobileMenu()" class="flex-1 text-center border border-primary text-primary dark:border-slate-400 dark:text-slate-300 font-label-bold text-label-bold px-4 py-2.5 rounded-lg hover:bg-surface-container-low hover:dark:bg-slate-800/50 transition-colors text-sm">
                Login
            </a>
            <?php if ($is_open): ?>
                <a href="<?= base_url('register') ?>" onclick="toggleMobileMenu()" class="flex-1 text-center bg-primary text-on-primary dark:bg-white dark:text-primary font-label-bold text-label-bold px-4 py-2.5 rounded-lg hover:opacity-90 dark:hover:opacity-90 transition-opacity text-sm shadow-md">
                    Daftar
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Main Content Section -->
    <?= $this->renderSection('content') ?>

    <!-- Footer -->
    <footer id="kontak" class="bg-inverse-surface text-inverse-on-surface py-12 mt-auto z-10 shrink-0">
        <div class="max-w-[1200px] mx-auto px-gutter grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="w-full flex flex-col">
                <?php
                $containerClass = 'flex items-center gap-2 mb-4';
                $imgWrapClass   = 'w-8 h-8 rounded-lg overflow-hidden bg-slate-50 flex items-center justify-center shrink-0';
                $imgClass       = 'w-full h-full object-contain p-0.5';
                $iconClass      = 'material-symbols-outlined text-tertiary-fixed-dim text-3xl';
                $iconExtra      = 'data-icon="school" data-weight="fill"';
                $textClass      = 'font-headline-sm text-headline-sm font-bold';
                ?>
                <?php include __DIR__ . '/../components/shared/logo-display.php'; ?>
                <p class="font-body-sm text-body-sm text-outline-variant w-full text-justify"><?= esc($footer_desc ?? 'Membentuk generasi pemimpin masa depan yang beriman, cerdas, kreatif, mandiri, dan berwawasan global.') ?></p>
            </div>
            <div class="w-full flex flex-col">
                <h4 class="font-label-bold text-label-bold mb-4 text-white">Hubungi Panitia SPMB</h4>
                <ul class="space-y-2 font-body-sm text-body-sm text-outline-variant">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-sm"
                            data-icon="call">call</span> <?= esc($footer_phone ?? '+62 21 555 0123') ?></li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-sm"
                            data-icon="mail">mail</span> <?= esc($footer_email ?? 'spmb@unggulbangsa.sch.id') ?></li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-sm"
                            data-icon="location_on">location_on</span> <?= esc($footer_address ?? 'Jl. Pendidikan No. 1, Jakarta') ?></li>
                </ul>
            </div>
            <div class="w-full flex flex-col">
                <h4 class="font-label-bold text-label-bold mb-4 text-white">Tautan Penting</h4>
                <ul class="space-y-2 font-body-sm text-body-sm text-outline-variant">
                    <li><a class="hover:text-white transition-colors" href="<?= base_url('login') ?>">Portal Pendaftaran</a></li>
                    <li><a class="hover:text-white transition-colors" href="#">Kebijakan Privasi</a></li>
                    <li><a class="hover:text-white transition-colors" href="#">Tanya Jawab (FAQ)</a></li>
                </ul>
            </div>
        </div>
        <div
            class="max-w-[1200px] mx-auto px-gutter mt-12 pt-8 border-t border-outline/30 text-center font-caption text-caption text-outline-variant">
            © <?= date('Y') ?> <?= esc($logoText) ?>. Hak Cipta Dilindungi.
        </div>
    </footer>

    <!-- Additional page script section -->
    <?= $this->renderSection('scripts') ?>

    <!-- Mobile Drawer Interactivity Script -->
    <?= script_tag('js/public/main.js') ?>

    <?php
    $flashSuccess = session()->getFlashdata('success');
    $flashError   = session()->getFlashdata('error');
    $flashMessage = session()->getFlashdata('message');
    $flashErrors  = session()->getFlashdata('errors');
    ?>
    <?php if ($flashSuccess || $flashError || $flashMessage || $flashErrors): ?>
    <script id="flash-toast-data" type="application/json"><?= json_encode([
        'success' => $flashSuccess,
        'error'   => $flashError,
        'message' => $flashMessage,
        'errors'  => is_array($flashErrors) ? $flashErrors : [],
    ]) ?></script>
    <?php endif; ?>

    <script src="<?= base_url('js/core/toast.js') ?>"></script>
</body>

</html>
