<?php

namespace App\Models\Siswa;

use CodeIgniter\Model;

class MasterSekolahModel extends Model
{
    protected $table            = 'master_sekolah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\MasterSekolah::class;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_sekolah',
    ];

    protected $validationRules      = [
        'nama_sekolah'   => 'required|min_length[3]|max_length[150]',
    ];

    public function getOrCreateSekolahId(string $namaSekolah): int
    {
        $namaSekolahClean = trim($namaSekolah);
        $sekolah = $this->where('LOWER(nama_sekolah)', strtolower($namaSekolahClean))->first();

        if ($sekolah) {
            return (int) $sekolah['id'];
        }

        $this->insert([
            'nama_sekolah'   => $namaSekolahClean,
        ]);

        return (int) $this->getInsertID();
    }
}
