<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStatusPendaftaran extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'siswa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'status_verifikasi_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 1,
            ],
            'is_finalized' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'catatan_verifikasi' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addForeignKey('status_verifikasi_id', 'status_verifikasi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('status_pendaftaran');
    }

    public function down()
    {
        $this->forge->dropTable('status_pendaftaran', true);
    }
}
