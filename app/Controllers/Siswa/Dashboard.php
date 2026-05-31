<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\BiodataModel;
use App\Models\SekolahModel;
use App\Models\BerkasModel;

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

        $userId = $shieldUser->id;

        // 3. Inisialisasi Model Relasional
        $biodataModel = new BiodataModel();
        $sekolahModel = new SekolahModel();
        $berkasModel  = new BerkasModel();

        // 4. Tarik Data Relasional dari Database
        $biodata = $biodataModel->where('user_id', $userId)->first();
        $sekolah = $sekolahModel->where('user_id', $userId)->first();
        $berkas  = $berkasModel->where('user_id', $userId)->first();

        // 5. Kalkulasi Status Kelengkapan Formulir
        $hasBiodata = ! empty($biodata);
        $hasSekolah = ! empty($sekolah);
        $hasBerkas  = ! empty($berkas)
            && (! empty($berkas['file_foto']) || ! empty($berkas['file_ijazah']) || ! empty($berkas['file_kk']));

        // 6. Hitung Progres Pendaftaran (4 Langkah, masing-masing 25%)
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

        // 7. Data Sapaan & Status Verifikasi
        $namaLengkap       = $hasBiodata ? $biodata['nama_lengkap'] : ucfirst($shieldUser->username ?? 'Siswa');
        $statusVerifikasi  = $berkas ? $berkas['status_verifikasi'] : 'Pending';
        $catatanVerifikasi = $berkas ? $berkas['catatan_verifikasi'] : null;
        $isFinalized       = $berkas ? (int)$berkas['is_finalized'] : 0;
        $statusKelulusan   = $berkas ? $berkas['status_kelulusan'] : 'Belum Pengumuman';

        // 8. Siapkan Data untuk View
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

        $userId = $user->id;
        $db = \Config\Database::connect();

        // Ambil data berkas
        $berkas = $db->table('berkas')->where('user_id', $userId)->get()->getRowArray();

        if ($berkas) {
            $db->table('berkas')->where('user_id', $userId)->update([
                'is_finalized' => 1,
                'updated_at'   => date('Y-m-d H:i:s')
            ]);
        } else {
            $db->table('berkas')->insert([
                'user_id'            => $userId,
                'is_finalized'       => 1,
                'status_verifikasi'  => 'Pending',
                'status_kelulusan'   => 'Belum Pengumuman',
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s')
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

        $userId = $user->id;

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
            ->where('users.id', $userId);

        $student = $builder->get()->getRowArray();

        if (!$student) {
            return redirect()->back()->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Render beautiful clean printable card
        return view('siswa/form_pdf', ['student' => $student]);
    }
}
