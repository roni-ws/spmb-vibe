<?php

namespace App\Models\User;

use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Authentication\Authenticators\Session;

class UnifiedUserModel extends UserModel
{
    protected string $userTable = 'users';

    protected function initialize(): void
    {
        parent::initialize();
        $this->table = $this->userTable;
    }

    public function findById($id): ?User
    {
        foreach (['users', 'users_siswa'] as $table) {
            $row = $this->db->table($table)
                ->where($table . '.id', $id)
                ->where($table . '.deleted_at', null)
                ->get()
                ->getRowArray();

            if ($row !== null) {
                $user = new User($row);
                $user->syncOriginal();
                $this->table = $table;
                return $user;
            }
        }

        return null;
    }

    public function findByCredentials(array $credentials): ?User
    {
        $email = $credentials['email'] ?? null;
        unset($credentials['email']);

        if ($email === null && $credentials === []) {
            return null;
        }

        foreach (['users', 'users_siswa'] as $table) {
            $builder = $this->db->table($table);

            foreach ($credentials as $key => $value) {
                if ($key === 'username') {
                    $builder->groupStart()
                        ->where('LOWER(username)', strtolower($value))
                        ->orWhere('LOWER(nisn)', strtolower($value))
                        ->groupEnd();
                } else {
                    $builder->where('LOWER(' . $key . ')', strtolower($value));
                }
            }

            if ($email !== null) {
                $builder->join('auth_identities', 'auth_identities.user_id = ' . $table . '.id')
                    ->where('auth_identities.type', Session::ID_TYPE_EMAIL_PASSWORD)
                    ->where('LOWER(auth_identities.secret)', strtolower($email))
                    ->select($table . '.*, auth_identities.secret as email, auth_identities.secret2 as password_hash');

                $row = $builder->get()->getRowArray();

                if ($row !== null) {
                    $emailValue = $row['email'];
                    unset($row['email']);
                    $passwordHash = $row['password_hash'];
                    unset($row['password_hash']);

                    $user = new User($row);
                    $user->email = $emailValue;
                    $user->password_hash = $passwordHash;
                    $user->syncOriginal();

                    $this->table = $table;
                    return $user;
                }
            } else {
                $row = $builder->get()->getRowArray();

                if ($row !== null) {
                    $user = new User($row);
                    $user->syncOriginal();

                    $this->table = $table;
                    return $user;
                }
            }
        }

        return null;
    }

    public function save($row = null): bool
    {
        if (is_object($row) && isset($row->id)) {
            $id = $row->id;

            $exists = $this->db->table('users')
                ->where('id', $id)
                ->countAllResults();

            if ($exists > 0) {
                $this->table = 'users';
                return parent::save($row);
            }

            $exists = $this->db->table('users_siswa')
                ->where('id', $id)
                ->countAllResults();

            if ($exists > 0) {
                $this->table = 'users_siswa';
                return parent::save($row);
            }
        }

        $this->table = 'users';
        return parent::save($row);
    }

    public function updateActiveDate(User $user): void
    {
        $lastActive = $this->timeToDate($user->last_active);

        $this->db->table('users')
            ->set('last_active', $lastActive)
            ->where('id', $user->id)
            ->update();

        $this->db->table('users_siswa')
            ->set('last_active', $lastActive)
            ->where('id', $user->id)
            ->update();
    }
}
