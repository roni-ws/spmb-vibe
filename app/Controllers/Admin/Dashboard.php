<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    protected $helpers = ['url', 'html', 'form'];

    public function index()
    {
        // 1. Proteksi rute: Harus login dan masuk dalam group admin
        if (! auth()->loggedIn() || ! auth()->user()->inGroup('admin')) {
            return redirect()->to(base_url('login'))
                ->with('error', 'Akses ditolak. Rute ini khusus untuk Panitia / Admin.');
        }

        $shieldUser = auth()->user();

        // 2. Ambil semua data pendaftar (siswa) dari tabel calon_siswa untuk kalkulasi statistik
        $db = \Config\Database::connect();
        $builder = $db->table('calon_siswa')
            ->select('
                calon_siswa.id, 
                status_verifikasi.nama_status as status_verifikasi,
                COALESCE(kelengkapan_administrasi.created_at, NOW()) as created_at
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
            ->select("DATE_FORMAT(COALESCE(kelengkapan_administrasi.created_at, NOW()), '%Y-%m') as month_key, COUNT(calon_siswa.id) as count")
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
            ->select('calon_siswa.id, calon_siswa.nisn as username, calon_siswa.nama_lengkap, COALESCE(kelengkapan_administrasi.created_at, NOW()) as created_at')
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
        // Ensure request is AJAX
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }
        $db = \Config\Database::connect();
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
        // 1. Proteksi rute: Harus login dan masuk dalam group admin
        if (! auth()->loggedIn() || ! auth()->user()->inGroup('admin')) {
            return redirect()->to(base_url('login'))
                ->with('error', 'Akses ditolak. Rute ini khusus untuk Panitia / Admin.');
        }

        $shieldUser = auth()->user();

        // 2. Ambil semua data pendaftar (siswa) dari calon_siswa
        $db = \Config\Database::connect();
        $builder = $db->table('calon_siswa')
            ->select('
                calon_siswa.id, 
                calon_siswa.nisn as username, 
                calon_siswa.nik, 
                COALESCE(kelengkapan_administrasi.created_at, NOW()) as created_at,
                calon_siswa.nama_lengkap, 
                calon_siswa.tempat_lahir,
                calon_siswa.tanggal_lahir,
                calon_siswa.jenis_kelamin,
                calon_siswa.nisn,
                calon_siswa.no_kk,
                master_sekolah.nama_sekolah, 
                kelengkapan_administrasi.pas_foto,
                status_verifikasi.nama_status as status_verifikasi, 
                status_pendaftaran.is_finalized,
                status_pendaftaran.catatan_verifikasi
            ')
            ->join('master_sekolah', 'master_sekolah.id = calon_siswa.sekolah_id', 'left')
            ->join('kelengkapan_administrasi', 'kelengkapan_administrasi.siswa_id = calon_siswa.id', 'left')
            ->join('status_pendaftaran', 'status_pendaftaran.siswa_id = calon_siswa.id', 'left')
            ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left')
            ->orderBy('calon_siswa.id', 'DESC');

        $students = $builder->get()->getResultArray();

        $data = [
            'admin_username' => $shieldUser->username,
            'students'       => $students,
        ];

        $renderedHtml = view('admin/pendaftar', $data);
        
        return $renderedHtml;
    }

    public function verify($userId)
    {
        // Proteksi rute
        if (! auth()->loggedIn() || ! auth()->user()->inGroup('admin')) {
            return redirect()->to(base_url('login'))
                ->with('error', 'Akses ditolak.');
        }

        $statusId        = $this->request->getPost('status_verifikasi_id');
        $catatan         = $this->request->getPost('catatan_verifikasi');

        $db = \Config\Database::connect();
        
        // Periksa apakah berkas ada
        $statusRecord = $db->table('status_pendaftaran')->where('siswa_id', $userId)->get()->getRowArray();

        if ($statusRecord) {
            $db->table('status_pendaftaran')->where('siswa_id', $userId)->update([
                'status_verifikasi_id' => $statusId,
                'catatan_verifikasi'   => $catatan,
                'updated_at'           => date('Y-m-d H:i:s'),
            ]);
        } else {
            $db->table('status_pendaftaran')->insert([
                'siswa_id'             => $userId,
                'status_verifikasi_id' => $statusId,
                'catatan_verifikasi'   => $catatan,
                'created_at'           => date('Y-m-d H:i:s'),
                'updated_at'           => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->back()
            ->with('success', 'Data verifikasi berkas calon siswa berhasil diperbarui!');
    }

    public function editStudent($userId)
    {
        // 1. Proteksi rute
        if (! auth()->loggedIn() || ! auth()->user()->inGroup('admin', 'panitia')) {
            return redirect()->to(base_url('login'))
                ->with('error', 'Akses ditolak.');
        }

        // 2. Validasi input
        $rules = [
            'nama_lengkap'     => 'required|min_length[3]|max_length[100]',
            'nik'              => 'required|min_length[5]|max_length[30]',
            'nama_sekolah'     => 'required|min_length[3]|max_length[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data. Periksa kembali isian form Anda.');
        }

        $db = \Config\Database::connect();

        // Cari atau buat master_sekolah
        $ppdbController = new \App\Controllers\Admin\Ppdb();
        $sekolahId = $ppdbController->getOrCreateSekolahId($this->request->getPost('nama_sekolah'));

        // 3. Update tabel calon_siswa
        $db->table('calon_siswa')->where('id', $userId)->update([
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'nik'           => $this->request->getPost('nik'),
            'sekolah_id'    => $sekolahId,
        ]);

        return redirect()->back()
            ->with('success', 'Data pendaftar berhasil diperbarui secara permanen!');
    }

    public function exportCsv()
    {
        // Proteksi rute
        if (! auth()->loggedIn() || ! auth()->user()->inGroup('admin')) {
            return redirect()->to(base_url('login'))->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('calon_siswa')
            ->select('
                calon_siswa.id, 
                calon_siswa.nisn as username, 
                calon_siswa.nama_lengkap, 
                calon_siswa.nik, 
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
            'ID', 'NISN', 'Nama Lengkap', 'NIK', 'Tempat Lahir', 
            'Tanggal Lahir', 'Jenis Kelamin', 'Asal Sekolah', 
            'Status Berkas', 'Status Finalisasi'
        ]);

        foreach ($students as $student) {
            fputcsv($output, [
                $student['id'],
                $student['username'],
                $student['nama_lengkap'] ?: 'Belum Mengisi',
                $student['nik'] ?: '-',
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

    public function exportPrint()
    {
        // Proteksi rute
        if (! auth()->loggedIn() || ! auth()->user()->inGroup('admin')) {
            return redirect()->to(base_url('login'))->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('calon_siswa')
            ->select('
                calon_siswa.id, 
                calon_siswa.nisn as username, 
                calon_siswa.nama_lengkap, 
                calon_siswa.nik, 
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
        return view('admin/export_print', ['students' => $students]);
    }

    public function downloadForm($studentId)
    {
        // Proteksi rute
        if (! auth()->loggedIn()) {
            return redirect()->to(base_url('login'))->with('error', 'Akses ditolak.');
        }

        $user = auth()->user();

        $db = \Config\Database::connect();
        $builder = $db->table('calon_siswa')
            ->select('
                calon_siswa.id, 
                calon_siswa.nisn as username, 
                COALESCE(kelengkapan_administrasi.created_at, NOW()) as created_at,
                calon_siswa.nama_lengkap, 
                calon_siswa.nik, 
                calon_siswa.tempat_lahir,
                calon_siswa.tanggal_lahir,
                calon_siswa.jenis_kelamin,
                calon_siswa.nisn as no_hp,
                data_orang_tua.nama_ayah,
                data_orang_tua.pekerjaan_ayah,
                data_orang_tua.nama_ibu,
                data_orang_tua.pekerjaan_ibu,
                calon_siswa.alamat_desa as alamat,
                master_sekolah.nama_sekolah, 
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
        return view('siswa/form_pdf', ['student' => $student]);
    }

    public function deleteStudent($userId)
    {
        // 1. Proteksi rute: Harus login dan masuk dalam group admin/superadmin/panitia
        if (! auth()->loggedIn() || ! auth()->user()->inGroup('admin', 'superadmin', 'panitia')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Akses ditolak.']);
        }

        $db = \Config\Database::connect();
        
        // 2. Hapus calon_siswa. Karena ON DELETE CASCADE aktif di semua relasi (data_orang_tua, kelengkapan_administrasi),
        // menghapus baris dari tabel 'calon_siswa' akan membersihkan semuanya secara otomatis!
        $deleted = $db->table('calon_siswa')->where('id', $userId)->delete();

        if ($deleted) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data pendaftar berhasil dihapus secara permanen.']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data pendaftar.']);
    }

    public function bulkDeleteStudents()
    {
        if (! auth()->loggedIn() || ! auth()->user()->inGroup('admin', 'superadmin', 'panitia')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Akses ditolak.']);
        }

        $userIds = $this->request->getPost('ids');
        if (empty($userIds) || !is_array($userIds)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada calon siswa terpilih untuk dihapus.']);
        }

        $db = \Config\Database::connect();
        $deletedCount = 0;

        foreach ($userIds as $userId) {
            $userId = (int) $userId;
            
            // Hapus calon_siswa (cascades)
            if ($db->table('calon_siswa')->where('id', $userId)->delete()) {
                $deletedCount++;
            }
        }

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => "Berhasil menghapus {$deletedCount} calon siswa terpilih."
        ]);
    }
}
