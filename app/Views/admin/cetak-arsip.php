<?php
use App\Models\SettingsModel;
include __DIR__ . '/../components/shared/kop_surat.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Pendaftaran - <?= esc($student['nama_lengkap']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 20px;
            color: #333;
            background-color: #fff;
        }
        .container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            padding: 10px 20px;
            box-sizing: border-box;
        }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 3px double #002045;
            padding-bottom: 15px;
            margin-bottom: 10px;
        }
        .header-logo {
            width: 80px;
            height: 80px;
            margin-right: 25px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #002045;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }
        .header-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 4px;
        }

        .header-text {
            flex-grow: 1;
        }
        .header-text h1,
        .header-text h2,
        .header-text h3,
        .header-text h4 {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            color: #002045;
            line-height: 1.3;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header-text p {
            margin: 4px 0 0;
            font-size: 11px;
            color: #4b5563;
            line-height: 1.4;
        }

        .header-text p.contact-info {
            font-weight: 600;
            color: #1e293b;
        }

        /* Quill alignment classes */
        .ql-align-left { text-align: left; }
        .ql-align-center { text-align: center; }
        .ql-align-right { text-align: right; }
        .ql-align-justify { text-align: justify; }

        .arsip-title {
            text-align: center;
            text-transform: uppercase;
            font-size: 16px;
            color: #002045;
            font-weight: 800;
            margin-bottom: 4px;
            letter-spacing: 1px;
            padding-bottom: 8px;
        }
        .print-date {
            text-align: right;
            font-size: 11px;
            color: #4b5563;
            margin-bottom: 8px;
            font-style: italic;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 12px;
        }
        .data-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            vertical-align: top;
        }
        .data-table .label {
            width: 120px;
            font-weight: 700;
            color: #002045;
            background-color: #f8fafc;
        }
        .sections-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 10px;
        }
        .section-card {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 12px;
            break-inside: avoid;
        }
        .section-title {
            font-size: 13px;
            font-weight: 800;
            color: #002045;
            margin: 0 0 4px;
            padding-bottom: 4px;
            border-bottom: 2px solid #002045;
            text-transform: uppercase;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
            text-align: center;
            border: 1px solid transparent;
            text-transform: uppercase;
        }
        .badge-valid { background-color: #d1fae5; color: #065f46; border-color: #a7f3d0; }
        .badge-pending { background-color: #fef3c7; color: #92400e; border-color: #fde68a; }
        .badge-revisi { background-color: #fee2e2; color: #991b1b; border-color: #fca5a5; }
        .badge-belum { background-color: #f1f5f9; color: #475569; border-color: #cbd5e1; }
        .foto-container {
            display: flex;
            justify-content: center;
            margin: 8px 0 20px;
        }
        .foto-container img {
            width: 100px;
            height: 130px;
            object-fit: cover;
            border: 2px solid #cbd5e1;
            border-radius: 4px;
        }
        .file-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #1d4ed8;
            font-weight: 600;
            text-decoration: none;
        }
        .file-link:hover {
            text-decoration: underline;
        }
        .sign-area {
            display: flex;
            justify-content: flex-end;
            margin-top: 40px;
            padding-right: 20px;
        }
        .sign-box {
            text-align: center;
            font-size: 12px;
            width: 250px;
            color: #1e293b;
        }
        .sign-box p {
            margin: 2px 0;
        }
        .sign-box .space {
            height: 70px;
        }
        .sign-box .sign-line {
            color: #64748b;
        }
        .sign-box .sign-name {
            font-weight: 700;
            color: #002045;
        }

        .no-print {
            text-align: right;
            margin-bottom: 20px;
        }
        .no-print-btn {
            background-color: #002045;
            color: #fff;
            border: none;
            padding: 10px 15px;
            font-size: 13px;
            cursor: pointer;
            border-radius: 6px;
            font-weight: 600;
            transition: opacity 0.2s;
            margin-left: 5px;
        }
        .no-print-btn:hover {
            opacity: 0.9;
        }

        @media print {
            @page {
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
            body {
                padding: 5mm 8mm;
                margin: 0;
            }
            .container {
                max-width: 100%;
                padding: 0;
            }
            .header {
                margin-bottom: 4px;
                padding-bottom: 8px;
            }
            .header-text h1,
            .header-text h2,
            .header-text h3,
            .header-text h4 {
                font-size: 14px;
            }
            .header-text p {
                font-size: 9px;
                margin: 2px 0 0;
            }
            .arsip-title {
                margin-bottom: 2px;
                font-size: 13px;
                padding-bottom: 4px;
            }
            .print-date {
                margin-bottom: 4px;
                font-size: 10px;
            }
            .foto-container {
                margin: 4px 0 12px;
            }
            .foto-container img {
                width: 70px;
                height: 90px;
            }
            .sections-grid {
                gap: 5px;
                margin-top: 4px;
            }
            .section-card {
                padding: 6px 8px;
            }
            .section-title {
                font-size: 11px;
                margin: 0 0 4px;
                padding-bottom: 2px;
            }
            .data-table td {
                padding: 5px 7px;
                font-size: 10px;
            }
            .data-table .label {
                width: 110px;
            }
            .sections-grid {
                gap: 6px;
            }
            .sign-area {
                margin-top: 20px;
            }
            .sign-box .space {
                height: 35px;
            }
        }
    </style>
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

        <?php $s = $student; ?>

        <!-- Pas Foto -->
        <?php if (!empty($s['pas_foto'])): ?>
            <div class="foto-container">
                <img src="<?= base_url('uploads/berkas/' . $s['pas_foto']) ?>" alt="Pas Foto">
            </div>
        <?php endif; ?>

        <div class="sections-grid">

        <!-- Biodata -->
        <div class="section-card">
        <div class="section-title">A. Biodata</div>
        <table class="data-table">
            <tr><td class="label">No. Registrasi</td><td><?= esc($s['no_registrasi'] ?? '-') ?></td></tr>
            <tr><td class="label">NISN</td><td><?= esc($s['nisn'] ?? '-') ?></td></tr>
            <tr><td class="label">NIK</td><td><?= esc($s['nik'] ?? '-') ?></td></tr>
            <tr><td class="label">No. KK</td><td><?= esc($s['no_kk'] ?? '-') ?></td></tr>
            <tr><td class="label">Nama Lengkap</td><td><?= esc($s['nama_lengkap'] ?? '-') ?></td></tr>
            <tr><td class="label">Tempat, Tanggal Lahir</td><td><?= esc($s['tempat_lahir'] ?? '-') ?>, <?= !empty($s['tanggal_lahir']) ? date('d/m/Y', strtotime($s['tanggal_lahir'])) : '-' ?></td></tr>
            <tr><td class="label">Jenis Kelamin</td><td><?= ($s['jenis_kelamin'] ?? '') === 'L' ? 'Laki-laki' : 'Perempuan' ?></td></tr>
            <tr><td class="label">Anak Ke-</td><td><?= esc($s['anak_ke'] ?? '-') ?></td></tr>
            <tr><td class="label">Jumlah Saudara</td><td><?= esc($s['jumlah_saudara'] ?? '-') ?></td></tr>
            <tr><td class="label">No. Handphone</td><td><?= esc($s['no_hp'] ?? '-') ?></td></tr>
        </table>
        </div>

        <!-- Alamat -->
        <div class="section-card">
        <div class="section-title">B. Alamat</div>
        <table class="data-table">
            <tr><td class="label">Desa</td><td><?= esc($s['alamat_desa'] ?? '-') ?></td></tr>
            <tr><td class="label">Kecamatan</td><td><?= esc($s['alamat_kecamatan'] ?? '-') ?></td></tr>
            <tr><td class="label">Kabupaten / Kota</td><td><?= esc($s['alamat_kabupaten'] ?? '-') ?></td></tr>
        </table>
        </div>

        <!-- Orang Tua -->
        <div class="section-card">
        <div class="section-title">C. Data Orang Tua</div>
        <table class="data-table">
            <tr><td class="label">Nama Ayah</td><td><?= esc($s['nama_ayah'] ?? '-') ?></td></tr>
            <tr><td class="label">Pekerjaan Ayah</td><td><?= esc($s['pekerjaan_ayah'] ?? '-') ?></td></tr>
            <tr><td class="label">Nama Ibu</td><td><?= esc($s['nama_ibu'] ?? '-') ?></td></tr>
            <tr><td class="label">Pekerjaan Ibu</td><td><?= esc($s['pekerjaan_ibu'] ?? '-') ?></td></tr>
        </table>
        </div>

        <!-- Asal Sekolah -->
        <div class="section-card">
        <div class="section-title">D. Asal Sekolah</div>
        <table class="data-table">
            <tr><td class="label">Nama Sekolah</td><td><?= esc($s['nama_sekolah'] ?? '-') ?></td></tr>
            <tr><td class="label">Alamat Sekolah</td><td><?= esc($s['alamat_sekolah'] ?? '-') ?></td></tr>
        </table>
        </div>

        <!-- Dokumen Unggahan -->
        <div class="section-card">
        <div class="section-title">E. Dokumen Unggahan</div>
        <table class="data-table">
            <tr>
                <td class="label">Scan Ijazah / SKL</td>
                <td>
                    <?php if (!empty($s['fc_ijazah_skl'])): ?>
                        <span class="badge badge-valid">SUDAH DIUNGGAH</span>
                        &nbsp;
                        <a href="<?= base_url('uploads/berkas/' . $s['fc_ijazah_skl']) ?>" target="_blank" class="file-link">
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
                    <?php if (!empty($s['fc_kk'])): ?>
                        <span class="badge badge-valid">SUDAH DIUNGGAH</span>
                        &nbsp;
                        <a href="<?= base_url('uploads/berkas/' . $s['fc_kk']) ?>" target="_blank" class="file-link">
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
                    <?php if (!empty($s['pas_foto'])): ?>
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
                        $st = $s['status_verifikasi'] ?? 'Belum Verifikasi';
                        $stClass = match($st) {
                            'Valid'  => 'badge-valid',
                            'Revisi' => 'badge-revisi',
                            default  => 'badge-pending',
                        };
                    ?>
                    <span class="badge <?= $stClass ?>"><?= strtoupper($st) ?></span>
                </td>
            </tr>
            <tr><td class="label">Finalisasi</td><td><span class="badge <?= $s['is_finalized'] ? 'badge-valid' : 'badge-belum' ?>"><?= $s['is_finalized'] ? 'SUDAH FINAL' : 'BELUM FINAL' ?></span></td></tr>
            <tr><td class="label">Catatan Verifikasi</td><td><?= !empty($s['catatan_verifikasi']) ? nl2br(esc($s['catatan_verifikasi'])) : '-' ?></td></tr>
            <tr><td class="label">Tanggal Daftar</td><td><?= !empty($s['created_at']) ? date('d F Y', strtotime($s['created_at'])) : '-' ?></td></tr>
        </table>
        </div>

        </div>

        <!-- Tanda Tangan -->
        <div class="sign-area">
            <div class="sign-box">
                <p><?= esc(SettingsModel::getSetting('kota_ttd', 'Jakarta')) ?>, <?= date('d F Y') ?></p>
                <p>Ketua Panitia SPMB <?= esc(SettingsModel::getSetting('logo_text', 'SMA Unggul Bangsa')) ?>,</p>
                <div class="space"></div>
                <p class="sign-name">(<?= esc(SettingsModel::getSetting('ketua_panitia', '')) ?>)</p>
            </div>
        </div>
    </div>
</body>
</html>
