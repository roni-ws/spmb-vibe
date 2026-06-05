<?php

namespace App\Models\Siswa;

use CodeIgniter\Model;

class StatusPendaftaranModel extends Model
{
    protected $table            = 'status_pendaftaran';
    protected $primaryKey       = 'siswa_id';
    protected $useAutoIncrement = false;
    protected $returnType       = \App\Entities\StatusPendaftaran::class;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'siswa_id',
        'status_verifikasi_id',
        'is_finalized',
        'catatan_verifikasi',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'siswa_id'              => 'required|is_natural_no_zero',
        'status_verifikasi_id'  => 'permit_empty|is_natural_no_zero',
        'is_finalized'          => 'permit_empty|integer|in_list[0,1]',
        'catatan_verifikasi'    => 'permit_empty|max_length[1000]',
    ];

    public function getWithVerifikasiBySiswaId(int $siswaId): ?array
    {
        return $this->builder()
            ->select('status_pendaftaran.*, status_verifikasi.nama_status as status_verifikasi')
            ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left')
            ->where('status_pendaftaran.siswa_id', $siswaId)
            ->get()
            ->getRowArray() ?: null;
    }
}
