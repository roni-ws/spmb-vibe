<?php
use App\Models\SettingsModel;

// Ambil data pengaturan Kop Surat dari database secara dinamis dengan fallback data default yang rapi
$kopNama    = SettingsModel::getSetting('kop_nama', "PANITIA PENERIMAAN SISWA BARU (SPMB)\nSMA UNGGUL BANGSA");
$kopAlamat  = SettingsModel::getSetting('kop_alamat', 'Jl. Raya Pendidikan No. 45, Jakarta Pusat');
$kopTelepon = SettingsModel::getSetting('kop_telepon', 'Telp: (021) 555 0123');
$kopWebsite = SettingsModel::getSetting('kop_website', 'Website: spmb.unggulbangsa.sch.id');

// Determine Logo URL
$logoImg = SettingsModel::getSetting('logo_img');

if (!empty($logoImg) && file_exists(FCPATH . $logoImg)) {
    $logoUrl = base_url($logoImg);
} else {
    // Fallback default logo dari Prestige Academy / SPMB
    $logoUrl = 'https://lh3.googleusercontent.com/aida-public/AB6AXuD-kgMEmSQjBKy4Tre7YWucX09yezgvRCPorvwnk9Q6TLStxR8R32stGse6lp6DAdp4Uq5-VOWYmP2FzGs8tlHztOgrAtOSraiZeyOQO7qHdktyS7UrxxyhIBqqOP0ngRDJuWiL7STSSsbB-3fjDIaJ7cbxulqHfEkmwXv9wrnnXnt7bZ9arT9KnmK8zlZtthr_OL0ONknlQQ-WUd9uzIIwpV8YVKfZr4mumr4LM4GXX7DHTTUdw4GHwjzNB8WoGkaJ49Q9V1o1HC4';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Bukti Pendaftaran SPMB - <?= esc($student['nama_lengkap'] ?: $student['username']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 20px;
            color: #333;
            background-color: #f8fafc;
        }
        .container {
            width: 100%;
            max-width: 840px;
            margin: 0 auto;
            border: 2px solid #002045;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            box-sizing: border-box;
            background-color: #fff;
            position: relative;
        }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 3px double #002045;
            padding-bottom: 15px;
            margin-bottom: 20px;
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
        .header-text h1 {
            margin: 0;
            font-size: 17px;
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
        .slip-title {
            text-align: center;
            text-transform: uppercase;
            font-size: 15px;
            color: #002045;
            font-weight: 800;
            margin-bottom: 20px;
            letter-spacing: 1.5px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
        }
        .biodata-wrapper {
            display: flex;
            gap: 24px;
            margin-bottom: 20px;
            align-items: flex-start;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
        }
        .biodata-content {
            flex-grow: 1;
        }
        .pas-foto-container {
            width: 120px;
            text-align: center;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
        }
        .pas-foto-box {
            width: 100px;
            height: 133px;
            border: 2px solid #002045;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .pas-foto-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .pas-foto-placeholder {
            width: 100px;
            height: 133px;
            border: 2px dashed #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            background-color: #f1f5f9;
        }
        .pas-foto-label {
            font-size: 9px;
            color: #64748b;
            margin-top: 6px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .grid {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -12px;
        }
        .col-6 {
            width: 50%;
            padding: 0 12px;
            box-sizing: border-box;
        }
        .section-title {
            font-size: 12px;
            font-weight: 800;
            color: #002045;
            border-bottom: 2px solid #002045;
            padding-bottom: 4px;
            margin-top: 10px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .info-table td {
            padding: 5px 0;
            font-size: 11.5px;
            vertical-align: top;
            line-height: 1.4;
        }
        .info-table td.label {
            width: 38%;
            color: #4b5563;
            font-weight: 500;
        }
        .info-table td.colon {
            width: 4%;
            color: #4b5563;
        }
        .info-table td.value {
            color: #1e293b;
            font-weight: 700;
        }
        .info-table td.value.text-primary {
            color: #002045;
        }
        .info-table td.value.text-large {
            font-size: 13px;
            color: #002045;
        }
        .info-table td.value.address-val {
            font-size: 10.5px;
            font-weight: 500;
            color: #334155;
        }
        .status-card-new {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 8px;
        }
        .status-item-new {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 14px;
        }
        .status-item-new.border-b {
            border-bottom: 1px solid #e2e8f0;
        }
        .status-label-new {
            font-size: 11px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 800;
            text-align: center;
            border: 1px solid transparent;
        }
        .badge-valid { background-color: #d1fae5; color: #065f46; border-color: #a7f3d0; }
        .badge-pending { background-color: #fef3c7; color: #92400e; border-color: #fde68a; }
        .badge-revisi { background-color: #fee2e2; color: #991b1b; border-color: #fca5a5; }
        
        .badge-finalized { background-color: #d1fae5; color: #065f46; border-color: #a7f3d0; }
        .badge-lulus { background-color: #d1fae5; color: #065f46; border-color: #a7f3d0; }
        .badge-tidak-lulus { background-color: #fee2e2; color: #991b1b; border-color: #fca5a5; }
        .badge-belumpengumuman { background-color: #e2e8f0; color: #475569; border-color: #cbd5e1; }
        .badge-belum-pengumuman { background-color: #e2e8f0; color: #475569; border-color: #cbd5e1; }

        .sign-area {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding: 0 15px;
        }
        .sign-box {
            text-align: center;
            font-size: 11.5px;
            width: 42%;
            color: #1e293b;
        }
        .sign-box p {
            margin: 2px 0;
        }
        .sign-box .space {
            height: 60px;
        }
        .sign-box .sign-line {
            color: #64748b;
        }
        .sign-box .sign-name {
            font-weight: 700;
            color: #002045;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                margin: 0;
                background-color: #fff;
            }
            .container {
                border: 2px solid #002045;
                box-shadow: none;
                max-width: 100%;
                padding: 20px;
            }
        }
        .no-print-btn {
            background-color: #002045;
            color: #fff;
            border: none;
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 600;
            transition: opacity 0.2s;
        }
        .no-print-btn:hover {
            opacity: 0.9;
        }
    </style>
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
                <h1><?= nl2br(esc($kopNama)) ?></h1>
                <p><?= esc($kopAlamat) ?></p>
                <p class="contact-info">
                    <?php 
                    $contacts = [];
                    if (!empty($kopTelepon)) $contacts[] = esc($kopTelepon);
                    if (!empty($kopWebsite)) $contacts[] = esc($kopWebsite);
                    echo implode(' | ', $contacts);
                    ?>
                </p>
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
                        <td class="value text-primary">SPMB-2026-<?= sprintf('%04d', $student['id']) ?></td>
                    </tr>
                    <tr>
                        <td class="label">NISN (Username)</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['nisn'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Nama Lengkap</td>
                        <td class="colon">:</td>
                        <td class="value text-large"><?= esc($student['nama_lengkap'] ?: 'Belum Mengisi') ?></td>
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
                    <img src="<?= base_url('uploads/berkas/' . $student['pas_foto']) ?>" alt="Pas Foto Siswa">
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
                        $status = $student['status_verifikasi'] ?: 'Belum Verifikasi'; 
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
                    <div class="status-item-new">
                        <span class="status-label-new">Kelulusan</span>
                        <?php $lulus = $student['status_kelulusan'] ?: 'Belum Pengumuman'; ?>
                        <span class="badge badge-<?= strtolower(str_replace(' ', '-', $lulus)) ?>">
                            <?= strtoupper($lulus) ?>
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
                <p><?= esc(SettingsModel::getSetting('footer_address', 'Jakarta')) ?>, <?= date('d F Y') ?></p>
                <p>Panitia PPDB <?= esc(SettingsModel::getSetting('logo_text', 'SMA Unggul Bangsa')) ?>,</p>
                <div class="space"></div>
                <p class="sign-line">__________________________</p>
                <p class="sign-name">NIP. PANITIA-PPDB-2026</p>
            </div>
        </div>
    </div>
</body>
</html>
