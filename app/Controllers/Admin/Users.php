<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\UserService;
use CodeIgniter\HTTP\ResponseInterface;

class Users extends BaseController
{
    protected $helpers = ['url', 'html', 'form'];

    protected UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function index(): ResponseInterface
    {
        $this->requireAdmin();
        $shieldUser = auth()->user();

        $staffs = $this->userService->getStaffList();

        $data = [
            ...$this->getSiteSettings(),
            'admin_username' => $shieldUser->username,
            'user_email'     => $shieldUser->email,
            'authUserId'     => $shieldUser->id,
            'roleLabel'      => $shieldUser->inGroup(ROLE_ADMIN) ? 'Administrator' : 'Panitia SPMB',
            'isAdmin'        => $shieldUser->inGroup(ROLE_ADMIN),
            'staffs'         => $staffs,
        ];

        return $this->response->setBody(view('admin/users', $data));
    }

    public function store(): ResponseInterface
    {
        $this->requireAdmin();

        if (! $this->validateData($this->request->getPost(), 'staffStore')) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role     = $this->request->getPost('role');

        try {
            $this->userService->create([
                'username' => $username,
                'email'    => $email,
                'password' => $password,
                'role'     => $role,
            ]);
        } catch (\RuntimeException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()->to(base_url('admin/users'))
            ->with('success', 'Akun staff baru dengan peran ' . strtoupper((string) $role) . ' berhasil dibuat!');
    }

    public function delete(int $userId): ResponseInterface
    {
        $this->requireAdmin();

        if (auth()->id() == $userId) {
            return redirect()->back()
                ->with('error', 'Keamanan Sistem: Anda tidak diizinkan untuk menghapus akun Anda sendiri.');
        }

        $targetUser = auth()->getProvider()->findById($userId);
        if ($targetUser && $targetUser->inGroup(ROLE_ADMIN)) {
            return redirect()->back()
                ->with('error', 'Keamanan Sistem: Akun Administrator tidak dapat dihapus.');
        }

        $this->userService->delete($userId);

        return redirect()->to(base_url('admin/users'))
            ->with('success', 'Akun staff berhasil dihapus dari sistem.');
    }

    public function edit(int $userId): ResponseInterface
    {
        $this->requireAdmin();

        if (! $this->validateData($this->request->getPost(), 'staffEdit')) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $email    = $this->request->getPost('email');
        $role     = $this->request->getPost('role');

        if ($this->userService->isUsernameTaken($username, $userId)) {
            return redirect()->back()->withInput()->with('errors', ['username' => 'Username sudah digunakan oleh akun lain.']);
        }

        if ($this->userService->isEmailTaken($email, $userId)) {
            return redirect()->back()->withInput()->with('errors', ['email' => 'Email sudah terdaftar di sistem.']);
        }

        $this->userService->updateUserProfile($userId, $username, $email, $role);

        return redirect()->to(base_url('admin/users'))
            ->with('success', 'Data akun staff berhasil diperbarui!');
    }

    public function changePassword(int $userId): ResponseInterface
    {
        $this->requireAdmin();

        if (! $this->validateData($this->request->getPost(), 'staffPassword')) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors());
        }

        $password = $this->request->getPost('password');

        $this->userService->updatePassword($userId, $password);

        return redirect()->to(base_url('admin/users'))
            ->with('success', 'Kata sandi staff berhasil diperbarui!');
    }
}
