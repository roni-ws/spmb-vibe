<?php

namespace App\Entities;

use App\Entities\Entity;

class CalonSiswa extends Entity
{
    protected $casts = [
        'id'             => 'integer',
        'sekolah_id'     => 'integer',
        'anak_ke'        => 'integer',
        'jumlah_saudara' => 'integer',
        'tanggal_lahir'  => 'datetime',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    public function getJenisKelaminLabel(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getUmur(): int
    {
        if (!$this->tanggal_lahir) {
            return 0;
        }
        $now = new \DateTime();
        $tgl = $this->tanggal_lahir instanceof \DateTime ? $this->tanggal_lahir : new \DateTime($this->tanggal_lahir);
        return (int) $now->diff($tgl)->y;
    }

    public function getTanggalLahirFormatted(string $format = 'd F Y'): string
    {
        if (!$this->tanggal_lahir) {
            return '-';
        }
        $tgl = $this->tanggal_lahir instanceof \DateTime ? $this->tanggal_lahir : new \DateTime($this->tanggal_lahir);
        return $tgl->format($format);
    }
}
