<?php

namespace App\Controllers\Api\Siswa;

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

        if ($user->role !== ROLE_STUDENT) {
            $auth->logout();
            return $this->failForbidden('Akses ditolak. Akun Anda bukan merupakan akun Calon Siswa.');
        }

        $jwtManager = service('jwtmanager');
        $token      = $jwtManager->generateToken($user);

        return $this->respond([
            'status'  => true,
            'message' => 'Login Calon Siswa berhasil.',
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

        $nisn = $user->nisn ?? $user->username;
        $studentService = new \App\Services\StudentService();
        $studentProfile = $studentService->getStudentProfileByNisn($nisn);

        return $this->respond([
            'status' => true,
            'user'   => [
                'id'       => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
                'role'     => $user->role,
            ],
            'profile' => $studentProfile ?: null,
        ]);
    }
}
