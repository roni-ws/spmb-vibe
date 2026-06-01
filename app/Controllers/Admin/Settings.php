<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingsModel;
use App\Models\PengumumanModel;

class Settings extends BaseController
{
    protected $helpers = ['url', 'html', 'form'];

    public function index()
    {
        // 1. Proteksi rute: Hanya Admin atau Super Admin yang boleh mengatur konfigurasi frontend
        if (! auth()->loggedIn() || (! auth()->user()->inGroup('admin') && ! auth()->user()->inGroup('superadmin'))) {
            return redirect()->to(base_url('admin/dashboard'))
                ->with('error', 'Akses ditolak. Halaman pengaturan hanya dapat diakses oleh Administrator.');
        }

        $shieldUser = auth()->user();

        // 2. Ambil data konfigurasi dari model dengan fallback defaults
        $settings = [
            'is_open'           => SettingsModel::getSetting('is_open', '1'),
            'tahun_ajaran'      => SettingsModel::getSetting('tahun_ajaran', '2026/2027'),
            'frontend_title'    => SettingsModel::getSetting('frontend_title', 'Raih Masa Depan Gemilang di SMA Unggul Bangsa'),
            'frontend_subtitle' => SettingsModel::getSetting('frontend_subtitle', 'Bergabunglah dengan komunitas pembelajar yang dinamis dan berprestasi. Program kurikulum berstandar tinggi serta fasilitas modern kami siap mempersiapkan Anda untuk mencapai keunggulan akademik dan karakter.'),
            
            // Branding Logo & Hero Image
            'logo_text'         => SettingsModel::getSetting('logo_text', 'SPMB Unggul Bangsa'),
            'logo_img'          => SettingsModel::getSetting('logo_img', ''),
            'hero_img'          => SettingsModel::getSetting('hero_img', ''),

            // Alur Pendaftaran (Steps 1-4)
            'step1_title'       => SettingsModel::getSetting('step1_title', '1. Registrasi Akun'),
            'step1_desc'        => SettingsModel::getSetting('step1_desc', 'Buat akun pendaftar secara online menggunakan username, email aktif, dan password Anda.'),
            'step1_icon'        => SettingsModel::getSetting('step1_icon', 'app_registration'),

            'step2_title'       => SettingsModel::getSetting('step2_title', '2. Isi Formulir'),
            'step2_desc'        => SettingsModel::getSetting('step2_desc', 'Masuk ke sistem, lengkapi formulir biodata pribadi, data kedua orang tua/wali, serta asal sekolah.'),
            'step2_icon'        => SettingsModel::getSetting('step2_icon', 'description'),

            'step3_title'       => SettingsModel::getSetting('step3_title', '3. Upload & Verifikasi'),
            'step3_desc'        => SettingsModel::getSetting('step3_desc', 'Unggah pas foto, ijazah/SKL, dan Kartu Keluarga (KK). Pantau proses verifikasi berkas oleh admin.'),
            'step3_icon'        => SettingsModel::getSetting('step3_icon', 'fact_check'),

            'step4_title'       => SettingsModel::getSetting('step4_title', '4. Hasil & Dashboard'),
            'step4_desc'        => SettingsModel::getSetting('step4_desc', 'Unduh formulir pendaftaran PDF, gabung grup WhatsApp resmi, dan cek hasil pengumuman kelulusan.'),
            'step4_icon'        => SettingsModel::getSetting('step4_icon', 'celebration'),

            // Dokumen Persyaratan (Reqs 1-3)
            'req1_title'        => SettingsModel::getSetting('req1_title', 'Pas Foto Terbaru'),
            'req1_desc'         => SettingsModel::getSetting('req1_desc', 'Pas foto berwarna ukuran 3x4 cm dengan latar belakang merah (format JPG/PNG, ukuran maks. 2MB, diambil dalam 3 bulan terakhir).'),
            'req1_icon'         => SettingsModel::getSetting('req1_icon', 'portrait'),

            'req2_title'        => SettingsModel::getSetting('req2_title', 'Ijazah SMP / SKL'),
            'req2_desc'         => SettingsModel::getSetting('req2_desc', 'Salinan ijazah asli atau Surat Keterangan Lulus (SKL) sementara jika ijazah resmi belum diterbitkan sekolah (format PDF/Gambar).'),
            'req2_icon'         => SettingsModel::getSetting('req2_icon', 'school'),

            'req3_title'        => SettingsModel::getSetting('req3_title', 'Kartu Keluarga (KK)'),
            'req3_desc'         => SettingsModel::getSetting('req3_desc', 'Salinan dokumen Kartu Keluarga resmi dari Dinas Kependudukan dan Catatan Sipil setempat.'),
            'req3_icon'         => SettingsModel::getSetting('req3_icon', 'family_history'),

            // Footer Settings
            'footer_phone'      => SettingsModel::getSetting('footer_phone', '+62 21 555 0123'),
            'footer_email'      => SettingsModel::getSetting('footer_email', 'spmb@unggulbangsa.sch.id'),
            'footer_address'    => SettingsModel::getSetting('footer_address', 'Jl. Pendidikan No. 1, Jakarta'),
            'footer_desc'       => SettingsModel::getSetting('footer_desc', 'Membentuk generasi pemimpin masa depan yang beriman, cerdas, kreatif, mandiri, dan berwawasan global.'),

            // Pengumuman & WhatsApp Group Settings
            'whatsapp_link'        => SettingsModel::getSetting('whatsapp_link', 'https://wa.me/6281234567890'),
            'announcement_title_1' => SettingsModel::getSetting('announcement_title_1', 'Jadwal Ujian Masuk Seleksi Akademik'),
            'announcement_desc_1'  => SettingsModel::getSetting('announcement_desc_1', 'Ujian seleksi potensi akademik dan wawancara online akan diselenggarakan pada hari Selasa depan. Harap cetak bukti pendaftaran dan kartu ujian Anda terlebih dahulu.'),
            'announcement_time_1'  => SettingsModel::getSetting('announcement_time_1', '2 jam yang lalu'),
            'announcement_title_2' => SettingsModel::getSetting('announcement_title_2', 'Panduan Unggah Berkas Persyaratan'),
            'announcement_desc_2'  => SettingsModel::getSetting('announcement_desc_2', 'Pastikan semua dokumen hasil scan (Ijazah/SKL, Kartu Keluarga, Rapor) terbaca dengan sangat jelas. Format file yang diterima hanya berupa gambar/PDF dengan batas ukuran maksimal 2MB per file.'),
            'announcement_time_2'  => SettingsModel::getSetting('announcement_time_2', 'Kemarin'),
        ];

        $pengumumanModel = new PengumumanModel();
        $announcements = $pengumumanModel->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'admin_username' => $shieldUser->username,
            'settings'       => $settings,
            'announcements'  => $announcements,
        ];

        return view('admin/settings', $data);
    }

    public function save()
    {
        // 1. Proteksi rute
        if (! auth()->loggedIn() || (! auth()->user()->inGroup('admin') && ! auth()->user()->inGroup('superadmin'))) {
            return redirect()->to(base_url('admin/dashboard'))
                ->with('error', 'Akses ditolak.');
        }


        // 2. Validasi input pengaturan
        $rules = [
            'is_open'           => 'required|in_list[0,1]',
            'tahun_ajaran'      => 'required|min_length[4]|max_length[15]',
            'frontend_title'    => 'required|min_length[5]|max_length[255]',
            'frontend_subtitle' => 'required|min_length[10]',
            
            'logo_text'         => 'required|min_length[3]|max_length[100]',

            // Alur Pendaftaran
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

            // Dokumen Persyaratan
            'req1_title'        => 'required|min_length[3]|max_length[100]',
            'req1_desc'         => 'required|min_length[5]|max_length[255]',
            'req1_icon'         => 'required|min_length[3]|max_length[50]',

            'req2_title'        => 'required|min_length[3]|max_length[100]',
            'req2_desc'         => 'required|min_length[5]|max_length[255]',
            'req2_icon'         => 'required|min_length[3]|max_length[50]',

            'req3_title'        => 'required|min_length[3]|max_length[100]',
            'req3_desc'         => 'required|min_length[5]|max_length[255]',
            'req3_icon'         => 'required|min_length[3]|max_length[50]',

            // Footer Settings
            'footer_phone'      => 'required|min_length[5]|max_length[50]',
            'footer_email'      => 'required|valid_email|max_length[100]',
            'footer_address'    => 'required|min_length[5]|max_length[255]',
            'footer_desc'       => 'required|min_length[10]|max_length[500]',

            // Pengumuman & WA Rules
            'whatsapp_link'        => 'required|valid_url',
            'announcement_title_1' => 'required|min_length[3]|max_length[255]',
            'announcement_desc_1'  => 'required|min_length[5]',
            'announcement_time_1'  => 'required|min_length[2]|max_length[50]',
            'announcement_title_2' => 'required|min_length[3]|max_length[255]',
            'announcement_desc_2'  => 'required|min_length[5]',
            'announcement_time_2'  => 'required|min_length[2]|max_length[50]',
        ];

        // Validate logo file only if a file was selected/uploaded
        $logoFile = $this->request->getFile('logo_file');
        if ($logoFile && $logoFile->isValid()) {
            $rules['logo_file'] = 'is_image[logo_file]|mime_in[logo_file,image/png,image/jpg,image/jpeg]|max_size[logo_file,2048]';
        }

        // Validate hero file only if a file was selected/uploaded
        $heroFile = $this->request->getFile('hero_file');
        if ($heroFile && $heroFile->isValid()) {
            $rules['hero_file'] = 'is_image[hero_file]|mime_in[hero_file,image/png,image/jpg,image/jpeg]|max_size[hero_file,4096]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // 3. Simpan teks pengaturan ke database
        SettingsModel::setSetting('is_open', $this->request->getPost('is_open'));
        SettingsModel::setSetting('tahun_ajaran', $this->request->getPost('tahun_ajaran'));
        SettingsModel::setSetting('frontend_title', $this->request->getPost('frontend_title'));
        SettingsModel::setSetting('frontend_subtitle', $this->request->getPost('frontend_subtitle'));
        SettingsModel::setSetting('logo_text', $this->request->getPost('logo_text'));

        // Simpan Alur Pendaftaran
        SettingsModel::setSetting('step1_title', $this->request->getPost('step1_title'));
        SettingsModel::setSetting('step1_desc', $this->request->getPost('step1_desc'));
        SettingsModel::setSetting('step1_icon', $this->request->getPost('step1_icon'));

        SettingsModel::setSetting('step2_title', $this->request->getPost('step2_title'));
        SettingsModel::setSetting('step2_desc', $this->request->getPost('step2_desc'));
        SettingsModel::setSetting('step2_icon', $this->request->getPost('step2_icon'));

        SettingsModel::setSetting('step3_title', $this->request->getPost('step3_title'));
        SettingsModel::setSetting('step3_desc', $this->request->getPost('step3_desc'));
        SettingsModel::setSetting('step3_icon', $this->request->getPost('step3_icon'));

        SettingsModel::setSetting('step4_title', $this->request->getPost('step4_title'));
        SettingsModel::setSetting('step4_desc', $this->request->getPost('step4_desc'));
        SettingsModel::setSetting('step4_icon', $this->request->getPost('step4_icon'));

        // Simpan Dokumen Persyaratan
        SettingsModel::setSetting('req1_title', $this->request->getPost('req1_title'));
        SettingsModel::setSetting('req1_desc', $this->request->getPost('req1_desc'));
        SettingsModel::setSetting('req1_icon', $this->request->getPost('req1_icon'));

        SettingsModel::setSetting('req2_title', $this->request->getPost('req2_title'));
        SettingsModel::setSetting('req2_desc', $this->request->getPost('req2_desc'));
        SettingsModel::setSetting('req2_icon', $this->request->getPost('req2_icon'));

        SettingsModel::setSetting('req3_title', $this->request->getPost('req3_title'));
        SettingsModel::setSetting('req3_desc', $this->request->getPost('req3_desc'));
        SettingsModel::setSetting('req3_icon', $this->request->getPost('req3_icon'));

        // Simpan Footer Settings
        SettingsModel::setSetting('footer_phone', $this->request->getPost('footer_phone'));
        SettingsModel::setSetting('footer_email', $this->request->getPost('footer_email'));
        SettingsModel::setSetting('footer_address', $this->request->getPost('footer_address'));
        SettingsModel::setSetting('footer_desc', $this->request->getPost('footer_desc'));

        // Simpan Pengumuman & WA
        SettingsModel::setSetting('whatsapp_link', $this->request->getPost('whatsapp_link'));
        SettingsModel::setSetting('announcement_title_1', $this->request->getPost('announcement_title_1'));
        SettingsModel::setSetting('announcement_desc_1', $this->request->getPost('announcement_desc_1'));
        SettingsModel::setSetting('announcement_time_1', $this->request->getPost('announcement_time_1'));
        SettingsModel::setSetting('announcement_title_2', $this->request->getPost('announcement_title_2'));
        SettingsModel::setSetting('announcement_desc_2', $this->request->getPost('announcement_desc_2'));
        SettingsModel::setSetting('announcement_time_2', $this->request->getPost('announcement_time_2'));

        // 4. Proses File Upload Logo
        $logoFile = $this->request->getFile('logo_file');
        if ($logoFile && $logoFile->isValid() && ! $logoFile->hasMoved()) {
            $uploadsDir = ROOTPATH . 'public/uploads/';
            if (! is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0755, true);
            }
            // Hapus berkas logo lama jika ada
            $oldLogo = SettingsModel::getSetting('logo_img');
            if ($oldLogo && file_exists(ROOTPATH . 'public/' . $oldLogo)) {
                @unlink(ROOTPATH . 'public/' . $oldLogo);
            }
            $newName = 'logo_' . time() . '.' . $logoFile->guessExtension();
            $logoFile->move($uploadsDir, $newName);
            SettingsModel::setSetting('logo_img', 'uploads/' . $newName);
        }

        // 5. Proses File Upload Hero Image
        $heroFile = $this->request->getFile('hero_file');
        if ($heroFile && $heroFile->isValid() && ! $heroFile->hasMoved()) {
            $uploadsDir = ROOTPATH . 'public/uploads/';
            if (! is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0755, true);
            }
            // Hapus berkas hero lama jika ada
            $oldHero = SettingsModel::getSetting('hero_img');
            if ($oldHero && file_exists(ROOTPATH . 'public/' . $oldHero)) {
                @unlink(ROOTPATH . 'public/' . $oldHero);
            }
            $newName = 'hero_' . time() . '.' . $heroFile->guessExtension();
            $heroFile->move($uploadsDir, $newName);
            SettingsModel::setSetting('hero_img', 'uploads/' . $newName);
        }

        return redirect()->to(base_url('admin/settings'))
            ->with('success', 'Seluruh konfigurasi frontend berhasil diperbarui secara real-time!');
    }

    // =========================================================================
    // POST: Tambah Pengumuman Baru
    // =========================================================================
    public function addAnnouncement()
    {
        if (! auth()->loggedIn() || (! auth()->user()->inGroup('admin') && ! auth()->user()->inGroup('superadmin'))) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'Akses ditolak.');
        }

        $rules = [
            'judul'  => 'required|min_length[3]|max_length[255]',
            'konten' => 'required|min_length[5]',
        ];

        $messages = [
            'judul' => [
                'required'   => 'Judul pengumuman wajib diisi.',
                'min_length' => 'Judul minimal terdiri dari 3 karakter.',
                'max_length' => 'Judul maksimal terdiri dari 255 karakter.',
            ],
            'konten' => [
                'required'   => 'Isi konten pengumuman wajib diisi.',
                'min_length' => 'Isi konten minimal terdiri dari 5 karakter.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->to(base_url('admin/settings?tab=pengumuman'))
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $pengumumanModel = new PengumumanModel();
        $pengumumanModel->insert([
            'judul'  => trim($this->request->getPost('judul')),
            'konten' => trim($this->request->getPost('konten')),
        ]);

        return redirect()->to(base_url('admin/settings?tab=pengumuman'))
            ->with('success', 'Pengumuman baru berhasil dipublikasikan!');
    }

    // =========================================================================
    // GET: Hapus Pengumuman
    // =========================================================================
    public function deleteAnnouncement($id)
    {
        if (! auth()->loggedIn() || (! auth()->user()->inGroup('admin') && ! auth()->user()->inGroup('superadmin'))) {
            return redirect()->to(base_url('admin/dashboard'))->with('error', 'Akses ditolak.');
        }

        $pengumumanModel = new PengumumanModel();
        $pengumuman = $pengumumanModel->find($id);

        if (! $pengumuman) {
            return redirect()->to(base_url('admin/settings?tab=pengumuman'))
                ->with('error', 'Data pengumuman tidak ditemukan.');
        }

        $pengumumanModel->delete($id);

        return redirect()->to(base_url('admin/settings?tab=pengumuman'))
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
