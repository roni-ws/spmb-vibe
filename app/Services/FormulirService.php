<?php

namespace App\Services;

use App\Models\Siswa\AdministrasiModel;
use App\Models\Siswa\MasterSekolahModel;
use App\Models\Siswa\OrangTuaModel;
use App\Models\Siswa\SiswaModel;
use CodeIgniter\Database\BaseConnection;
use Config\Database;

class FormulirService
{
    protected BaseConnection $db;
    protected SiswaModel $siswaModel;
    protected MasterSekolahModel $sekolahModel;
    protected OrangTuaModel $orangTuaModel;
    protected AdministrasiModel $administrasiModel;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db                = $db ?? Database::connect();
        $this->siswaModel        = model(SiswaModel::class);
        $this->sekolahModel      = model(MasterSekolahModel::class);
        $this->orangTuaModel     = model(OrangTuaModel::class);
        $this->administrasiModel = model(AdministrasiModel::class);
    }

    public function updateBiodata(int $siswaId, array $data): void
    {
        $this->siswaModel->update($siswaId, $data);

        if (!empty($data['nisn'])) {
            $siswa = $this->siswaModel->find($siswaId);
            if ($siswa && $siswa['user_id']) {
                $userModel = model(\App\Models\User\SiswaUserModel::class);
                $user = $userModel->find($siswa['user_id']);
                if ($user) {
                    $user->nisn = $data['nisn'];
                    $userModel->save($user);
                }
            }
        }
    }

    public function updateSekolah(int $siswaId, string $namaSekolah, string $alamatSekolah): void
    {
        $namaSekolah   = trim($namaSekolah);
        $alamatSekolah = trim($alamatSekolah);

        $this->db->transBegin();

        $existing = $this->sekolahModel->where('LOWER(nama_sekolah)', strtolower($namaSekolah))->first();

        if ($existing) {
            $sekolahId = $existing['id'];
        } else {
            $this->sekolahModel->insert([
                'nama_sekolah'   => $namaSekolah,
            ]);
            $sekolahId = $this->sekolahModel->getInsertID();
        }

        $this->siswaModel->update($siswaId, [
            'sekolah_id'     => $sekolahId,
            'alamat_sekolah' => $alamatSekolah,
        ]);

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            throw new \RuntimeException('Gagal menyimpan data sekolah. Silakan coba lagi.');
        }

        $this->db->transCommit();
    }

    public function updateOrangTua(int $siswaId, array $data): void
    {
        $orangTua = $this->orangTuaModel->where('siswa_id', $siswaId)->first();

        if ($orangTua) {
            $this->orangTuaModel->update($siswaId, $data);
        } else {
            $data['siswa_id'] = $siswaId;
            $this->orangTuaModel->insert($data);
        }
    }

    public function updateBerkas(int $siswaId, array $files): void
    {
        $uploadService = new FileUploadService();
        $berkas = $this->administrasiModel->where('siswa_id', $siswaId)->first();
        $updateData = [];

        $berkasFields = [
            'fc_ijazah_skl' => 'ijazah',
            'fc_kk'         => 'kk',
            'pas_foto'      => 'pas_foto',
        ];

        foreach ($berkasFields as $field => $prefix) {
            $file = $files[$field] ?? null;
            if ($file && $file->isValid()) {
                $newName = $uploadService->uploadBerkas($file, $siswaId, $prefix);
                if ($newName) {
                    $updateData[$field] = $newName;
                }
            }
        }

        if (!empty($updateData)) {
            if ($berkas) {
                $this->administrasiModel->update($siswaId, $updateData);
            } else {
                $updateData['siswa_id'] = $siswaId;
                $this->administrasiModel->insert($updateData);
            }
        }
    }

    public function getBerkasRules(array $files, ?array $existingBerkas): array
    {
        $berkasFields = [
            'fc_ijazah_skl' => 'ijazah',
            'fc_kk'         => 'kk',
            'pas_foto'      => 'pas_foto',
        ];

        $rules = [];
        foreach ($berkasFields as $field => $prefix) {
            $isNewUpload = isset($files[$field]) && $files[$field]->isValid();
            $hasExisting = $existingBerkas && !empty($existingBerkas[$field]);
            $requiredRule = ($isNewUpload || !$hasExisting) ? 'uploaded[' . $field . ']|' : '';
            $rules[$field] = $requiredRule . 'max_size[' . $field . ',2048]|ext_in[' . $field . ',pdf,jpg,jpeg,png]|mime_in[' . $field . ',application/pdf,image/jpg,image/jpeg,image/png]';
        }

        return $rules;
    }

    public function getBerkasMessages(): array
    {
        $baseMsgs = [
            'fc_ijazah_skl' => 'Ijazah / SKL',
            'fc_kk'         => 'Kartu Keluarga',
            'pas_foto'      => 'Pas Foto',
        ];

        $messages = [];
        foreach ($baseMsgs as $field => $label) {
            $messages[$field] = [
                'uploaded' => "File scan {$label} wajib diunggah.",
                'max_size' => "Ukuran file {$label} maksimal adalah 2MB.",
                'ext_in'   => "Format file {$label} harus berupa PDF, JPG, JPEG, atau PNG.",
                'mime_in'  => "Format file {$label} harus berupa PDF, JPG, JPEG, atau PNG.",
            ];
        }

        return $messages;
    }
}
