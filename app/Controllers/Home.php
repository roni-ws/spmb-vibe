<?php

namespace App\Controllers;

class Home extends BaseController
{
    protected $helpers = ['url', 'html'];

    public function index(): string
    {
        $data = [
            'is_open'           => \App\Models\SettingsModel::getSetting('is_open', '1') === '1',
            'tahun_ajaran'      => \App\Models\SettingsModel::getSetting('tahun_ajaran', '2026/2027'),
            'frontend_title'    => \App\Models\SettingsModel::getSetting('frontend_title', 'Raih Masa Depan Gemilang di SMA Unggul Bangsa'),
            'frontend_subtitle' => \App\Models\SettingsModel::getSetting('frontend_subtitle', 'Bergabunglah dengan komunitas pembelajar yang dinamis dan berprestasi. Program kurikulum berstandar tinggi serta fasilitas modern kami siap mempersiapkan Anda untuk mencapai keunggulan akademik dan karakter.'),
        ];
        return view('public/home', $data);
    }
}

