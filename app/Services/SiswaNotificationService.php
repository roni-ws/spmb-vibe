<?php

namespace App\Services;

use App\Models\Siswa\SiswaModel;
use App\Models\Siswa\SiswaNotificationModel;

class SiswaNotificationService
{
    protected SiswaNotificationModel $notifModel;

    public function __construct(?SiswaNotificationModel $notifModel = null)
    {
        $this->notifModel = $notifModel ?? model(SiswaNotificationModel::class);
    }

    public function send(int $siswaId, string $type, string $title, ?string $konten = null): int
    {
        $this->notifModel->insert([
            'siswa_id' => $siswaId,
            'type'     => $type,
            'title'    => $title,
            'konten'   => $konten,
            'is_read'  => 0,
        ]);

        return (int) $this->notifModel->getInsertID();
    }

    public function broadcast(string $type, string $title, ?string $konten = null): void
    {
        $allSiswa = model(SiswaModel::class)->select('id')->findAll();

        foreach ($allSiswa as $siswa) {
            $this->send((int) $siswa['id'], $type, $title, $konten);
        }
    }

    public function getUnreadCount(int $siswaId): int
    {
        return $this->notifModel
            ->where('siswa_id', $siswaId)
            ->where('is_read', 0)
            ->countAllResults();
    }

    public function getRecent(int $siswaId, int $limit = 5): array
    {
        return $this->notifModel
            ->where('siswa_id', $siswaId)
            ->orderBy('created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function markAsRead(int $siswaId): void
    {
        $this->notifModel
            ->where('siswa_id', $siswaId)
            ->where('is_read', 0)
            ->set(['is_read' => 1])
            ->update();
    }
}
