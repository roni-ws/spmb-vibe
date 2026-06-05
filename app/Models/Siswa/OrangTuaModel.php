<?php

namespace App\Models\Siswa;

use CodeIgniter\Model;

class OrangTuaModel extends Model
{
    protected $table            = 'data_orang_tua';
    protected $primaryKey       = 'siswa_id';
    protected $useAutoIncrement = false;
    protected $useTimestamps    = true;
    protected $returnType       = \App\Entities\OrangTua::class;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'siswa_id',
        'nama_ayah',
        'pekerjaan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
    ];

    protected $validationRules = [
        'nama_ayah'      => 'required|min_length[3]|max_length[150]',
        'pekerjaan_ayah' => 'required|max_length[100]',
        'nama_ibu'       => 'required|min_length[3]|max_length[150]',
        'pekerjaan_ibu'  => 'required|max_length[100]',
    ];
}
