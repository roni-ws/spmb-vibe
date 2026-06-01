<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\PengumumanModel;
use App\Models\SettingsModel;
use Config\Database;

/**
 * @internal
 */
final class AnnouncementTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate     = false; // We will run migrations manually for both namespaces
    protected $refresh     = true;
    protected $namespace   = 'App';

    protected function setUp(): void
    {
        parent::setUp();
        
        try {
            // Run CodeIgniter Settings migrations
            $runner = \Config\Services::migrations();
            $runner->setNamespace('CodeIgniter\Settings');
            $runner->latest();
            
            // Run App migrations
            $runner->setNamespace('App');
            $runner->latest();

            $db = Database::connect();
            $db->table('pengumuman')->truncate();
            $db->table('settings')->truncate();
        } catch (\Throwable $e) {
            // Ignore
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        try {
            $db = Database::connect();
            $db->table('pengumuman')->truncate();
            $db->table('settings')->truncate();
        } catch (\Throwable $e) {
            // Ignore
        }
    }

    /**
     * Test adding and listing announcements via PengumumanModel
     */
    public function testAddAndListAnnouncements(): void
    {
        try {
            $db = Database::connect();
            $db->initialize();
        } catch (\Throwable $e) {
            $this->markTestSkipped('Koneksi database tidak tersedia untuk pengujian ini.');
            return;
        }

        $model = new PengumumanModel();

        // 1. Insert announcement 1 (older)
        $model->insert([
            'judul'      => 'Pengumuman Penting Pertama',
            'konten'     => 'Isi dari pengumuman penting yang pertama.',
            'created_at' => date('Y-m-d H:i:s', time() - 3600), // 1 hour ago
        ]);

        // 2. Insert announcement 2 (newer)
        $model->insert([
            'judul'      => 'Pengumuman Penting Kedua',
            'konten'     => 'Isi dari pengumuman penting yang kedua.',
            'created_at' => date('Y-m-d H:i:s', time()), // now
        ]);

        // Retrieve and check ordering (created_at DESC)
        $announcements = $model->orderBy('created_at', 'DESC')->findAll();

        $this->assertCount(2, $announcements);
        // The most recent one should be first
        $this->assertSame('Pengumuman Penting Kedua', $announcements[0]['judul']);
        $this->assertSame('Pengumuman Penting Pertama', $announcements[1]['judul']);
    }

    /**
     * Test deleting announcement
     */
    public function testDeleteAnnouncement(): void
    {
        try {
            $db = Database::connect();
            $db->initialize();
        } catch (\Throwable $e) {
            $this->markTestSkipped('Koneksi database tidak tersedia untuk pengujian ini.');
            return;
        }

        $model = new PengumumanModel();

        $id = $model->insert([
            'judul'  => 'Pengumuman Mau Dihapus',
            'konten' => 'Isi pengumuman yang akan segera dihapus.',
        ]);

        $this->assertNotNull($model->find($id));

        // Delete
        $model->delete($id);

        $this->assertNull($model->find($id));
    }

    /**
     * Test WhatsApp Link configurations in SettingsModel
     */
    public function testWhatsAppLinkSetting(): void
    {
        try {
            $db = Database::connect();
            $db->initialize();
        } catch (\Throwable $e) {
            $this->markTestSkipped('Koneksi database tidak tersedia untuk pengujian ini.');
            return;
        }

        // Set value
        SettingsModel::setSetting('whatsapp_link', 'https://chat.whatsapp.com/SPMBVibeTestGroup');

        $updatedVal = SettingsModel::getSetting('whatsapp_link');
        $this->assertSame('https://chat.whatsapp.com/SPMBVibeTestGroup', $updatedVal);
    }
}
