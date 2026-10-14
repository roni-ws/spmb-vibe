<!-- Announcements Section -->
<div class="md:col-span-12 mt-md order-5">
    <div class="flex items-center justify-between mb-sm border-b border-outline-variant pb-2">
        <h2 class="font-headline-sm text-headline-sm text-primary flex items-center gap-xs">
            <span class="material-symbols-outlined text-2xl shrink-0 text-primary">campaign</span> Pengumuman Penting
        </h2>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-sm">
        <?php if (!empty($announcements)): ?>
            <?php foreach ($announcements as $index => $ann): ?>
                <div class="p-sm bg-surface-container-low rounded-lg border border-outline-variant hover:bg-surface-container transition-colors shadow-sm">
                    <div class="flex items-start gap-sm">
                        <div class="w-2.5 h-2.5 mt-1.5 rounded-full flex-shrink-0 <?= $index === 0 ? 'bg-primary animate-[pulse_2s_infinite]' : 'bg-outline' ?>"></div>
                        <div class="flex-grow">
                            <h3 class="font-label-bold text-body-sm text-on-surface mb-1 flex items-center gap-xs">
                                <?= esc($ann['judul']) ?>
                                <?php if ($index === 0): ?>
                                    <span class="px-1.5 py-0.5 bg-primary/10 text-primary text-[9px] font-bold uppercase rounded-md tracking-wider">Baru</span>
                                <?php endif; ?>
                            </h3>
                            <p class="font-caption text-caption text-on-surface-variant mb-2 whitespace-pre-line leading-relaxed">
                                <?= esc($ann['konten']) ?>
                            </p>
                            <span class="font-caption text-[10px] text-outline flex items-center gap-xs">
                                <span class="material-symbols-outlined text-[12px]">schedule</span>
                                <?= date('d M Y H:i', strtotime($ann['created_at'])) ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="md:col-span-2 text-center py-8 text-on-surface-variant border border-dashed border-outline-variant rounded-xl bg-surface/30">
                <span class="material-symbols-outlined text-4xl block mb-2 text-outline">campaign</span>
                <p class="font-label-bold text-body-sm">Belum ada pengumuman penting</p>
                <p class="text-caption text-on-surface-variant mt-1">Pantau terus halaman ini untuk mendapatkan informasi terbaru dari panitia pendaftaran.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
