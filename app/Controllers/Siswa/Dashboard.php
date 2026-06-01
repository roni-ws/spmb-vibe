<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\MasterSekolahModel;
use App\Models\AdministrasiModel;
use App\Models\OrangTuaModel;
use App\Models\PengumumanModel;

class Dashboard extends BaseController
{
    protected $helpers = ['url', 'html'];

    public function index()
    {
        // 1. Proteksi rute: Harus sudah login sebagai student
        if (! auth()->loggedIn()) {
            return redirect()->to(base_url('login'))
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses dashboard.');
        }

        // 2. Ambil user yang sedang login melalui CodeIgniter Shield
        $shieldUser = auth()->user();

        // Pastikan bukan admin yang mengakses dashboard siswa
        if ($shieldUser->inGroup('admin')) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        // 3. Tarik Data dari Database Baru berdasarkan username (NISN)
        $siswaModel = new SiswaModel();
        $biodata = $siswaModel->where('nisn', $shieldUser->username)->first();

        $sekolah            = null;
        $berkas             = null;
        $statusPendaftaran  = null;

        if ($biodata) {
            $sekolahModel = new MasterSekolahModel();
            $sekolah = $sekolahModel->find($biodata['sekolah_id']);

            $berkasModel = new AdministrasiModel();
            $berkas = $berkasModel->where('siswa_id', $biodata['id'])->first();

            $statusPendaftaranModel = new \App\Models\StatusPendaftaranModel();
            $statusPendaftaran = $statusPendaftaranModel
                ->select('status_pendaftaran.*, status_verifikasi.nama_status as status_verifikasi')
                ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left')
                ->where('siswa_id', $biodata['id'])
                ->first();
        }

        // 4. Kalkulasi Status Kelengkapan Formulir
        $hasBiodata = ! empty($biodata) && ! empty($biodata['nik']) && ! str_contains($biodata['nik'], 'REG-');
        $hasSekolah = ! empty($sekolah) && $sekolah['nama_sekolah'] !== 'Belum Diisi';
        $hasBerkas  = ! empty($berkas) && (!empty($berkas['fc_ijazah_skl']) && !empty($berkas['fc_kk']) && !empty($berkas['pas_foto']));

        // 5. Hitung Progres Pendaftaran (4 Langkah, masing-masing 25%)
        //    Langkah 1: Akun Terdaftar       → +25% (selalu terpenuhi jika bisa login)
        //    Langkah 2: Biodata Diri Terisi   → +25%
        //    Langkah 3: Asal Sekolah Terisi   → +25%
        //    Langkah 4: Berkas Diunggah       → +25%
        $progressStep    = 1;
        $progressPercent = 25;

        if ($hasBiodata) {
            $progressStep    = 2;
            $progressPercent += 25;
        }
        if ($hasSekolah) {
            $progressStep    = 3;
            $progressPercent += 25;
        }
        if ($hasBerkas) {
            $progressStep    = 4;
            $progressPercent += 25;
        }

        // 6. Data Sapaan & Status Verifikasi
        $namaLengkap       = $hasBiodata ? $biodata['nama_lengkap'] : ucfirst($shieldUser->username ?? 'Siswa');
        $statusVerifikasi  = $statusPendaftaran ? $statusPendaftaran['status_verifikasi'] : 'Belum Verifikasi';
        $catatanVerifikasi = $statusPendaftaran ? $statusPendaftaran['catatan_verifikasi'] : null;
        $isFinalized       = $statusPendaftaran ? (int)$statusPendaftaran['is_finalized'] : 0;
        $statusKelulusan   = 'Belum Pengumuman';

        // Fetch active announcements from database
        $pengumumanModel = new PengumumanModel();
        $announcements   = $pengumumanModel->orderBy('created_at', 'DESC')->findAll();

        // 7. Siapkan Data untuk View
        $data = [
            'username'           => $shieldUser->username,
            'nama_lengkap'       => $namaLengkap,
            'has_biodata'        => $hasBiodata,
            'has_sekolah'        => $hasSekolah,
            'has_berkas'         => $hasBerkas,
            'progress_step'      => $progressStep,
            'progress_percent'   => $progressPercent,
            'status_verifikasi'  => $statusVerifikasi,
            'catatan_verifikasi' => $catatanVerifikasi,
            'is_finalized'       => $isFinalized,
            'status_kelulusan'   => $statusKelulusan,
            'announcements'      => $announcements,
        ];

        return view('siswa/dashboard', $data);
    }

    public function finalize()
    {
        // Proteksi rute: Harus sudah login sebagai student
        if (! auth()->loggedIn()) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();
        if ($user->inGroup('admin')) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        $db = \Config\Database::connect();

        // Cari calon_siswa berdasarkan username pendaftar (NISN)
        $siswa = $db->table('calon_siswa')->where('nisn', $user->username)->get()->getRowArray();

        if (!$siswa) {
            return redirect()->to(base_url('siswa/dashboard'))->with('error', 'Data pendaftar tidak ditemukan.');
        }

        $siswaId = $siswa['id'];

        // Ambil data status_pendaftaran
        $statusPendaftaran = $db->table('status_pendaftaran')->where('siswa_id', $siswaId)->get()->getRowArray();

        if ($statusPendaftaran) {
            $db->table('status_pendaftaran')->where('siswa_id', $siswaId)->update([
                'is_finalized' => 1,
                'updated_at'   => date('Y-m-d H:i:s')
            ]);
        } else {
            $db->table('status_pendaftaran')->insert([
                'siswa_id'             => $siswaId,
                'is_finalized'         => 1,
                'status_verifikasi_id' => 1,
                'created_at'           => date('Y-m-d H:i:s'),
                'updated_at'           => date('Y-m-d H:i:s')
            ]);
        }

        return redirect()->to(base_url('siswa/dashboard'))->with('success', 'Pendaftaran Anda berhasil difinalisasi! Data Anda telah dikunci dan tidak dapat diubah lagi.');
    }

    public function downloadForm()
    {
        // Proteksi rute: Harus sudah login sebagai student
        if (! auth()->loggedIn()) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();
        if ($user->inGroup('admin')) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        $db = \Config\Database::connect();
        $builder = $db->table('calon_siswa')
            ->select('
                calon_siswa.id, 
                calon_siswa.nisn, 
                calon_siswa.nisn as username, 
                calon_siswa.nisn as email, 
                COALESCE(kelengkapan_administrasi.created_at, NOW()) as created_at,
                calon_siswa.nama_lengkap, 
                calon_siswa.nik, 
                calon_siswa.no_kk,
                calon_siswa.tempat_lahir,
                calon_siswa.tanggal_lahir,
                calon_siswa.jenis_kelamin,
                calon_siswa.nisn as no_hp,
                calon_siswa.anak_ke,
                calon_siswa.jumlah_saudara,
                CONCAT(calon_siswa.alamat_desa, \', \', calon_siswa.alamat_kabupaten, \', \', calon_siswa.alamat_kecamatan) as alamat,
                data_orang_tua.nama_ayah,
                data_orang_tua.pekerjaan_ayah,
                data_orang_tua.nama_ibu,
                data_orang_tua.pekerjaan_ibu,
                master_sekolah.nama_sekolah, 
                master_sekolah.alamat_sekolah, 
                kelengkapan_administrasi.fc_ijazah_skl,
                kelengkapan_administrasi.fc_kk,
                kelengkapan_administrasi.pas_foto,
                status_verifikasi.nama_status as status_verifikasi, 
                status_pendaftaran.is_finalized,
                \'Belum Pengumuman\' as status_kelulusan
            ')
            ->join('master_sekolah', 'master_sekolah.id = calon_siswa.sekolah_id', 'left')
            ->join('kelengkapan_administrasi', 'kelengkapan_administrasi.siswa_id = calon_siswa.id', 'left')
            ->join('status_pendaftaran', 'status_pendaftaran.siswa_id = calon_siswa.id', 'left')
            ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left')
            ->join('data_orang_tua', 'data_orang_tua.siswa_id = calon_siswa.id', 'left')
            ->where('calon_siswa.nisn', $user->username);

        $student = $builder->get()->getRowArray();

        if (!$student) {
            return redirect()->back()->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Proteksi download berkas: Harus sudah finalisasi dan status verifikasi Valid
        $isFinalized = isset($student['is_finalized']) ? (int)$student['is_finalized'] : 0;
        $statusVerifikasi = $student['status_verifikasi'] ?? 'Belum Verifikasi';

        if ($isFinalized === 0 || $statusVerifikasi !== 'Valid') {
            return redirect()->to(base_url('siswa/dashboard'))->with('error', 'Anda harus memfinalisasi data dan berkas Anda telah diverifikasi oleh Panitia sebelum dapat mengunduh bukti pendaftaran.');
        }

        // Render beautiful clean printable card
        return view('siswa/form_pdf', ['student' => $student]);
    }

    // =========================================================================
    // GET: Langkah 1 - Biodata Diri
    // =========================================================================
    public function biodata()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();
        if ($user->inGroup('admin')) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        $siswaModel = new SiswaModel();
        $biodata = $siswaModel->where('nisn', $user->username)->first();

        if (!$biodata) {
            return redirect()->to(base_url('siswa/dashboard'))->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Cek apakah data sudah difinalisasi
        $db = \Config\Database::connect();
        $statusPendaftaran = $db->table('status_pendaftaran')->where('siswa_id', $biodata['id'])->get()->getRowArray();
        if ($statusPendaftaran && (int)$statusPendaftaran['is_finalized'] === 1) {
            return redirect()->to(base_url('siswa/dashboard'))
                ->with('error', 'Data pendaftar Anda telah difinalisasi secara permanen.');
        }

        $data = [
            'biodata' => $biodata,
            'nama_lengkap' => !empty($biodata['nama_lengkap']) ? $biodata['nama_lengkap'] : $user->username,
            'errors' => session('errors'),
        ];

        return view('siswa/biodata', $data);
    }

    // =========================================================================
    // POST: Langkah 1 - Simpan Biodata Diri
    // =========================================================================
    public function saveBiodata()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to(base_url('login'));
        }

        $user = auth()->user();
        $siswaModel = new SiswaModel();
        $biodata = $siswaModel->where('nisn', $user->username)->first();

        if (!$biodata) {
            return redirect()->to(base_url('siswa/dashboard'))->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Cek finalisasi
        $db = \Config\Database::connect();
        $statusPendaftaran = $db->table('status_pendaftaran')->where('siswa_id', $biodata['id'])->get()->getRowArray();
        if ($statusPendaftaran && (int)$statusPendaftaran['is_finalized'] === 1) {
            return redirect()->to(base_url('siswa/dashboard'))
                ->with('error', 'Data pendaftar Anda telah difinalisasi secara permanen.');
        }

        $rules = [
            'nik'              => 'required|numeric|exact_length[16]',
            'no_kk'            => 'required|numeric|exact_length[16]',
            'nama_lengkap'     => 'required|min_length[3]|max_length[150]',
            'tempat_lahir'     => 'required|min_length[3]',
            'tanggal_lahir'    => 'required|valid_date[Y-m-d]',
            'jenis_kelamin'    => 'required|in_list[L,P]',
            'anak_ke'          => 'required|integer|greater_than_equal_to[1]',
            'jumlah_saudara'   => 'required|integer|greater_than_equal_to[0]',
            'alamat_desa'      => 'required',
            'alamat_kabupaten' => 'required',
            'alamat_kecamatan' => 'required',
        ];

        $messages = [
            'nik' => [
                'required'     => 'NIK wajib diisi.',
                'numeric'      => 'NIK hanya boleh berupa angka.',
                'exact_length' => 'NIK harus tepat 16 digit.',
            ],
            'no_kk' => [
                'required'     => 'Nomor KK wajib diisi.',
                'numeric'      => 'Nomor KK hanya boleh berupa angka.',
                'exact_length' => 'Nomor KK harus tepat 16 digit.',
            ],
            'nama_lengkap' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
                'max_length' => 'Nama lengkap maksimal 150 karakter.',
            ],
            'tempat_lahir' => [
                'required'   => 'Tempat lahir wajib diisi.',
                'min_length' => 'Tempat lahir minimal 3 karakter.',
            ],
            'tanggal_lahir' => [
                'required'   => 'Tanggal lahir wajib diisi.',
                'valid_date' => 'Format tanggal lahir tidak valid.',
            ],
            'jenis_kelamin' => [
                'required' => 'Jenis kelamin wajib dipilih.',
                'in_list'  => 'Pilihan jenis kelamin tidak valid.',
            ],
            'anak_ke' => [
                'required'              => 'Status anak ke-berapa wajib diisi.',
                'integer'               => 'Status anak ke-berapa harus berupa angka.',
                'greater_than_equal_to' => 'Status anak ke-berapa minimal bernilai 1.',
            ],
            'jumlah_saudara' => [
                'required'              => 'Jumlah saudara kandung wajib diisi.',
                'integer'               => 'Jumlah saudara kandung harus berupa angka.',
                'greater_than_equal_to' => 'Jumlah saudara kandung minimal bernilai 0.',
            ],
            'alamat_desa' => [
                'required' => 'Alamat jalan/desa wajib diisi.',
            ],
            'alamat_kabupaten' => [
                'required' => 'Kota/Kabupaten wajib diisi.',
            ],
            'alamat_kecamatan' => [
                'required' => 'Provinsi wajib diisi.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'nik'              => $this->request->getPost('nik'),
            'no_kk'            => $this->request->getPost('no_kk'),
            'nama_lengkap'     => $this->request->getPost('nama_lengkap'),
            'tempat_lahir'     => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'    => $this->request->getPost('tanggal_lahir'),
            'jenis_kelamin'    => $this->request->getPost('jenis_kelamin'),
            'anak_ke'          => $this->request->getPost('anak_ke'),
            'jumlah_saudara'   => $this->request->getPost('jumlah_saudara'),
            'alamat_desa'      => $this->request->getPost('alamat_desa'),
            'alamat_kabupaten' => $this->request->getPost('alamat_kabupaten'),
            'alamat_kecamatan' => $this->request->getPost('alamat_kecamatan'),
        ];

        $siswaModel->update($biodata['id'], $updateData);

        return redirect()->to(base_url('siswa/sekolah'))->with('success', 'Biodata diri berhasil disimpan! Silakan lanjutkan mengisi asal sekolah.');
    }

    // =========================================================================
    // GET: Langkah 2 - Asal Sekolah
    // =========================================================================
    public function sekolah()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();
        if ($user->inGroup('admin')) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        $siswaModel = new SiswaModel();
        $biodata = $siswaModel->where('nisn', $user->username)->first();

        if (!$biodata) {
            return redirect()->to(base_url('siswa/dashboard'))->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Cek finalisasi
        $db = \Config\Database::connect();
        $statusPendaftaran = $db->table('status_pendaftaran')->where('siswa_id', $biodata['id'])->get()->getRowArray();
        if ($statusPendaftaran && (int)$statusPendaftaran['is_finalized'] === 1) {
            return redirect()->to(base_url('siswa/dashboard'))
                ->with('error', 'Data pendaftar Anda telah difinalisasi secara permanen.');
        }

        $sekolahModel = new MasterSekolahModel();
        $sekolah = $sekolahModel->find($biodata['sekolah_id']);

        // Jika sekolah adalah placeholder 'Belum Diisi', kosongkan agar form bersih
        if ($sekolah && $sekolah['nama_sekolah'] === 'Belum Diisi') {
            $sekolah['nama_sekolah'] = '';
            $sekolah['alamat_sekolah'] = '';
        }

        $data = [
            'sekolah' => $sekolah,
            'nama_lengkap' => !empty($biodata['nama_lengkap']) ? $biodata['nama_lengkap'] : $user->username,
            'errors' => session('errors'),
        ];

        return view('siswa/sekolah', $data);
    }

    // =========================================================================
    // POST: Langkah 2 - Simpan Asal Sekolah
    // =========================================================================
    public function saveSekolah()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to(base_url('login'));
        }

        $user = auth()->user();
        $siswaModel = new SiswaModel();
        $biodata = $siswaModel->where('nisn', $user->username)->first();

        if (!$biodata) {
            return redirect()->to(base_url('siswa/dashboard'))->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Cek finalisasi
        $db = \Config\Database::connect();
        $statusPendaftaran = $db->table('status_pendaftaran')->where('siswa_id', $biodata['id'])->get()->getRowArray();
        if ($statusPendaftaran && (int)$statusPendaftaran['is_finalized'] === 1) {
            return redirect()->to(base_url('siswa/dashboard'))
                ->with('error', 'Data pendaftar Anda telah difinalisasi secara permanen.');
        }

        $rules = [
            'nama_sekolah'   => 'required|min_length[3]',
            'alamat_sekolah' => 'required|min_length[5]',
        ];

        $messages = [
            'nama_sekolah' => [
                'required'   => 'Nama sekolah asal wajib diisi.',
                'min_length' => 'Nama sekolah minimal 3 karakter.',
            ],
            'alamat_sekolah' => [
                'required'   => 'Alamat sekolah asal wajib diisi.',
                'min_length' => 'Alamat sekolah minimal 5 karakter.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $namaSekolah = trim($this->request->getPost('nama_sekolah'));
        $alamatSekolah = trim($this->request->getPost('alamat_sekolah'));

        $sekolahModel = new MasterSekolahModel();

        // 1. Cek apakah sekolah dengan nama tersebut sudah ada (case-insensitive)
        $existingSekolah = $sekolahModel->where('LOWER(nama_sekolah)', strtolower($namaSekolah))->first();

        if ($existingSekolah) {
            // Gunakan sekolah yang ada
            $sekolahId = $existingSekolah['id'];
            // Update juga alamatnya jika berbeda
            $sekolahModel->update($sekolahId, ['alamat_sekolah' => $alamatSekolah]);
        } else {
            // 2. Cek apakah sekolah_id siswa aktif saat ini menunjuk ke sekolah kustom (bukan placeholder 'Belum Diisi')
            $currentSekolah = $sekolahModel->find($biodata['sekolah_id']);
            if ($currentSekolah && $currentSekolah['nama_sekolah'] !== 'Belum Diisi') {
                // Update record sekolah kustom tersebut secara langsung
                $sekolahModel->update($biodata['sekolah_id'], [
                    'nama_sekolah'   => $namaSekolah,
                    'alamat_sekolah' => $alamatSekolah,
                ]);
                $sekolahId = $biodata['sekolah_id'];
            } else {
                // Buat record sekolah baru
                $sekolahModel->insert([
                    'nama_sekolah'   => $namaSekolah,
                    'alamat_sekolah' => $alamatSekolah,
                ]);
                $sekolahId = $sekolahModel->getInsertID();
            }
        }

        // Perbarui sekolah_id siswa aktif
        $siswaModel->update($biodata['id'], ['sekolah_id' => $sekolahId]);

        return redirect()->to(base_url('siswa/orang-tua'))->with('success', 'Data asal sekolah berhasil disimpan! Silakan lanjutkan mengisi data orang tua.');
    }

    // =========================================================================
    // GET: Langkah 3 - Data Orang Tua
    // =========================================================================
    public function orangTua()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();
        if ($user->inGroup('admin')) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        $siswaModel = new SiswaModel();
        $biodata = $siswaModel->where('nisn', $user->username)->first();

        if (!$biodata) {
            return redirect()->to(base_url('siswa/dashboard'))->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Cek finalisasi
        $db = \Config\Database::connect();
        $statusPendaftaran = $db->table('status_pendaftaran')->where('siswa_id', $biodata['id'])->get()->getRowArray();
        if ($statusPendaftaran && (int)$statusPendaftaran['is_finalized'] === 1) {
            return redirect()->to(base_url('siswa/dashboard'))
                ->with('error', 'Data pendaftar Anda telah difinalisasi secara permanen.');
        }

        $orangTuaModel = new OrangTuaModel();
        $orangTua = $orangTuaModel->where('siswa_id', $biodata['id'])->first();

        $data = [
            'orang_tua' => $orangTua,
            'nama_lengkap' => !empty($biodata['nama_lengkap']) ? $biodata['nama_lengkap'] : $user->username,
            'errors' => session('errors'),
        ];

        return view('siswa/orang_tua', $data);
    }

    // =========================================================================
    // POST: Langkah 3 - Simpan Data Orang Tua
    // =========================================================================
    public function saveOrangTua()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to(base_url('login'));
        }

        $user = auth()->user();
        $siswaModel = new SiswaModel();
        $biodata = $siswaModel->where('nisn', $user->username)->first();

        if (!$biodata) {
            return redirect()->to(base_url('siswa/dashboard'))->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Cek finalisasi
        $db = \Config\Database::connect();
        $statusPendaftaran = $db->table('status_pendaftaran')->where('siswa_id', $biodata['id'])->get()->getRowArray();
        if ($statusPendaftaran && (int)$statusPendaftaran['is_finalized'] === 1) {
            return redirect()->to(base_url('siswa/dashboard'))
                ->with('error', 'Data pendaftar Anda telah difinalisasi secara permanen.');
        }

        $rules = [
            'nama_ayah'      => 'required|min_length[3]',
            'pekerjaan_ayah' => 'required',
            'nama_ibu'       => 'required|min_length[3]',
            'pekerjaan_ibu'  => 'required',
        ];

        $messages = [
            'nama_ayah' => [
                'required'   => 'Nama ayah wajib diisi.',
                'min_length' => 'Nama ayah minimal 3 karakter.',
            ],
            'pekerjaan_ayah' => [
                'required' => 'Pekerjaan ayah wajib diisi.',
            ],
            'nama_ibu' => [
                'required'   => 'Nama ibu wajib diisi.',
                'min_length' => 'Nama ibu minimal 3 karakter.',
            ],
            'pekerjaan_ibu' => [
                'required' => 'Pekerjaan ibu wajib diisi.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $orangTuaModel = new OrangTuaModel();
        $orangTua = $orangTuaModel->where('siswa_id', $biodata['id'])->first();

        $updateData = [
            'nama_ayah'      => $this->request->getPost('nama_ayah'),
            'pekerjaan_ayah' => $this->request->getPost('pekerjaan_ayah'),
            'nama_ibu'       => $this->request->getPost('nama_ibu'),
            'pekerjaan_ibu'  => $this->request->getPost('pekerjaan_ibu'),
        ];

        if ($orangTua) {
            $orangTuaModel->update($biodata['id'], $updateData);
        } else {
            $updateData['siswa_id'] = $biodata['id'];
            $orangTuaModel->insert($updateData);
        }

        return redirect()->to(base_url('siswa/berkas'))->with('success', 'Data orang tua berhasil disimpan! Silakan lanjutkan dengan mengunggah berkas.');
    }

    // =========================================================================
    // GET: Langkah 4 - Kelengkapan Berkas
    // =========================================================================
    public function berkas()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to(base_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();
        if ($user->inGroup('admin')) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        $siswaModel = new SiswaModel();
        $biodata = $siswaModel->where('nisn', $user->username)->first();

        if (!$biodata) {
            return redirect()->to(base_url('siswa/dashboard'))->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Cek finalisasi
        $db = \Config\Database::connect();
        $statusPendaftaran = $db->table('status_pendaftaran')->where('siswa_id', $biodata['id'])->get()->getRowArray();
        if ($statusPendaftaran && (int)$statusPendaftaran['is_finalized'] === 1) {
            return redirect()->to(base_url('siswa/dashboard'))
                ->with('error', 'Data pendaftar Anda telah difinalisasi secara permanen.');
        }

        $berkasModel = new AdministrasiModel();
        $berkas = $berkasModel->where('siswa_id', $biodata['id'])->first();

        $data = [
            'berkas' => $berkas,
            'nama_lengkap' => !empty($biodata['nama_lengkap']) ? $biodata['nama_lengkap'] : $user->username,
            'errors' => session('errors'),
        ];

        return view('siswa/berkas', $data);
    }

    // =========================================================================
    // POST: Langkah 4 - Simpan Kelengkapan Berkas
    // =========================================================================
    public function saveBerkas()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to(base_url('login'));
        }

        $user = auth()->user();
        $siswaModel = new SiswaModel();
        $biodata = $siswaModel->where('nisn', $user->username)->first();

        if (!$biodata) {
            return redirect()->to(base_url('siswa/dashboard'))->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Cek finalisasi
        $db = \Config\Database::connect();
        $statusPendaftaran = $db->table('status_pendaftaran')->where('siswa_id', $biodata['id'])->get()->getRowArray();
        if ($statusPendaftaran && (int)$statusPendaftaran['is_finalized'] === 1) {
            return redirect()->to(base_url('siswa/dashboard'))
                ->with('error', 'Data pendaftar Anda telah difinalisasi secara permanen.');
        }

        $rules = [
            'fc_ijazah_skl' => 'uploaded[fc_ijazah_skl]|max_size[fc_ijazah_skl,2048]|ext_in[fc_ijazah_skl,pdf,jpg,jpeg,png]',
            'fc_kk'         => 'uploaded[fc_kk]|max_size[fc_kk,2048]|ext_in[fc_kk,pdf,jpg,jpeg,png]',
            'pas_foto'      => 'uploaded[pas_foto]|max_size[pas_foto,2048]|ext_in[pas_foto,jpg,jpeg,png]',
        ];

        $berkasModel = new AdministrasiModel();
        $berkas = $berkasModel->where('siswa_id', $biodata['id'])->first();

        // Jika data berkas sudah pernah diunggah sebelumnya, upload berkas baru bersifat opsional
        if ($berkas) {
            if (!empty($berkas['fc_ijazah_skl'])) {
                $rules['fc_ijazah_skl'] = 'max_size[fc_ijazah_skl,2048]|ext_in[fc_ijazah_skl,pdf,jpg,jpeg,png]';
            }
            if (!empty($berkas['fc_kk'])) {
                $rules['fc_kk'] = 'max_size[fc_kk,2048]|ext_in[fc_kk,pdf,jpg,jpeg,png]';
            }
            if (!empty($berkas['pas_foto'])) {
                $rules['pas_foto'] = 'max_size[pas_foto,2048]|ext_in[pas_foto,jpg,jpeg,png]';
            }
        }

        $messages = [
            'fc_ijazah_skl' => [
                'uploaded' => 'File scan Ijazah / SKL wajib diunggah.',
                'max_size' => 'Ukuran file Ijazah / SKL maksimal adalah 2MB.',
                'ext_in'   => 'Format file Ijazah / SKL harus berupa PDF, JPG, JPEG, atau PNG.',
            ],
            'fc_kk' => [
                'uploaded' => 'File scan Kartu Keluarga wajib diunggah.',
                'max_size' => 'Ukuran file Kartu Keluarga maksimal adalah 2MB.',
                'ext_in'   => 'Format file Kartu Keluarga harus berupa PDF, JPG, JPEG, atau PNG.',
            ],
            'pas_foto' => [
                'uploaded' => 'Pas Foto 3x4 wajib diunggah.',
                'max_size' => 'Ukuran Pas Foto maksimal adalah 2MB.',
                'ext_in'   => 'Format Pas Foto harus berupa JPG, JPEG, atau PNG.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $uploadPath = FCPATH . 'uploads/berkas/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $administrasiModel = new AdministrasiModel();
        $updateData = [];

        // Proses scan Ijazah / SKL
        $fileIjazah = $this->request->getFile('fc_ijazah_skl');
        if ($fileIjazah && $fileIjazah->isValid() && !$fileIjazah->hasMoved()) {
            // Hapus file ijazah lama jika ada
            $oldFiles = glob($uploadPath . 'ijazah_' . $biodata['id'] . '_*.*');
            foreach ($oldFiles as $oldFile) {
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // Simpan file baru
            $newName = 'ijazah_' . $biodata['id'] . '_' . time() . '.' . $fileIjazah->getClientExtension();
            $fileIjazah->move($uploadPath, $newName);
            $updateData['fc_ijazah_skl'] = $newName;
        }

        // Proses scan Kartu Keluarga
        $fileKk = $this->request->getFile('fc_kk');
        if ($fileKk && $fileKk->isValid() && !$fileKk->hasMoved()) {
            // Hapus file KK lama jika ada
            $oldFiles = glob($uploadPath . 'kk_' . $biodata['id'] . '_*.*');
            foreach ($oldFiles as $oldFile) {
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // Simpan file baru
            $newName = 'kk_' . $biodata['id'] . '_' . time() . '.' . $fileKk->getClientExtension();
            $fileKk->move($uploadPath, $newName);
            $updateData['fc_kk'] = $newName;
        }

        // Proses Pas Foto 3x4
        $filePasFoto = $this->request->getFile('pas_foto');
        if ($filePasFoto && $filePasFoto->isValid() && !$filePasFoto->hasMoved()) {
            // Hapus file pas foto lama jika ada
            $oldFiles = glob($uploadPath . 'pas_foto_' . $biodata['id'] . '_*.*');
            foreach ($oldFiles as $oldFile) {
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }

            // Simpan file baru
            $newName = 'pas_foto_' . $biodata['id'] . '_' . time() . '.' . $filePasFoto->getClientExtension();
            $filePasFoto->move($uploadPath, $newName);
            $updateData['pas_foto'] = $newName;
        }

        if (!empty($updateData)) {
            if ($berkas) {
                $administrasiModel->update($biodata['id'], $updateData);
            } else {
                $updateData['siswa_id'] = $biodata['id'];
                $administrasiModel->insert($updateData);
            }
        }

        return redirect()->to(base_url('siswa/dashboard'))->with('success', 'Seluruh berkas persyaratan berhasil diunggah! Harap lakukan finalisasi pendaftaran untuk mengunci data Anda.');
    }
}
