<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// -----------------------------------------------------------------------
// Rute Publik (Landing Page & Autentikasi)
// -----------------------------------------------------------------------
$routes->get('/', 'Home::index');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::loginAction');       // POST: Proses login via Shield
$routes->get('/logout', 'Auth::logout');            // Logout via Shield
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::registerAction');
$routes->post('/check-email', 'Auth::checkEmail');
$routes->post('/check-username', 'Auth::checkUsername');
$routes->get('/forgot-password', 'Auth::forgotPassword');

// -----------------------------------------------------------------------
// Rute Dashboard Calon Siswa — Dilindungi filter Shield (harus login)
// -----------------------------------------------------------------------
$routes->group('siswa', ['filter' => 'session'], static function ($routes) {
    $routes->get('dashboard', 'Siswa\Dashboard::index');
    $routes->post('finalize', 'Siswa\Dashboard::finalize');
    $routes->get('cetak-formulir', 'Siswa\Dashboard::cetakFormulir');

    // Rute Langkah 1: Biodata Diri
    $routes->get('biodata', 'Siswa\Dashboard::biodata');
    $routes->post('biodata', 'Siswa\Dashboard::saveBiodata');

    // Rute Langkah 2: Asal Sekolah
    $routes->get('sekolah', 'Siswa\Dashboard::sekolah');
    $routes->post('sekolah', 'Siswa\Dashboard::saveSekolah');

    // Rute Langkah 3: Data Orang Tua
    $routes->get('orang-tua', 'Siswa\Dashboard::orangTua');
    $routes->post('orang-tua', 'Siswa\Dashboard::saveOrangTua');

    // Rute Langkah 4: Kelengkapan Berkas
    $routes->get('berkas', 'Siswa\Dashboard::berkas');
    $routes->post('berkas', 'Siswa\Dashboard::saveBerkas');
});

// -----------------------------------------------------------------------
// Rute Dashboard Panitia / Admin — Dilindungi filter Shield khusus Admin
// -----------------------------------------------------------------------
$routes->group('admin', ['filter' => 'group:admin,panitia'], static function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('pendaftar', 'Admin\Dashboard::pendaftar');
    $routes->post('verify/(:num)', 'Admin\Dashboard::verify/$1');
    $routes->post('edit-student/(:num)', 'Admin\Dashboard::editStudent/$1');
    $routes->get('export/csv', 'Admin\Dashboard::exportCsv');
    $routes->get('cetak-data', 'Admin\Dashboard::cetakData');
    $routes->get('cetak-formulir/(:num)', 'Admin\Dashboard::cetakFormulir/$1');
    $routes->get('arsip/(:num)', 'Admin\Dashboard::cetakArsip/$1');
    $routes->post('delete-student/(:num)', 'Admin\Dashboard::deleteStudent/$1');
    $routes->post('bulk-delete-students', 'Admin\Dashboard::bulkDeleteStudents');
    $routes->post('add-student', 'Admin\Dashboard::addStudent');

    // Rute Khusus Manajemen User (Hanya Admin & Superadmin)
    $routes->group('users', ['filter' => 'group:admin'], static function ($routes) {
        $routes->get('/', 'Admin\Users::index');
        $routes->post('store', 'Admin\Users::store');
        $routes->post('edit/(:num)', 'Admin\Users::edit/$1');
        $routes->post('change-password/(:num)', 'Admin\Users::changePassword/$1');
        $routes->post('delete/(:num)', 'Admin\Users::delete/$1');
    });

    // Rute Khusus Pengaturan Frontend (Hanya Admin & Superadmin)
    $routes->group('settings', ['filter' => 'group:admin'], static function ($routes) {
        $routes->get('/', 'Admin\Settings::index');
        $routes->post('save', 'Admin\Settings::save');
        
        // Rute Manajemen Pengumuman
        $routes->post('announcement/add', 'Admin\Settings::addAnnouncement');
        $routes->post('announcement/delete/(:num)', 'Admin\Settings::deleteAnnouncement/$1');
    });
});

// -----------------------------------------------------------------------
// Rute API (JWT Authentication & Testing)
// -----------------------------------------------------------------------
$routes->group('api', static function ($routes) {
    $routes->post('admin/login', 'Api\Admin\AuthController::loginAction');
    $routes->post('siswa/login', 'Api\Siswa\AuthController::loginAction');

    // Endpoint testing terproteksi filter JWT bawaan Shield
    $routes->get('admin/profile', 'Api\Admin\AuthController::profile', ['filter' => 'jwt']);
    $routes->get('siswa/profile', 'Api\Siswa\AuthController::profile', ['filter' => 'jwt']);
});

// -----------------------------------------------------------------------
// Rute Shield bawaan (magic link, 2FA, token, dsb.)
// -----------------------------------------------------------------------
service('auth')->routes($routes);
