<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDataOrangTua extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'siswa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nama_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'pekerjaan_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'nama_ibu' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'pekerjaan_ibu' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
        ]);
        $this->forge->addKey('siswa_id', true);
        $this->forge->addForeignKey('siswa_id', 'calon_siswa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('data_orang_tua');
    }

    public function down()
    {
        $this->forge->dropTable('data_orang_tua');
    }
}
