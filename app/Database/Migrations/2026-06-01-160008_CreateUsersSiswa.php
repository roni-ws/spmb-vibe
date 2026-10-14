<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersSiswa extends Migration
{
    public function up()
    {
        // 1. Create users_siswa table
        $this->forge->addField([
            'id'             => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'username'       => ['type' => 'varchar', 'constraint' => 30, 'null' => true],
            'status'         => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'status_message' => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'active'         => ['type' => 'tinyint', 'constraint' => 1, 'null' => 0, 'default' => 0],
            'last_active'    => ['type' => 'datetime', 'null' => true],
            'email'          => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'role'           => ['type' => 'varchar', 'constraint' => 50, 'null' => true, 'default' => 'student'],
            'created_at'     => ['type' => 'datetime', 'null' => true],
            'updated_at'     => ['type' => 'datetime', 'null' => true],
            'deleted_at'     => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('username');
        
        $attributes = ($this->db->getPlatform() === 'MySQLi') ? ['ENGINE' => 'InnoDB'] : [];
        $this->forge->createTable('users_siswa', false, $attributes);

        // 2. Set auto increment to start from 1000000
        $this->db->query("ALTER TABLE users_siswa AUTO_INCREMENT = 1000000");

        // 3. Drop foreign keys pointing to users from Shield tables
        try {
            $this->db->query("ALTER TABLE auth_identities DROP FOREIGN KEY auth_identities_user_id_foreign");
        } catch (\Throwable $e) {}

        try {
            $this->db->query("ALTER TABLE auth_groups_users DROP FOREIGN KEY auth_groups_users_user_id_foreign");
        } catch (\Throwable $e) {}

        try {
            $this->db->query("ALTER TABLE auth_remember_tokens DROP FOREIGN KEY auth_remember_tokens_user_id_foreign");
        } catch (\Throwable $e) {}

        try {
            $this->db->query("ALTER TABLE auth_permissions_users DROP FOREIGN KEY auth_permissions_users_user_id_foreign");
        } catch (\Throwable $e) {}

        // 4. Migrate existing student data from `users` to `users_siswa`
        $students = $this->db->table('users')->where('role', 'student')->get()->getResultArray();
        
        foreach ($students as $student) {
            $oldId = $student['id'];
            $newId = 1000000 + $oldId;
            
            // Insert into users_siswa with the new ID
            $studentData = $student;
            $studentData['id'] = $newId;
            $this->db->table('users_siswa')->insert($studentData);
            
            // Update all references in Shield tables
            $this->db->table('auth_identities')->where('user_id', $oldId)->update(['user_id' => $newId]);
            $this->db->table('auth_groups_users')->where('user_id', $oldId)->update(['user_id' => $newId]);
            $this->db->table('auth_remember_tokens')->where('user_id', $oldId)->update(['user_id' => $newId]);
            $this->db->table('auth_permissions_users')->where('user_id', $oldId)->update(['user_id' => $newId]);
            
            // Delete old student from users
            $this->db->table('users')->where('id', $oldId)->delete();
        }
    }

    public function down()
    {
        // 1. Move students back to `users` table
        $students = $this->db->table('users_siswa')->get()->getResultArray();
        
        foreach ($students as $student) {
            $newId = $student['id'];
            $oldId = $newId - 1000000;
            
            $studentData = $student;
            $studentData['id'] = $oldId;
            
            // Put it back in users
            $this->db->table('users')->insert($studentData);
            
            // Move references back
            $this->db->table('auth_identities')->where('user_id', $newId)->update(['user_id' => $oldId]);
            $this->db->table('auth_groups_users')->where('user_id', $newId)->update(['user_id' => $oldId]);
            $this->db->table('auth_remember_tokens')->where('user_id', $newId)->update(['user_id' => $oldId]);
            $this->db->table('auth_permissions_users')->where('user_id', $newId)->update(['user_id' => $oldId]);
            
            // Delete from users_siswa
            $this->db->table('users_siswa')->where('id', $newId)->delete();
        }

        // 2. Re-add foreign keys pointing to `users`
        try {
            $this->db->query("ALTER TABLE auth_identities ADD CONSTRAINT auth_identities_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE");
        } catch (\Throwable $e) {}

        try {
            $this->db->query("ALTER TABLE auth_groups_users ADD CONSTRAINT auth_groups_users_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE");
        } catch (\Throwable $e) {}

        try {
            $this->db->query("ALTER TABLE auth_remember_tokens ADD CONSTRAINT auth_remember_tokens_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE");
        } catch (\Throwable $e) {}

        try {
            $this->db->query("ALTER TABLE auth_permissions_users ADD CONSTRAINT auth_permissions_users_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE");
        } catch (\Throwable $e) {}

        // 3. Drop users_siswa table
        $this->forge->dropTable('users_siswa', true);
    }
}
