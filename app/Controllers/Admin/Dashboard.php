<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use Config\Database;

class Dashboard extends BaseController
{
    protected $helpers = ['url', 'html', 'form'];

    public function index()
    {
        $this->requireGroup(['admin']);
        $shieldUser = auth()->user();

        // 2. Ambil semua data pendaftar (siswa) dari tabel calon_siswa untuk kalkulasi statistik
        $db = Database::connect();
        $builder = $db->table('calon_siswa')
            ->select('
                calon_siswa.id, 
                status_verifikasi.nama_status as status_verifikasi,
                calon_siswa.created_at as created_at
            ')
            ->join('kelengkapan_administrasi', 'kelengkapan_administrasi.siswa_id = calon_siswa.id', 'left')
            ->join('status_pendaftaran', 'status_pendaftaran.siswa_id = calon_siswa.id', 'left')
            ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left')
            ->orderBy('calon_siswa.id', 'DESC');

        $students = $builder->get()->getResultArray();

        // 3. Hitung statistik untuk dashboard admin
        $stats = [
            'total'     => count($students),
            'pending'   => 0,
            'valid'     => 0,
            'invalid'   => 0,
        ];

        foreach ($students as $student) {
            $status = $student['status_verifikasi'] ?? 'Belum Verifikasi';
            if ($status === 'Valid') {
                $stats['valid']++;
            } elseif ($status === 'Tidak Valid' || $status === 'Revisi') {
                $stats['invalid']++;
            } else {
                $stats['pending']++;
            }
        }

        // Ambil data trend pendaftaran (jumlah pendaftar per bulan selama 6 bulan terakhir) secara dinamis
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months")); // 'Jan', 'Feb', etc.
            
            // Terjemahkan nama bulan ke Indonesia
            $indonesianMonths = [
                'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Apr', 
                'May' => 'Mei', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Agu', 
                'Sep' => 'Sep', 'Oct' => 'Okt', 'Nov' => 'Nov', 'Dec' => 'Des'
            ];
            $displayMonth = $indonesianMonths[$monthName] ?? $monthName;

            $monthlyTrends[$date] = [
                'name'  => $displayMonth,
                'count' => 0
            ];
        }

        $trendBuilder = $db->table('calon_siswa')
            ->select("DATE_FORMAT(calon_siswa.created_at, '%Y-%m') as month_key, COUNT(calon_siswa.id) as count")
            ->join('kelengkapan_administrasi', 'kelengkapan_administrasi.siswa_id = calon_siswa.id', 'left')
            ->groupBy("month_key");
        $trendsRaw = $trendBuilder->get()->getResultArray();

        foreach ($trendsRaw as $row) {
            $key = $row['month_key'];
            if (isset($monthlyTrends[$key])) {
                $monthlyTrends[$key]['count'] = (int)$row['count'];
            }
        }

        // Ambil 4 pendaftar terbaru untuk daftar aktivitas/pendaftar terbaru
        $recentBuilder = $db->table('calon_siswa')
            ->select('calon_siswa.id, calon_siswa.nisn as username, calon_siswa.nama_lengkap, calon_siswa.created_at as created_at')
            ->join('kelengkapan_administrasi', 'kelengkapan_administrasi.siswa_id = calon_siswa.id', 'left')
            ->orderBy('calon_siswa.id', 'DESC')
            ->limit(4);
        $recentStudents = $recentBuilder->get()->getResultArray();

        $data = [
            'admin_username' => $shieldUser->username,
            'recent_students'=> $recentStudents,
            'stats'          => $stats,
            'monthly_trends' => $monthlyTrends,
        ];

        return view('admin/dashboard', $data);
    }

    /**
    * Return real-time stats as JSON for AJAX polling
    */
    public function statsAjax()
    {
        $this->requireGroup(['admin']);
        // Ensure request is AJAX
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }
        $db = Database::connect();
        // Build stats similar to index()
        $builder = $db->table('calon_siswa')
            ->select('calon_siswa.id, status_verifikasi.nama_status as status_verifikasi')
            ->join('status_pendaftaran', 'status_pendaftaran.siswa_id = calon_siswa.id', 'left')
            ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left');
        $students = $builder->get()->getResultArray();
        $stats = ['total' => count($students), 'pending' => 0, 'valid' => 0, 'invalid' => 0];
        foreach ($students as $s) {
            $status = $s['status_verifikasi'] ?? 'Belum Verifikasi';
            if ($status === 'Valid') {
                $stats['valid']++;
            } elseif (in_array($status, ['Tidak Valid', 'Revisi'])) {
                $stats['invalid']++;
            } else {
                $stats['pending']++;
            }
        }
        return $this->response->setJSON($stats);
    }


    public function pendaftar()
    {
        $this->requireGroup(['admin', 'panitia']);
        $shieldUser = auth()->user();

        // 2. Ambil semua data pendaftar (siswa) dari calon_siswa
        $db = Database::connect();
        $builder = $db->table('calon_siswa')
            ->select('
                calon_siswa.id, 
                calon_siswa.nisn as username, 
                calon_siswa.no_registrasi,
                calon_siswa.nik, 
                calon_siswa.created_at,
                calon_siswa.nama_lengkap, 
                calon_siswa.tempat_lahir,
                calon_siswa.tanggal_lahir,
                calon_siswa.jenis_kelamin,
                calon_siswa.nisn,
                calon_siswa.no_kk,
                calon_siswa.no_hp,
                calon_siswa.anak_ke,
                calon_siswa.jumlah_saudara,
                calon_siswa.alamat_desa,
                calon_siswa.alamat_kecamatan,
                calon_siswa.alamat_kabupaten,
                master_sekolah.nama_sekolah,
                master_sekolah.alamat_sekolah,
                kelengkapan_administrasi.pas_foto,
                kelengkapan_administrasi.fc_ijazah_skl,
                kelengkapan_administrasi.fc_kk,
                data_orang_tua.nama_ayah,
                data_orang_tua.pekerjaan_ayah,
                data_orang_tua.nama_ibu,
                data_orang_tua.pekerjaan_ibu,
                status_pendaftaran.status_verifikasi_id,
                status_verifikasi.nama_status as status_verifikasi, 
                status_pendaftaran.is_finalized,
                status_pendaftaran.catatan_verifikasi
            ')
            ->join('master_sekolah', 'master_sekolah.id = calon_siswa.sekolah_id', 'left')
            ->join('kelengkapan_administrasi', 'kelengkapan_administrasi.siswa_id = calon_siswa.id', 'left')
            ->join('data_orang_tua', 'data_orang_tua.siswa_id = calon_siswa.id', 'left')
            ->join('status_pendaftaran', 'status_pendaftaran.siswa_id = calon_siswa.id', 'left')
            ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left')
            ->orderBy('calon_siswa.id', 'DESC');

        $students = $builder->get()->getResultArray();

        $data = [
            'admin_username' => $shieldUser->username,
            'students'       => $students,
        ];

        $renderedHtml = view('admin/data-siswa', $data);
        
        return $renderedHtml;
    }

    public function verify($userId)
    {
        $this->requireGroup(['admin', 'panitia']);
        $statusId        = $this->request->getPost('status_verifikasi_id');
        $catatan         = $this->request->getPost('catatan_verifikasi');

        $db = Database::connect();
        
        // Periksa apakah berkas ada
        $statusRecord = $db->table('status_pendaftaran')->where('siswa_id', $userId)->get()->getRowArray();

        $updateData = [
            'status_verifikasi_id' => $statusId,
            'catatan_verifikasi'   => $catatan,
            'updated_at'           => date('Y-m-d H:i:s'),
        ];

        // Status Revisi: buka kembali formulir agar siswa dapat memperbaiki data
        if ((int) $statusId === 2) {
            $updateData['is_finalized'] = 0;
        }

        if ($statusRecord) {
            $db->table('status_pendaftaran')->where('siswa_id', $userId)->update($updateData);
        } else {
            $db->table('status_pendaftaran')->insert(array_merge([
                'siswa_id'     => $userId,
                'is_finalized' => 0,
                'created_at'   => date('Y-m-d H:i:s'),
            ], $updateData));
        }

        return redirect()->back()
            ->with('success', 'Data verifikasi berkas calon siswa berhasil diperbarui!');
    }

    public function editStudent($userId)
    {
        $this->requireGroup(['admin', 'panitia']);

        $rules = [
            'nama_lengkap'     => 'required|min_length[3]|max_length[150]',
            'nik'              => 'required|min_length[5]|max_length[30]',
            'no_kk'            => 'permit_empty|min_length[5]|max_length[30]',
            'tempat_lahir'     => 'required|min_length[3]|max_length[100]',
            'tanggal_lahir'    => 'required|valid_date[Y-m-d]',
            'jenis_kelamin'    => 'required|in_list[L,P]',
            'anak_ke'          => 'required|is_natural_no_zero',
            'jumlah_saudara'   => 'required|is_natural',
            'no_hp'            => 'permit_empty|min_length[10]|max_length[15]',
            'alamat_desa'      => 'required|min_length[3]|max_length[100]',
            'alamat_kecamatan' => 'required|min_length[3]|max_length[100]',
            'alamat_kabupaten' => 'required|min_length[3]|max_length[100]',
            'nama_sekolah'     => 'required|min_length[3]|max_length[100]',
            'alamat_sekolah'   => 'required|min_length[5]',
            'nama_ayah'        => 'required|min_length[3]|max_length[150]',
            'pekerjaan_ayah'   => 'required|min_length[3]',
            'nama_ibu'         => 'required|min_length[3]|max_length[150]',
            'pekerjaan_ibu'    => 'required|min_length[3]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data. Periksa kembali isian form Anda.');
        }

        $db = Database::connect();

        // Cari atau buat master_sekolah & update alamat_sekolah
        $sekolahModel = new \App\Models\MasterSekolahModel();
        $sekolahId = $sekolahModel->getOrCreateSekolahId($this->request->getPost('nama_sekolah'));
        $db->table('master_sekolah')->where('id', $sekolahId)->update([
            'alamat_sekolah' => $this->request->getPost('alamat_sekolah'),
        ]);

        // Update tabel calon_siswa
        $db->table('calon_siswa')->where('id', $userId)->update([
            'nama_lengkap'       => $this->request->getPost('nama_lengkap'),
            'nik'                => $this->request->getPost('nik'),
            'no_kk'              => $this->request->getPost('no_kk'),
            'tempat_lahir'       => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'      => $this->request->getPost('tanggal_lahir'),
            'jenis_kelamin'      => $this->request->getPost('jenis_kelamin'),
            'anak_ke'            => $this->request->getPost('anak_ke'),
            'jumlah_saudara'     => $this->request->getPost('jumlah_saudara'),
            'no_hp'              => $this->request->getPost('no_hp'),
            'alamat_desa'        => $this->request->getPost('alamat_desa'),
            'alamat_kecamatan'   => $this->request->getPost('alamat_kecamatan'),
            'alamat_kabupaten'   => $this->request->getPost('alamat_kabupaten'),
            'sekolah_id'         => $sekolahId,
        ]);

        // Upsert data_orang_tua
        $orangTua = $db->table('data_orang_tua')->where('siswa_id', $userId)->get()->getRowArray();
        $orangTuaData = [
            'nama_ayah'      => $this->request->getPost('nama_ayah'),
            'pekerjaan_ayah' => $this->request->getPost('pekerjaan_ayah'),
            'nama_ibu'       => $this->request->getPost('nama_ibu'),
            'pekerjaan_ibu'  => $this->request->getPost('pekerjaan_ibu'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ];
        if ($orangTua) {
            $db->table('data_orang_tua')->where('siswa_id', $userId)->update($orangTuaData);
        } else {
            $orangTuaData['siswa_id'] = $userId;
            $orangTuaData['created_at'] = date('Y-m-d H:i:s');
            $db->table('data_orang_tua')->insert($orangTuaData);
        }

        return redirect()->back()
            ->with('success', 'Data pendaftar berhasil diperbarui secara permanen!');
    }

    public function addStudent()
    {
        $this->requireGroup(['admin', 'panitia']);

        $rules = [
            'nisn'             => 'required|min_length[3]|max_length[30]|is_unique[calon_siswa.nisn]',
            'nama_lengkap'     => 'required|min_length[3]|max_length[150]',
            'nik'              => 'required|min_length[5]|max_length[30]',
            'no_kk'            => 'permit_empty|min_length[5]|max_length[30]',
            'tempat_lahir'     => 'required|min_length[3]|max_length[100]',
            'tanggal_lahir'    => 'required|valid_date[Y-m-d]',
            'jenis_kelamin'    => 'required|in_list[L,P]',
            'anak_ke'          => 'required|is_natural_no_zero',
            'jumlah_saudara'   => 'required|is_natural',
            'no_hp'            => 'permit_empty|min_length[10]|max_length[15]',
            'alamat_desa'      => 'required|min_length[3]|max_length[100]',
            'alamat_kecamatan' => 'required|min_length[3]|max_length[100]',
            'alamat_kabupaten' => 'required|min_length[3]|max_length[100]',
            'nama_sekolah'     => 'required|min_length[3]|max_length[100]',
            'alamat_sekolah'   => 'required|min_length[5]',
            'nama_ayah'        => 'required|min_length[3]|max_length[150]',
            'pekerjaan_ayah'   => 'required|min_length[3]',
            'nama_ibu'         => 'required|min_length[3]|max_length[150]',
            'pekerjaan_ibu'    => 'required|min_length[3]',
            'pas_foto'         => 'max_size[pas_foto,2048]|ext_in[pas_foto,jpg,jpeg,png]|is_image[pas_foto]',
            'fc_ijazah_skl'    => 'max_size[fc_ijazah_skl,2048]|ext_in[fc_ijazah_skl,pdf,jpg,jpeg,png]',
            'fc_kk'            => 'max_size[fc_kk,2048]|ext_in[fc_kk,pdf,jpg,jpeg,png]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data pendaftar. Periksa kembali isian form Anda.');
        }

        $db = Database::connect();

        // Cari atau buat master_sekolah & update alamat_sekolah
        $sekolahModel = new \App\Models\MasterSekolahModel();
        $sekolahId = $sekolahModel->getOrCreateSekolahId($this->request->getPost('nama_sekolah'));
        $db->table('master_sekolah')->where('id', $sekolahId)->update([
            'alamat_sekolah' => $this->request->getPost('alamat_sekolah'),
        ]);

        // Insert tabel calon_siswa
        $db->table('calon_siswa')->insert([
            'nisn'               => $this->request->getPost('nisn'),
            'nama_lengkap'       => $this->request->getPost('nama_lengkap'),
            'nik'                => $this->request->getPost('nik'),
            'no_kk'              => $this->request->getPost('no_kk'),
            'tempat_lahir'       => $this->request->getPost('tempat_lahir'),
            'tanggal_lahir'      => $this->request->getPost('tanggal_lahir'),
            'jenis_kelamin'      => $this->request->getPost('jenis_kelamin'),
            'anak_ke'            => $this->request->getPost('anak_ke'),
            'jumlah_saudara'     => $this->request->getPost('jumlah_saudara'),
            'no_hp'              => $this->request->getPost('no_hp'),
            'alamat_desa'        => $this->request->getPost('alamat_desa'),
            'alamat_kecamatan'   => $this->request->getPost('alamat_kecamatan'),
            'alamat_kabupaten'   => $this->request->getPost('alamat_kabupaten'),
            'sekolah_id'         => $sekolahId,
            'created_at'         => date('Y-m-d H:i:s'),
            'updated_at'         => date('Y-m-d H:i:s'),
        ]);
        $siswaId = $db->insertID();

        // Buat no registrasi
        $noReg = 'REG-' . date('Y') . '-' . $siswaId;
        $db->table('calon_siswa')->where('id', $siswaId)->update(['no_registrasi' => $noReg]);

        // Insert data_orang_tua
        $db->table('data_orang_tua')->insert([
            'siswa_id'       => $siswaId,
            'nama_ayah'      => $this->request->getPost('nama_ayah'),
            'pekerjaan_ayah' => $this->request->getPost('pekerjaan_ayah'),
            'nama_ibu'       => $this->request->getPost('nama_ibu'),
            'pekerjaan_ibu'  => $this->request->getPost('pekerjaan_ibu'),
        ]);

        // Proses Upload Berkas (Opsional)
        $pasFotoName = null;
        $ijazahName = null;
        $kkName = null;
        
        $uploadPath = FCPATH . 'uploads/berkas/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $filePasFoto = $this->request->getFile('pas_foto');
        if ($filePasFoto && $filePasFoto->isValid() && !$filePasFoto->hasMoved()) {
            $pasFotoName = $filePasFoto->getRandomName();
            $filePasFoto->move($uploadPath, $pasFotoName);
        }

        $fileIjazah = $this->request->getFile('fc_ijazah_skl');
        if ($fileIjazah && $fileIjazah->isValid() && !$fileIjazah->hasMoved()) {
            $ijazahName = $fileIjazah->getRandomName();
            $fileIjazah->move($uploadPath, $ijazahName);
        }

        $fileKk = $this->request->getFile('fc_kk');
        if ($fileKk && $fileKk->isValid() && !$fileKk->hasMoved()) {
            $kkName = $fileKk->getRandomName();
            $fileKk->move($uploadPath, $kkName);
        }

        // Insert kelengkapan_administrasi
        $db->table('kelengkapan_administrasi')->insert([
            'siswa_id'      => $siswaId,
            'pas_foto'      => $pasFotoName,
            'fc_ijazah_skl' => $ijazahName,
            'fc_kk'         => $kkName,
        ]);

        // Insert status_pendaftaran
        $db->table('status_pendaftaran')->insert([
            'siswa_id'             => $siswaId,
            'status_verifikasi_id' => 1,
            'is_finalized'         => 0,
            'created_at'           => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()
            ->with('success', 'Data pendaftar baru berhasil ditambahkan!');
    }

    public function exportCsv()
    {
        $this->requireGroup(['admin', 'panitia']);
        $db = Database::connect();
        $builder = $db->table('calon_siswa')
            ->select('
                calon_siswa.id, 
                calon_siswa.nisn as username, 
                calon_siswa.nama_lengkap, 
                calon_siswa.nik, 
                calon_siswa.no_hp,
                calon_siswa.tempat_lahir,
                calon_siswa.tanggal_lahir,
                calon_siswa.jenis_kelamin,
                master_sekolah.nama_sekolah, 
                status_verifikasi.nama_status as status_verifikasi, 
                status_pendaftaran.is_finalized
            ')
            ->join('master_sekolah', 'master_sekolah.id = calon_siswa.sekolah_id', 'left')
            ->join('kelengkapan_administrasi', 'kelengkapan_administrasi.siswa_id = calon_siswa.id', 'left')
            ->join('status_pendaftaran', 'status_pendaftaran.siswa_id = calon_siswa.id', 'left')
            ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left')
            ->orderBy('calon_siswa.id', 'DESC');

        $students = $builder->get()->getResultArray();

        // Header for Excel CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=daftar_pendaftar_spmb_' . date('Ymd_His') . '.csv');

        $output = fopen('php://output', 'w');
        
        // Add UTF-8 BOM to prevent Excel parsing error of special characters
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Add headers
        fputcsv($output, [
            'ID', 'NISN', 'Nama Lengkap', 'NIK', 'No HP', 'Tempat Lahir', 
            'Tanggal Lahir', 'Jenis Kelamin', 'Asal Sekolah', 
            'Status Berkas', 'Status Finalisasi'
        ]);

        foreach ($students as $student) {
            fputcsv($output, [
                $student['id'],
                $student['username'],
                $student['nama_lengkap'] ?: 'Belum Mengisi',
                $student['nik'] ?: '-',
                $student['no_hp'] ?: '-',
                $student['tempat_lahir'] ?: '-',
                $student['tanggal_lahir'] ?: '-',
                $student['jenis_kelamin'] ?: '-',
                $student['nama_sekolah'] ?: '-',
                $student['status_verifikasi'] ?: 'Belum Verifikasi',
                $student['is_finalized'] ? 'Selesai' : 'Belum Final'
            ]);
        }
        fclose($output);
        exit;
    }

    public function cetakData()
    {
        $this->requireGroup(['admin', 'panitia']);
        $db = Database::connect();
        $builder = $db->table('calon_siswa')
            ->select('
                calon_siswa.id, 
                calon_siswa.nisn as username, 
                calon_siswa.nama_lengkap, 
                calon_siswa.nik, 
                calon_siswa.no_hp,
                master_sekolah.nama_sekolah, 
                status_verifikasi.nama_status as status_verifikasi, 
                status_pendaftaran.is_finalized
            ')
            ->join('master_sekolah', 'master_sekolah.id = calon_siswa.sekolah_id', 'left')
            ->join('kelengkapan_administrasi', 'kelengkapan_administrasi.siswa_id = calon_siswa.id', 'left')
            ->join('status_pendaftaran', 'status_pendaftaran.siswa_id = calon_siswa.id', 'left')
            ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left')
            ->orderBy('calon_siswa.id', 'DESC');

        $students = $builder->get()->getResultArray();

        // Render clean print page
        return view('admin/cetak-data', ['students' => $students]);
    }

    public function cetakFormulir($studentId)
    {
        $this->requireGroup(['admin', 'panitia']);
        $user = auth()->user();

        $db = Database::connect();
        $builder = $db->table('calon_siswa')
            ->select('
                calon_siswa.id, 
                calon_siswa.nisn, 
                calon_siswa.nisn as username, 
                calon_siswa.created_at,
                calon_siswa.nama_lengkap, 
                calon_siswa.nik, 
                calon_siswa.no_kk,
                calon_siswa.tempat_lahir,
                calon_siswa.tanggal_lahir,
                calon_siswa.jenis_kelamin,
                calon_siswa.no_hp,
                calon_siswa.anak_ke,
                calon_siswa.jumlah_saudara,
                calon_siswa.no_registrasi,
                CONCAT(calon_siswa.alamat_desa, \', \', calon_siswa.alamat_kecamatan, \', \', calon_siswa.alamat_kabupaten) as alamat,
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
                status_pendaftaran.is_finalized
            ')
            ->join('master_sekolah', 'master_sekolah.id = calon_siswa.sekolah_id', 'left')
            ->join('kelengkapan_administrasi', 'kelengkapan_administrasi.siswa_id = calon_siswa.id', 'left')
            ->join('status_pendaftaran', 'status_pendaftaran.siswa_id = calon_siswa.id', 'left')
            ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left')
            ->join('data_orang_tua', 'data_orang_tua.siswa_id = calon_siswa.id', 'left')
            ->where('calon_siswa.id', $studentId);

        $student = $builder->get()->getRowArray();

        if (!$student) {
            return redirect()->back()->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Render beautiful clean printable card
        return view('siswa/cetak-formulir', ['student' => $student]);
    }

    public function cetakArsip($studentId)
    {
        $this->requireGroup(['admin', 'panitia']);
        $user = auth()->user();

        $db = Database::connect();
        $builder = $db->table('calon_siswa')
            ->select('
                calon_siswa.id,
                calon_siswa.nisn,
                calon_siswa.no_registrasi,
                calon_siswa.nik,
                calon_siswa.nama_lengkap,
                calon_siswa.tempat_lahir,
                calon_siswa.tanggal_lahir,
                calon_siswa.jenis_kelamin,
                calon_siswa.no_kk,
                calon_siswa.no_hp,
                calon_siswa.anak_ke,
                calon_siswa.jumlah_saudara,
                calon_siswa.alamat_desa,
                calon_siswa.alamat_kecamatan,
                calon_siswa.alamat_kabupaten,
                calon_siswa.created_at,
                master_sekolah.nama_sekolah,
                master_sekolah.alamat_sekolah,
                kelengkapan_administrasi.fc_ijazah_skl,
                kelengkapan_administrasi.fc_kk,
                kelengkapan_administrasi.pas_foto,
                data_orang_tua.nama_ayah,
                data_orang_tua.pekerjaan_ayah,
                data_orang_tua.nama_ibu,
                data_orang_tua.pekerjaan_ibu,
                status_verifikasi.nama_status as status_verifikasi,
                status_pendaftaran.is_finalized,
                status_pendaftaran.catatan_verifikasi
            ')
            ->join('master_sekolah', 'master_sekolah.id = calon_siswa.sekolah_id', 'left')
            ->join('kelengkapan_administrasi', 'kelengkapan_administrasi.siswa_id = calon_siswa.id', 'left')
            ->join('status_pendaftaran', 'status_pendaftaran.siswa_id = calon_siswa.id', 'left')
            ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left')
            ->join('data_orang_tua', 'data_orang_tua.siswa_id = calon_siswa.id', 'left')
            ->where('calon_siswa.id', $studentId);

        $student = $builder->get()->getRowArray();

        if (!$student) {
            return redirect()->back()->with('error', 'Data pendaftar tidak ditemukan.');
        }

        return view('admin/cetak-arsip', ['student' => $student]);
    }

    public function deleteStudent($userId)
    {
        $this->requireGroup(['admin']);

        if ($this->deleteStudentByUserId($userId)) {
            return redirect()->back()->with('success', 'Data pendaftar dan akun terkait berhasil dihapus secara permanen!');
        }

        return redirect()->back()->with('error', 'Gagal menghapus data pendaftar. Data tidak ditemukan.');
    }

    public function bulkDeleteStudents()
    {
        $this->requireGroup(['admin']);

        $userIds = $this->request->getPost('ids');
        if (empty($userIds) || !is_array($userIds)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada calon siswa terpilih untuk dihapus.']);
        }

        $deletedCount = 0;
        foreach ($userIds as $userId) {
            if ($this->deleteStudentByUserId((int) $userId)) {
                $deletedCount++;
            }
        }

        if ($deletedCount > 0) {
            session()->setFlashdata('success', "Berhasil menghapus {$deletedCount} calon siswa terpilih.");
            return $this->response->setJSON(['status' => 'success']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada data yang berhasil dihapus.']);
    }
}
