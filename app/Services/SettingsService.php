<?php

namespace App\Services;

use App\Models\PengumumanModel;
use App\Models\SettingsModel;
use App\Services\SiswaNotificationService;
use CodeIgniter\HTTP\Files\UploadedFile;

class SettingsService
{
    private const ALLOWED_KOP_TAGS = ['p','br','strong','em','u','h1','h2','h3','h4','span','div','ul','ol','li'];

    protected PengumumanModel $pengumumanModel;

    public function __construct(?PengumumanModel $pengumumanModel = null)
    {
        $this->pengumumanModel = $pengumumanModel ?? model(PengumumanModel::class);
    }

    public function get(string $key, $default = null): mixed
    {
        return SettingsModel::getSetting($key, $default);
    }

    public function update(array $data): void
    {
        foreach ($data as $key => $value) {
            SettingsModel::setSetting($key, $value);
        }
    }

    public function uploadImage(UploadedFile $file, string $settingKey, string $prefix): string
    {
        $uploadsDir = ROOTPATH . 'public/uploads/';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }

        $oldFile = SettingsModel::getSetting($settingKey);
        if ($oldFile && file_exists(ROOTPATH . 'public/' . $oldFile)) {
            @unlink(ROOTPATH . 'public/' . $oldFile);
        }

        $newName = $prefix . time() . '.' . $file->guessExtension();
        $file->move($uploadsDir, $newName);

        $path = 'uploads/' . $newName;
        SettingsModel::setSetting($settingKey, $path);

        return $path;
    }

    public function sanitizeKopContent(?string $content): string
    {
        if ($content === null || $content === '') {
            return '';
        }

        $content = preg_replace_callback('/<(\/?)(\w+)([^>]*)>/', function ($m) {
            if (!in_array(strtolower($m[2]), self::ALLOWED_KOP_TAGS)) {
                return $m[1] === '/' ? '' : '';
            }
            return $m[0];
        }, $content);
        $content = preg_replace('/\s+on\w+\s*=\s*"[^"]*"/i', '', $content);
        $content = preg_replace('/\s+on\w+\s*=\s*\'[^\']*\'/i', '', $content);
        $content = preg_replace('/href\s*=\s*"(?!https?:\/\/|#|mailto:|\/)[^"]*"/i', 'href="#"', $content);

        return $content;
    }

    public function addAnnouncement(string $judul, string $konten): int
    {
        $this->pengumumanModel->insert([
            'judul'  => trim($judul),
            'konten' => trim($konten),
        ]);

        $notifService = new SiswaNotificationService();
        $notifService->broadcast(NOTIF_SISWA_PENGUMUMAN,
            'Pengumuman Baru: ' . trim($judul),
            trim($konten)
        );

        return (int) $this->pengumumanModel->getInsertID();
    }

    public function deleteAnnouncement(int $id): void
    {
        $this->pengumumanModel->delete($id);
    }

    public function getAnnouncement(int $id): ?object
    {
        return $this->pengumumanModel->find($id);
    }

    public function getAllAnnouncements(): array
    {
        return $this->pengumumanModel->orderBy('created_at', 'DESC')->findAll();
    }
}
