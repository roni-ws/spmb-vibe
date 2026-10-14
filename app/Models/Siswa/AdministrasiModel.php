<?php

namespace App\Models\Siswa;

use CodeIgniter\Model;

class AdministrasiModel extends Model
{
    protected $table            = 'kelengkapan_administrasi';
    protected $primaryKey       = 'siswa_id';
    protected $useAutoIncrement = false;
    protected $returnType       = \App\Entities\Administrasi::class;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'siswa_id',
        'fc_ijazah_skl',
        'fc_kk',
        'pas_foto',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'siswa_id'     => 'required|is_natural_no_zero',
        'fc_ijazah_skl' => 'permit_empty|max_length[255]',
        'fc_kk'         => 'permit_empty|max_length[255]',
        'pas_foto'      => 'permit_empty|max_length[255]',
    ];
}
