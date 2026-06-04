<?php

namespace App\Models;

use CodeIgniter\Model;

class OrangTuaModel extends Model
{
    protected $table            = 'data_orang_tua';
    protected $primaryKey       = 'siswa_id';
    protected $useAutoIncrement = false;
    protected $useTimestamps    = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'siswa_id',
        'nama_ayah',
        'pekerjaan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
    ];
}
