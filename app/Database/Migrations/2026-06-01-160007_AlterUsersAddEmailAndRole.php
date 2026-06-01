<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterUsersAddEmailAndRole extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('users')) {
            $fields = [
                'email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                    'after'      => 'username'
                ],
                'role' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                    'after'      => 'email'
                ]
            ];
            
            if (!$this->db->fieldExists('email', 'users')) {
                $this->forge->addColumn('users', ['email' => $fields['email']]);
            }
            if (!$this->db->fieldExists('role', 'users')) {
                $this->forge->addColumn('users', ['role' => $fields['role']]);
            }
        }
    }

    public function down()
    {
        if ($this->db->tableExists('users')) {
            try {
                if ($this->db->fieldExists('role', 'users')) {
                    $this->forge->dropColumn('users', 'role');
                }
            } catch (\Throwable $e) {}

            try {
                if ($this->db->fieldExists('email', 'users')) {
                    $this->forge->dropColumn('users', 'email');
                }
            } catch (\Throwable $e) {}
        }
    }
}
