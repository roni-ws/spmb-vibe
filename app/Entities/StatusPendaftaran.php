<?php

namespace App\Entities;

use App\Entities\Entity;

class StatusPendaftaran extends Entity
{
    protected $casts = [
        'siswa_id'            => 'integer',
        'status_verifikasi_id' => 'integer',
        'is_finalized'        => 'boolean',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
    ];

    public function getIsFinalizedLabel(): string
    {
        return $this->is_finalized ? 'Ya' : 'Tidak';
    }
}
