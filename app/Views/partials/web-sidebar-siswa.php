<nav id="sidebar"
    class="hidden md:flex bg-surface-container-low text-on-surface font-label-bold text-label-bold
           h-screen w-60 rounded-r-2xl shadow-md fixed left-0 top-0 z-40 flex-col p-md">

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

    <!-- Student Profile -->
    <div class="flex items-center gap-sm mb-md pb-md border-b border-outline-variant">
        <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center shrink-0 text-lg font-bold uppercase">
            <?= mb_substr($nama_lengkap ?? 'S', 0, 1) ?>
        </div>
        <div class="overflow-hidden">
            <p class="font-semibold text-on-surface truncate"><?= esc($nama_lengkap ?? 'Siswa') ?></p>
            <p class="font-caption text-caption text-on-surface-variant">Calon Siswa</p>
        </div>
    </div>

    <!-- Nav Links -->
    <div class="flex flex-col gap-1 flex-grow overflow-y-auto">
        <p class="font-caption text-caption text-on-surface-variant uppercase tracking-wider px-sm mb-1">Menu Utama</p>

        <a href="<?= base_url('siswa/dashboard') ?>"
           class="sidebar-link flex items-center gap-sm rounded-xl px-sm py-3 transition-all
                  <?= $isDashboard ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' ?>">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' <?= $isDashboard ? '1' : '0' ?>">dashboard</span>
            Dashboard
        </a>

        <?php if ($can_cetak_bukti): ?>
        <a href="<?= base_url('siswa/cetak-formulir') ?>"
           class="sidebar-link flex items-center gap-sm rounded-xl px-sm py-3 transition-all
                  <?= $isCetakFormulir ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' ?>">
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' <?= $isCetakFormulir ? '1' : '0' ?>">print</span>
            Cetak Bukti
        </a>
        <?php else: ?>
        <span class="sidebar-link flex items-center gap-sm rounded-xl px-sm py-3 text-on-surface-variant/50 cursor-not-allowed select-none"
              title="Tersedia setelah berkas dinyatakan Valid">
            <span class="material-symbols-outlined">lock</span>
            Cetak Bukti
        </span>
        <?php endif; ?>

        <div class="mt-auto pt-md border-t border-outline-variant">
            <a href="<?= base_url('logout') ?>"
               class="sidebar-link flex items-center gap-sm rounded-xl px-sm py-3 text-error hover:bg-error-container hover:text-on-error-container transition-all">
                <span class="material-symbols-outlined">logout</span>
                Keluar
            </a>
        </div>
    </div>
</nav>
