<?php

namespace App\Controllers\Api\Admin;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    use ResponseTrait;

    public function loginAction(): ResponseInterface
    {
        $rules = [
            'identifier' => 'required',
            'password'   => 'required',
        ];

        if (! $this->validateData($this->request->getJSON(true), $rules)) {
            return $this->fail($this->validator->getErrors());
        }

        $identifier = $this->request->getJSON(true)['identifier'] ?? '';
        $password   = $this->request->getJSON(true)['password'] ?? '';

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $identifier, 'password' => $password];
        } elseif (ctype_digit($identifier)) {
            $credentials = ['nisn' => $identifier, 'password' => $password];
        } else {
            $credentials = ['username' => $identifier, 'password' => $password];
        }

        $auth = service('auth');

        $result = $auth->attempt($credentials);

        if (! $result->isOK()) {
            return $this->failUnauthorized('Kredensial tidak valid. Silakan coba lagi.');
        }

        $user = $auth->user();

        if (! $user->inGroup(ROLE_ADMIN, ROLE_PANITIA)) {
            $auth->logout();
            return $this->failForbidden('Akses ditolak. Akun Anda bukan merupakan Admin/Panitia.');
        }

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

    public function profile(): ResponseInterface
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
