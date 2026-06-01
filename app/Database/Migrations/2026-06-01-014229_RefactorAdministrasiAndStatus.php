<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RefactorAdministrasiAndStatus extends Migration
{
    public function up()
    {
        // 1. Buat tabel status_pendaftaran
        $this->forge->addField([
            'siswa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        $this->forge->createTable('status_pendaftaran');

        // 2. Pindahkan data jika ada
        $this->db->query('
            INSERT INTO status_pendaftaran (siswa_id, status_verifikasi, is_finalized, catatan_verifikasi, created_at, updated_at)
            SELECT siswa_id, status_verifikasi, is_finalized, catatan_verifikasi, NOW(), NOW()
            FROM kelengkapan_administrasi
        ');

        // 3. Hapus kolom lama dari kelengkapan_administrasi
        $this->forge->dropColumn('kelengkapan_administrasi', ['status_verifikasi', 'is_finalized', 'catatan_verifikasi']);

        // 4. Tambahkan kolom pas_foto
        $this->forge->addColumn('kelengkapan_administrasi', [
            'pas_foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        // 1. Hapus kolom pas_foto
        $this->forge->dropColumn('kelengkapan_administrasi', 'pas_foto');

        // 2. Tambahkan kembali kolom lama
        $this->forge->addColumn('kelengkapan_administrasi', [
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
        ]);

        // 3. Kembalikan data
        $this->db->query('
            UPDATE kelengkapan_administrasi ka
            JOIN status_pendaftaran sp ON ka.siswa_id = sp.siswa_id
            SET ka.status_verifikasi = sp.status_verifikasi,
                ka.is_finalized = sp.is_finalized,
                ka.catatan_verifikasi = sp.catatan_verifikasi
        ');

        // 4. Hapus tabel status_pendaftaran
        $this->forge->dropTable('status_pendaftaran');
    }
}
