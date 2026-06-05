<?php

namespace App\Services;

use App\Models\Admin\AdminNotificationModel;
use App\Models\User\UserModel;

class AdminNotificationService
{
    protected AdminNotificationModel $notifModel;

    public function __construct(?AdminNotificationModel $notifModel = null)
    {
        $this->notifModel = $notifModel ?? model(AdminNotificationModel::class);
    }

    public function send(int $userId, string $type, string $title, ?string $konten = null): int
    {
        $this->notifModel->insert([
            'user_id'  => $userId,
            'type'     => $type,
            'title'    => $title,
            'konten'   => $konten,
            'is_read'  => 0,
        ]);

        return (int) $this->notifModel->getInsertID();
    }

    public function broadcast(string $type, string $title, ?string $konten = null): void
    {
        $allAdmin = model(UserModel::class)
            ->whereIn('role', [ROLE_ADMIN, ROLE_PANITIA])
            ->findAll();

        foreach ($allAdmin as $admin) {
            $this->send((int) $admin->id, $type, $title, $konten);
        }
    }

    public function getUnreadCount(int $userId): int
    {
        return $this->notifModel
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->countAllResults();
    }

    public function getRecent(int $userId, int $limit = 5): array
    {
        return $this->notifModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function markAsRead(int $userId): void
    {
        $this->notifModel
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->set(['is_read' => 1])
            ->update();
    }
}
