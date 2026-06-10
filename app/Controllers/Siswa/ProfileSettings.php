<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\Siswa\SiswaModel;
use App\Models\User\SiswaUserModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProfileSettings extends BaseController
{
    protected $helpers = ['url', 'html', 'form'];

    public function index(): ResponseInterface
    {
        $this->requireSiswa();

        $shieldUser = auth()->user();
        $siswa = model(SiswaModel::class)
            ->where('user_id', $shieldUser->id)
            ->first();

        return $this->renderSiswaView('siswa/form-profile-settings', [
            'username'     => $shieldUser->username,
            'email'        => $shieldUser->email,
            'nama_lengkap' => $siswa['nama_lengkap'] ?? $shieldUser->username,
            'nisn'         => $shieldUser->nisn ?? $shieldUser->username,
        ]);
    }

    public function update(): ResponseInterface
    {
        $this->requireSiswa();

        $shieldUser = auth()->user();
        $siswaUserModel = model(SiswaUserModel::class);
        $userRow = $siswaUserModel->find($shieldUser->id);

        $rules = [];

        $newUsername = $this->request->getPost('username');
        if ($newUsername && $newUsername !== $shieldUser->username) {
            $rules['username'] = 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users_siswa.username]';
        }

        $newEmail = $this->request->getPost('email');
        if ($newEmail && $newEmail !== $shieldUser->email) {
            $rules['email'] = 'required|valid_email|is_unique[users_siswa.email]';
        }

        $oldPassword = $this->request->getPost('password_lama');
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
            $siswaUserModel->save($userRow);
        }

        if ($newPassword) {
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

        return redirect()->to(base_url('siswa/profile-settings'))
            ->with('success', 'Pengaturan akun berhasil diperbarui!');
    }
}
