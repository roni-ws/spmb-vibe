<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTimestampsToExistingTables extends Migration
{
    public function up()
    {
        $timestampCols = [
            'created_at' => 'DATETIME NULL DEFAULT NULL',
            'updated_at' => 'DATETIME NULL DEFAULT NULL',
        ];

        $tables = [
            'calon_siswa'              => ['created_at', 'updated_at'],
            'data_orang_tua'           => ['created_at', 'updated_at'],
            'status_pendaftaran'       => ['created_at', 'updated_at'],
            'kelengkapan_administrasi' => ['created_at', 'updated_at'],
            'pengumuman'               => ['created_at', 'updated_at'],
        ];

        foreach ($tables as $table => $neededCols) {
            $existingCols = [];
            try {
                $fields = $this->db->getFieldData($table);
                $existingCols = array_map(fn($f) => $f->name, $fields);
            } catch (\Throwable $e) {
                echo "  Skipping {$table} (not found): {$e->getMessage()}\n";
                continue;
            }

            foreach ($neededCols as $col) {
                if (!in_array($col, $existingCols, true)) {
                    $def = $timestampCols[$col];
                    $this->db->query("ALTER TABLE `{$table}` ADD `{$col}` {$def}");
                    echo "  Added `{$col}` to `{$table}`\n";
                }
            }
        }
    }

    public function down()
    {
        $tables = [
            'calon_siswa',
            'data_orang_tua',
            'status_pendaftaran',
            'kelengkapan_administrasi',
            'pengumuman',
        ];

        foreach ($tables as $table) {
            try {
                $fields = $this->db->getFieldData($table);
                $colNames = array_map(fn($f) => $f->name, $fields);

                if (in_array('created_at', $colNames, true)) {
                    $this->db->query("ALTER TABLE `{$table}` DROP `created_at`");
                }
                if (in_array('updated_at', $colNames, true)) {
                    $this->db->query("ALTER TABLE `{$table}` DROP `updated_at`");
                }
            } catch (\Throwable $e) {
                continue;
            }
        }
    }
}
