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

        // Gabungkan kolom kustom yang dibutuhkan oleh aplikasi SPMB
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
