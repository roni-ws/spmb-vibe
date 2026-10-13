<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use CodeIgniter\Shield\Authentication\Authenticators\Session;
use CodeIgniter\Shield\Models\UserIdentityModel;

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

        if (! empty(\App\Models\SettingsModel::getSetting('recaptcha_site_key'))) {
            $rules['g-recaptcha-response'] = 'required|validate_recaptcha';
        }

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
        if (auth()->loggedIn()) {
            return $this->redirectByRole();
        }

        return $this->response->setBody(view('auth/forgot_password', $this->getPublicSettings()));
    }

    public function forgotPasswordAction(): ResponseInterface
    {
        if (! empty(\App\Models\SettingsModel::getSetting('recaptcha_site_key'))) {
            $recaptcha = $this->request->getPost('g-recaptcha-response');
            if (! $recaptcha || ! $this->validateRecaptcha($recaptcha)) {
                return redirect()->back()->withInput()->with('error', 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.');
            }
        }

        $email = $this->request->getPost('email');

        if (! $email || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Format alamat email tidak valid.');
        }

        $user = auth()->getProvider()->findByCredentials(['email' => $email]);

        if ($user === null) {
            return redirect()->to(base_url('forgot-password'))
                ->with('error', 'Email "' . esc($email) . '" tidak terdaftar di sistem. Silakan periksa kembali alamat email Anda.');
        }

        try {
            /** @var UserIdentityModel $identityModel */
            $identityModel = model(UserIdentityModel::class);

            $identityModel->deleteIdentitiesByType($user, Session::ID_TYPE_MAGIC_LINK);

            helper('text');
            $token = random_string('crypto', 20);

            $identityModel->insert([
                'user_id' => $user->id,
                'type'    => Session::ID_TYPE_MAGIC_LINK,
                'secret'  => $token,
                'expires' => Time::now()->addSeconds(setting('Auth.magicLinkLifetime')),
            ]);

            $ipAddress = $this->request->getIPAddress();
            $userAgent = (string) $this->request->getUserAgent();
            $date      = Time::now()->toDateTimeString();

            helper('email');
            $email = emailer(['mailType' => 'html'])
                ->setFrom(setting('Email.fromEmail'), setting('Email.fromName') ?? '');
            $email->setTo($user->email);
            $email->setSubject(lang('Auth.magicLinkSubject'));
            $email->setMessage(view(
                setting('Auth.views')['magic-link-email'],
                ['token' => $token, 'user' => $user, 'ipAddress' => $ipAddress, 'userAgent' => $userAgent, 'date' => $date],
            ));

            if ($email->send(false) === false) {
                log_message('error', 'Magic link email gagal: ' . $email->printDebugger(['headers']));
            }
        } catch (\Throwable $e) {
            log_message('error', 'Magic link error: ' . $e->getMessage());
        }

        return redirect()->to(base_url('forgot-password'))
            ->with('message', 'Tautan atur ulang kata sandi telah dikirim ke email ' . esc($email) . '. Silakan periksa kotak masuk (termasuk folder Spam atau Promosi).');
    }

    public function resetPasswordForm(): ResponseInterface
    {
        if (auth()->loggedIn()) {
            return $this->redirectByRole();
        }

        $token = $this->request->getGet('token');

        if (! $token) {
            return redirect()->to(base_url('login'))
                ->with('error', 'Tautan atur ulang kata sandi tidak valid.');
        }

        /** @var UserIdentityModel $identityModel */
        $identityModel = model(UserIdentityModel::class);
        $identity = $identityModel->getIdentityBySecret(Session::ID_TYPE_MAGIC_LINK, $token);

        if ($identity === null || Time::now()->isAfter($identity->expires)) {
            return redirect()->to(base_url('login'))
                ->with('error', 'Tautan atur ulang kata sandi sudah tidak berlaku. Silakan ajukan permintaan baru.');
        }

        return $this->response->setBody(view('auth/reset_password', [
            ...$this->getPublicSettings(),
            'token' => $token,
        ]));
    }

    public function resetPasswordAction(): ResponseInterface
    {
        if (! empty(\App\Models\SettingsModel::getSetting('recaptcha_site_key'))) {
            $recaptcha = $this->request->getPost('g-recaptcha-response');
            if (! $recaptcha || ! $this->validateRecaptcha($recaptcha)) {
                return redirect()->back()->withInput()->with('error', 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.');
            }
        }

        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');

        if (! $token) {
            return redirect()->to(base_url('login'))
                ->with('error', 'Tautan atur ulang kata sandi tidak valid.');
        }

        $rules = [
            'password' => [
                'label' => 'Kata sandi',
                'rules' => 'required|regex_match[/(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])/]',
                'errors' => [
                    'required'     => 'Kata sandi wajib diisi.',
                    'regex_match'  => 'Kata sandi harus mengandung kombinasi huruf, angka, dan simbol.',
                ],
            ],
            'password_confirm' => [
                'label' => 'Konfirmasi kata sandi',
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi kata sandi wajib diisi.',
                    'matches'  => 'Konfirmasi kata sandi tidak cocok.',
                ],
            ],
        ];

        if (! $this->validateData($this->request->getPost(), $rules)) {
            return redirect()->back()
                ->withInput()
                ->with('token', $token)
                ->with('errors', $this->validator->getErrors());
        }

        /** @var UserIdentityModel $identityModel */
        $identityModel = model(UserIdentityModel::class);
        $identity = $identityModel->getIdentityBySecret(Session::ID_TYPE_MAGIC_LINK, $token);

        if ($identity === null || Time::now()->isAfter($identity->expires)) {
            return redirect()->to(base_url('login'))
                ->with('error', 'Tautan atur ulang kata sandi sudah tidak berlaku. Silakan ajukan permintaan baru.');
        }

        try {
            $provider = auth()->getProvider();
            $user = $provider->findById($identity->user_id);

            if ($user === null) {
                return redirect()->to(base_url('login'))
                    ->with('error', 'Akun tidak ditemukan.');
            }

            $user->setPassword($password);
            $provider->save($user);

            $identityModel->delete($identity->id);

            auth('session')->getAuthenticator()->loginById($user->id);

            return redirect()->to(config('Auth')->loginRedirect())
                ->with('message', 'Kata sandi berhasil diperbarui. Anda sekarang telah masuk.');
        } catch (\Throwable $e) {
            log_message('error', 'Reset password error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba beberapa saat lagi.');
        }
    }

}
