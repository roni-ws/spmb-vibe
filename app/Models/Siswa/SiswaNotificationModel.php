<?php

namespace App\Models\Siswa;

use CodeIgniter\Model;

class SiswaNotificationModel extends Model
{
    protected $table            = 'siswa_notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['siswa_id', 'type', 'title', 'konten', 'is_read'];

    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}
