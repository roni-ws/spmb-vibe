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

        <!-- A. Biodata -->
        <div class="section-title">A. Biodata</div>
        <div class="biodata-wrapper">
            <div class="biodata-content">
                <table class="info-table">
                    <tr><td class="label">No. Registrasi</td><td class="colon">:</td><td class="value"><?= esc($student['no_registrasi'] ?? '-') ?></td></tr>
                    <tr><td class="label">NISN</td><td class="colon">:</td><td class="value"><?= esc($student['nisn'] ?? '-') ?></td></tr>
                    <tr><td class="label">NIK</td><td class="colon">:</td><td class="value"><?= esc($student['nik'] ?? '-') ?></td></tr>
                    <tr><td class="label">No. KK</td><td class="colon">:</td><td class="value"><?= esc($student['no_kk'] ?? '-') ?></td></tr>
                    <tr><td class="label">Nama Lengkap</td><td class="colon">:</td><td class="value"><?= esc($student['nama_lengkap'] ?? '-') ?></td></tr>
                    <tr><td class="label">Tempat, Tanggal Lahir</td><td class="colon">:</td><td class="value"><?= esc($student['tempat_lahir'] ?? '-') ?>, <?= !empty($student['tanggal_lahir']) ? date('d/m/Y', strtotime($student['tanggal_lahir'])) : '-' ?></td></tr>
                    <tr><td class="label">Jenis Kelamin</td><td class="colon">:</td><td class="value"><?= ($student['jenis_kelamin'] ?? '') === 'L' ? 'Laki-laki' : 'Perempuan' ?></td></tr>
                    <tr><td class="label">Anak Ke-</td><td class="colon">:</td><td class="value"><?= esc($student['anak_ke'] ?? '-') ?></td></tr>
                    <tr><td class="label">Jumlah Saudara</td><td class="colon">:</td><td class="value"><?= esc($student['jumlah_saudara'] ?? '-') ?></td></tr>
                    <tr><td class="label">No. Handphone</td><td class="colon">:</td><td class="value"><?= esc($student['no_hp'] ?? '-') ?></td></tr>
                </table>
            </div>
            <?php if (!empty($student['pas_foto'])): ?>
            <div class="pas-foto-container">
                <div class="pas-foto-box">
                    <img src="<?= base_url(UPLOAD_PATH . $student['pas_foto']) ?>" alt="Pas Foto">
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- B. Alamat -->
        <div class="section-title">B. Alamat</div>
        <table class="info-table">
            <tr><td class="label">Desa</td><td class="colon">:</td><td class="value"><?= esc($student['alamat_desa'] ?? '-') ?></td></tr>
            <tr><td class="label">Kecamatan</td><td class="colon">:</td><td class="value"><?= esc($student['alamat_kecamatan'] ?? '-') ?></td></tr>
            <tr><td class="label">Kabupaten / Kota</td><td class="colon">:</td><td class="value"><?= esc($student['alamat_kabupaten'] ?? '-') ?></td></tr>
        </table>

        <div class="grid">
            <div class="col-6">
                <!-- C. Data Orang Tua -->
                <div class="section-title">C. Data Orang Tua</div>
                <table class="info-table">
                    <tr><td class="label">Nama Ayah</td><td class="colon">:</td><td class="value"><?= esc($student['nama_ayah'] ?? '-') ?></td></tr>
                    <tr><td class="label">Pekerjaan Ayah</td><td class="colon">:</td><td class="value"><?= esc($student['pekerjaan_ayah'] ?? '-') ?></td></tr>
                    <tr><td class="label">Nama Ibu</td><td class="colon">:</td><td class="value"><?= esc($student['nama_ibu'] ?? '-') ?></td></tr>
                    <tr><td class="label">Pekerjaan Ibu</td><td class="colon">:</td><td class="value"><?= esc($student['pekerjaan_ibu'] ?? '-') ?></td></tr>
                </table>

                <!-- D. Asal Sekolah -->
                <div class="section-title">D. Asal Sekolah</div>
                <table class="info-table">
                    <tr><td class="label">Nama Sekolah</td><td class="colon">:</td><td class="value"><?= esc($student['nama_sekolah'] ?? '-') ?></td></tr>
                    <tr><td class="label">Alamat Sekolah</td><td class="colon">:</td><td class="value"><?= esc($student['alamat_sekolah'] ?? '-') ?></td></tr>
                </table>
            </div>

            <div class="col-6">
                <!-- E. Dokumen Unggahan -->
                <div class="section-title">E. Dokumen Unggahan</div>
                <table class="info-table">
                    <tr>
                        <td class="label" style="width:44%;">Scan Ijazah / SKL</td>
                        <td class="colon">:</td>
                        <td class="value">
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
                        <td class="label" style="width:44%;">Scan Kartu Keluarga</td>
                        <td class="colon">:</td>
                        <td class="value">
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
                        <td class="label" style="width:44%;">Pas Foto 3x4</td>
                        <td class="colon">:</td>
                        <td class="value">
                            <?php if (!empty($student['pas_foto'])): ?>
                                <span class="badge badge-valid">SUDAH DIUNGGAH</span>
                                &nbsp;
                                <a href="<?= base_url(UPLOAD_PATH . $student['pas_foto']) ?>" target="_blank" class="file-link">
                                    <span style="font-size:14px;">📎</span> Download / Lihat
                                </a>
                            <?php else: ?>
                                <span class="badge badge-belum">BELUM DIUNGGAH</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>

                <!-- F. Status Verifikasi -->
                <div class="section-title">F. Status Verifikasi</div>
                <table class="info-table">
                    <tr>
                        <td class="label" style="width:44%;">Status Berkas</td>
                        <td class="colon">:</td>
                        <td class="value">
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
                    <tr><td class="label" style="width:44%;">Finalisasi</td><td class="colon">:</td><td class="value"><span class="badge <?= $student['is_finalized'] ? 'badge-valid' : 'badge-belum' ?>"><?= $student['is_finalized'] ? 'SUDAH FINAL' : 'BELUM FINAL' ?></span></td></tr>
                    <tr><td class="label" style="width:44%;">Catatan Verifikasi</td><td class="colon">:</td><td class="value"><?= !empty($student['catatan_verifikasi']) ? nl2br(esc($student['catatan_verifikasi'])) : '-' ?></td></tr>
                    <tr><td class="label" style="width:44%;">Tanggal Daftar</td><td class="colon">:</td><td class="value"><?= !empty($student['created_at']) ? date('d F Y', strtotime($student['created_at'])) : '-' ?></td></tr>
                </table>
            </div>
        </div>

        <!-- Tanda Tangan -->
        <div class="sign-area">
            <div class="sign-box">
                <p><?= esc($kota_ttd) ?>, <?= date('d F Y') ?></p>
                <p>Ketua Panitia SPMB <?= esc($logoText) ?>,</p>
                <div class="space"></div>
                <p class="sign-name">(<?= esc($ketua_panitia) ?>)</p>
            </div>
        </div>
    </div>
</body>
</html>
