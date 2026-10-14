<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterNisnNullable extends Migration
{
    public function up()
    {
        $this->db->query('ALTER TABLE `calon_siswa` MODIFY `nisn` VARCHAR(10) NULL;');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `calon_siswa` MODIFY `nisn` VARCHAR(10) NOT NULL;');
    }
}
