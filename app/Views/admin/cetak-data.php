<?php
use App\Models\SettingsModel;

include __DIR__ . '/../components/shared/kop_surat.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Calon Siswa Baru SPMB</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 20px;
            color: #333;
            background-color: #fff;
        }
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
        h2 {
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 30px;
        }
        th {
            background-color: #002045;
            color: #fff;
            padding: 8px 6px;
            text-align: left;
            font-weight: 600;
        }
        td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-valid {
            color: #2e7d32;
            font-weight: 600;
        }
        .status-invalid {
            color: #c62828;
            font-weight: 600;
        }
        .status-pending {
            color: #f57f17;
            font-weight: 600;
        }
        .finalized {
            color: #2e7d32;
        }
        .not-finalized {
            color: #c62828;
        }
        .sign-area {
            display: flex;
            justify-content: flex-end;
            margin-top: 40px;
        }
        .sign-box {
            text-align: center;
            width: 250px;
        }
        .space {
            height: 60px;
        }
        .sign-line {
            margin: 0;
            font-size: 13px;
        }
        .sign-name {
            margin: 5px 0 0;
            font-weight: 600;
            font-size: 13px;
        }
        @media print {
            @page { margin: 0; }
            body { padding: 5mm; margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Daftar Calon Siswa Baru Terdaftar (SPMB)</h2>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Pendaftaran</th>
                    <th>Nama Lengkap</th>
                    <th>NISN</th>
                    <th>NIK</th>
                    <th>No. HP</th>
                    <th>Asal Sekolah</th>
                    <th>Status Berkas</th>
                    <th>Finalisasi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($students as $s): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($s['id']) ?></td>
                    <td><?= esc($s['nama_lengkap']) ?></td>
                    <td><?= esc($s['username'] ?? '-') ?></td>
                    <td><?= esc($s['nik'] ?? '-') ?></td>
                    <td><?= esc($s['no_hp'] ?? '-') ?></td>
                    <td><?= esc($s['nama_sekolah'] ?? '-') ?></td>
                    <td class="<?= $s['status_verifikasi'] === 'Valid' ? 'status-valid' : ($s['status_verifikasi'] === 'Tidak Valid' ? 'status-invalid' : 'status-pending') ?>">
                        <?= esc($s['status_verifikasi'] ?? 'Belum Verifikasi') ?>
                    </td>
                    <td class="<?= ($s['is_finalized'] ?? 0) ? 'finalized' : 'not-finalized' ?>">
                        <?= ($s['is_finalized'] ?? 0) ? 'Sudah' : 'Belum' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p style="text-align:right; font-size:11px; color:#666;">
            Dicetak pada: <?= date('d F Y H:i:s') ?>
        </p>

        <div class="sign-area">
            <div class="sign-box">
                <p><?= esc(SettingsModel::getSetting('footer_address', 'Jakarta')) ?>, <?= date('d F Y') ?></p>
                <p>Panitia PPDB <?= esc(SettingsModel::getSetting('logo_text', 'SMA Unggul Bangsa')) ?>,</p>
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
