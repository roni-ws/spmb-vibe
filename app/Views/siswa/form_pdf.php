<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Bukti Pendaftaran SPMB - <?= esc($student['nama_lengkap'] ?: $student['username']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #002045;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            box-sizing: border-box;
        }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 3px double #002045;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header-logo {
            width: 70px;
            height: 70px;
            margin-right: 20px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #002045;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header-logo img {
            width: 100%;
            height: 100%;
            object-cover: cover;
        }
        .header-text {
            flex-grow: 1;
        }
        .header-text h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            color: #002045;
        }
        .header-text h2 {
            margin: 3px 0 0;
            font-size: 14px;
            color: #333;
        }
        .header-text p {
            margin: 3px 0 0;
            font-size: 11px;
            color: #666;
        }
        .slip-title {
            text-align: center;
            text-transform: uppercase;
            font-size: 16px;
            color: #002045;
            font-weight: bold;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }
        .grid {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        .col-6 {
            width: 50%;
            padding: 0 10px;
            box-sizing: border-box;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #002045;
            border-bottom: 2px solid #dee8ff;
            padding-bottom: 5px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 6px 0;
            font-size: 12px;
            vertical-align: top;
        }
        .info-table td.label {
            width: 35%;
            color: #666;
            font-weight: normal;
        }
        .info-table td.colon {
            width: 3%;
            color: #666;
        }
        .info-table td.value {
            color: #111;
            font-weight: bold;
        }
        .card-status {
            background-color: #f0f3ff;
            border: 1px solid #c4c6cf;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        .status-box {
            text-align: center;
        }
        .status-box p {
            margin: 0 0 5px;
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }
        .status-box h3 {
            margin: 0;
            font-size: 14px;
            color: #002045;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-valid { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-pending { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-revisi { background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        .badge-lulus { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-tidak-lulus { background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .badge-belum { background-color: #e2e8f0; color: #475569; border: 1px solid #cbd5e1; }

        .sign-area {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding: 0 20px;
        }
        .sign-box {
            text-align: center;
            font-size: 12px;
            width: 40%;
        }
        .sign-box .space {
            height: 70px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
            }
            .container {
                border: 2px solid #000;
                box-shadow: none;
                max-width: 100%;
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
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right; max-width: 800px; margin: 0 auto;">
        <button class="no-print-btn" onclick="window.print()">Cetak Kartu Pendaftaran (Print/PDF)</button>
        <button class="no-print-btn" style="background-color: #4b5563;" onclick="window.history.back()">Kembali</button>
    </div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-logo">
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuD-kgMEmSQjBKy4Tre7YWucX09yezgvRCPorvwnk9Q6TLStxR8R32stGse6lp6DAdp4Uq5-VOWYmP2FzGs8tlHztOgrAtOSraiZeyOQO7qHdktyS7UrxxyhIBqqOP0ngRDJuWiL7STSSsbB-3fjDIaJ7cbxulqHfEkmwXv9wrnnXnt7bZ9arT9KnmK8zlZtthr_OL0ONknlQQ-WUd9uzIIwpV8YVKfZr4mumr4LM4GXX7DHTTUdw4GHwjzNB8WoGkaJ49Q9V1o1HC4" alt="Prestige Academy logo">
            </div>
            <div class="header-text">
                <h1>PANITIA PENERIMAAN SISWA BARU (SPMB)</h1>
                <h2>SMA UNGGUL BANGSA</h2>
                <p>Alamat: Jl. Raya Pendidikan No. 45, Jakarta Pusat | Website: spmb.unggulbangsa.sch.id</p>
            </div>
        </div>

        <div class="slip-title">
            Kartu Bukti Pendaftaran Calon Siswa Baru
        </div>

        <div class="grid">
            <!-- Left Column: Biodata -->
            <div class="col-6">
                <div class="section-title">A. Biodata Calon Siswa</div>
                <table class="info-table">
                    <tr>
                        <td class="label">ID Pendaftaran</td>
                        <td class="colon">:</td>
                        <td class="value">SPMB-2026-<?= sprintf('%04d', $student['id']) ?></td>
                    </tr>
                    <tr>
                        <td class="label">Nama Lengkap</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['nama_lengkap'] ?: 'Belum Mengisi') ?></td>
                    </tr>
                    <tr>
                        <td class="label">NIK</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['nik'] ?: '-') ?></td>
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
                        <td class="label">No. HP / WA</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['no_hp'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Alamat Lengkap</td>
                        <td class="colon">:</td>
                        <td class="value" style="font-size: 11px; font-weight: normal;"><?= esc($student['alamat'] ?: '-') ?></td>
                    </tr>
                </table>
            </div>

            <!-- Right Column: Asal Sekolah & Wali -->
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
                        <td class="label">NPSN Sekolah</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['npsn'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Tahun Lulus</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['tahun_lulus'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <td class="label">Rata-rata Rapor</td>
                        <td class="colon">:</td>
                        <td class="value"><?= esc($student['nilai_rata_rata'] ? number_format($student['nilai_rata_rata'], 2) : '-') ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Status Card -->
        <div class="card-status">
            <div class="status-box">
                <p>Status Finalisasi</p>
                <h3>
                    <span class="badge badge-<?= $student['is_finalized'] ? 'valid' : 'revisi' ?>">
                        <?= $student['is_finalized'] ? 'SUDAH FINALISASI' : 'BELUM FINALISASI' ?>
                    </span>
                </h3>
            </div>
            <div class="status-box">
                <p>Status Verifikasi Berkas</p>
                <h3>
                    <?php $status = $student['status_verifikasi'] ?: 'Pending'; ?>
                    <span class="badge badge-<?= strtolower(str_replace(' ', '', $status)) ?>">
                        <?= $status ?>
                    </span>
                </h3>
            </div>
            <div class="status-box">
                <p>Status Kelulusan</p>
                <h3>
                    <?php $lulus = $student['status_kelulusan'] ?: 'Belum Pengumuman'; ?>
                    <span class="badge badge-<?= strtolower(str_replace(' ', '-', $lulus)) ?>">
                        <?= $lulus ?>
                    </span>
                </h3>
            </div>
        </div>

        <!-- Signatures -->
        <div class="sign-area">
            <div class="sign-box">
                <p>Calon Peserta Didik,</p>
                <div class="space"></div>
                <p>__________________________</p>
                <p><?= esc($student['nama_lengkap'] ?: $student['username']) ?></p>
            </div>
            <div class="sign-box">
                <p>Jakarta, <?= date('d F Y') ?></p>
                <p>Panitia PPDB SMA Unggul Bangsa,</p>
                <div class="space"></div>
                <p>__________________________</p>
                <p>NIP. PANITIA-PPDB-2026</p>
            </div>
        </div>
    </div>
</body>
</html>
