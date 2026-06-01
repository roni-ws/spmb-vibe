<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\MasterSekolahModel;
use App\Models\AdministrasiModel;

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

        $sekolah = null;
        $berkas  = null;

        if ($biodata) {
            $sekolahModel = new MasterSekolahModel();
            $sekolah = $sekolahModel->find($biodata['sekolah_id']);

            $berkasModel = new AdministrasiModel();
            $berkas = $berkasModel->where('siswa_id', $biodata['id'])->first();
        }

        // 4. Kalkulasi Status Kelengkapan Formulir
        $hasBiodata = ! empty($biodata) && ! empty($biodata['nik']) && ! str_contains($biodata['nik'], 'REG-');
        $hasSekolah = ! empty($sekolah) && $sekolah['nama_sekolah'] !== 'Belum Diisi';
        $hasBerkas  = ! empty($berkas) && ((int)$berkas['fc_ijazah_skl'] === 1 && (int)$berkas['fc_kk'] === 1);

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
        $statusVerifikasi  = $berkas ? $berkas['status_verifikasi'] : 'Belum Verifikasi';
        $catatanVerifikasi = $berkas ? $berkas['catatan_verifikasi'] : null;
        $isFinalized       = $berkas ? (int)$berkas['is_finalized'] : 0;
        $statusKelulusan   = 'Belum Pengumuman';

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

        // Ambil data kelengkapan_administrasi
        $berkas = $db->table('kelengkapan_administrasi')->where('siswa_id', $siswaId)->get()->getRowArray();

        if ($berkas) {
            $db->table('kelengkapan_administrasi')->where('siswa_id', $siswaId)->update([
                'is_finalized' => 1,
                'updated_at'   => date('Y-m-d H:i:s')
            ]);
        } else {
            $db->table('kelengkapan_administrasi')->insert([
                'siswa_id'           => $siswaId,
                'is_finalized'       => 1,
                'status_verifikasi'  => 'Belum Verifikasi',
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

        $db = \Config\Database::connect();
        $builder = $db->table('calon_siswa')
            ->select('
                calon_siswa.id, 
                calon_siswa.nisn as username, 
                calon_siswa.nisn as email, 
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
                kelengkapan_administrasi.status_verifikasi, 
                kelengkapan_administrasi.is_finalized
            ')
            ->join('master_sekolah', 'master_sekolah.id = calon_siswa.sekolah_id', 'left')
            ->join('kelengkapan_administrasi', 'kelengkapan_administrasi.siswa_id = calon_siswa.id', 'left')
            ->join('data_orang_tua', 'data_orang_tua.siswa_id = calon_siswa.id', 'left')
            ->where('calon_siswa.nisn', $user->username);

        $student = $builder->get()->getRowArray();

        if (!$student) {
            return redirect()->back()->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Render beautiful clean printable card
        return view('siswa/form_pdf', ['student' => $student]);
    }
}
