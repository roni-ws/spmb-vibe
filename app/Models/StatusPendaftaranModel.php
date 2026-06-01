<?php

namespace App\Models;

use CodeIgniter\Model;

class StatusPendaftaranModel extends Model
{
    protected $table            = 'status_pendaftaran';
    protected $primaryKey       = 'siswa_id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'siswa_id',
        'status_verifikasi',
        'is_finalized',
        'catatan_verifikasi',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
