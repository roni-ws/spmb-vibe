<?php
$kota_ttd ??= 'Jakarta';
$ketua_panitia ??= '';
$logoText ??= 'SMA Unggul Bangsa';
include __DIR__ . '/../components/shared/kop_surat.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Pendaftaran - <?= esc($student['nama_lengkap']) ?></title>
    <link rel="stylesheet" href="<?= base_url('css/print-arsip.css') ?>">
</head>
<body>
    <div class="no-print" style="max-width: 1000px; margin: 0 auto 20px auto;">
        <button class="no-print-btn" onclick="window.print()">Cetak / PDF</button>
        <button class="no-print-btn" style="background-color: #4b5563;" onclick="window.close()">Tutup</button>
    </div>

    <div class="container">
        <div class="header">
            <div class="header-logo">
                <img src="<?= $logoUrl ?>" alt="Logo Instansi">
            </div>
            <div class="header-text">
                <?= $kopContent ?>
            </div>
        </div>

        <div class="arsip-title">Arsip Pendaftaran Calon Siswa Baru</div>
        <div class="print-date">Dicetak pada: <?= date('d F Y H:i') ?></div>

        <?php $student = $student; // alias for readability ?>

        <!-- Pas Foto -->
        <?php if (!empty($student['pas_foto'])): ?>
            <div class="foto-container">
                <img src="<?= base_url(UPLOAD_PATH . $student['pas_foto']) ?>" alt="Pas Foto">
            </div>
        <?php endif; ?>

        <div class="sections-grid">

        <!-- Biodata -->
        <div class="section-card">
        <div class="section-title">A. Biodata</div>
        <table class="data-table">
            <tr><td class="label">No. Registrasi</td><td><?= esc($student['no_registrasi'] ?? '-') ?></td></tr>
            <tr><td class="label">NISN</td><td><?= esc($student['nisn'] ?? '-') ?></td></tr>
            <tr><td class="label">NIK</td><td><?= esc($student['nik'] ?? '-') ?></td></tr>
            <tr><td class="label">No. KK</td><td><?= esc($student['no_kk'] ?? '-') ?></td></tr>
            <tr><td class="label">Nama Lengkap</td><td><?= esc($student['nama_lengkap'] ?? '-') ?></td></tr>
            <tr><td class="label">Tempat, Tanggal Lahir</td><td><?= esc($student['tempat_lahir'] ?? '-') ?>, <?= !empty($student['tanggal_lahir']) ? date('d/m/Y', strtotime($student['tanggal_lahir'])) : '-' ?></td></tr>
            <tr><td class="label">Jenis Kelamin</td><td><?= ($student['jenis_kelamin'] ?? '') === 'L' ? 'Laki-laki' : 'Perempuan' ?></td></tr>
            <tr><td class="label">Anak Ke-</td><td><?= esc($student['anak_ke'] ?? '-') ?></td></tr>
            <tr><td class="label">Jumlah Saudara</td><td><?= esc($student['jumlah_saudara'] ?? '-') ?></td></tr>
            <tr><td class="label">No. Handphone</td><td><?= esc($student['no_hp'] ?? '-') ?></td></tr>
        </table>
        </div>

        <!-- Alamat -->
        <div class="section-card">
        <div class="section-title">B. Alamat</div>
        <table class="data-table">
            <tr><td class="label">Desa</td><td><?= esc($student['alamat_desa'] ?? '-') ?></td></tr>
            <tr><td class="label">Kecamatan</td><td><?= esc($student['alamat_kecamatan'] ?? '-') ?></td></tr>
            <tr><td class="label">Kabupaten / Kota</td><td><?= esc($student['alamat_kabupaten'] ?? '-') ?></td></tr>
        </table>
        </div>

        <!-- Orang Tua -->
        <div class="section-card">
        <div class="section-title">C. Data Orang Tua</div>
        <table class="data-table">
            <tr><td class="label">Nama Ayah</td><td><?= esc($student['nama_ayah'] ?? '-') ?></td></tr>
            <tr><td class="label">Pekerjaan Ayah</td><td><?= esc($student['pekerjaan_ayah'] ?? '-') ?></td></tr>
            <tr><td class="label">Nama Ibu</td><td><?= esc($student['nama_ibu'] ?? '-') ?></td></tr>
            <tr><td class="label">Pekerjaan Ibu</td><td><?= esc($student['pekerjaan_ibu'] ?? '-') ?></td></tr>
        </table>
        </div>

        <!-- Asal Sekolah -->
        <div class="section-card">
        <div class="section-title">D. Asal Sekolah</div>
        <table class="data-table">
            <tr><td class="label">Nama Sekolah</td><td><?= esc($student['nama_sekolah'] ?? '-') ?></td></tr>
            <tr><td class="label">Alamat Sekolah</td><td><?= esc($student['alamat_sekolah'] ?? '-') ?></td></tr>
        </table>
        </div>

        <!-- Dokumen Unggahan -->
        <div class="section-card">
        <div class="section-title">E. Dokumen Unggahan</div>
        <table class="data-table">
            <tr>
                <td class="label">Scan Ijazah / SKL</td>
                <td>
                    <?php if (!empty($student['fc_ijazah_skl'])): ?>
                        <span class="badge badge-valid">SUDAH DIUNGGAH</span>
                        &nbsp;
                        <a href="<?= base_url(UPLOAD_PATH . $student['fc_ijazah_skl']) ?>" target="_blank" class="file-link">
                            <span style="font-size:14px;">📎</span> Download / Lihat
                        </a>
                    <?php else: ?>
                        <span class="badge badge-belum">BELUM DIUNGGAH</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td class="label">Scan Kartu Keluarga</td>
                <td>
                    <?php if (!empty($student['fc_kk'])): ?>
                        <span class="badge badge-valid">SUDAH DIUNGGAH</span>
                        &nbsp;
                        <a href="<?= base_url(UPLOAD_PATH . $student['fc_kk']) ?>" target="_blank" class="file-link">
                            <span style="font-size:14px;">📎</span> Download / Lihat
                        </a>
                    <?php else: ?>
                        <span class="badge badge-belum">BELUM DIUNGGAH</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td class="label">Pas Foto 3x4</td>
                <td>
                    <?php if (!empty($student['pas_foto'])): ?>
                        <span class="badge badge-valid">SUDAH DIUNGGAH</span>
                    <?php else: ?>
                        <span class="badge badge-belum">BELUM DIUNGGAH</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        </div>

        <!-- Status -->
        <div class="section-card">
        <div class="section-title">F. Status Verifikasi</div>
        <table class="data-table">
            <tr>
                <td class="label">Status Berkas</td>
                <td>
                    <?php
                        $statusVerif = $student['status_verifikasi'] ?? VERIF_STATUS_BELUM;
                        $statusVerifClass = match($statusVerif) {
                            'Valid'  => 'badge-valid',
                            'Revisi' => 'badge-revisi',
                            default  => 'badge-pending',
                        };
                    ?>
                    <span class="badge <?= $statusVerifClass ?>"><?= strtoupper($statusVerif) ?></span>
                </td>
            </tr>
            <tr><td class="label">Finalisasi</td><td><span class="badge <?= $student['is_finalized'] ? 'badge-valid' : 'badge-belum' ?>"><?= $student['is_finalized'] ? 'SUDAH FINAL' : 'BELUM FINAL' ?></span></td></tr>
            <tr><td class="label">Catatan Verifikasi</td><td><?= !empty($student['catatan_verifikasi']) ? nl2br(esc($student['catatan_verifikasi'])) : '-' ?></td></tr>
            <tr><td class="label">Tanggal Daftar</td><td><?= !empty($student['created_at']) ? date('d F Y', strtotime($student['created_at'])) : '-' ?></td></tr>
        </table>
        </div>

        </div>

        <!-- Tanda Tangan -->
        <div class="sign-area">
            <div class="sign-box">
                <p><?= esc($kota_ttd) ?>, <?= date('d F Y') ?></p>
                <p>Ketua Panitia SPMB <?= esc($logoText) ?>,</p>
                <p class="sign-name">(<?= esc($ketua_panitia) ?>)</p>
            </div>
        </div>
    </div>
</body>
</html>
