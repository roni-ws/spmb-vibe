<?php
$containerClass ??= 'flex items-center gap-sm';
$imgWrapClass   ??= 'w-10 h-10 rounded-xl overflow-hidden bg-slate-50 flex items-center justify-center shrink-0 shadow-sm border border-outline-variant/40';
$imgClass       ??= 'w-full h-full object-contain p-1';
$iconClass      ??= 'material-symbols-outlined text-primary text-3xl';
$textClass      ??= 'font-headline-sm text-headline-sm text-primary leading-tight';
$altText        ??= 'Logo';
$useEmpty       ??= false;
$iconExtra      ??= '';
$logoUrl        ??= base_url(); // Tautan default ke beranda utama
?>
<a href="<?= $logoUrl ?>" class="<?= $containerClass ?> hover:opacity-90 active:scale-98 transition-all">
    <?php if ($useEmpty ? (!empty($logoImg) && file_exists(FCPATH . $logoImg)) : ($logoImg && file_exists(FCPATH . $logoImg))): ?>
        <div class="<?= $imgWrapClass ?>">
            <img src="<?= base_url($logoImg) ?>" class="<?= $imgClass ?>" alt="<?= $altText ?>">
        </div>
    <?php else: ?>
        <span class="<?= $iconClass ?>" <?= $iconExtra ?>>school</span>
    <?php endif; ?>
    <span class="<?= $textClass ?>"><?= esc($logoText) ?></span>
</a>
