<?php

namespace App\Controllers;

use App\Models\SettingsModel;
use CodeIgniter\HTTP\ResponseInterface;

class Home extends BaseController
{
    protected $helpers = ['url', 'html'];

    public function index(): ResponseInterface
    {
        $stepDefaults = [
            1 => ['title' => '1. Registrasi Akun', 'desc' => 'Buat akun pendaftar secara online menggunakan username, email aktif, dan password Anda.', 'icon' => 'app_registration'],
            2 => ['title' => '2. Isi Formulir', 'desc' => 'Masuk ke sistem, lengkapi formulir biodata pribadi, data kedua orang tua/wali, serta asal sekolah.', 'icon' => 'description'],
            3 => ['title' => '3. Upload & Verifikasi', 'desc' => 'Unggah pas foto, ijazah/SKL, dan Kartu Keluarga (KK). Pantau proses verifikasi berkas oleh admin.', 'icon' => 'fact_check'],
            4 => ['title' => '4. Hasil & Dashboard', 'desc' => 'Unduh formulir pendaftaran PDF, gabung grup WhatsApp resmi, dan cek hasil pengumuman kelulusan.', 'icon' => 'celebration'],
        ];

        $reqDefaults = [
            1 => ['title' => 'Pas Foto Terbaru', 'desc' => 'Pas foto berwarna ukuran 3x4 cm dengan latar belakang merah (format JPG/PNG, ukuran maks. 2MB, diambil dalam 3 bulan terakhir).', 'icon' => 'portrait'],
            2 => ['title' => 'Ijazah SMP / SKL', 'desc' => 'Salinan ijazah asli atau Surat Keterangan Lulus (SKL) sementara jika ijazah resmi belum diterbitkan sekolah (format PDF/Gambar).', 'icon' => 'school'],
            3 => ['title' => 'Kartu Keluarga (KK)', 'desc' => 'Salinan dokumen Kartu Keluarga resmi dari Dinas Kependudukan dan Catatan Sipil setempat.', 'icon' => 'family_history'],
        ];

        $stepData = [];
        for ($i = 1; $i <= 4; $i++) {
            $stepData[$i] = [
                'title' => SettingsModel::getSetting("step{$i}_title", $stepDefaults[$i]['title']),
                'desc'  => SettingsModel::getSetting("step{$i}_desc", $stepDefaults[$i]['desc']),
                'icon'  => SettingsModel::getSetting("step{$i}_icon", $stepDefaults[$i]['icon']),
            ];
        }

        $reqData = [];
        for ($i = 1; $i <= 3; $i++) {
            $reqData[$i] = [
                'title' => SettingsModel::getSetting("req{$i}_title", $reqDefaults[$i]['title']),
                'desc'  => SettingsModel::getSetting("req{$i}_desc", $reqDefaults[$i]['desc']),
                'icon'  => SettingsModel::getSetting("req{$i}_icon", $reqDefaults[$i]['icon']),
            ];
        }

        $data = [
            ...$this->getPublicSettings(),
            'tahun_ajaran'      => SettingsModel::getSetting('tahun_ajaran', '2026/2027'),
            'frontend_title'    => SettingsModel::getSetting('frontend_title', 'Raih Masa Depan Gemilang di SMA Unggul Bangsa'),
            'frontend_subtitle' => SettingsModel::getSetting('frontend_subtitle', 'Bergabunglah dengan komunitas pembelajar yang dinamis dan berprestasi. Program kurikulum berstandar tinggi serta fasilitas modern kami siap mempersiapkan Anda untuk mencapai keunggulan akademik dan karakter.'),
            'hero_img'          => SettingsModel::getSetting('hero_img', ''),
            'step_data'         => $stepData,
            'req_data'          => $reqData,
        ];
        return $this->response->setBody(view('public/home', $data));
    }
}

