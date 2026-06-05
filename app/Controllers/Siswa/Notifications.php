<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Services\SiswaNotificationService;
use CodeIgniter\HTTP\ResponseInterface;

class Notifications extends BaseController
{
    protected SiswaNotificationService $notifService;

    public function __construct()
    {
        $this->notifService = new SiswaNotificationService();
    }

    public function index(): ResponseInterface
    {
        $this->requireSiswa();

        $user = auth()->user();
        $nisn = $user->nisn ?? $user->username;
        $siswa = model(\App\Models\Siswa\SiswaModel::class)
            ->where('nisn', $nisn)
            ->first();

        if (!$siswa) {
            return $this->response->setJSON(['unread' => 0, 'notifications' => []]);
        }

        $siswaId = (int) $siswa['id'];
        $unread = $this->notifService->getUnreadCount($siswaId);
        $notifications = $this->notifService->getRecent($siswaId, NOTIF_DROPDOWN_LIMIT);

        return $this->response->setJSON([
            'unread'        => $unread,
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(): ResponseInterface
    {
        $this->requireSiswa();

        $user = auth()->user();
        $nisn = $user->nisn ?? $user->username;
        $siswa = model(\App\Models\Siswa\SiswaModel::class)
            ->where('nisn', $nisn)
            ->first();

        if ($siswa) {
            $this->notifService->markAsRead((int) $siswa['id']);
        }

        return $this->response->setJSON(['status' => 'success']);
    }
}
