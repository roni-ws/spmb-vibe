<?php

namespace App\Controllers\Api\Admin;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class AuthController extends BaseController
{
    use ResponseTrait;

    public function loginAction()
    {
        $rules = [
            'identifier' => 'required',
            'password'   => 'required',
        ];

        if (! $this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $identifier = $this->request->getVar('identifier');
        $password   = $this->request->getVar('password');

        $credentials = filter_var($identifier, FILTER_VALIDATE_EMAIL)
            ? ['email' => $identifier, 'password' => $password]
            : ['username' => $identifier, 'password' => $password];

        // Access the auth service
        $auth = service('auth');
        
        $result = $auth->attempt($credentials);

        if (! $result->isOK()) {
            return $this->failUnauthorized('Kredensial tidak valid. Silakan coba lagi.');
        }

        $user = $auth->user();

        // Enforce admin/panitia authorization
        if (! $user->inGroup('admin', 'panitia')) {
            $auth->logout();
            return $this->failForbidden('Akses ditolak. Akun Anda bukan merupakan Admin/Panitia.');
        }

        // Generate JWT token
        $jwtManager = service('jwtmanager');
        $token      = $jwtManager->generateToken($user);

        return $this->respond([
            'status'  => true,
            'message' => 'Login Admin berhasil.',
            'token'   => $token,
            'user'    => [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
                'role'     => $user->role,
            ],
        ]);
    }

    public function profile()
    {
        $user = auth()->user();

        if (! $user) {
            return $this->failUnauthorized('Sesi tidak valid.');
        }

        return $this->respond([
            'status' => true,
            'user'   => [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
                'role'     => $user->role,
            ],
        ]);
    }
}
