<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToCalonSiswa extends Migration
{
    public function up()
    {
        $this->forge->addColumn('calon_siswa', [
            'user_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);

        $calonSiswa = $this->db->table('calon_siswa')
            ->select('id')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        $usersSiswa = $this->db->table('users_siswa')
            ->select('id')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($calonSiswa as $i => $cs) {
            if (isset($usersSiswa[$i])) {
                $this->db->table('calon_siswa')
                    ->where('id', $cs['id'])
                    ->update(['user_id' => $usersSiswa[$i]['id']]);
            }
        }

        $this->db->query('ALTER TABLE `calon_siswa` ADD CONSTRAINT `calon_siswa_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users_siswa`(`id`) ON DELETE CASCADE ON UPDATE SET NULL');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `calon_siswa` DROP FOREIGN KEY `calon_siswa_user_id_foreign`');
        $this->forge->dropColumn('calon_siswa', 'user_id');
    }
}
