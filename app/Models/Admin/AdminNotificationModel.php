<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class AdminNotificationModel extends Model
{
    protected $table            = 'admin_notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'type', 'title', 'konten', 'is_read'];

    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}
