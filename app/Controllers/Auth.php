<?php

namespace App\Controllers;

use CodeIgniter\Shield\Exceptions\ValidationException;

class Auth extends BaseController
{
    protected $helpers = ['url', 'html'];

    // ----------------------------------------------------------------
    // Halaman Login (GET)
    // ----------------------------------------------------------------
    public function login()
    {
        // Jika sudah login, alihkan sesuai role
        if (auth()->loggedIn()) {
            return $this->redirectByRole();
        }

        return view('auth/login');
    }

    // ----------------------------------------------------------------
    // Proses Login (POST) — diintegrasikan dengan CodeIgniter Shield
    // ----------------------------------------------------------------
    public function loginAction()
    {
        // Validasi input dasar
        $rules = [
            'identifier' => 'required|min_length[3]',
            'password'   => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $identifier = $this->request->getPost('identifier');
        $password   = $this->request->getPost('password');
        $remember   = (bool) $this->request->getPost('remember');

        // Tentukan field login: email atau username
        $credentials = filter_var($identifier, FILTER_VALIDATE_EMAIL)
            ? ['email' => $identifier, 'password' => $password]
            : ['username' => $identifier, 'password' => $password];

        // Proses autentikasi melalui Shield
        $result = auth()->attempt($credentials, $remember);

        if (! $result->isOK()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email/username atau kata sandi tidak valid. Silakan coba lagi.');
        }

        // Login berhasil — alihkan berdasarkan role
        return $this->redirectByRole();
    }

    // ----------------------------------------------------------------
    // Logout — menggunakan Shield
    // ----------------------------------------------------------------
    public function logout()
    {
        auth()->logout();
        return redirect()->to(base_url('login'))->with('message', 'Anda telah berhasil keluar.');
    }

    // ----------------------------------------------------------------
    // Halaman Register (GET)
    // ----------------------------------------------------------------
    public function register(): string
    {
        if (auth()->loggedIn()) {
            return $this->redirectByRole();
        }

        return view('auth/register');
    }

    // ----------------------------------------------------------------
    // Proses Registrasi Calon Siswa (POST)
    // ----------------------------------------------------------------
    public function registerAction()
    {
        // 1. Ambil & Validasi Input
        $rules = [
            'fullName' => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'username' => 'required|min_length[3]|max_length[30]|is_unique[users.username]|regex_match[/\A[a-zA-Z0-9\.]+\z/]',
            'password' => 'required|min_length[8]',
            'terms'    => 'required'
        ];

        $messages = [
            'fullName' => [
                'required' => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.'
            ],
            'email' => [
                'required' => 'Alamat email wajib diisi.',
                'valid_email' => 'Format alamat email tidak valid.',
                'is_unique' => 'Alamat email sudah terdaftar. Silakan gunakan email lain.'
            ],
            'username' => [
                'required' => 'Nama pengguna (username) wajib diisi.',
                'min_length' => 'Username minimal terdiri dari 3 karakter.',
                'is_unique' => 'Username sudah digunakan. Silakan pilih username lain.',
                'regex_match' => 'Username hanya boleh berisi huruf, angka, dan titik.'
            ],
            'password' => [
                'required' => 'Kata sandi wajib diisi.',
                'min_length' => 'Kata sandi minimal harus terdiri dari 8 karakter.'
            ],
            'terms' => [
                'required' => 'Anda harus menyetujui Ketentuan Layanan dan Kebijakan Privasi.'
            ]
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // 2. Buat akun baru via Shield
        $users = auth()->getProvider();
        
        $user = new \CodeIgniter\Shield\Entities\User([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'active'   => 1 // Akun langsung aktif
        ]);

        try {
            // Simpan ke database
            if (! $users->save($user)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Gagal membuat akun. Silakan coba beberapa saat lagi.');
            }

            // Dapatkan user instansi yang baru saja dibuat
            $userId = $users->getInsertID();
            $user = $users->findById($userId);
            
            // Tambahkan user ke group 'user' bawaan Shield
            $user->addGroup('user');

            // 3. Inisialisasi Data Relasional di Database
            $db = \Config\Database::connect();

            // Set role khusus 'student' di tabel users
            $db->table('users')->where('id', $userId)->update(['role' => 'student']);

            // Buat record Biodata (mengisi nama_lengkap)
            // Field yang bertipe NOT NULL lainnya diberi nilai default kosong
            $db->table('biodata')->insert([
                'user_id'        => $userId,
                'nama_lengkap'   => $this->request->getPost('fullName'),
                'nik'            => 'REG-' . $userId . '-' . time(), // Unique placeholder NIK
                'tempat_lahir'   => '',
                'tanggal_lahir'  => '2000-01-01',
                'jenis_kelamin'  => 'L',
                'no_hp'          => '',
                'nama_ayah'      => '',
                'pekerjaan_ayah' => '',
                'nama_ibu'       => '',
                'pekerjaan_ibu'  => '',
                'alamat'         => ''
            ]);

            // Buat record Berkas awal
            $db->table('berkas')->insert([
                'user_id'            => $userId,
                'status_verifikasi'  => 'Pending',
                'status_kelulusan'   => 'Belum Pengumuman',
                'is_finalized'       => 0
            ]);

            // 4. Login Otomatis & Redirect ke Dashboard
            auth()->login($user);
            
            return redirect()->to(base_url('siswa/dashboard'))
                ->with('success', 'Akun Anda berhasil dibuat! Selamat datang di dashboard SPMB.');

        } catch (\Throwable $e) {
            log_message('error', 'Registration Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem saat registrasi: ' . $e->getMessage());
        }
    }

    // ----------------------------------------------------------------
    // Halaman Lupa Password (GET)
    // ----------------------------------------------------------------
    public function forgotPassword(): string
    {
        return view('auth/forgot_password');
    }

    // ----------------------------------------------------------------
    // Helper: Redirect berdasarkan role pengguna yang login
    // ----------------------------------------------------------------
    protected function redirectByRole()
    {
        $user = auth()->user();

        if ($user && ($user->inGroup('admin') || $user->inGroup('superadmin') || $user->inGroup('panitia'))) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        return redirect()->to(base_url('siswa/dashboard'));
    }
}
