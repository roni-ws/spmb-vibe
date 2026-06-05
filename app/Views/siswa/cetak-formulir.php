<?php
$kota_ttd ??= 'Jakarta';
$ketua_panitia ??= '';
$logoText ??= 'SPMB Unggul Bangsa';
include __DIR__ . '/../components/shared/kop_surat.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Bukti Pendaftaran SPMB - <?= esc($student['nama_lengkap'] ?: $student['username']) ?></title>
    <link rel="stylesheet" href="<?= base_url('css/print-formulir.css') ?>">
</head>
<body>
    <div class="no-print" style="text-align: right; max-width: 840px; margin: 0 auto;">
        <button class="no-print-btn" onclick="window.print()">Cetak Kartu Pendaftaran (Print/PDF)</button>
        <button class="no-print-btn" style="background-color: #4b5563;" onclick="window.history.back()">Kembali</button>
    </div>

    <div class="container">
        <!-- Header / Kop Surat Dinamis -->
        <div class="header">
            <div class="header-logo">
                <img src="<?= $logoUrl ?>" alt="Logo Instansi">
            </div>
            <div class="header-text">
                <?= $kopContent ?>
            </div>
        </div>

        <div class="slip-title">
            Kartu Bukti Pendaftaran Calon Siswa Baru
        </div>

        <!-- Section A: Biodata Calon Siswa (Full Width dengan Pas Foto di Sebelah Kanan) -->
        <div class="section-title">A. Biodata Calon Siswa</div>
        <div class="biodata-wrapper">
            <div class="biodata-content">
                <table class="info-table">
                    <tr>
                        <td class="label">ID Pendaftaran</td>
                        <td class="colon">:</td>
                        <td class="value text-primary">SPMB-<?= date('Y') ?>-<?= sprintf('%04d', $student['id']) ?></td>
                    </tr>
                    <tr>
                        <td class="label">NISN (Username)</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['nisn'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Nama Lengkap</td>
                        <td class="colon">:</td>
                        <td class="value text-large"><?= esc($student['nama_lengkap'] ?: DEFAULT_BELUM_MENGISI) ?></td>
                    </tr>
                    <tr>
                        <td class="label">NIK</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['nik'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <td class="label">No. Kartu Keluarga</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['no_kk'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Tempat/Tgl Lahir</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['tempat_lahir'] ?: '-') ?>, <?= $student['tanggal_lahir'] ? date('d F Y', strtotime($student['tanggal_lahir'])) : '-' ?></td>
                    </tr>
                    <tr>
                        <td class="label">Jenis Kelamin</td>
                        <td class="colon">:</td>
                        <td class="value"><?= ($student['jenis_kelamin'] === 'L') ? 'Laki-laki' : (($student['jenis_kelamin'] === 'P') ? 'Perempuan' : '-') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Status Anak</td>
                        <td class="colon">:</td>
                        <td class="value">Anak ke-<?= esc($student['anak_ke'] ?: '0') ?> dari <?= esc($student['jumlah_saudara'] ?: '0') ?> bersaudara</td>
                    </tr>
                    <tr>
                        <td class="label">No. HP / WA</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['no_hp'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Alamat Lengkap</td>
                        <td class="colon">:</td>
                        <td class="value address-val"><?= esc($student['alamat'] ?: '-') ?></td>
                    </tr>
                </table>
            </div>
            
            <div class="pas-foto-container">
                <?php if (!empty($student['pas_foto'])): ?>
                <div class="pas-foto-box">
                    <img src="<?= base_url(UPLOAD_PATH . $student['pas_foto']) ?>" alt="Pas Foto Siswa">
                </div>
                <?php else: ?>
                <div class="pas-foto-placeholder">
                    Pas Foto<br>3 x 4
                </div>
                <?php endif; ?>
                <div class="pas-foto-label">Calon Siswa</div>
            </div>
        </div>

        <div class="grid">
            <!-- Left Column: Data Orang Tua & Asal Sekolah -->
            <div class="col-6">
                <div class="section-title">B. Data Orang Tua & Wali</div>
                <table class="info-table">
                    <tr>
                        <td class="label">Nama Ayah</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['nama_ayah'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Pekerjaan Ayah</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['pekerjaan_ayah'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Nama Ibu</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['nama_ibu'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Pekerjaan Ibu</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['pekerjaan_ibu'] ?: '-') ?></td>
                    </tr>
                </table>

                <div class="section-title">C. Data Asal Sekolah</div>
                <table class="info-table">
                    <tr>
                        <td class="label">Nama Sekolah</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['nama_sekolah'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Alamat Sekolah</td>
                        <td class="colon">:</td>
                        <td class="value address-val"><?= esc($student['alamat_sekolah'] ?: '-') ?></td>
                    </tr>
                </table>
            </div>

            <!-- Right Column: Kelengkapan Berkas & Status Pendaftaran -->
            <div class="col-6">
                <div class="section-title">D. Kelengkapan Berkas Dokumen</div>
                <table class="info-table">
                    <tr>
                        <td class="label" style="width: 45%;">Scan Ijazah / SKL</td>
                        <td class="colon">:</td>
                        <td class="value">
                            <span class="badge badge-<?= (!empty($student['fc_ijazah_skl'])) ? 'valid' : 'revisi' ?>">
                                <?= (!empty($student['fc_ijazah_skl'])) ? 'SUDAH DIUNGGAH' : 'BELUM DIUNGGAH' ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label" style="width: 45%;">Scan Kartu Keluarga</td>
                        <td class="colon">:</td>
                        <td class="value">
                            <span class="badge badge-<?= (!empty($student['fc_kk'])) ? 'valid' : 'revisi' ?>">
                                <?= (!empty($student['fc_kk'])) ? 'SUDAH DIUNGGAH' : 'BELUM DIUNGGAH' ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label" style="width: 45%;">Pas Foto 3x4</td>
                        <td class="colon">:</td>
                        <td class="value">
                            <span class="badge badge-<?= (!empty($student['pas_foto'])) ? 'valid' : 'revisi' ?>">
                                <?= (!empty($student['pas_foto'])) ? 'SUDAH DIUNGGAH' : 'BELUM DIUNGGAH' ?>
                            </span>
                        </td>
                    </tr>
                </table>

                <div class="section-title">E. Status Kelayakan / Kelulusan</div>
                <div class="status-card-new">
                    <div class="status-item-new border-b">
                        <span class="status-label-new">Finalisasi</span>
                        <span class="badge badge-<?= $student['is_finalized'] ? 'valid' : 'revisi' ?>">
                            <?= $student['is_finalized'] ? 'SUDAH FINALISASI' : 'BELUM FINALISASI' ?>
                        </span>
                    </div>
                    <div class="status-item-new border-b">
                        <span class="status-label-new">Verifikasi Berkas</span>
                        <?php 
                        $status = $student['status_verifikasi'] ?: VERIF_STATUS_BELUM; 
                        $badgeClass = match($status) {
                            'Valid' => 'valid',
                            'Revisi' => 'revisi',
                            default => 'pending',
                        };
                        ?>
                        <span class="badge badge-<?= $badgeClass ?>">
                            <?= strtoupper($status) ?>
                        </span>
                    </div>

                </div>
            </div>
        </div>

        <!-- Signatures / Lembar Tanda Tangan -->
        <div class="sign-area">
            <div class="sign-box">
                <p>Calon Peserta Didik,</p>
                <div class="space"></div>
                <p class="sign-line">__________________________</p>
                <p class="sign-name"><?= esc($student['nama_lengkap'] ?: $student['username']) ?></p>
            </div>
            <div class="sign-box">
                <p><?= esc($kota_ttd) ?>, <?= date('d F Y') ?></p>
                <p>Ketua Panitia SPMB <?= esc($logoText) ?>,</p>
                <div class="space"></div>
                <p class="sign-line">__________________________</p>
                <p class="sign-name">(<?= esc($ketua_panitia) ?>)</p>
            </div>
        </div>
    </div>
</body>
</html>
