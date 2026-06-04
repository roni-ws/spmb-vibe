<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Entities\User;

class Users extends BaseController
{
    protected $helpers = ['url', 'html', 'form'];

    public function index()
    {
        $this->requireAdmin();
        $shieldUser = auth()->user();

        // 2. Ambil semua data staff (Admin & Panitia) dari database
        $db = \Config\Database::connect();
        $builder = $db->table('users')
            ->select('id, username, email, role, created_at')
            ->whereIn('role', ['admin', 'panitia'])
            ->orderBy('created_at', 'DESC');

        $staffs = $builder->get()->getResultArray();

        $data = [
            'admin_username' => $shieldUser->username,
            'staffs'         => $staffs,
        ];

        return view('admin/users', $data);
    }

    public function store()
    {
        $this->requireAdmin();
        // 2. Validasi input pembuatan user baru
        $rules = [
            'username'         => 'required|min_length[3]|max_length[30]|regex_match[/^[a-zA-Z0-9_]+$/]|is_unique[users.username]',
            'email'            => 'required|valid_email|is_unique[auth_identities.secret]',
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
            'role'             => 'required|in_list[admin,panitia]',
        ];

        $messages = [
            'username' => [
                'regex_match' => 'Username hanya boleh mengandung huruf, angka, dan underscore.',
                'is_unique'   => 'Username sudah digunakan oleh akun lain.',
            ],
            'email' => [
                'is_unique' => 'Email sudah terdaftar di sistem.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role     = $this->request->getPost('role');

        // 3. Daftarkan akun baru menggunakan Shield Provider
        $usersProvider = auth()->getProvider();

        $user = new User([
            'username' => $username,
            'email'    => $email,
            'password' => $password,
        ]);

        // Simpan dan tangani error dari Shield
        if (! $usersProvider->save($user)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $usersProvider->errors());
        }

        // Ambil User yang baru dimasukkan untuk mengeset group & role
        $newUserId = $usersProvider->getInsertID();
        $newUser   = $usersProvider->findById($newUserId);

        if ($newUser) {
            // Set grup Shield
            $newUser->addGroup($role);

            // Update kolom custom 'role' pada tabel users
            $db = \Config\Database::connect();
            $db->table('users')->where('id', $newUserId)->update([
                'role' => $role
            ]);
        }

        return redirect()->to(base_url('admin/users'))
            ->with('success', 'Akun staff baru dengan peran ' . strtoupper($role) . ' berhasil dibuat!');
    }

    public function delete($userId)
    {
        $this->requireAdmin();
        // 2. Proteksi diri: Jangan ijinkan menghapus diri sendiri
        if (auth()->id() == $userId) {
            return redirect()->back()
                ->with('error', 'Keamanan Sistem: Anda tidak diizinkan untuk menghapus akun Anda sendiri.');
        }

        // 3. Proses penghapusan penuh (Purge / Hard Delete) via Shield
        $usersProvider = auth()->getProvider();
        $usersProvider->delete($userId, true);

        return redirect()->to(base_url('admin/users'))
            ->with('success', 'Akun staff berhasil dihapus dari sistem.');
    }

    public function edit($userId)
    {
        $this->requireAdmin();
        // 2. Validasi input
        $rules = [
            'username' => 'required|min_length[3]|max_length[30]|regex_match[/^[a-zA-Z0-9_]+$/]',
            'email'    => 'required|valid_email',
            'role'     => 'required|in_list[admin,panitia]',
        ];

        $messages = [
            'username' => [
                'regex_match' => 'Username hanya boleh mengandung huruf, angka, dan underscore.',
            ]
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $email    = $this->request->getPost('email');
        $role     = $this->request->getPost('role');

        $db = \Config\Database::connect();

        // Cek keunikan username
        $existingUsername = $db->table('users')->where('username', $username)->where('id !=', $userId)->get()->getRow();
        if ($existingUsername) {
            return redirect()->back()->withInput()->with('errors', ['username' => 'Username sudah digunakan oleh akun lain.']);
        }

        // Cek keunikan email
        $existingEmail = $db->table('users')->where('email', $email)->where('id !=', $userId)->get()->getRow();
        if ($existingEmail) {
            return redirect()->back()->withInput()->with('errors', ['email' => 'Email sudah terdaftar di sistem.']);
        }

        // 3. Update data users
        $db->table('users')->where('id', $userId)->update([
            'username'   => $username,
            'email'      => $email,
            'role'       => $role,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // 4. Update data Shield auth_identities (email)
        $db->table('auth_identities')->where('user_id', $userId)->where('type', 'email_password')->update([
            'secret'     => $email,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // 5. Update data Shield auth_groups_users (group)
        $db->table('auth_groups_users')->where('user_id', $userId)->delete();
        $db->table('auth_groups_users')->insert([
            'user_id'    => $userId,
            'group'      => $role,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('admin/users'))
            ->with('success', 'Data akun staff berhasil diperbarui!');
    }

    public function changePassword($userId)
    {
        $this->requireAdmin();
        // 2. Validasi input
        $rules = [
            'password'         => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors());
        }

        $password = $this->request->getPost('password');
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $db = \Config\Database::connect();

        // 3. Update kata sandi di auth_identities
        $db->table('auth_identities')->where('user_id', $userId)->where('type', 'email_password')->update([
            'secret2'    => $passwordHash,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to(base_url('admin/users'))
            ->with('success', 'Kata sandi staff berhasil diperbarui!');
    }
}
