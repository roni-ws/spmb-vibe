<?php

namespace App\Entities;

use App\Entities\Entity;

class OrangTua extends Entity
{
    protected $casts = [
        'siswa_id'   => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
