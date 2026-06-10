<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterNikNullable extends Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE `calon_siswa` MODIFY `nik` VARCHAR(16) NULL;');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `calon_siswa` MODIFY `nik` VARCHAR(16) NOT NULL;');
    }
}
