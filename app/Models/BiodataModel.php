<?php

namespace App\Models;

use CodeIgniter\Model;

class BiodataModel extends Model
{
    protected $table            = 'biodata';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 
        'nama_lengkap', 
        'nik', 
        'tempat_lahir', 
        'tanggal_lahir', 
        'jenis_kelamin', 
        'no_hp', 
        'nama_ayah', 
        'pekerjaan_ayah', 
        'nama_ibu', 
        'pekerjaan_ibu', 
        'alamat'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
