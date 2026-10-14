<?php

namespace App\Models\User;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class BaseUserModel extends ShieldUserModel
{
    protected string $userTable = 'users';

    protected $beforeInsert = ['injectEmailAndPassword'];
    protected $beforeUpdate = ['injectEmailAndPassword'];

    protected function initialize(): void
    {
        parent::initialize();

        $this->table = $this->userTable;

        $this->allowedFields = array_merge($this->allowedFields, [
            'email',
            'role',
            'nisn',
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
