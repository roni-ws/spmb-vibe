<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ProfileSettings extends BaseController
{
    protected $helpers = ['url', 'html', 'form'];

    public function index(): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);

        $shieldUser = auth()->user();

        return $this->response->setBody(view('admin/form-profile-settings', [
            ...$this->getSiteSettings(),
            'admin_username' => $shieldUser->username,
            'email'          => $shieldUser->email,
            'roleLabel'      => $shieldUser->inGroup(ROLE_ADMIN) ? 'Administrator' : 'Panitia SPMB',
            'isAdmin'        => $shieldUser->inGroup(ROLE_ADMIN),
        ]));
    }

    public function update(): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);

        $shieldUser = auth()->user();
        $userModel = model(\App\Models\User\UserModel::class);
        $userRow = $userModel->find($shieldUser->id);

        $rules = [];

        $newUsername = $this->request->getPost('username');
        if ($newUsername && $newUsername !== $shieldUser->username) {
            $rules['username'] = 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]';
        }

        $newEmail = $this->request->getPost('email');
        if ($newEmail && $newEmail !== $shieldUser->email) {
            $rules['email'] = 'required|valid_email|is_unique[users.email]';
        }

        $newPassword = $this->request->getPost('password_baru');
        if ($newPassword) {
            $rules['password_lama']  = 'required';
            $rules['password_baru']  = 'required|min_length[8]';
            $rules['password_konfirmasi'] = 'required|matches[password_baru]';
        }

        if (!empty($rules) && !$this->validateData($this->request->getPost(), $rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $updateData = [];

        if ($newUsername && $newUsername !== $shieldUser->username) {
            $updateData['username'] = $newUsername;
        }

        if ($newEmail && $newEmail !== $shieldUser->email) {
            $updateData['email'] = $newEmail;
        }

        if (!empty($updateData) && $userRow) {
            $userRow->fill($updateData);
            $userModel->save($userRow);
        }

        if ($newPassword) {
            $oldPassword = $this->request->getPost('password_lama');
            $users = auth()->getProvider();
            $userEntity = $users->findById($shieldUser->id);
            if ($userEntity && password_verify($oldPassword, $userEntity->password_hash)) {
                $userEntity->password = $newPassword;
                $users->save($userEntity);
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Password lama tidak sesuai.');
            }
        }

        return redirect()->to(base_url('admin/profile-settings'))
            ->with('success', 'Pengaturan akun berhasil diperbarui!');
    }
}
