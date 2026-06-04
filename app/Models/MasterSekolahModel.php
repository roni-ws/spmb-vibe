<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterSekolahModel extends Model
{
    protected $table            = 'master_sekolah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_sekolah',
        'alamat_sekolah',
    ];

    public function getOrCreateSekolahId(string $namaSekolah, ?string $alamatSekolah = null): int
    {
        $namaSekolahClean = trim($namaSekolah);

        $sekolah = $this->where('LOWER(nama_sekolah)', strtolower($namaSekolahClean))->first();

        if ($sekolah) {
            return (int) $sekolah['id'];
        }

        $this->insert([
            'nama_sekolah'   => $namaSekolahClean,
            'alamat_sekolah' => $alamatSekolah ? trim($alamatSekolah) : null
        ]);

        return (int) $this->getInsertID();
    }
}
