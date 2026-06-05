<nav id="sidebar"
    class="hidden md:flex bg-surface-container-low text-on-surface font-label-bold text-label-bold
           h-screen w-60 rounded-r-2xl shadow-md fixed left-0 top-0 z-40 flex-col p-md">

    <!-- Admin Profile -->
    <div class="flex items-center gap-sm mb-md pb-md border-b border-outline-variant">
        <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center shrink-0 text-lg font-bold uppercase">
            <?= mb_substr($admin_username ?? 'A', 0, 1) ?>
        </div>
        <div class="overflow-hidden">
            <p class="font-semibold text-on-surface truncate"><?= esc($admin_username ?? 'Administrator') ?></p>
            <p class="font-caption text-caption text-on-surface-variant"><?= esc($roleLabel) ?></p>
        </div>
    </div>

    <!-- Nav Links -->
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

        <?php if ($isAdmin): ?>
            <a href="<?= base_url('admin/pengumuman') ?>"
               class="sidebar-link flex items-center gap-sm rounded-xl px-sm py-3 transition-all
                      <?= $isPengumuman ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' ?>">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' <?= $isPengumuman ? '1' : '0' ?>">campaign</span>
                Pengumuman
            </a>

            <a href="<?= base_url('admin/users') ?>"
               class="sidebar-link flex items-center gap-sm rounded-xl px-sm py-3 transition-all
                      <?= $isUsers ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' ?>">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' <?= $isUsers ? '1' : '0' ?>">group</span>
                Manajemen User
            </a>

            <a href="<?= base_url('admin/settings') ?>"
               class="sidebar-link flex items-center gap-sm rounded-xl px-sm py-3 transition-all
                      <?= $isSettings ? 'bg-secondary-container text-on-secondary-container font-bold' : 'text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' ?>">
                <span class="material-symbols-outlined" style="font-variation-settings:'FILL' <?= $isSettings ? '1' : '0' ?>">settings</span>
                Pengaturan
            </a>
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
