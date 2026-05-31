<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected $beforeInsert = ['injectEmailAndPassword'];
    protected $beforeUpdate = ['injectEmailAndPassword'];

    protected function initialize(): void
    {
        parent::initialize();

        // Dynamically merge custom columns needed by SPMB application
        $this->allowedFields = array_merge($this->allowedFields, [
            'email',
            'password_hash',
            'role'
        ]);
    }

    protected function injectEmailAndPassword(array $data): array
    {
        if ($this->tempUser !== null) {
            $data['data']['email'] = $this->tempUser->email;
            if (!empty($this->tempUser->password)) {
                $data['data']['password_hash'] = password_hash($this->tempUser->password, PASSWORD_BCRYPT);
            }
        }
        return $data;
    }
}
