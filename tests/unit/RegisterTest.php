<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class RegisterTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    // Matikan migrasi otomatis trait agar kita bisa jalankan migrasi multi-namespace secara manual
    protected $migrate     = false;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    protected function setUp(): void
    {
        parent::setUp();

        // Jalankan migrasi Shield, Settings, & App secara manual dengan me-reset (regress) terlebih dahulu
        $migrations = \Config\Services::migrations();
        try {
            // Reset schema
            $migrations->setNamespace('App')->regress();
            $migrations->setNamespace('CodeIgniter\Shield')->regress();
            $migrations->setNamespace('CodeIgniter\Settings')->regress();
            
            // Jalankan migrasi ulang dari nol
            $migrations->setNamespace('CodeIgniter\Shield')->latest();
            $migrations->setNamespace('CodeIgniter\Settings')->latest();
            $migrations->setNamespace('App')->latest();
        } catch (\Throwable $e) {
            // Hiraukan
        }

        // Clean up any existing test user to start fresh
        $db = \Config\Database::connect();
        try {
            $db->table('users')->where('username', 'teststuden')->delete();
            $db->table('kelengkapan_administrasi')->truncate();
            
            $db->query('SET FOREIGN_KEY_CHECKS = 0;');
            $db->table('calon_siswa')->truncate();
            $db->query('SET FOREIGN_KEY_CHECKS = 1;');
        } catch (\Throwable $e) {
            // Hiraukan
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // Clean up after test
        $db = \Config\Database::connect();
        try {
            $db->table('users')->where('username', 'teststuden')->delete();
            $db->table('kelengkapan_administrasi')->truncate();
            
            $db->query('SET FOREIGN_KEY_CHECKS = 0;');
            $db->table('calon_siswa')->truncate();
            $db->query('SET FOREIGN_KEY_CHECKS = 1;');
        } catch (\Throwable $e) {
            // Hiraukan
        }
    }

    public function testRegisterActionSuccess(): void
    {
        $response = $this->post('register', [
            'fullName'         => 'Test Student',
            'email'            => 'test.student@example.com',
            'username'         => 'teststuden',
            'password'         => 'securePassword123!',
            'password_confirm' => 'securePassword123!',
            'terms'            => 'on'
        ]);

        // Check redirect
        $response->assertRedirectTo('siswa/dashboard');

        // Verify that the user was successfully saved in the database
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('username', 'teststuden')->get()->getRowArray();

        $this->assertNotNull($user, 'User should be saved in users table');
        $this->assertSame('test.student@example.com', $user['email']);
        $this->assertSame('student', $user['role']);
        $this->assertSame(1, (int)$user['active']);

        // Verify that calon_siswa was initialized
        $siswa = $db->table('calon_siswa')->where('nisn', $user['username'])->get()->getRowArray();
        $this->assertNotNull($siswa, 'calon_siswa should be initialized');
        $this->assertSame('Test Student', $siswa['nama_lengkap']);

        // Verify that kelengkapan_administrasi was initialized
        $berkas = $db->table('kelengkapan_administrasi')->where('siswa_id', $siswa['id'])->get()->getRowArray();
        $this->assertNotNull($berkas, 'kelengkapan_administrasi should be initialized');
        $this->assertSame('Belum Verifikasi', $berkas['status_verifikasi']);
    }

    public function testRegisterActionValidationFails(): void
    {
        $response = $this->post('register', [
            'fullName'         => '',
            'email'            => 'invalid-email',
            'username'         => 'ts',
            'password'         => 'short',
            'password_confirm' => 'mismatch',
            'terms'            => 'on'
        ]);

        // Check redirect back
        $response->assertStatus(302);

        // Check that no user was created
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('username', 'ts')->get()->getRowArray();
        $this->assertNull($user, 'User with invalid data should not be saved');
    }
}

