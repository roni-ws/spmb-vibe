<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// -----------------------------------------------------------------------
// Rute Publik (Landing Page & Autentikasi)
// -----------------------------------------------------------------------
$routes->get('/', 'Home::index');
$routes->get('/login', 'Auth\Login::index');
$routes->post('/login', 'Auth\Login::loginAction');
$routes->get('/logout', 'Auth\Login::logout');
$routes->get('/register', 'Auth\Register::index');
$routes->post('/register', 'Auth\Register::store');
$routes->post('/check-email', 'Auth\Register::checkEmail');
$routes->post('/check-username', 'Auth\Register::checkUsername');
$routes->get('/forgot-password', 'Auth\Login::forgotPassword');

// -----------------------------------------------------------------------
// Rute Dashboard Calon Siswa — Dilindungi filter Shield (harus login)
// -----------------------------------------------------------------------
$routes->group('siswa', ['filter' => 'session'], static function ($routes) {
    $routes->get('dashboard', 'Siswa\Dashboard::index');
    $routes->post('finalize', 'Siswa\Dashboard::finalize');
    $routes->get('cetak-formulir', 'Siswa\Dashboard::printFormulir');

    // Rute Langkah 1: Biodata Diri
    $routes->get('biodata', 'Siswa\IsiFormulir::biodata');
    $routes->post('biodata', 'Siswa\IsiFormulir::updateBiodata');

    // Rute Langkah 2: Asal Sekolah
    $routes->get('sekolah', 'Siswa\IsiFormulir::sekolah');
    $routes->post('sekolah', 'Siswa\IsiFormulir::updateSekolah');

    // Rute Langkah 3: Data Orang Tua
    $routes->get('orang-tua', 'Siswa\IsiFormulir::orangTua');
    $routes->post('orang-tua', 'Siswa\IsiFormulir::updateOrangTua');

    // Rute Langkah 4: Kelengkapan Berkas
    $routes->get('berkas', 'Siswa\IsiFormulir::berkas');
    $routes->post('berkas', 'Siswa\IsiFormulir::updateBerkas');

    // Rute Notifikasi
    $routes->get('notifications', 'Siswa\Notifications::index');
    $routes->post('notifications/mark-as-read', 'Siswa\Notifications::markAsRead');

    // Rute Pengaturan Profil
    $routes->get('profile-settings', 'Siswa\ProfileSettings::index');
    $routes->post('profile-settings', 'Siswa\ProfileSettings::update');
});

// -----------------------------------------------------------------------
// Rute Dashboard Panitia / Admin — Dilindungi filter Shield khusus Admin
// -----------------------------------------------------------------------
$routes->group('admin', ['filter' => 'group:admin,panitia'], static function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('pendaftar', 'Admin\DataSiswa::index');
    $routes->post('verify/(:num)', 'Admin\DataSiswa::verify/$1');
    $routes->post('edit-student/(:num)', 'Admin\DataSiswa::edit/$1');
    $routes->get('export/csv', 'Admin\Export::csv');
    $routes->get('cetak-data', 'Admin\Export::printData');
    $routes->get('cetak-formulir/(:num)', 'Admin\Export::printFormulir/$1');
    $routes->get('arsip/(:num)', 'Admin\Export::printArsip/$1');
    $routes->post('delete-student/(:num)', 'Admin\DataSiswa::delete/$1');
    $routes->post('bulk-delete-students', 'Admin\DataSiswa::bulkDelete');
    $routes->post('add-student', 'Admin\DataSiswa::add');
    $routes->get('grid-data', 'Admin\DataSiswa::gridData');

    // Rute Khusus Manajemen User (Hanya Admin & Superadmin)
    $routes->group('users', ['filter' => 'group:admin'], static function ($routes) {
        $routes->get('/', 'Admin\Users::index');
        $routes->post('store', 'Admin\Users::store');
        $routes->post('edit/(:num)', 'Admin\Users::edit/$1');
        $routes->post('change-password/(:num)', 'Admin\Users::changePassword/$1');
        $routes->post('delete/(:num)', 'Admin\Users::delete/$1');
    });

    // Rute Notifikasi Admin
    $routes->get('notifications', 'Admin\Notifications::index');
    $routes->post('notifications/mark-as-read', 'Admin\Notifications::markAsRead');

    // Rute Pengaturan Profil Admin
    $routes->get('profile-settings', 'Admin\ProfileSettings::index');
    $routes->post('profile-settings', 'Admin\ProfileSettings::update');

    // Rute Khusus Pengaturan Frontend (Hanya Admin & Superadmin)
    $routes->group('settings', ['filter' => 'group:admin'], static function ($routes) {
        $routes->get('/', 'Admin\Settings::index');
        $routes->post('save', 'Admin\Settings::save');
    });

    // Rute Manajemen Pengumuman & WhatsApp (Hanya Admin)
    $routes->group('pengumuman', ['filter' => 'group:admin'], static function ($routes) {
        $routes->get('/', 'Admin\Pengumuman::index');
        $routes->post('add', 'Admin\Pengumuman::add');
        $routes->post('delete/(:num)', 'Admin\Pengumuman::delete/$1');
        $routes->post('save-whatsapp', 'Admin\Pengumuman::saveWhatsapp');
    });
});

// -----------------------------------------------------------------------
// Rute API (JWT Authentication & Testing)
// -----------------------------------------------------------------------
$routes->group('api', static function ($routes) {
    $routes->post('admin/login', 'Api\Admin\AuthController::loginAction');
    $routes->post('siswa/login', 'Api\Siswa\AuthController::loginAction');

    // Endpoint API Internal Terbuka (Public)
    $routes->get('sekolah/search', 'Api\Sekolah::search');

    // Endpoint testing terproteksi filter JWT bawaan Shield
    $routes->get('admin/profile', 'Api\Admin\AuthController::profile', ['filter' => 'jwt']);
    $routes->get('siswa/profile', 'Api\Siswa\AuthController::profile', ['filter' => 'jwt']);
});

// -----------------------------------------------------------------------
// Rute Shield bawaan (magic link, 2FA, token, dsb.)
// -----------------------------------------------------------------------
service('auth')->routes($routes);
