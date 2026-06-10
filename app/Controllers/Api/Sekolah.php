<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Siswa\MasterSekolahModel;

class Sekolah extends ResourceController
{
    public function search()
    {
        $query = $this->request->getGet('q');
        
        if (empty($query) || strlen($query) < 2) {
            return $this->respond([]);
        }

        $model = new MasterSekolahModel();
        
        // Cari kemiripan nama sekolah, urutkan berdasarkan nama
        $sekolah = $model->like('LOWER(nama_sekolah)', strtolower($query))
                         ->where('nama_sekolah !=', DEFAULT_SEKOLAH)
                         ->orderBy('nama_sekolah', 'ASC')
                         ->limit(20)
                         ->find();

        $results = [];
        foreach ($sekolah as $s) {
            $results[] = [
                'text'  => $s['nama_sekolah'],
                'value' => $s['nama_sekolah'] // kita menyimpan namanya langsung, bukan ID-nya
            ];
        }

        return $this->respond($results);
    }
}
