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
            <?= mb_substr($admin_username ?? 'A', 0, 1) ?>
        </div>
        <div>
            <p class="font-semibold"><?= esc($admin_username ?? 'Administrator') ?></p>
            <p class="font-caption text-caption text-on-surface-variant"><?= esc($roleLabel) ?></p>
        </div>
    </div>
    <div class="flex flex-col gap-1 flex-grow overflow-y-auto">
        <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center gap-sm rounded-xl px-sm py-3 <?= $isDashboard ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' ?> transition-all">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' <?= $isDashboard ? '1' : '0' ?>">dashboard</span> Dashboard
        </a>
        <a href="<?= base_url('admin/pendaftar') ?>" class="flex items-center gap-sm rounded-xl px-sm py-3 <?= $isPendaftar ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' ?> transition-all">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' <?= $isPendaftar ? '1' : '0' ?>">description</span> Data Pendaftar
        </a>

        <?php if ($shieldUser && ($shieldUser->inGroup('admin'))): ?>
            <a href="<?= base_url('admin/users') ?>" class="flex items-center gap-sm rounded-xl px-sm py-3 <?= $isUsers ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' ?> transition-all">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' <?= $isUsers ? '1' : '0' ?>">group</span> Manajemen User
            </a>
            <a href="<?= base_url('admin/settings') ?>" class="flex items-center gap-sm rounded-xl px-sm py-3 <?= $isSettings ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:bg-surface-container-high' ?> transition-all">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' <?= $isSettings ? '1' : '0' ?>">settings</span> Pengaturan
            </a>
        <?php endif; ?>

        <a href="<?= base_url('logout') ?>" class="flex items-center gap-sm rounded-xl px-sm py-3 text-error hover:bg-error-container mt-auto">
            <span class="material-symbols-outlined">logout</span> Keluar
        </a>
    </div>
</nav>
