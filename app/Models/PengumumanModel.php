<?php

namespace App\Models;

use CodeIgniter\Model;

class PengumumanModel extends Model
{
    protected $table            = 'pengumuman';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\Pengumuman::class;
    protected $protectFields    = true;
    protected $allowedFields    = ['judul', 'konten'];

    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'judul'  => 'required|min_length[3]|max_length[255]',
        'konten' => 'required',
    ];
}
