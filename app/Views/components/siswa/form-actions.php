<?php
$backUrl    ??= 'siswa/dashboard';
$backLabel  ??= 'Kembali';
$submitLabel ??= 'Simpan & Lanjutkan';
$submitIcon  ??= 'arrow_forward';
$showBackIcon = $backLabel === 'Kembali';
?>
<div class="flex flex-col sm:flex-row justify-between items-center gap-md border-t border-outline-variant pt-md mt-lg">
    <a href="<?= base_url($backUrl) ?>" class="w-full sm:w-auto rounded-lg border border-outline text-on-surface-variant hover:bg-surface-container-high transition-colors flex items-center justify-center gap-xs font-label-bold text-body-sm text-center"
       style="padding: 12px 36px !important; min-width: 160px;">
        <?php if ($showBackIcon): ?>
        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        <?php endif; ?>
        <?= $backLabel ?>
    </a>
    <button class="w-full sm:w-auto rounded-lg bg-primary text-on-primary font-label-bold text-body-sm hover:bg-primary-container transition-colors shadow-sm hover:shadow-md flex items-center justify-center gap-xs group" 
            style="padding: 12px 36px !important; min-width: 160px; cursor: pointer;"
            type="submit">
        <?= $submitLabel ?>
        <span class="material-symbols-outlined text-[20px] <?= $submitIcon === 'arrow_forward' ? 'group-hover:translate-x-1 transition-transform' : '' ?>"><?= $submitIcon ?></span>
    </button>
</div>
