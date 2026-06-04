<!-- BOTTOM NAV (Mobile) -->
<nav class="bg-surface border-t border-outline-variant shadow-lg fixed bottom-0 w-full z-20
            flex justify-around items-center py-2 md:hidden text-caption font-caption">
    <a href="<?= base_url('siswa/dashboard') ?>" class="flex flex-col items-center gap-0.5 px-4 py-1 rounded-full transition-all <?= $isDashboard ? 'text-on-secondary-container bg-secondary-container' : 'text-on-surface-variant hover:bg-surface-container-high' ?>">
        <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' <?= $isDashboard ? '1' : '0' ?>">dashboard</span>
        Dashboard
    </a>
    <?php if ($can_cetak_bukti): ?>
    <a href="<?= base_url('siswa/cetak-formulir') ?>" class="flex flex-col items-center gap-0.5 px-4 py-1 rounded-full transition-all <?= $isCetakFormulir ? 'text-on-secondary-container bg-secondary-container' : 'text-on-surface-variant hover:bg-surface-container-high' ?>">
        <span class="material-symbols-outlined text-xl" style="font-variation-settings:'FILL' <?= $isCetakFormulir ? '1' : '0' ?>">print</span>
        Cetak Bukti
    </a>
    <?php else: ?>
    <span class="flex flex-col items-center gap-0.5 px-4 py-1 rounded-full text-on-surface-variant/40 cursor-not-allowed select-none">
        <span class="material-symbols-outlined text-xl">lock</span>
        Cetak Bukti
    </span>
    <?php endif; ?>

    <a href="<?= base_url('logout') ?>" class="flex flex-col items-center gap-0.5 text-error px-4 py-1 rounded-full">
        <span class="material-symbols-outlined text-xl">logout</span>
        Keluar
    </a>
</nav>
