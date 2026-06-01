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
$routes->get('/forgot-password', 'Auth::forgotPassword');

// -----------------------------------------------------------------------
// Rute Dashboard Calon Siswa — Dilindungi filter Shield (harus login)
// -----------------------------------------------------------------------
$routes->group('siswa', ['filter' => 'session'], static function ($routes) {
    $routes->get('dashboard', 'Siswa\Dashboard::index');
    $routes->post('finalize', 'Siswa\Dashboard::finalize');
    $routes->get('download-form', 'Siswa\Dashboard::downloadForm');
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
    $routes->get('export/pdf', 'Admin\Dashboard::exportPrint');
    $routes->get('download-form/(:num)', 'Admin\Dashboard::downloadForm/$1');
    $routes->post('delete-student/(:num)', 'Admin\Dashboard::deleteStudent/$1');
    $routes->post('bulk-delete-students', 'Admin\Dashboard::bulkDeleteStudents');

    // Rute Khusus Manajemen User (Hanya Admin & Superadmin)
    $routes->group('users', ['filter' => 'group:admin'], static function ($routes) {
        $routes->get('/', 'Admin\Users::index');
        $routes->post('store', 'Admin\Users::store');
        $routes->post('edit/(:num)', 'Admin\Users::edit/$1');
        $routes->post('change-password/(:num)', 'Admin\Users::changePassword/$1');
        $routes->get('delete/(:num)', 'Admin\Users::delete/$1');
    });

    // Rute Khusus Pengaturan Frontend (Hanya Admin & Superadmin)
    $routes->group('settings', ['filter' => 'group:admin'], static function ($routes) {
        $routes->get('/', 'Admin\Settings::index');
        $routes->post('save', 'Admin\Settings::save');
    });
});

// -----------------------------------------------------------------------
// Rute Shield bawaan (magic link, 2FA, token, dsb.)
// -----------------------------------------------------------------------
service('auth')->routes($routes);
