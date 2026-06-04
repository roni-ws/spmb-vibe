<!-- TOP APP BAR -->
<header class="bg-surface border-b border-outline-variant shadow-sm flex justify-between items-center
               px-md md:px-lg py-3 w-full sticky top-0 z-30">
    <!-- Mobile: Logo + nama sekolah -->
    <div class="flex items-center gap-sm md:hidden min-w-0">
        <?php if (!empty($logoImg) && file_exists(FCPATH . $logoImg)): ?>
            <div class="w-9 h-9 rounded-lg overflow-hidden bg-slate-50 flex items-center justify-center shrink-0 shadow-sm border border-outline-variant/40">
                <img src="<?= base_url($logoImg) ?>" class="w-full h-full object-contain p-0.5" alt="Logo">
            </div>
        <?php else: ?>
            <span class="material-symbols-outlined text-primary text-2xl shrink-0">school</span>
        <?php endif; ?>
        <span class="font-headline-sm text-headline-sm font-bold text-primary truncate leading-tight"><?= esc($logoText) ?></span>
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
