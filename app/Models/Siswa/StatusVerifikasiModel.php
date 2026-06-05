<?php

namespace App\Models\Siswa;

use CodeIgniter\Model;

class StatusVerifikasiModel extends Model
{
    protected $table            = 'status_verifikasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_status'];

    protected $validationRules = [
        'nama_status' => 'required|max_length[50]',
    ];
}
