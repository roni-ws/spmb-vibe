<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKelengkapanAdministrasi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'siswa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'fc_ijazah_skl' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'fc_kk' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'status_verifikasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Belum Verifikasi',
            ],
            'is_finalized' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'catatan_verifikasi' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'created_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('siswa_id', true);
        $this->forge->addForeignKey('siswa_id', 'calon_siswa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kelengkapan_administrasi');
    }

    public function down()
    {
        $this->forge->dropTable('kelengkapan_administrasi');
    }
}
