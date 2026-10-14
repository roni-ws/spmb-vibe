<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\SettingsService;
use CodeIgniter\HTTP\ResponseInterface;

class Pengumuman extends BaseController
{
    protected $helpers = ['url', 'html', 'form'];

    protected SettingsService $settingsService;

    public function __construct()
    {
        $this->settingsService = new SettingsService();
    }

    public function index(): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);

        $shieldUser = auth()->user();
        $announcements = $this->settingsService->getAllAnnouncements();
        $whatsappLink = $this->settingsService->get('whatsapp_link', 'https://wa.me/6281234567890');

        return $this->response->setBody(view('admin/pengumuman', [
            ...$this->getSiteSettings(),
            'admin_username' => $shieldUser->username,
            'user_email'     => $shieldUser->email,
            'roleLabel'      => $shieldUser->inGroup(ROLE_ADMIN) ? 'Administrator' : 'Panitia SPMB',
            'isAdmin'        => $shieldUser->inGroup(ROLE_ADMIN),
            'announcements'  => $announcements,
            'whatsapp_link'  => $whatsappLink,
        ]));
    }

    public function add(): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);

        if (! $this->validateData($this->request->getPost(), 'pengumuman')) {
            return redirect()->to(base_url('admin/pengumuman'))
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->settingsService->addAnnouncement(
            $this->request->getPost('judul'),
            $this->request->getPost('konten')
        );

        return redirect()->to(base_url('admin/pengumuman'))
            ->with('success', 'Pengumuman baru berhasil dipublikasikan!');
    }

    public function delete(int $id): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);

        $pengumuman = $this->settingsService->getAnnouncement($id);
        if (! $pengumuman) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data pengumuman tidak ditemukan.']);
        }

        $this->settingsService->deleteAnnouncement($id);

        session()->setFlashdata('success', 'Pengumuman berhasil dihapus.');
        return $this->response->setJSON(['status' => 'success']);
    }

    public function saveWhatsapp(): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);

        $rules = ['whatsapp_link' => 'required|valid_url'];

        if (! $this->validateData($this->request->getPost(), $rules)) {
            return redirect()->to(base_url('admin/pengumuman'))
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->settingsService->update([
            'whatsapp_link' => $this->request->getPost('whatsapp_link'),
        ]);

        return redirect()->to(base_url('admin/pengumuman'))
            ->with('success', 'Tautan WhatsApp Group berhasil diperbarui!');
    }
}
