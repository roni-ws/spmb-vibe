<!-- BOTTOM NAV (Mobile) -->
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
    <?php if ($shieldUser && ($shieldUser->inGroup('admin'))): ?>
        <a href="<?= base_url('admin/settings') ?>" class="flex flex-col items-center gap-0.5 <?= $isSettings ? 'text-on-secondary-container bg-secondary-container' : 'text-on-surface-variant hover:bg-surface-container-high' ?> px-4 py-1 rounded-full transition-all">
            <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' <?= $isSettings ? '1' : '0' ?>">settings</span>
            Setting
        </a>
    <?php endif; ?>

    <a href="<?= base_url('logout') ?>" class="flex flex-col items-center gap-0.5 text-error px-4 py-1 rounded-full">
        <span class="material-symbols-outlined text-xl">logout</span>
        Keluar
    </a>
</nav>
