<?php

namespace Config;

use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    private static array $authInstances = [];

    /**
     * Override Shield's auth service to dynamically switch User Providers
     * based on route context (Siswa vs Admin/Panitia).
     */
    public static function auth(bool $getShared = true): \CodeIgniter\Shield\Auth
    {
        $request = service('request');
        $uri = $request->getUri()->getPath();

        // Identify if request path or submitted payload belongs to student context
        $isSiswa = str_contains($uri, 'siswa') || str_contains($uri, 'api/siswa') || str_contains($uri, 'register');

        if ($request->getPost('role') === 'student') {
            $isSiswa = true;
        }

        $cacheKey = $isSiswa ? 'siswa' : 'admin';

        if ($getShared && isset(static::$authInstances[$cacheKey])) {
            return static::$authInstances[$cacheKey];
        }

        /** @var \CodeIgniter\Shield\Config\Auth $config */
        $config = config('Auth');
        $customConfig = clone $config;

        if ($isSiswa) {
            $customConfig->userProvider = \App\Models\SiswaUserModel::class;
        } else {
            $customConfig->userProvider = \App\Models\UserModel::class;
        }

        $instance = new \CodeIgniter\Shield\Auth($customConfig);

        if ($getShared) {
            static::$authInstances[$cacheKey] = $instance;
        }

        return $instance;
    }
}
