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
}
