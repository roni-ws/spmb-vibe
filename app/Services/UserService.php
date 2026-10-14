<?php

namespace App\Services;

use App\Models\User\UserModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserIdentityModel;

class UserService
{
    protected UserModel $userModel;

    public function __construct(?UserModel $userModel = null)
    {
        $this->userModel = $userModel ?? model(UserModel::class);
    }

    public function create(array $data): int
    {
        $usersProvider = auth()->getProvider();

        $user = new User([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);

        if (! $usersProvider->save($user)) {
            throw new \RuntimeException('Gagal membuat akun: ' . implode(', ', $usersProvider->errors()));
        }

        $newUserId = $usersProvider->getInsertID();
        $newUser   = $usersProvider->findById($newUserId);

        if ($newUser) {
            $newUser->addGroup($data['role']);
            $this->updateUserRole($newUserId, $data['role']);
        }

        return $newUserId;
    }

    public function getStaffList(): array
    {
        $users = $this->userModel
            ->whereIn('role', [ROLE_ADMIN, ROLE_PANITIA])
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $list = [];
        foreach ($users as $user) {
            $list[] = [
                'id'         => $user->id,
                'username'   => $user->username,
                'email'      => $user->email,
                'role'       => $user->role,
                'created_at' => $user->created_at,
            ];
        }
        return $list;
    }

    public function updateUserRole(int $userId, string $role): void
    {
        $user = $this->userModel->find($userId);
        if ($user) {
            $user->role = $role;
            $this->userModel->save($user);
        }
    }

    public function updateUserProfile(int $userId, string $username, string $email, string $role): void
    {
        $user = $this->userModel->find($userId);
        if (! $user) {
            return;
        }

        $user->username = $username;
        $user->email    = $email;
        $user->role     = $role;
        $this->userModel->save($user);

        $user->syncGroups($role);
    }

    public function isUsernameTaken(string $username, ?int $excludeId = null): bool
    {
        $query = $this->userModel->where('username', $username);
        if ($excludeId) {
            $query->where('id !=', $excludeId);
        }
        return (bool) $query->first();
    }

    public function isEmailTaken(string $email, ?int $excludeId = null): bool
    {
        $identityModel = model(UserIdentityModel::class);
        $builder = $identityModel->where('secret', $email)->where('type', 'email_password');
        if ($excludeId) {
            $builder->where('user_id !=', $excludeId);
        }
        return (bool) $builder->first();
    }

    public function updatePassword(int $userId, string $password): void
    {
        $user = $this->userModel->find($userId);
        if ($user) {
            $user->password = $password;
            $this->userModel->save($user);
        }
    }

    public function delete(int $userId): void
    {
        auth()->getProvider()->delete($userId, true);
    }
}
