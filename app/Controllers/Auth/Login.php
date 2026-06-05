<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController
{
    protected $helpers = ['url', 'html'];

    public function index(): ResponseInterface
    {
        if (auth()->loggedIn()) {
            return $this->redirectByRole();
        }

        return $this->response->setBody(view('auth/login', $this->getPublicSettings()));
    }

    public function loginAction(): ResponseInterface
    {
        $rules = [
            'identifier' => 'required',
            'password'   => 'required',
        ];

        $messages = [
            'identifier' => [
                'required' => 'Email atau username wajib diisi.',
            ],
            'password' => [
                'required' => 'Kata sandi wajib diisi.',
            ],
        ];

        if (! $this->validateData($this->request->getPost(), $rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $identifier = $this->request->getPost('identifier');
        $password   = $this->request->getPost('password');
        $remember   = (bool) $this->request->getPost('remember');

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $identifier, 'password' => $password];
        } elseif (ctype_digit($identifier)) {
            $credentials = ['nisn' => $identifier, 'password' => $password];
        } else {
            $credentials = ['username' => $identifier, 'password' => $password];
        }

        $result = auth()->attempt($credentials, $remember);

        if (! $result->isOK()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email/username atau kata sandi tidak valid. Silakan coba lagi.');
        }

        return $this->redirectByRole();
    }

    public function logout(): ResponseInterface
    {
        auth()->logout();
        return redirect()->to(base_url('login'))->with('message', 'Anda telah berhasil keluar.');
    }

    public function forgotPassword(): ResponseInterface
    {
        return $this->response->setBody(view('auth/forgot_password', $this->getPublicSettings()));
    }

}
