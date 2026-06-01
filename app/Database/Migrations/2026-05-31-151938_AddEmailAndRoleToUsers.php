<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailAndRoleToUsers extends Migration
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
            $this->forge->addColumn('users', $fields);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('users')) {
            if ($this->db->fieldExists('role', 'users')) {
                $this->forge->dropColumn('users', 'role');
            }
            if ($this->db->fieldExists('email', 'users')) {
                $this->forge->dropColumn('users', 'email');
            }
        }
    }
}
