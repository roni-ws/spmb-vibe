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
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'fc_kk' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'pas_foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('siswa_id', true);
        $this->forge->addForeignKey('siswa_id', 'calon_siswa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kelengkapan_administrasi');
    }

    public function down()
    {
        $this->forge->dropTable('kelengkapan_administrasi', true);
    }
}
