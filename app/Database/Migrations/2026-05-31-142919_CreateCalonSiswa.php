<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCalonSiswa extends Migration
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
            'nisn' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'unique'     => true,
            ],
            'nik' => [
                'type'       => 'VARCHAR',
                'constraint' => '16',
                'unique'     => true,
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'no_kk' => [
                'type'       => 'VARCHAR',
                'constraint' => '16',
            ],
            'tempat_lahir' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'tanggal_lahir' => [
                'type' => 'DATE',
            ],
            'jenis_kelamin' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
            ],
            'sekolah_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'anak_ke' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'jumlah_saudara' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'alamat_desa' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'alamat_kecamatan' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'alamat_kabupaten' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('sekolah_id', 'master_sekolah', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('calon_siswa');
    }

    public function down()
    {
        // Drop foreign keys first if RDBMS is strict, or dropTable takes care of it depending on order.
        $this->forge->dropTable('calon_siswa');
    }
}
