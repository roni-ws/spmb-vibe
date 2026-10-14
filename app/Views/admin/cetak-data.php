<?php
$footer_address ??= 'Jakarta';
$logoText ??= 'SMA Unggul Bangsa';
include __DIR__ . '/../components/shared/kop_surat.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Calon Siswa Baru SPMB</title>
    <link rel="stylesheet" href="<?= base_url('css/print-data.css') ?>">
</head>
<body>
    <div class="container">
        <h2>Daftar Calon Siswa Baru Terdaftar (SPMB)</h2>

        <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>NISN</th>
                    <th>No. Registrasi</th>
                    <th>NIK</th>
                    <th>No. KK</th>
                    <th>Nama Lengkap</th>
                    <th>Tempat Lahir</th>
                    <th>Tgl Lahir</th>
                    <th>JK</th>
                    <th>Anak Ke-</th>
                    <th>Jml Saudara</th>
                    <th>No. HP</th>
                    <th>Alamat Desa</th>
                    <th>Kecamatan</th>
                    <th>Kabupaten</th>
                    <th>Asal Sekolah</th>
                    <th>Alamat Sekolah</th>
                    <th>Nama Ayah</th>
                    <th>Pekerjaan Ayah</th>
                    <th>Nama Ibu</th>
                    <th>Pekerjaan Ibu</th>
                    <th>Status Berkas</th>
                    <th>Finalisasi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($student['id']) ?></td>
                    <td><?= esc($student['username'] ?? '-') ?></td>
                    <td><?= esc($student['no_registrasi'] ?? '-') ?></td>
                    <td><?= esc($student['nik'] ?? '-') ?></td>
                    <td><?= esc($student['no_kk'] ?? '-') ?></td>
                    <td><?= esc($student['nama_lengkap'] ?? '-') ?></td>
                    <td><?= esc($student['tempat_lahir'] ?? '-') ?></td>
                    <td><?= esc($student['tanggal_lahir'] ?? '-') ?></td>
                    <td><?= esc($student['jenis_kelamin'] ?? '-') ?></td>
                    <td><?= esc($student['anak_ke'] ?? '-') ?></td>
                    <td><?= esc($student['jumlah_saudara'] ?? '-') ?></td>
                    <td><?= esc($student['no_hp'] ?? '-') ?></td>
                    <td><?= esc($student['alamat_desa'] ?? '-') ?></td>
                    <td><?= esc($student['alamat_kecamatan'] ?? '-') ?></td>
                    <td><?= esc($student['alamat_kabupaten'] ?? '-') ?></td>
                    <td><?= esc($student['nama_sekolah'] ?? '-') ?></td>
                    <td><?= esc($student['alamat_sekolah'] ?? '-') ?></td>
                    <td><?= esc($student['nama_ayah'] ?? '-') ?></td>
                    <td><?= esc($student['pekerjaan_ayah'] ?? '-') ?></td>
                    <td><?= esc($student['nama_ibu'] ?? '-') ?></td>
                    <td><?= esc($student['pekerjaan_ibu'] ?? '-') ?></td>
                    <td class="<?= ($student['status_verifikasi'] ?? '') === VERIF_STATUS_VALID ? 'status-valid' : (($student['status_verifikasi'] ?? '') === VERIF_STATUS_TIDAK_VALID ? 'status-invalid' : 'status-pending') ?>">
                        <?= esc($student['status_verifikasi'] ?? VERIF_STATUS_BELUM) ?>
                    </td>
                    <td class="<?= ($student['is_finalized'] ?? 0) ? 'finalized' : 'not-finalized' ?>">
                        <?= ($student['is_finalized'] ?? 0) ? 'Sudah' : 'Belum' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>

        <p style="text-align:right; font-size:11px; color:#666;">
            Dicetak pada: <?= date('d F Y H:i:s') ?>
        </p>

        <div class="sign-area">
            <div class="sign-box">
                <p><?= esc($footer_address) ?>, <?= date('d F Y') ?></p>
                <p>Panitia PPDB <?= esc($logoText) ?>,</p>
                <div class="space"></div>
                <p class="sign-line">__________________________</p>
                <p class="sign-name">Ketua Panitia</p>
            </div>
        </div>
    </div>

    <div class="no-print" style="text-align:center; margin-top:20px;">
        <button onclick="window.print()" style="padding:10px 30px; background:#002045; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:14px;">
            Cetak Halaman (Print/PDF)
        </button>
    </div>
</body>
</html>
