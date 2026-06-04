<!-- MOBILE SIDEBAR OVERLAY -->
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
            <?= mb_substr($nama_lengkap ?? 'S', 0, 1) ?>
        </div>
        <div>
            <p class="font-semibold"><?= esc($nama_lengkap ?? 'Siswa') ?></p>
            <p class="font-caption text-caption text-on-surface-variant">Calon Siswa</p>
        </div>
    </div>
    <div class="flex flex-col gap-1 flex-grow overflow-y-auto">
        <a href="<?= base_url('siswa/dashboard') ?>" class="flex items-center gap-sm rounded-xl px-sm py-3 <?= $isDashboard ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' ?> transition-all">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' <?= $isDashboard ? '1' : '0' ?>">dashboard</span> Dashboard
        </a>
        <?php if ($can_cetak_bukti): ?>
        <a href="<?= base_url('siswa/cetak-formulir') ?>" class="flex items-center gap-sm rounded-xl px-sm py-3 <?= $isCetakFormulir ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' ?> transition-all">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' <?= $isCetakFormulir ? '1' : '0' ?>">print</span> Cetak Bukti
        </a>
        <?php else: ?>
        <span class="flex items-center gap-sm rounded-xl px-sm py-3 text-on-surface-variant/50 cursor-not-allowed select-none">
            <span class="material-symbols-outlined">lock</span> Cetak Bukti
        </span>
        <?php endif; ?>

        <a href="<?= base_url('logout') ?>" class="flex items-center gap-sm rounded-xl px-sm py-3 text-error hover:bg-error-container mt-auto">
            <span class="material-symbols-outlined">logout</span> Keluar
        </a>
    </div>
</nav>
