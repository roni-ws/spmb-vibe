<?php

namespace App\Entities;

use App\Entities\Entity;

class Pengumuman extends Entity
{
    protected $casts = [
        'id'         => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getTanggalFormatted(string $format = 'd F Y'): string
    {
        if (!$this->created_at) {
            return '-';
        }
        $tgl = $this->created_at instanceof \DateTime ? $this->created_at : new \DateTime($this->created_at);
        return $tgl->format($format);
    }
}
