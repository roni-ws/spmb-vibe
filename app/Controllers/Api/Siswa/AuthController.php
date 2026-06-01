<?php

namespace App\Controllers\Api\Siswa;

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

        // Access the auth service (dynamically resolves to student context via URI)
        $auth = service('auth');
        
        $result = $auth->attempt($credentials);

        if (! $result->isOK()) {
            return $this->failUnauthorized('Kredensial tidak valid. Silakan coba lagi.');
        }

        $user = $auth->user();

        // Enforce student authorization
        if ($user->role !== 'student') {
            $auth->logout();
            return $this->failForbidden('Akses ditolak. Akun Anda bukan merupakan akun Calon Siswa.');
        }

        // Generate JWT token
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

    public function profile()
    {
        $user = auth()->user();

        if (! $user) {
            return $this->failUnauthorized('Sesi tidak valid.');
        }

        // Retrieve operational details of the student from the database
        $db = \Config\Database::connect();
        $studentProfile = $db->table('calon_siswa')
            ->select('calon_siswa.*, status_verifikasi.nama_status as status_verifikasi, status_pendaftaran.is_finalized')
            ->join('status_pendaftaran', 'status_pendaftaran.siswa_id = calon_siswa.id', 'left')
            ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left')
            ->where('calon_siswa.nisn', $user->username)
            ->get()
            ->getRowArray();

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
