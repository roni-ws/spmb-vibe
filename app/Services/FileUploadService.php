<?php

namespace App\Services;

use CodeIgniter\HTTP\Files\UploadedFile;

class FileUploadService
{
    protected string $basePath;

    public function __construct()
    {
        $this->basePath = FCPATH . 'uploads/';
    }

    public function ensureUploadDir(string $subdir = ''): string
    {
        $path = $this->basePath . $subdir;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return $path;
    }

    public function deleteOldFiles(string $pattern): void
    {
        $files = glob($pattern);
        foreach ($files as $file) {
            if (file_exists($file)) {
                @unlink($file);
            }
        }
    }

    public function uploadBerkas(UploadedFile $file, int $siswaId, string $prefix): ?string
    {
        if (!$file->isValid() || $file->hasMoved()) {
            return null;
        }

        $uploadPath = $this->ensureUploadDir('berkas');

        $this->deleteOldFiles($uploadPath . $prefix . '_' . $siswaId . '_*.*');

        $newName = $prefix . '_' . $siswaId . '_' . time() . '.' . $file->guessExtension();
        $file->move($uploadPath, $newName);

        return $newName;
    }
}
