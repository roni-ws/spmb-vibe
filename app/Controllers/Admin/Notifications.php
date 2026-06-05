<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\AdminNotificationService;
use CodeIgniter\HTTP\ResponseInterface;

class Notifications extends BaseController
{
    protected AdminNotificationService $notifService;

    public function __construct()
    {
        $this->notifService = new AdminNotificationService();
    }

    public function index(): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);

        $shieldUser = auth()->user();
        $userId = (int) $shieldUser->id;
        $unread = $this->notifService->getUnreadCount($userId);
        $notifications = $this->notifService->getRecent($userId, NOTIF_DROPDOWN_LIMIT);

        return $this->response->setJSON([
            'unread'        => $unread,
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);

        $shieldUser = auth()->user();
        $this->notifService->markAsRead((int) $shieldUser->id);

        return $this->response->setJSON(['status' => 'success']);
    }
}
