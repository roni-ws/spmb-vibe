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

        // 2. Ambil semua data pendaftar (siswa) untuk kalkulasi statistik
        $db = \Config\Database::connect();
        $builder = $db->table('users')
            ->select('
                users.id, 
                berkas.status_verifikasi,
                users.created_at
            ')
            ->join('berkas', 'berkas.user_id = users.id', 'left')
            ->where('users.role', 'student')
            ->orderBy('users.created_at', 'DESC');

        $students = $builder->get()->getResultArray();

        // 3. Hitung statistik untuk dashboard admin
        $stats = [
            'total'     => count($students),
            'pending'   => 0,
            'valid'     => 0,
            'invalid'   => 0,
        ];

        foreach ($students as $student) {
            $status = $student['status_verifikasi'] ?? 'Pending';
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

        $trendBuilder = $db->table('users')
            ->select("DATE_FORMAT(users.created_at, '%Y-%m') as month_key, COUNT(users.id) as count")
            ->where('users.role', 'student')
            ->groupBy("month_key");
        $trendsRaw = $trendBuilder->get()->getResultArray();

        foreach ($trendsRaw as $row) {
            $key = $row['month_key'];
            if (isset($monthlyTrends[$key])) {
                $monthlyTrends[$key]['count'] = (int)$row['count'];
            }
        }

        // Ambil 4 pendaftar terbaru untuk daftar aktivitas/pendaftar terbaru
        $recentBuilder = $db->table('users')
            ->select('users.id, users.username, biodata.nama_lengkap, users.created_at')
            ->join('biodata', 'biodata.user_id = users.id', 'left')
            ->where('users.role', 'student')
            ->orderBy('users.created_at', 'DESC')
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
        $builder = $db->table('users')
            ->select('users.id, berkas.status_verifikasi, users.created_at')
            ->join('berkas', 'berkas.user_id = users.id', 'left')
            ->where('users.role', 'student');
        $students = $builder->get()->getResultArray();
        $stats = ['total' => count($students), 'pending' => 0, 'valid' => 0, 'invalid' => 0];
        foreach ($students as $s) {
            $status = $s['status_verifikasi'] ?? 'Pending';
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

        // 2. Ambil semua data pendaftar (siswa) bergabung dengan biodata, sekolah, dan berkas
        $db = \Config\Database::connect();
        $builder = $db->table('users')
            ->select('
                users.id, 
                users.username, 
                users.email, 
                users.created_at,
                biodata.nama_lengkap, 
                biodata.nik, 
                biodata.tempat_lahir,
                biodata.tanggal_lahir,
                biodata.jenis_kelamin,
                biodata.no_hp, 
                sekolah.nama_sekolah, 
                sekolah.nilai_rata_rata, 
                berkas.status_verifikasi, 
                berkas.is_finalized,
                berkas.status_kelulusan,
                berkas.catatan_verifikasi, 
                berkas.file_foto, 
                berkas.file_ijazah, 
                berkas.file_kk
            ')
            ->join('biodata', 'biodata.user_id = users.id', 'left')
            ->join('sekolah', 'sekolah.user_id = users.id', 'left')
            ->join('berkas', 'berkas.user_id = users.id', 'left')
            ->where('users.role', 'student')
            ->orderBy('users.created_at', 'DESC');

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

        $status          = $this->request->getPost('status_verifikasi');
        $statusKelulusan = $this->request->getPost('status_kelulusan');
        $catatan         = $this->request->getPost('catatan_verifikasi');

        $db = \Config\Database::connect();
        
        // Periksa apakah berkas ada
        $berkas = $db->table('berkas')->where('user_id', $userId)->get()->getRowArray();

        if ($berkas) {
            $db->table('berkas')->where('user_id', $userId)->update([
                'status_verifikasi'  => $status,
                'status_kelulusan'   => $statusKelulusan,
                'catatan_verifikasi' => $catatan,
                'updated_at'         => date('Y-m-d H:i:s'),
            ]);
        } else {
            $db->table('berkas')->insert([
                'user_id'            => $userId,
                'status_verifikasi'  => $status,
                'status_kelulusan'   => $statusKelulusan,
                'catatan_verifikasi' => $catatan,
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->back()
            ->with('success', 'Data verifikasi dan status kelulusan calon siswa berhasil diperbarui!');
    }

    public function exportCsv()
    {
        // Proteksi rute
        if (! auth()->loggedIn() || ! auth()->user()->inGroup('admin')) {
            return redirect()->to(base_url('login'))->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('users')
            ->select('
                users.id, 
                users.username, 
                users.email, 
                biodata.nama_lengkap, 
                biodata.nik, 
                biodata.tempat_lahir,
                biodata.tanggal_lahir,
                biodata.jenis_kelamin,
                biodata.no_hp, 
                sekolah.nama_sekolah, 
                sekolah.nilai_rata_rata, 
                berkas.status_verifikasi, 
                berkas.status_kelulusan,
                berkas.is_finalized
            ')
            ->join('biodata', 'biodata.user_id = users.id', 'left')
            ->join('sekolah', 'sekolah.user_id = users.id', 'left')
            ->join('berkas', 'berkas.user_id = users.id', 'left')
            ->where('users.role', 'student')
            ->orderBy('users.created_at', 'DESC');

        $students = $builder->get()->getResultArray();

        // Header for Excel CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=daftar_pendaftar_spmb_' . date('Ymd_His') . '.csv');

        $output = fopen('php://output', 'w');
        
        // Add UTF-8 BOM to prevent Excel parsing error of special characters
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Add headers
        fputcsv($output, [
            'ID', 'Username', 'Email', 'Nama Lengkap', 'NIK', 'Tempat Lahir', 
            'Tanggal Lahir', 'Jenis Kelamin', 'No. HP', 'Asal Sekolah', 
            'Nilai Rapor', 'Status Berkas', 'Status Kelulusan', 'Status Finalisasi'
        ]);

        foreach ($students as $student) {
            fputcsv($output, [
                $student['id'],
                $student['username'],
                $student['email'],
                $student['nama_lengkap'] ?: 'Belum Mengisi',
                $student['nik'] ?: '-',
                $student['tempat_lahir'] ?: '-',
                $student['tanggal_lahir'] ?: '-',
                $student['jenis_kelamin'] ?: '-',
                $student['no_hp'] ?: '-',
                $student['nama_sekolah'] ?: '-',
                $student['nilai_rata_rata'] ? number_format($student['nilai_rata_rata'], 2) : '-',
                $student['status_verifikasi'] ?: 'Pending',
                $student['status_kelulusan'] ?: 'Belum Pengumuman',
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
        $builder = $db->table('users')
            ->select('
                users.id, 
                users.username, 
                users.email, 
                biodata.nama_lengkap, 
                biodata.nik, 
                sekolah.nama_sekolah, 
                sekolah.nilai_rata_rata, 
                berkas.status_verifikasi, 
                berkas.status_kelulusan,
                berkas.is_finalized
            ')
            ->join('biodata', 'biodata.user_id = users.id', 'left')
            ->join('sekolah', 'sekolah.user_id = users.id', 'left')
            ->join('berkas', 'berkas.user_id = users.id', 'left')
            ->where('users.role', 'student')
            ->orderBy('users.created_at', 'DESC');

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

        // Jika bukan admin, pastikan siswa hanya mencetak form miliknya sendiri
        if (!$user->inGroup('admin') && $user->id != $studentId) {
            return redirect()->to(base_url('siswa/dashboard'))->with('error', 'Akses ditolak. Anda tidak berhak mencetak data pendaftar lain.');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('users')
            ->select('
                users.id, 
                users.username, 
                users.email, 
                users.created_at,
                biodata.nama_lengkap, 
                biodata.nik, 
                biodata.tempat_lahir,
                biodata.tanggal_lahir,
                biodata.jenis_kelamin,
                biodata.no_hp,
                biodata.nama_ayah,
                biodata.pekerjaan_ayah,
                biodata.nama_ibu,
                biodata.pekerjaan_ibu,
                biodata.alamat,
                sekolah.nama_sekolah, 
                sekolah.npsn,
                sekolah.tahun_lulus,
                sekolah.nilai_rata_rata, 
                berkas.status_verifikasi, 
                berkas.status_kelulusan,
                berkas.is_finalized
            ')
            ->join('biodata', 'biodata.user_id = users.id', 'left')
            ->join('sekolah', 'sekolah.user_id = users.id', 'left')
            ->join('berkas', 'berkas.user_id = users.id', 'left')
            ->where('users.id', $studentId);

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
        
        // 2. Cari data pendaftar & hapus berkas fisik dari folder uploads jika ada
        $berkas = $db->table('berkas')->where('user_id', $userId)->get()->getRowArray();
        if ($berkas) {
            $files = ['file_foto', 'file_ijazah', 'file_kk'];
            foreach ($files as $fileKey) {
                if (!empty($berkas[$fileKey])) {
                    $filePath = FCPATH . 'uploads/' . ($fileKey === 'file_foto' ? 'foto/' : 'dokumen/') . $berkas[$fileKey];
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
            }
        }

        // 3. Hapus user. Karena ON DELETE CASCADE aktif di semua relasi (biodata, sekolah, berkas, auth_identities, dsb),
        // menghapus baris dari tabel 'users' akan membersihkan semuanya secara otomatis!
        $deleted = $db->table('users')->where('id', $userId)->delete();

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
            
            // Hapus berkas fisik
            $berkas = $db->table('berkas')->where('user_id', $userId)->get()->getRowArray();
            if ($berkas) {
                $files = ['file_foto', 'file_ijazah', 'file_kk'];
                foreach ($files as $fileKey) {
                    if (!empty($berkas[$fileKey])) {
                        $filePath = FCPATH . 'uploads/' . ($fileKey === 'file_foto' ? 'foto/' : 'dokumen/') . $berkas[$fileKey];
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }
                    }
                }
            }

            // Hapus user (cascades)
            if ($db->table('users')->where('id', $userId)->delete()) {
                $deletedCount++;
            }
        }

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => "Berhasil menghapus {$deletedCount} calon siswa terpilih."
        ]);
    }
}
