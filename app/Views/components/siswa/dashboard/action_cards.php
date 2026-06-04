<!-- Action Cards Container -->
<div class="md:col-span-4 flex flex-col gap-md order-2">
    <!-- Status Action Alert Banner -->
    <?php if ($needs_revision): ?>
        <div class="p-sm bg-red-50 border border-red-200 rounded-xl flex gap-xs items-start shadow-sm text-red-800 animate-slide-up">
            <span class="material-symbols-outlined text-red-600 text-lg shrink-0">gpp_bad</span>
            <div class="flex-grow">
                <h4 class="font-label-bold text-[11px] text-red-900 mb-0.5">Berkas Perlu Revisi</h4>
                <p class="text-[10px] leading-normal text-red-800/90">
                    Berkas Anda dinyatakan **Butuh Revisi** oleh panitia. Perbaiki data/formulir sesuai catatan, lalu lakukan finalisasi ulang.
                </p>
            </div>
        </div>
    <?php elseif ($can_edit): ?>
        <div class="p-sm bg-amber-50 border border-amber-200 rounded-xl flex gap-xs items-start shadow-sm text-amber-800 animate-slide-up">
            <span class="material-symbols-outlined text-amber-600 text-lg shrink-0">info</span>
            <div class="flex-grow">
                <h4 class="font-label-bold text-[11px] text-amber-900 mb-0.5">Tombol Tindakan Terkunci</h4>
                <p class="text-[10px] leading-normal text-amber-800/90">
                    Selesaikan isian data Anda (Langkah 1 s.d 4) lalu lakukan **Finalisasi Pendaftaran** agar tombol cetak & grup WA aktif.
                </p>
            </div>
        </div>
    <?php elseif ($is_finalized === 1 && $status_verifikasi !== 'Valid'): ?>
        <div class="p-sm bg-blue-50 border border-blue-200 rounded-xl flex gap-xs items-start shadow-sm text-blue-800 animate-slide-up">
            <span class="material-symbols-outlined text-blue-600 text-lg shrink-0">pending_actions</span>
            <div class="flex-grow">
                <h4 class="font-label-bold text-[11px] text-blue-900 mb-0.5">Menunggu Verifikasi</h4>
                <p class="text-[10px] leading-normal text-blue-800/90">
                    Berkas Anda sedang **diverifikasi oleh Panitia**. Tombol cetak & grup WA akan terbuka setelah berkas dinyatakan **Valid**.
                </p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Lengkapi Formulir Action -->
    <?php if ($can_edit): ?>
    <a class="group flex items-center gap-sm p-sm text-white border border-amber-600/20 rounded-xl shadow-[0_4px_12px_rgba(217,119,6,0.15)] hover:shadow-[0_8px_24px_rgba(217,119,6,0.25)] transition-all cursor-pointer"
        style="background: linear-gradient(to right, #f59e0b, #d97706) !important;"
        href="<?= base_url('siswa/biodata') ?>">
        <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center text-white group-hover:bg-white group-hover:text-amber-600 transition-colors shrink-0 shadow-inner">
            <span class="material-symbols-outlined">edit_document</span>
        </div>
        <div class="flex-grow">
            <h3 class="font-label-bold text-body-sm text-white group-hover:underline">
                <?= $needs_revision ? 'Perbaiki Formulir' : 'Isi/Lengkapi Formulir' ?>
            </h3>
            <p class="font-caption text-[11px] text-white/90"><?= $needs_revision ? 'Revisi data sesuai catatan panitia' : 'Langkah 1 s.d 4 Pendaftaran' ?></p>
        </div>
        <span class="material-symbols-outlined text-white/70 group-hover:text-white transition-colors">chevron_right</span>
    </a>
    <?php endif; ?>

    <?php $can_action = ($is_finalized === 1 && $status_verifikasi === 'Valid'); ?>

    <!-- Download Form Action -->
    <?php if ($can_action): ?>
    <a class="group flex items-center gap-sm p-sm bg-surface border border-outline-variant rounded-xl shadow-[0_4px_12px_rgba(0,32,69,0.04)] hover:shadow-[0_8px_20px_rgba(0,32,69,0.08)] hover:border-primary transition-all cursor-pointer"
        href="<?= base_url('siswa/cetak-formulir') ?>">
        <div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-on-primary transition-colors shrink-0">
            <span class="material-symbols-outlined">download</span>
        </div>
        <div class="flex-grow">
            <h3 class="font-label-bold text-body-sm text-on-surface group-hover:text-primary transition-colors">
                Cetak Bukti Pendaftaran
            </h3>
            <p class="font-caption text-caption text-on-surface-variant">Dokumen Bukti PDF</p>
        </div>
        <span class="material-symbols-outlined text-outline-variant group-hover:text-primary transition-colors">chevron_right</span>
    </a>
    <?php else: ?>
    <div class="group flex items-center gap-sm p-sm bg-slate-50 border border-slate-200 rounded-xl opacity-60 cursor-not-allowed select-none">
        <div class="w-10 h-10 rounded-lg bg-slate-200 flex items-center justify-center text-slate-400 shrink-0">
            <span class="material-symbols-outlined">lock</span>
        </div>
        <div class="flex-grow">
            <h3 class="font-label-bold text-body-sm text-slate-500">
                Cetak Bukti Pendaftaran
            </h3>
            <p class="font-caption text-caption text-slate-400">Terkunci (Belum Terverifikasi)</p>
        </div>
        <span class="material-symbols-outlined text-slate-300">lock</span>
    </div>
    <?php endif; ?>
    
    <!-- WhatsApp Group Action -->
    <?php if ($can_action): ?>
    <?php $waLink = \App\Models\SettingsModel::getSetting('whatsapp_link', 'https://wa.me/6281234567890'); ?>
    <a class="group flex items-center gap-sm p-sm bg-surface border border-outline-variant rounded-xl shadow-[0_4px_12px_rgba(0,32,69,0.04)] hover:shadow-[0_8px_20px_rgba(0,32,69,0.08)] hover:border-[#25D366] transition-all cursor-pointer"
        href="<?= esc($waLink) ?>" target="_blank">
        <div class="w-10 h-10 rounded-lg bg-[#25D366]/10 flex items-center justify-center text-[#25D366] group-hover:bg-[#25D366] group-hover:text-white transition-colors shrink-0">
            <span class="material-symbols-outlined font-bold">chat</span>
        </div>
        <div class="flex-grow">
            <h3 class="font-label-bold text-body-sm text-on-surface group-hover:text-[#25D366] transition-colors">
                Grup WhatsApp Resmi
            </h3>
            <p class="font-caption text-caption text-on-surface-variant">Info & Koordinasi Panitia</p>
        </div>
        <span class="material-symbols-outlined text-outline-variant group-hover:text-[#25D366] transition-colors">chevron_right</span>
    </a>
    <?php else: ?>
    <div class="group flex items-center gap-sm p-sm bg-slate-50 border border-slate-200 rounded-xl opacity-60 cursor-not-allowed select-none">
        <div class="w-10 h-10 rounded-lg bg-slate-200 flex items-center justify-center text-slate-400 shrink-0">
            <span class="material-symbols-outlined">lock</span>
        </div>
        <div class="flex-grow">
            <h3 class="font-label-bold text-body-sm text-slate-500">
                Grup WhatsApp Resmi
            </h3>
            <p class="font-caption text-caption text-slate-400">Terkunci (Belum Terverifikasi)</p>
        </div>
        <span class="material-symbols-outlined text-slate-300">lock</span>
    </div>
    <?php endif; ?>
</div>
