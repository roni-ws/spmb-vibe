<?php
$steps = [
    1 => ['title' => 'Biodata Diri',       'url' => 'siswa/biodata',   'pending_label' => 'Belum Diisi'],
    2 => ['title' => 'Asal Sekolah',        'url' => 'siswa/sekolah',  'pending_label' => 'Belum Diisi'],
    3 => ['title' => 'Data Orang Tua',      'url' => 'siswa/orang-tua', 'pending_label' => 'Belum Diisi'],
    4 => ['title' => 'Berkas Persyaratan',   'url' => 'siswa/berkas',   'pending_label' => 'Belum Diunggah'],
];
$progressPct = ($currentStep / 4) * 100;
?>
<div class="mb-lg bg-surface border border-outline-variant rounded-2xl p-sm md:p-md shadow-sm">
    <div class="flex flex-col md:flex-row justify-between items-center gap-md">
        <?php foreach ($steps as $num => $step): ?>
        <?php if ($num > 1): ?>
        <div class="hidden md:block flex-grow h-px <?= $num <= $currentStep ? 'bg-emerald-200' : 'bg-outline-variant' ?> mx-xs"></div>
        <?php endif; ?>

        <?php if ($num < $currentStep): ?>
        <a href="<?= base_url($step['url']) ?>" class="flex items-center gap-xs w-full md:w-auto hover:opacity-80 transition-opacity">
            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">check</span>
            </div>
            <div>
                <span class="block text-sm font-bold text-emerald-600 leading-tight"><?= $step['title'] ?></span>
                <span class="block text-[11px] text-emerald-500">Selesai</span>
            </div>
        </a>

        <?php elseif ($num == $currentStep): ?>
        <div class="flex items-center gap-xs w-full md:w-auto">
            <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm shrink-0 shadow-md"><?= $num ?></div>
            <div>
                <span class="block text-sm font-bold text-primary leading-tight"><?= $step['title'] ?></span>
                <span class="block text-[11px] text-primary">Sedang Diisi</span>
            </div>
        </div>

        <?php else: ?>
        <div class="flex items-center gap-xs w-full md:w-auto opacity-50">
            <div class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant text-on-surface-variant flex items-center justify-center font-bold text-sm shrink-0"><?= $num ?></div>
            <div>
                <span class="block text-sm font-bold text-on-surface-variant leading-tight"><?= $step['title'] ?></span>
                <span class="block text-[11px] text-outline"><?= $step['pending_label'] ?></span>
            </div>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div class="mt-sm md:hidden">
        <div class="h-1.5 w-full bg-surface-container-high rounded-full overflow-hidden">
            <div class="h-full bg-primary rounded-full" style="width: <?= $progressPct ?>%;"></div>
        </div>
    </div>
</div>
