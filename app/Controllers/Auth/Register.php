<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Services\RegistrationService;
use CodeIgniter\HTTP\ResponseInterface;

class Register extends BaseController
{
    protected $helpers = ['url', 'html'];

    public function index(): ResponseInterface
    {
        $settings = $this->getPublicSettings();
        if (! $settings['is_open']) {
            return redirect()->to(base_url())->with('error', 'Pendaftaran calon siswa baru saat ini sedang ditutup.');
        }

        if (auth()->loggedIn()) {
            return $this->redirectByRole();
        }

        return $this->response->setBody(view('auth/register', $settings));
    }

    public function store(): ResponseInterface
    {
        $settings = $this->getPublicSettings();
        if (! $settings['is_open']) {
            return redirect()->to(base_url())->with('error', 'Pendaftaran calon siswa baru saat ini sedang ditutup.');
        }

        if (! empty(\App\Models\SettingsModel::getSetting('recaptcha_site_key'))) {
            $recaptcha = $this->request->getPost('g-recaptcha-response');
            if (! $this->validateRecaptcha($recaptcha)) {
                return redirect()->back()->withInput()->with('error', 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.');
            }
        }

        if (! $this->validateData($this->request->getPost(), 'pendaftaranSiswa')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $registrationService = new RegistrationService();

        try {
            $user = $registrationService->register(
                $this->request->getPost('username'),
                $this->request->getPost('email'),
                $this->request->getPost('password'),
                $this->request->getPost('fullName')
            );

            $authenticator = auth('session')->getAuthenticator();
            $authenticator->startLogin($user);
            $authenticator->startUpAction('register', $user);

            return redirect()->to(route_to('auth-action-show'))
                ->with('success', 'Akun Anda berhasil dibuat! Silakan cek email untuk verifikasi aktivasi akun.');
        } catch (\Throwable $e) {
            log_message('error', 'Registration Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem saat registrasi. Silakan coba beberapa saat lagi.');
        }
    }

    public function checkEmail(): ResponseInterface
    {
        $email = $this->request->getPost('email');
        if (!$email) {
            return $this->response->setJSON(['available' => false, 'message' => '']);
        }

        $user = model(\App\Models\User\SiswaUserModel::class)
            ->where('email', $email)
            ->first();

        return $this->response->setJSON([
            'available' => $user === null,
            'message'   => $user === null ? 'Email tersedia' : 'Email sudah terdaftar'
        ]);
    }

    public function checkUsername(): ResponseInterface
    {
        $username = $this->request->getPost('username');
        if (!$username) {
            return $this->response->setJSON(['available' => false, 'message' => '']);
        }

        $user = model(\App\Models\User\SiswaUserModel::class)
            ->where('username', $username)
            ->first();

        return $this->response->setJSON([
            'available' => $user === null,
            'message'   => $user === null ? 'Username tersedia' : 'Username sudah digunakan'
        ]);
    }

}
