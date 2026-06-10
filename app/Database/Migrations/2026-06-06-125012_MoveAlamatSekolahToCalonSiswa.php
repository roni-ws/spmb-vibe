<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MoveAlamatSekolahToCalonSiswa extends Migration
{
    public function up()
    {
        // 1. Add alamat_sekolah to calon_siswa
        $this->forge->addColumn('calon_siswa', [
            'alamat_sekolah' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        // 2. Migrate existing data
        $this->db->query("UPDATE calon_siswa cs JOIN master_sekolah ms ON cs.sekolah_id = ms.id SET cs.alamat_sekolah = ms.alamat_sekolah");

        // 3. Drop alamat_sekolah from master_sekolah
        $this->forge->dropColumn('master_sekolah', 'alamat_sekolah');
    }

    public function down()
    {
        // 1. Re-add alamat_sekolah to master_sekolah
        $this->forge->addColumn('master_sekolah', [
            'alamat_sekolah' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        // 2. We cannot easily migrate data back perfectly because multiple students could have different addresses
        // for the same school ID now, but we can try to restore the first one we find.
        $this->db->query("UPDATE master_sekolah ms JOIN calon_siswa cs ON ms.id = cs.sekolah_id SET ms.alamat_sekolah = cs.alamat_sekolah");

        // 3. Drop alamat_sekolah from calon_siswa
        $this->forge->dropColumn('calon_siswa', 'alamat_sekolah');
    }
}
