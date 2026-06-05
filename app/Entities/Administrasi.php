<?php

namespace App\Entities;

use App\Entities\Entity;

class Administrasi extends Entity
{
    protected $casts = [
        'siswa_id'   => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function hasAllFiles(): bool
    {
        return !empty($this->fc_ijazah_skl) && !empty($this->fc_kk) && !empty($this->pas_foto);
    }
}
