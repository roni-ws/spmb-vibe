<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public array $siswaBiodata = [
        'nisn'             => 'required|min_length[3]|max_length[10]|is_unique[calon_siswa.nisn]',
        'nik'              => 'required|numeric|exact_length[16]',
        'no_kk'            => 'required|numeric|exact_length[16]',
        'nama_lengkap'     => 'required|min_length[3]|max_length[150]',
        'tempat_lahir'     => 'required|min_length[3]',
        'tanggal_lahir'    => 'required|valid_date[Y-m-d]',
        'jenis_kelamin'    => 'required|in_list[L,P]',
        'anak_ke'          => 'required|integer|greater_than_equal_to[1]',
        'jumlah_saudara'   => 'required|integer|greater_than_equal_to[0]',
        'no_hp'            => 'required|min_length[10]|max_length[15]',
        'alamat_desa'      => 'required',
        'alamat_kabupaten' => 'required',
        'alamat_kecamatan' => 'required',
    ];

    public array $siswaSekolah = [
        'nama_sekolah'   => 'required|min_length[3]',
        'alamat_sekolah' => 'required|min_length[5]',
    ];

    public array $siswaOrangTua = [
        'nama_ayah'      => 'required|min_length[3]',
        'pekerjaan_ayah' => 'required',
        'nama_ibu'       => 'required|min_length[3]',
        'pekerjaan_ibu'  => 'required',
    ];

    public array $adminSiswaBiodata = [
        'nama_lengkap'     => 'required|min_length[3]|max_length[150]',
        'nik'              => 'required|numeric|exact_length[16]',
        'no_kk'            => 'required|numeric|exact_length[16]',
        'tempat_lahir'     => 'required|min_length[3]|max_length[100]',
        'tanggal_lahir'    => 'required|valid_date[Y-m-d]',
        'jenis_kelamin'    => 'required|in_list[L,P]',
        'anak_ke'          => 'required|is_natural_no_zero',
        'jumlah_saudara'   => 'required|is_natural',
        'no_hp'            => 'permit_empty|min_length[10]|max_length[15]',
        'alamat_desa'      => 'required|min_length[3]|max_length[100]',
        'alamat_kecamatan' => 'required|min_length[3]|max_length[100]',
        'alamat_kabupaten' => 'required|min_length[3]|max_length[100]',
        'nama_sekolah'     => 'required|min_length[3]|max_length[100]',
        'alamat_sekolah'   => 'required|min_length[5]',
        'nama_ayah'        => 'required|min_length[3]|max_length[150]',
        'pekerjaan_ayah'   => 'required|min_length[3]',
        'nama_ibu'         => 'required|min_length[3]|max_length[150]',
        'pekerjaan_ibu'    => 'required|min_length[3]',
    ];

    public array $adminSiswaBerkas = [
        'pas_foto'      => 'max_size[pas_foto,2048]|ext_in[pas_foto,jpg,jpeg,png]|is_image[pas_foto]',
        'fc_ijazah_skl' => 'max_size[fc_ijazah_skl,2048]|ext_in[fc_ijazah_skl,pdf,jpg,jpeg,png]',
        'fc_kk'         => 'max_size[fc_kk,2048]|ext_in[fc_kk,pdf,jpg,jpeg,png]',
    ];

    public array $siswaBiodata_errors = [
        'nisn' => [
            'required'   => 'NISN wajib diisi.',
            'min_length' => 'NISN minimal 3 karakter.',
            'max_length' => 'NISN maksimal 10 karakter.',
            'is_unique'  => 'NISN sudah terdaftar. Silakan gunakan NISN lain.',
        ],
        'nik' => [
            'required'     => 'NIK wajib diisi.',
            'numeric'      => 'NIK hanya boleh berupa angka.',
            'exact_length' => 'NIK harus tepat 16 digit.',
        ],
        'no_kk' => [
            'required'     => 'Nomor KK wajib diisi.',
            'numeric'      => 'Nomor KK hanya boleh berupa angka.',
            'exact_length' => 'Nomor KK harus tepat 16 digit.',
        ],
        'nama_lengkap' => [
            'required'   => 'Nama lengkap wajib diisi.',
            'min_length' => 'Nama lengkap minimal 3 karakter.',
            'max_length' => 'Nama lengkap maksimal 150 karakter.',
        ],
        'tempat_lahir' => [
            'required'   => 'Tempat lahir wajib diisi.',
            'min_length' => 'Tempat lahir minimal 3 karakter.',
        ],
        'tanggal_lahir' => [
            'required'   => 'Tanggal lahir wajib diisi.',
            'valid_date' => 'Format tanggal lahir tidak valid (Y-m-d).',
        ],
        'jenis_kelamin' => [
            'required' => 'Jenis kelamin wajib dipilih.',
            'in_list'  => 'Pilihan jenis kelamin tidak valid.',
        ],
        'anak_ke' => [
            'required'              => 'Anak ke-berapa wajib diisi.',
            'integer'               => 'Anak ke-berapa harus berupa angka.',
            'greater_than_equal_to' => 'Anak ke-berapa minimal 1.',
        ],
        'jumlah_saudara' => [
            'required'              => 'Jumlah saudara wajib diisi.',
            'integer'               => 'Jumlah saudara harus berupa angka.',
            'greater_than_equal_to' => 'Jumlah saudara tidak boleh negatif.',
        ],
        'no_hp' => [
            'required'   => 'Nomor HP wajib diisi.',
            'min_length' => 'Nomor HP minimal 10 digit.',
            'max_length' => 'Nomor HP maksimal 15 digit.',
        ],
        'alamat_desa' => [
            'required' => 'Desa/Dusun wajib diisi.',
        ],
        'alamat_kecamatan' => [
            'required' => 'Kecamatan wajib diisi.',
        ],
        'alamat_kabupaten' => [
            'required' => 'Kabupaten/Kota wajib diisi.',
        ],
    ];

    public array $siswaSekolah_errors = [
        'nama_sekolah' => [
            'required'   => 'Nama sekolah asal wajib diisi.',
            'min_length' => 'Nama sekolah minimal 3 karakter.',
        ],
        'alamat_sekolah' => [
            'required'   => 'Alamat sekolah asal wajib diisi.',
            'min_length' => 'Alamat sekolah minimal 5 karakter.',
        ],
    ];

    public array $siswaOrangTua_errors = [
        'nama_ayah' => [
            'required'   => 'Nama ayah wajib diisi.',
            'min_length' => 'Nama ayah minimal 3 karakter.',
        ],
        'pekerjaan_ayah' => [
            'required' => 'Pekerjaan ayah wajib diisi.',
        ],
        'nama_ibu' => [
            'required'   => 'Nama ibu wajib diisi.',
            'min_length' => 'Nama ibu minimal 3 karakter.',
        ],
        'pekerjaan_ibu' => [
            'required' => 'Pekerjaan ibu wajib diisi.',
        ],
    ];

    public array $pendaftaranSiswa = [
        'fullName'         => 'required|min_length[3]|max_length[100]',
        'email'            => 'required|valid_email|is_unique[users_siswa.email]|is_unique[auth_identities.secret]',
        'username'         => 'required|min_length[3]|max_length[30]|is_unique[users_siswa.username]|regex_match[/\A[a-zA-Z0-9\.]+\z/]',
        'password'         => 'required|min_length[8]|regex_match[/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])/]',
        'password_confirm' => 'required|matches[password]',
        'terms'            => 'required',
    ];

    public array $pendaftaranSiswa_errors = [
        'fullName' => [
            'required'   => 'Nama lengkap wajib diisi.',
            'min_length' => 'Nama lengkap minimal 3 karakter.',
        ],
        'email' => [
            'required'    => 'Alamat email wajib diisi.',
            'valid_email' => 'Format alamat email tidak valid.',
            'is_unique'   => 'Alamat email sudah terdaftar. Silakan gunakan email lain.',
        ],
        'username' => [
            'required'     => 'Nama pengguna (username) wajib diisi.',
            'min_length'   => 'Username minimal terdiri dari 3 karakter.',
            'is_unique'    => 'Username sudah digunakan. Silakan pilih username lain.',
            'regex_match'  => 'Username hanya boleh berisi huruf, angka, dan titik.',
        ],
        'password' => [
            'required'     => 'Kata sandi wajib diisi.',
            'min_length'   => 'Kata sandi minimal harus terdiri dari 8 karakter.',
            'regex_match'  => 'Kata sandi harus mengandung kombinasi huruf besar, huruf kecil, angka, dan simbol.',
        ],
        'password_confirm' => [
            'required' => 'Konfirmasi kata sandi wajib diisi.',
            'matches'  => 'Konfirmasi kata sandi tidak cocok dengan kata sandi yang dimasukkan.',
        ],
        'terms' => [
            'required' => 'Anda harus menyetujui Ketentuan Layanan dan Kebijakan Privasi.',
        ],
    ];

    public array $staffStore = [
        'username'         => 'required|min_length[3]|max_length[30]|regex_match[/^[a-zA-Z0-9_]+$/]|is_unique[users.username]',
        'email'            => 'required|valid_email|is_unique[auth_identities.secret]',
        'password'         => 'required|min_length[6]',
        'password_confirm' => 'required|matches[password]',
        'role'             => 'required|in_list[admin,panitia]',
    ];

    public array $staffStore_errors = [
        'username' => [
            'regex_match' => 'Username hanya boleh mengandung huruf, angka, dan underscore.',
            'is_unique'   => 'Username sudah digunakan oleh akun lain.',
        ],
        'email' => [
            'is_unique' => 'Email sudah terdaftar di sistem.',
        ],
    ];

    public array $staffEdit = [
        'username' => 'required|min_length[3]|max_length[30]|regex_match[/^[a-zA-Z0-9_]+$/]',
        'email'    => 'required|valid_email',
        'role'     => 'required|in_list[admin,panitia]',
    ];

    public array $staffEdit_errors = [
        'username' => [
            'regex_match' => 'Username hanya boleh mengandung huruf, angka, dan underscore.',
        ],
    ];

    public array $staffPassword = [
        'password'         => 'required|min_length[6]',
        'password_confirm' => 'required|matches[password]',
    ];

    public array $pengumuman = [
        'judul'  => 'required|min_length[3]|max_length[255]',
        'konten' => 'required',
    ];
}
