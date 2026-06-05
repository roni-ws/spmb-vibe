<!-- BOTTOM NAV (Mobile Landing Page) -->
<nav class="bg-surface/95 dark:bg-slate-900/95 border-t border-outline-variant/60 dark:border-outline/30 shadow-lg fixed bottom-0 w-full z-40
            flex justify-around items-center py-2 md:hidden text-caption font-caption backdrop-blur-md">
    
    <!-- Beranda -->
    <a href="<?= base_url() ?>" class="flex flex-col items-center gap-0.5 text-on-surface-variant hover:text-primary dark:text-slate-400 dark:hover:text-white transition-all">
        <span class="material-symbols-outlined text-xl">home</span>
        <span>Beranda</span>
    </a>

    <!-- Alur -->
    <a href="#alur" class="flex flex-col items-center gap-0.5 text-on-surface-variant hover:text-primary dark:text-slate-400 dark:hover:text-white transition-all">
        <span class="material-symbols-outlined text-xl">route</span>
        <span>Alur</span>
    </a>

    <!-- FAB Tengah (Daftar / Login / Akun) -->
    <div class="relative flex flex-col items-center">
        <?php if (auth()->loggedIn()): ?>
            <?php
            $dashboardUrl = base_url('siswa/dashboard');
            if (auth()->user()->inGroup('admin', 'panitia')) {
                $dashboardUrl = base_url('admin/dashboard');
            }
            ?>
            <a href="<?= $dashboardUrl ?>" 
               class="w-14 h-14 bg-secondary-container text-on-secondary-container dark:bg-secondary dark:text-on-secondary rounded-full flex items-center justify-center shadow-lg absolute -top-8 hover:scale-105 active:scale-95 transition-transform z-50 border-4 border-surface dark:border-slate-900">
                <span class="material-symbols-outlined text-2xl font-bold">dashboard</span>
            </a>
            <span class="text-caption font-caption text-on-surface-variant dark:text-slate-400 mt-7">Akun</span>
        <?php elseif ($is_open): ?>
            <a href="<?= base_url('register') ?>" 
               class="w-14 h-14 bg-primary text-on-primary dark:bg-white dark:text-primary rounded-full flex items-center justify-center shadow-lg absolute -top-8 hover:scale-105 active:scale-95 transition-transform z-50 border-4 border-surface dark:border-slate-900">
                <span class="material-symbols-outlined text-2xl font-bold">person_add</span>
            </a>
            <span class="text-caption font-caption text-on-surface-variant dark:text-slate-400 mt-7">Daftar</span>
        <?php else: ?>
            <a href="<?= base_url('login') ?>" 
               class="w-14 h-14 bg-surface-container-high text-on-surface-variant border border-outline-variant rounded-full flex items-center justify-center shadow-md absolute -top-8 hover:scale-105 active:scale-95 transition-transform z-50 border-4 border-surface dark:border-slate-900">
                <span class="material-symbols-outlined text-2xl">login</span>
            </a>
            <span class="text-caption font-caption text-on-surface-variant dark:text-slate-400 mt-7">Login</span>
        <?php endif; ?>
    </div>

    <!-- Syarat -->
    <a href="#syarat" class="flex flex-col items-center gap-0.5 text-on-surface-variant hover:text-primary dark:text-slate-400 dark:hover:text-white transition-all">
        <span class="material-symbols-outlined text-xl">verified</span>
        <span>Syarat</span>
    </a>

    <!-- Kontak -->
    <a href="#kontak" class="flex flex-col items-center gap-0.5 text-on-surface-variant hover:text-primary dark:text-slate-400 dark:hover:text-white transition-all">
        <span class="material-symbols-outlined text-xl">contact_support</span>
        <span>Kontak</span>
    </a>
</nav>
