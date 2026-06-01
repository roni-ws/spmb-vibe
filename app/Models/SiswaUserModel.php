<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class SiswaUserModel extends ShieldUserModel
{
    protected $beforeInsert = ['injectEmailAndPassword'];
    protected $beforeUpdate = ['injectEmailAndPassword'];

    protected function initialize(): void
    {
        parent::initialize();

        // Dynamically assign the siswa user table
        $this->table = 'users_siswa';

        // Keep local app custom fields allowed
        $this->allowedFields = array_merge($this->allowedFields, [
            'email',
            'role'
        ]);
    }

    protected function injectEmailAndPassword(array $data): array
    {
        if ($this->tempUser !== null) {
            $data['data']['email'] = $this->tempUser->email;
        }
        return $data;
    }
}
