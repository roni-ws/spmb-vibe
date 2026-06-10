<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\PengumumanModel;
use App\Services\AdminNotificationService;
use App\Services\StudentService;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    protected $helpers = ['url', 'html'];

    protected StudentService $studentService;

    public function __construct()
    {
        $this->studentService = new StudentService();
    }

    public function index(): ResponseInterface
    {
        $this->requireSiswa();

        $shieldUser = auth()->user();
        $biodata    = $this->studentService->getStudentProfileByUserId((int) $shieldUser->id);

        $sekolah           = [];
        $berkas            = [];
        $statusPendaftaran = null;

        if ($biodata) {
            $sekolah = $this->studentService->getStudentSchool((int) $biodata['id']);
            $berkas  = $this->studentService->getStudentBerkas((int) $biodata['id']);
            $statusPendaftaran = $this->studentService->getRegistrationStatus((int) $biodata['id']);
        }

        $statusVerifikasi  = $statusPendaftaran['status_verifikasi'] ?? VERIF_STATUS_BELUM;
        $catatanVerifikasi = $statusPendaftaran['catatan_verifikasi'] ?? null;
        $isFinalized       = (int) ($statusPendaftaran['is_finalized'] ?? 0);

        $progress = $this->studentService->calculateProgress($biodata, $sekolah, $berkas);

        $namaLengkap   = $progress['hasBiodata'] ? $biodata['nama_lengkap'] : ucfirst($shieldUser->username ?? 'Siswa');
        $needsRevision = $this->studentService->needsRevision($statusVerifikasi);

        $announcements = model(PengumumanModel::class)->orderBy('created_at', 'DESC')->findAll();
        $waLink = service('settings')->get('App.whatsapp_link', 'https://wa.me/6281234567890');

        return $this->renderSiswaView('siswa/dashboard', [
            'waLink'             => $waLink,
            'username'           => $shieldUser->username,
            'nama_lengkap'       => $namaLengkap,
            'has_biodata'        => $progress['hasBiodata'],
            'has_sekolah'        => $progress['hasSekolah'],
            'has_berkas'         => $progress['hasBerkas'],
            'progress_step'      => $progress['progressStep'],
            'progress_percent'   => $progress['progressPercent'],
            'status_verifikasi'  => $statusVerifikasi,
            'catatan_verifikasi' => $catatanVerifikasi,
            'is_finalized'       => $isFinalized,
            'needs_revision'     => $needsRevision,
            'can_edit'           => $isFinalized === 0,
            'announcements'      => $announcements,
        ]);
    }

    public function finalize(): ResponseInterface
    {
        $this->requireSiswa();

        $user  = auth()->user();
        $siswa = $this->studentService->getStudentByUserId((int) $user->id);

        if (!$siswa) {
            return redirect()->to(base_url('siswa/dashboard'))->with('error', 'Data pendaftar tidak ditemukan.');
        }

        $this->studentService->finalizeStudent((int) $siswa['id']);

        $adminNotif = new AdminNotificationService();
        $adminNotif->broadcast(NOTIF_ADMIN_SISWA_FINALIZED,
            'Siswa Finalisasi',
            "{$siswa['nama_lengkap']} telah melakukan finalisasi pendaftaran. Silakan verifikasi berkasnya."
        );

        return redirect()->to(base_url('siswa/dashboard'))
            ->with('success', 'Pendaftaran Anda berhasil difinalisasi! Data Anda telah dikunci dan tidak dapat diubah lagi.');
    }

    public function printFormulir(): ResponseInterface
    {
        $this->requireSiswa();

        $user    = auth()->user();
        $student = $this->studentService->getStudentForCetakByUserId((int) $user->id);

        if (!$student) {
            return redirect()->back()->with('error', 'Data pendaftar tidak ditemukan.');
        }

        $statusRow = $this->getRegistrationStatus((int) $student['id']);
        if (! $this->canCetakBukti($statusRow)) {
            return redirect()->to(base_url('siswa/dashboard'))
                ->with('error', 'Cetak bukti pendaftaran hanya tersedia setelah berkas dinyatakan Valid oleh panitia.');
        }

        return $this->response->setBody(view('siswa/cetak-formulir', [
            ...$this->getSiteSettings(),
            'student' => $student,
        ]));
    }
}
