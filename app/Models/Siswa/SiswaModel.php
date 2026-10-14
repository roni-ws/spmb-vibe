<?php

namespace App\Models\Siswa;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table            = 'calon_siswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $returnType       = \App\Entities\CalonSiswa::class;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'nisn',
        'no_registrasi',
        'nik',
        'nama_lengkap',
        'no_kk',
        'no_hp',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'sekolah_id',
        'anak_ke',
        'jumlah_saudara',
        'alamat_desa',
        'alamat_kecamatan',
        'alamat_kabupaten',
        'alamat_sekolah',
    ];

    protected $validationRules = [
        'nisn'             => 'permit_empty|max_length[10]|is_unique[calon_siswa.nisn]',
        'nik'              => 'permit_empty|max_length[16]',
        'no_kk'            => 'permit_empty|max_length[16]',
        'nama_lengkap'     => 'required|min_length[3]|max_length[150]',
        'tempat_lahir'     => 'permit_empty|max_length[100]',
        'tanggal_lahir'    => 'required|valid_date[Y-m-d]',
        'jenis_kelamin'    => 'required|in_list[L,P]',
        'anak_ke'          => 'required|integer|greater_than_equal_to[1]',
        'jumlah_saudara'   => 'required|integer|greater_than_equal_to[0]',
        'no_hp'            => 'permit_empty|min_length[10]|max_length[15]',
        'alamat_desa'      => 'permit_empty|max_length[100]',
        'alamat_kecamatan' => 'permit_empty|max_length[100]',
        'alamat_kabupaten' => 'permit_empty|max_length[100]',
    ];
}
