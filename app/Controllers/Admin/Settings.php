<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\SettingsService;
use CodeIgniter\HTTP\ResponseInterface;

class Settings extends BaseController
{
    protected $helpers = ['url', 'html', 'form'];

    protected SettingsService $settingsService;

    public function __construct()
    {
        $this->settingsService = new SettingsService();
    }

    public function index(): ResponseInterface
    {
        $this->requireAdmin();
        $shieldUser = auth()->user();

        $settings = $this->loadAllSettings();

        return $this->response->setBody(view('admin/form-settings', [
            ...$this->getSiteSettings(),
            'admin_username' => $shieldUser->username,
            'user_email'     => $shieldUser->email,
            'roleLabel'      => $shieldUser->inGroup(ROLE_ADMIN) ? 'Administrator' : 'Panitia SPMB',
            'isAdmin'        => $shieldUser->inGroup(ROLE_ADMIN),
            'settings'       => $settings,
        ]));
    }

    public function save(): ResponseInterface
    {
        $this->requireAdmin();

        $rules = $this->buildSaveRules();

        if (! $this->validateData($this->request->getPost(), $rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->settingsService->update($this->request->getPost([
            'is_open', 'tahun_ajaran', 'frontend_title', 'frontend_subtitle',
            'logo_text', 'step1_title', 'step1_desc', 'step1_icon',
            'step2_title', 'step2_desc', 'step2_icon',
            'step3_title', 'step3_desc', 'step3_icon',
            'step4_title', 'step4_desc', 'step4_icon',
            'req1_title', 'req1_desc', 'req1_icon',
            'req2_title', 'req2_desc', 'req2_icon',
            'req3_title', 'req3_desc', 'req3_icon',
            'footer_phone', 'footer_email', 'footer_address', 'footer_desc',
        ]));

        $kopContent = $this->settingsService->sanitizeKopContent($this->request->getPost('kop_content'));
        $this->settingsService->update([
            'kop_content'   => $kopContent,
            'ketua_panitia' => $this->request->getPost('ketua_panitia'),
            'kota_ttd'      => $this->request->getPost('kota_ttd'),
        ]);

        $logoFile = $this->request->getFile('logo_file');
        if ($logoFile && $logoFile->isValid()) {
            $this->settingsService->uploadImage($logoFile, 'logo_img', 'logo_');
        }

        $heroFile = $this->request->getFile('hero_file');
        if ($heroFile && $heroFile->isValid()) {
            $this->settingsService->uploadImage($heroFile, 'hero_img', 'hero_');
        }

        for ($i = 1; $i <= 4; $i++) {
            $galleryFile = $this->request->getFile("gallery_file_{$i}");
            if ($galleryFile && $galleryFile->isValid()) {
                $this->settingsService->uploadImage($galleryFile, "gallery_img_{$i}", "gallery_{$i}_");
            }
        }

        return redirect()->to(base_url('admin/settings'))
            ->with('success', 'Seluruh konfigurasi frontend berhasil diperbarui secara real-time!');
    }

    private function loadAllSettings(): array
    {
        return [
            'is_open'           => $this->settingsService->get(SETTING_IS_OPEN, '1'),
            'tahun_ajaran'      => $this->settingsService->get(SETTING_TAHUN_AJARAN, '2026/2027'),
            'frontend_title'    => $this->settingsService->get('frontend_title', 'Raih Masa Depan Gemilang di SMA Unggul Bangsa'),
            'frontend_subtitle' => $this->settingsService->get('frontend_subtitle', 'Bergabunglah dengan komunitas pembelajar yang dinamis dan berprestasi. Program kurikulum berstandar tinggi serta fasilitas modern kami siap mempersiapkan Anda untuk mencapai keunggulan akademik dan karakter.'),
            'logo_text'         => $this->settingsService->get(SETTING_LOGO_TEXT, 'SPMB Unggul Bangsa'),
            'logo_img'          => $this->settingsService->get('logo_img', ''),
            'hero_img'          => $this->settingsService->get('hero_img', ''),
            'kop_content'       => $this->settingsService->get('kop_content', '<h2 style="margin:0; font-size:18px; text-transform:uppercase;">PANITIA PENERIMAAN SISWA BARU (SPMB)</h2><h3 style="margin:2px 0; font-size:16px; font-weight:600;">SMA UNGGUL BANGSA</h3><p style="margin:2px 0;">Jl. Raya Pendidikan No. 45, Jakarta Pusat</p><p style="margin:2px 0;">Telp: (021) 555 0123 | Website: spmb.unggulbangsa.sch.id</p>'),
            'ketua_panitia'     => $this->settingsService->get('ketua_panitia', ''),
            'kota_ttd'          => $this->settingsService->get('kota_ttd', 'Jakarta'),
            'step1_title'       => $this->settingsService->get('step1_title', '1. Registrasi Akun'),
            'step1_desc'        => $this->settingsService->get('step1_desc', 'Buat akun pendaftar secara online menggunakan username, email aktif, dan password Anda.'),
            'step1_icon'        => $this->settingsService->get('step1_icon', 'app_registration'),
            'step2_title'       => $this->settingsService->get('step2_title', '2. Isi Formulir'),
            'step2_desc'        => $this->settingsService->get('step2_desc', 'Masuk ke sistem, lengkapi formulir biodata pribadi, data kedua orang tua/wali, serta asal sekolah.'),
            'step2_icon'        => $this->settingsService->get('step2_icon', 'description'),
            'step3_title'       => $this->settingsService->get('step3_title', '3. Upload & Verifikasi'),
            'step3_desc'        => $this->settingsService->get('step3_desc', 'Unggah pas foto, ijazah/SKL, dan Kartu Keluarga (KK). Pantau proses verifikasi berkas oleh admin.'),
            'step3_icon'        => $this->settingsService->get('step3_icon', 'fact_check'),
            'step4_title'       => $this->settingsService->get('step4_title', '4. Hasil & Dashboard'),
            'step4_desc'        => $this->settingsService->get('step4_desc', 'Unduh formulir pendaftaran PDF, gabung grup WhatsApp resmi, dan cek hasil pengumuman kelulusan.'),
            'step4_icon'        => $this->settingsService->get('step4_icon', 'celebration'),
            'req1_title'        => $this->settingsService->get('req1_title', 'Pas Foto Terbaru'),
            'req1_desc'         => $this->settingsService->get('req1_desc', 'Pas foto berwarna ukuran 3x4 cm dengan latar belakang merah (format JPG/PNG, ukuran maks. 2MB, diambil dalam 3 bulan terakhir).'),
            'req1_icon'         => $this->settingsService->get('req1_icon', 'portrait'),
            'req2_title'        => $this->settingsService->get('req2_title', 'Ijazah SMP / SKL'),
            'req2_desc'         => $this->settingsService->get('req2_desc', 'Salinan ijazah asli atau Surat Keterangan Lulus (SKL) sementara jika ijazah resmi belum diterbitkan sekolah (format PDF/Gambar).'),
            'req2_icon'         => $this->settingsService->get('req2_icon', 'school'),
            'req3_title'        => $this->settingsService->get('req3_title', 'Kartu Keluarga (KK)'),
            'req3_desc'         => $this->settingsService->get('req3_desc', 'Salinan dokumen Kartu Keluarga resmi dari Dinas Kependudukan dan Catatan Sipil setempat.'),
            'req3_icon'         => $this->settingsService->get('req3_icon', 'family_history'),
            'footer_phone'      => $this->settingsService->get('footer_phone', '+62 21 555 0123'),
            'footer_email'      => $this->settingsService->get('footer_email', 'spmb@unggulbangsa.sch.id'),
            'footer_address'    => $this->settingsService->get('footer_address', 'Jl. Pendidikan No. 1, Jakarta'),
            'footer_desc'       => $this->settingsService->get(SETTING_FOOTER_DESC, 'Membentuk generasi pemimpin masa depan yang beriman, cerdas, kreatif, mandiri, dan berwawasan global.'),
            'gallery_img_1'     => $this->settingsService->get('gallery_img_1', ''),
            'gallery_img_2'     => $this->settingsService->get('gallery_img_2', ''),
            'gallery_img_3'     => $this->settingsService->get('gallery_img_3', ''),
            'gallery_img_4'     => $this->settingsService->get('gallery_img_4', ''),
        ];
    }

    private function buildSaveRules(): array
    {
        $rules = [
            'is_open'           => 'required|in_list[0,1]',
            'tahun_ajaran'      => 'required|min_length[4]|max_length[15]',
            'frontend_title'    => 'required|min_length[5]|max_length[255]',
            'frontend_subtitle' => 'required|min_length[10]',
            'logo_text'         => 'required|min_length[3]|max_length[100]',
            'step1_title'       => 'required|min_length[3]|max_length[100]',
            'step1_desc'        => 'required|min_length[5]|max_length[255]',
            'step1_icon'        => 'required|min_length[3]|max_length[50]',
            'step2_title'       => 'required|min_length[3]|max_length[100]',
            'step2_desc'        => 'required|min_length[5]|max_length[255]',
            'step2_icon'        => 'required|min_length[3]|max_length[50]',
            'step3_title'       => 'required|min_length[3]|max_length[100]',
            'step3_desc'        => 'required|min_length[5]|max_length[255]',
            'step3_icon'        => 'required|min_length[3]|max_length[50]',
            'step4_title'       => 'required|min_length[3]|max_length[100]',
            'step4_desc'        => 'required|min_length[5]|max_length[255]',
            'step4_icon'        => 'required|min_length[3]|max_length[50]',
            'req1_title'        => 'required|min_length[3]|max_length[100]',
            'req1_desc'         => 'required|min_length[5]|max_length[255]',
            'req1_icon'         => 'required|min_length[3]|max_length[50]',
            'req2_title'        => 'required|min_length[3]|max_length[100]',
            'req2_desc'         => 'required|min_length[5]|max_length[255]',
            'req2_icon'         => 'required|min_length[3]|max_length[50]',
            'req3_title'        => 'required|min_length[3]|max_length[100]',
            'req3_desc'         => 'required|min_length[5]|max_length[255]',
            'req3_icon'         => 'required|min_length[3]|max_length[50]',
            'footer_phone'      => 'required|min_length[5]|max_length[50]',
            'footer_email'      => 'required|valid_email|max_length[100]',
            'footer_address'    => 'required|min_length[5]|max_length[255]',
            'footer_desc'       => 'required|min_length[10]|max_length[500]',
            'kop_content'       => 'permit_empty|max_length[5000]',
            'ketua_panitia'     => 'permit_empty|max_length[200]',
            'kota_ttd'          => 'permit_empty|max_length[100]',
        ];

        $logoFile = $this->request->getFile('logo_file');
        if ($logoFile && $logoFile->isValid()) {
            $rules['logo_file'] = 'is_image[logo_file]|mime_in[logo_file,image/png,image/jpg,image/jpeg]|max_size[logo_file,2048]';
        }

        $heroFile = $this->request->getFile('hero_file');
        if ($heroFile && $heroFile->isValid()) {
            $rules['hero_file'] = 'is_image[hero_file]|mime_in[hero_file,image/png,image/jpg,image/jpeg]|max_size[hero_file,4096]';
        }

        for ($i = 1; $i <= 4; $i++) {
            $galleryFile = $this->request->getFile("gallery_file_{$i}");
            if ($galleryFile && $galleryFile->isValid()) {
                $rules["gallery_file_{$i}"] = 'is_image[gallery_file_' . $i . ']|mime_in[gallery_file_' . $i . ',image/png,image/jpg,image/jpeg]|max_size[gallery_file_' . $i . ',2048]';
            }
        }

        return $rules;
    }
}
