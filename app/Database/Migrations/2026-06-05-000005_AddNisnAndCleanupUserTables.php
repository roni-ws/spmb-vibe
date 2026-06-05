<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNisnAndCleanupUserTables extends Migration
{
    public function up()
    {
        // 1. Tambah kolom nisn ke users_siswa
        $this->forge->addColumn('users_siswa', [
            'nisn' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
                'after'      => 'username',
            ],
        ]);

        // 2. Tambah kolom nisn ke users
        $this->forge->addColumn('users', [
            'nisn' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'null'       => true,
                'after'      => 'username',
            ],
        ]);

        // 3. Isi nisn = username untuk semua record existing
        $this->db->table('users_siswa')->update(['nisn' => new \CodeIgniter\Database\RawSql('username')]);
        $this->db->table('users')->update(['nisn' => new \CodeIgniter\Database\RawSql('username')]);

        // 4. Pindah siswa dari tabel 'users' ke 'users_siswa'
        //    ID tetap sama karena tidak ada konflik (old students di
        //    users_siswa punya ID 1000000+ sedangkan new students di
        //    users punya ID kecil)
        $students = $this->db->table('users')
            ->where('role', 'student')
            ->get()
            ->getResultArray();

        foreach ($students as $student) {
            $oldId = (int) $student['id'];

            // Skip jika username sudah ada di users_siswa (duplikat)
            $existing = $this->db->table('users_siswa')
                ->where('username', $student['username'])
                ->countAllResults();

            if ($existing > 0) {
                $this->db->table('users')->where('id', $oldId)->delete();
                continue;
            }

            // Insert ke users_siswa dengan ID yang SAMA
            $this->db->table('users_siswa')->insert([
                'id'             => $oldId,
                'username'       => $student['username'],
                'nisn'           => $student['nisn'] ?? $student['username'],
                'status'         => $student['status'],
                'status_message' => $student['status_message'],
                'active'         => $student['active'],
                'last_active'    => $student['last_active'],
                'email'          => $student['email'],
                'role'           => $student['role'] ?? 'student',
                'created_at'     => $student['created_at'],
                'updated_at'     => $student['updated_at'],
                'deleted_at'     => $student['deleted_at'],
            ]);

            // Hapus dari users
            $this->db->table('users')->where('id', $oldId)->delete();
        }

        // 5. Hapus foreign keys yang指向 users (auth_* user_id sekarang
        //    bisa指向 users_siswa juga)
        $this->dropForeignKeys();

        // 6. Hapus foreign keys users_siswa dari Shield tables (jika ada)
        $this->dropSiswaForeignKeys();
    }

    public function down()
    {
        // Kembalikan siswa dari users_siswa ke users
        $siswaList = $this->db->table('users_siswa')
            ->where('role', 'student')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($siswaList as $siswa) {
            $oldId = (int) $siswa['id'];

            $exists = $this->db->table('users')
                ->where('id', $oldId)
                ->countAllResults();

            if ($exists > 0) {
                continue;
            }

            // Pindah balik ke users
            $insertData = [
                'username'       => $siswa['username'],
                'nisn'           => $siswa['nisn'],
                'status'         => $siswa['status'],
                'status_message' => $siswa['status_message'],
                'active'         => $siswa['active'],
                'last_active'    => $siswa['last_active'],
                'email'          => $siswa['email'],
                'role'           => $siswa['role'] ?? 'student',
                'created_at'     => $siswa['created_at'],
                'updated_at'     => $siswa['updated_at'],
                'deleted_at'     => $siswa['deleted_at'],
            ];
            $this->db->table('users')->insert($insertData);
            $newId = (int) $this->db->insertID();

            // Update auth_* untuk指向 ID baru
            $this->db->table('auth_identities')->where('user_id', $oldId)->update(['user_id' => $newId]);
            $this->db->table('auth_groups_users')->where('user_id', $oldId)->update(['user_id' => $newId]);
            $this->db->table('auth_remember_tokens')->where('user_id', $oldId)->update(['user_id' => $newId]);
            $this->db->table('auth_permissions_users')->where('user_id', $oldId)->update(['user_id' => $newId]);

            $this->db->table('users_siswa')->where('id', $oldId)->delete();
        }

        // Hapus kolom nisn
        $this->forge->dropColumn('users_siswa', 'nisn');
        $this->forge->dropColumn('users', 'nisn');
    }

    private function dropForeignKeys(): void
    {
        $tables = ['auth_identities', 'auth_groups_users', 'auth_remember_tokens', 'auth_permissions_users'];
        foreach ($tables as $table) {
            try {
                $this->db->query("ALTER TABLE {$table} DROP FOREIGN KEY {$table}_user_id_foreign");
            } catch (\Throwable $e) {
            }
        }
    }

    private function dropSiswaForeignKeys(): void
    {
        $tables = ['auth_identities', 'auth_groups_users', 'auth_remember_tokens', 'auth_permissions_users'];
        foreach ($tables as $table) {
            try {
                $this->db->query("ALTER TABLE {$table} DROP FOREIGN KEY {$table}_siswa_user_id_foreign");
            } catch (\Throwable $e) {
            }
        }
    }
}
