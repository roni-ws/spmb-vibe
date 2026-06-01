<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMasterSekolah extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_sekolah' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'alamat_sekolah' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('master_sekolah');
    }

    public function down()
    {
        $this->forge->dropTable('master_sekolah');
    }
}
