<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table            = 'calon_siswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nisn',
        'nik',
        'nama_lengkap',
        'no_kk',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'sekolah_id',
        'anak_ke',
        'jumlah_saudara',
        'alamat_desa',
        'alamat_kecamatan',
        'alamat_kabupaten',
    ];
}
