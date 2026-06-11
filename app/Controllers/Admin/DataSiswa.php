<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\RegistrationService;
use App\Services\StudentService;
use CodeIgniter\HTTP\ResponseInterface;

class DataSiswa extends BaseController
{
    protected $helpers = ['url', 'html', 'form'];

    public function index(): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);
        $shieldUser = auth()->user();

        $studentService = new StudentService();
        $students = $studentService->getAllStudentsWithDetail();

        $data = [
            ...$this->getSiteSettings(),
            'admin_username' => $shieldUser->username,
            'user_email'     => $shieldUser->email,
            'roleLabel'      => $shieldUser->inGroup(ROLE_ADMIN) ? 'Administrator' : 'Panitia SPMB',
            'isAdmin'        => $shieldUser->inGroup(ROLE_ADMIN),
            'students'       => $students,
        ];

        return $this->response->setBody(view('admin/data-siswa', $data));
    }

    public function verify(int $userId): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);
        $statusId = (int) $this->request->getPost('status_verifikasi_id');
        $catatan  = $this->request->getPost('catatan_verifikasi');

        $studentService = new StudentService();

        try {
            $studentService->verifyStudent($userId, $statusId, $catatan);

            return redirect()->back()
                ->with('success', 'Data verifikasi berkas calon siswa berhasil diperbarui!');
        } catch (\RuntimeException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function edit(int $userId): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);

        if (! $this->validateData($this->request->getPost(), 'adminSiswaBiodata')) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data. Periksa kembali isian form Anda.');
        }

        $studentService = new StudentService();
        $updated = $studentService->updateStudent($userId, $this->request->getPost());

        if (! $updated) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data. Silakan coba lagi.');
        }

        return redirect()->back()
            ->with('success', 'Data pendaftar berhasil diperbarui secara permanen!');
    }

    public function add(): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);

        $extraRules = [
            'nisn'             => 'required|min_length[3]|max_length[30]|is_unique[calon_siswa.nisn]',
            'pas_foto'         => 'max_size[pas_foto,2048]|ext_in[pas_foto,jpg,jpeg,png]|is_image[pas_foto]',
            'fc_ijazah_skl'    => 'max_size[fc_ijazah_skl,2048]|ext_in[fc_ijazah_skl,pdf,jpg,jpeg,png]',
            'fc_kk'            => 'max_size[fc_kk,2048]|ext_in[fc_kk,pdf,jpg,jpeg,png]',
        ];

        $rules = array_merge(config('Validation')->adminSiswaBiodata, $extraRules);

        if (! $this->validateData($this->request->getPost(), $rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data pendaftar. Periksa kembali isian form Anda.');
        }

        $registrationService = new RegistrationService();
        $registrationService->createManualStudent(
            $this->request->getPost(),
            [
                'pas_foto'      => $this->request->getFile('pas_foto'),
                'fc_ijazah_skl' => $this->request->getFile('fc_ijazah_skl'),
                'fc_kk'         => $this->request->getFile('fc_kk'),
            ]
        );

        return redirect()->back()
            ->with('success', 'Data pendaftar baru berhasil ditambahkan!');
    }

    public function delete(int $userId): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN]);

        $studentService = new StudentService();
        if ($studentService->deleteStudent($userId)) {
            return redirect()->back()->with('success', 'Data pendaftar dan akun terkait berhasil dihapus secara permanen!');
        }

        return redirect()->back()->with('error', 'Gagal menghapus data pendaftar. Data tidak ditemukan.');
    }

    public function gridData(): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);

        $limit  = (int) ($this->request->getGet('limit') ?? 15);
        $offset = (int) ($this->request->getGet('offset') ?? 0);
        $search = $this->request->getGet('search') ?? '';
        $status = $this->request->getGet('status') ?? '';

        $studentService = new StudentService();
        $result = $studentService->getGridData($limit, $offset, $search, $status);

        return $this->response->setJSON($result);
    }

    public function bulkDelete(): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN]);

        $userIds = $this->request->getPost('ids');
        if (empty($userIds) || !is_array($userIds)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tidak ada calon siswa terpilih untuk dihapus.']);
        }

        $studentService = new StudentService();
        $deletedCount = 0;
        foreach ($userIds as $userId) {
            if ($studentService->deleteStudent((int) $userId)) {
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
