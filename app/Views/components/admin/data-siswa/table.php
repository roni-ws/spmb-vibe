<!-- ================================
     DATA TABLE
     ================================ -->
<div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
    <div class="overflow-x-auto custom-scrollbar">
        <table id="student-table" class="w-full text-left border-collapse min-w-[1000px]" style="width:100%">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant">
                    <th class="p-4 w-10">
                        <input type="checkbox" id="select-all"
                               class="rounded border-outline-variant text-primary focus:ring-primary cursor-pointer" />
                    </th>
                    <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider">Calon Siswa</th>
                    <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider">No. Registrasi</th>
                    <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider">NIK</th>
                    <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider">Asal Sekolah</th>
                    <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider">Tgl Daftar</th>
                    <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider">Status Berkas</th>
                    <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider">Finalisasi</th>
                    <th class="p-4 font-semibold text-label-bold text-primary text-xs uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $i => $student): ?>
                    <?php
                        $nama   = esc($student['nama_lengkap'] ?: $student['username'] ?? '-');
                        $inisial = strtoupper(mb_substr($student['nama_lengkap'] ?: $student['username'] ?? 'X', 0, 2));
                        $status = $student['status_verifikasi'] ?? VERIF_STATUS_BELUM;
                        $final  = $student['is_finalized'] ?? 0;

                        if ($final && in_array($status, [VERIF_STATUS_REVISI, VERIF_STATUS_TIDAK_VALID])) {
                            $status = VERIF_STATUS_BELUM;
                        }

                        $statusClass = match($status) {
                            'Valid'       => 'badge-valid',
                            'Revisi'      => 'badge-invalid',
                            default       => 'badge-pending',
                        };
                        $statusIcon = match($status) {
                            'Valid'       => 'check_circle',
                            'Revisi'      => 'edit_note',
                            default       => 'pending_actions',
                        };

                        // Avatar color cycling
                        $avatarColors = ['bg-primary-container text-on-primary-container', 'bg-secondary-container text-on-secondary-container', 'bg-tertiary-container text-on-tertiary-container', 'bg-surface-container-high text-primary'];
                        $avatarClass  = $avatarColors[$i % count($avatarColors)];
                    ?>
                    <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors"
                        data-id="<?= $student['id'] ?>">
                        <!-- Checkbox -->
                        <td class="p-4">
                            <input type="checkbox" class="row-checkbox rounded border-outline-variant text-primary focus:ring-primary cursor-pointer"
                                   value="<?= $student['id'] ?>" />
                        </td>
                        <!-- Name + Info -->
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full <?= $avatarClass ?> flex items-center justify-center font-bold text-xs shrink-0">
                                    <?= $inisial ?>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-body-sm text-on-surface truncate max-w-[160px]"><?= $nama ?></p>
                                    <p class="text-caption text-on-surface-variant truncate max-w-[160px]">NISN: <?= esc($student['nisn'] ?? '-') ?></p>
                                </div>
                            </div>
                        </td>
                        <!-- No. Registrasi -->
                        <td class="p-4 text-body-sm text-on-surface-variant font-mono">
                            <?= esc($student['no_registrasi'] ?? '-') ?>
                        </td>
                        <!-- NIK -->
                        <td class="p-4 text-body-sm text-on-surface font-semibold">
                            <?= (!empty($student['nik']) && !str_contains($student['nik'], REG_PREFIX)) ? esc($student['nik']) : '<span class="text-outline italic">' . DEFAULT_BELUM_MENGISI . '</span>' ?>
                        </td>
                        <!-- Sekolah -->
                        <td class="p-4 text-body-sm text-on-surface-variant max-w-[160px]">
                            <span class="truncate block"><?= esc($student['nama_sekolah'] ?? '-') ?></span>
                        </td>
                        <!-- Tgl Daftar -->
                        <td class="p-4 text-body-sm text-on-surface-variant whitespace-nowrap">
                            <?= date('d M Y', strtotime($student['created_at'])) ?>
                        </td>
                        <!-- Status Berkas -->
                        <td class="p-4">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-caption font-bold <?= $statusClass ?>">
                                <span class="material-symbols-outlined text-[14px]"><?= $statusIcon ?></span>
                                <?= esc($status) ?>
                            </span>
                        </td>
                        <!-- Finalisasi -->
                        <td class="p-4">
                            <?php if ($final): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-caption font-semibold bg-tertiary-container/40 text-on-tertiary-container">
                                    <span class="material-symbols-outlined text-[14px]">lock</span> Final
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-caption font-semibold bg-surface-container text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[14px]">lock_open</span> Belum
                                </span>
                            <?php endif; ?>
                        </td>
                        <!-- Actions -->
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <!-- Quick View -->
                                <button class="view-student-btn p-1.5 text-info hover:bg-info-container/20 rounded-full transition-colors"
                                        data-id="<?= $student['id'] ?>"
                                        data-student='<?= esc(json_encode($student), 'attr') ?>'
                                        title="Lihat Detail">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </button>
                                <!-- Quick Edit -->
                                <button class="edit-student-btn p-1.5 text-primary hover:bg-primary-container/20 rounded-full transition-colors"
                                        data-id="<?= $student['id'] ?>"
                                        data-student='<?= esc(json_encode($student), 'attr') ?>'
                                        title="Edit Calon Siswa">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                
                                <!-- Quick Delete -->
                                <button class="delete-student-btn p-1.5 text-error hover:bg-error-container/20 rounded-full transition-colors"
                                        data-id="<?= $student['id'] ?>"
                                        data-nama="<?= esc($nama) ?>"
                                        title="Hapus Calon Siswa">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                                
                                <!-- Quick Archive -->
                                <a href="<?= base_url('admin/arsip/' . $student['id']) ?>" target="_blank"
                                   class="p-1.5 text-secondary hover:bg-secondary-container/20 rounded-full transition-colors"
                                   title="Cetak Arsip">
                                    <span class="material-symbols-outlined text-[18px]">print</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="py-16 text-center text-on-surface-variant text-body-sm">
                            <span class="material-symbols-outlined text-5xl block mb-3 text-outline">inbox</span>
                            Belum ada data pendaftar.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Table Footer Info (DataTables pagination goes here) -->
    <div class="px-4 py-3 border-t border-outline-variant bg-surface-container-lowest flex items-center justify-between flex-wrap gap-2">
        <span class="text-caption text-on-surface-variant" id="table-info-text">
            Menampilkan <?= count($students) ?> pendaftar
        </span>
        <div id="dt-paginate-wrapper"></div>
    </div>
</div>
