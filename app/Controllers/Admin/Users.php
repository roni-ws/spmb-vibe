<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Entities\User;

class Users extends BaseController
{
    protected $helpers = ['url', 'html', 'form'];

    public function index()
    {
        // 1. Proteksi rute: Hanya Admin atau Super Admin yang boleh mengelola user
        if (! auth()->loggedIn() || (! auth()->user()->inGroup('admin') && ! auth()->user()->inGroup('superadmin'))) {
            return redirect()->to(base_url('admin/dashboard'))
                ->with('error', 'Akses ditolak. Rute ini hanya dapat diakses oleh Admin Utama.');
        }

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
        // 1. Proteksi rute
        if (! auth()->loggedIn() || (! auth()->user()->inGroup('admin') && ! auth()->user()->inGroup('superadmin'))) {
            return redirect()->to(base_url('admin/dashboard'))
                ->with('error', 'Akses ditolak.');
        }

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
        // 1. Proteksi rute
        if (! auth()->loggedIn() || (! auth()->user()->inGroup('admin') && ! auth()->user()->inGroup('superadmin'))) {
            return redirect()->to(base_url('admin/dashboard'))
                ->with('error', 'Akses ditolak.');
        }

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
}
