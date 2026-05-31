<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class RegisterTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        // Clean up any existing test user to start fresh
        $db = \Config\Database::connect();
        $db->table('users')->where('username', 'teststudent')->delete();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        // Clean up after test
        $db = \Config\Database::connect();
        $db->table('users')->where('username', 'teststudent')->delete();
    }

    public function testRegisterActionSuccess(): void
    {
        $response = $this->post('register', [
            'fullName' => 'Test Student',
            'email'    => 'test.student@example.com',
            'username' => 'teststudent',
            'password' => 'securePassword123',
            'terms'    => 'on'
        ]);

        // Check redirect
        $response->assertRedirectTo('siswa/dashboard');

        // Verify that the user was successfully saved in the database
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('username', 'teststudent')->get()->getRowArray();

        $this->assertNotNull($user, 'User should be saved in users table');
        $this->assertSame('test.student@example.com', $user['email']);
        $this->assertSame('student', $user['role']);
        $this->assertSame(1, (int)$user['active']);

        // Verify that biodata was initialized
        $biodata = $db->table('biodata')->where('user_id', $user['id'])->get()->getRowArray();
        $this->assertNotNull($biodata, 'Biodata should be initialized');
        $this->assertSame('Test Student', $biodata['nama_lengkap']);

        // Verify that berkas was initialized
        $berkas = $db->table('berkas')->where('user_id', $user['id'])->get()->getRowArray();
        $this->assertNotNull($berkas, 'Berkas should be initialized');
        $this->assertSame('Pending', $berkas['status_verifikasi']);
    }

    public function testRegisterActionValidationFails(): void
    {
        $response = $this->post('register', [
            'fullName' => '',
            'email'    => 'invalid-email',
            'username' => 'ts',
            'password' => 'short',
            'terms'    => 'on'
        ]);

        // Check redirect back
        $response->assertStatus(302);

        // Check that no user was created
        $db = \Config\Database::connect();
        $user = $db->table('users')->where('username', 'ts')->get()->getRowArray();
        $this->assertNull($user, 'User with invalid data should not be saved');
    }
}
