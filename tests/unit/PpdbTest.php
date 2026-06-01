<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Controllers\Admin\Ppdb;
use Config\Database;

/**
 * @internal
 */
final class PpdbTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    // Otomatis jalankan migrasi di database pengujian sebelum test berjalan
    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    protected function setUp(): void
    {
        parent::setUp();
        // Bersihkan data uji jika koneksi database aktif
        try {
            $db = Database::connect();
            $db->table('kelengkapan_administrasi')->truncate();
            $db->table('data_orang_tua')->truncate();
            
            // Matikan pengecekan foreign key sementara untuk mengosongkan tabel induk safely
            $db->query('SET FOREIGN_KEY_CHECKS = 0;');
            $db->table('calon_siswa')->truncate();
            $db->table('master_sekolah')->truncate();
            $db->query('SET FOREIGN_KEY_CHECKS = 1;');
        } catch (\Throwable $e) {
            // Hiraukan jika database belum online selama pengecekan awal
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        try {
            $db = Database::connect();
            $db->table('kelengkapan_administrasi')->truncate();
            $db->table('data_orang_tua')->truncate();
            
            $db->query('SET FOREIGN_KEY_CHECKS = 0;');
            $db->table('calon_siswa')->truncate();
            $db->table('master_sekolah')->truncate();
            $db->query('SET FOREIGN_KEY_CHECKS = 1;');
        } catch (\Throwable $e) {
            // Hiraukan
        }
    }

    /**
     * Menguji validasi NISN dan NIK (Format dan Keunikan)
     */
    public function testValidationRules(): void
    {
        $ppdb = new Ppdb();

        // Skenario 1: Data Valid
        $validData = [
            'nik'  => '1234567890123456',
            'nisn' => '1234567890',
        ];
        $errors = $ppdb->validateSiswa($validData);
        $this->assertEmpty($errors, 'Data valid tidak boleh memicu error');

        // Skenario 2: Format NIK salah (kurang dari 16 digit atau mengandung huruf)
        $invalidNikData = [
            'nik'  => '12345ABC90123456',
            'nisn' => '1234567890',
        ];
        $errors = $ppdb->validateSiswa($invalidNikData);
        $this->assertArrayHasKey('nik', $errors);
        $this->assertSame('NIK harus persis 16 digit angka.', $errors['nik']);

        // Skenario 3: Format NISN salah (kurang dari 10 digit)
        $invalidNisnData = [
            'nik'  => '1234567890123456',
            'nisn' => '12345',
        ];
        $errors = $ppdb->validateSiswa($invalidNisnData);
        $this->assertArrayHasKey('nisn', $errors);
        $this->assertSame('NISN harus persis 10 digit angka.', $errors['nisn']);
    }

    /**
     * Menguji registerSiswa dengan alur sukses (Insert ke 4 tabel dan Join Check)
     */
    public function testRegisterSiswaSuccess(): void
    {
        // Lewati test jika database tidak terhubung
        try {
            $db = Database::connect();
            $db->initialize();
        } catch (\Throwable $e) {
            $this->markTestSkipped('Koneksi database tidak tersedia untuk pengujian ini.');
            return;
        }

        $ppdb = new Ppdb();

        $data = [
            'nisn'             => '9988776655',
            'nik'              => '9988776655443322',
            'nama_lengkap'     => 'Budi Siswanto',
            'no_kk'            => '3201020304050607',
            'tempat_lahir'     => 'Lamongan',
            'tanggal_lahir'    => '2008-05-12',
            'jenis_kelamin'    => 'L',
            'asal_sekolah'     => 'SMP Negeri 1 Karanggeneng',
            'alamat_sekolah'   => 'Jl. Raya Karanggeneng No. 1',
            'anak_ke'          => 2,
            'jumlah_saudara'   => 3,
            'alamat_desa'      => 'Karanggeneng',
            'alamat_kecamatan' => 'Karanggeneng',
            'alamat_kabupaten' => 'Lamongan',
            'nama_ayah'        => 'Ahmad',
            'pekerjaan_ayah'   => 'Wiraswasta',
            'nama_ibu'         => 'Siti',
            'pekerjaan_ibu'    => 'Ibu Rumah Tangga',
            'fc_ijazah_skl'    => 1,
            'fc_kk'            => 1,
        ];

        $response = $ppdb->registerSiswa($data);
        
        $this->assertTrue($response['status'], 'Pendaftaran harusnya berhasil');
        $this->assertNotNull($response['siswa_id'], 'ID siswa harusnya dikembalikan');

        $siswaId = $response['siswa_id'];

        // Verifikasi dengan JOIN melintasi ke-4 tabel
        $result = $db->table('calon_siswa cs')
            ->select('cs.nama_lengkap, ms.nama_sekolah, ot.nama_ayah, kb.fc_ijazah_skl, kb.status_verifikasi, kb.is_finalized')
            ->join('master_sekolah ms', 'ms.id = cs.sekolah_id')
            ->join('data_orang_tua ot', 'ot.siswa_id = cs.id')
            ->join('kelengkapan_administrasi kb', 'kb.siswa_id = cs.id')
            ->where('cs.id', $siswaId)
            ->get()
            ->getRowArray();

        $this->assertNotNull($result, 'Data hasil JOIN harusnya ditemukan');
        $this->assertSame('Budi Siswanto', $result['nama_lengkap']);
        $this->assertSame('SMP Negeri 1 Karanggeneng', $result['nama_sekolah']);
        $this->assertSame('Ahmad', $result['nama_ayah']);
        $this->assertSame(1, (int)$result['fc_ijazah_skl']);
        $this->assertSame('Belum Verifikasi', $result['status_verifikasi']);
        $this->assertSame(0, (int)$result['is_finalized']);
    }

    /**
     * Menguji skenario kegagalan dan bekerjanya Database Transaction Rollback
     */
    public function testRegisterSiswaRollbackOnFailure(): void
    {
        // Lewati test jika database tidak terhubung
        try {
            $db = Database::connect();
            $db->initialize();
        } catch (\Throwable $e) {
            $this->markTestSkipped('Koneksi database tidak tersedia untuk pengujian ini.');
            return;
        }

        $ppdb = new Ppdb();

        $dataDenganError = [
            'nisn'             => '1122334455',
            'nik'              => '1122334455667788',
            'nama_lengkap'     => 'Anisa Rahmawati',
            'no_kk'            => '3201020304050608',
            'tempat_lahir'     => 'Lamongan',
            'tanggal_lahir'    => '2008-08-20',
            'jenis_kelamin'    => 'P',
            'asal_sekolah'     => 'MTs Unggul Karanggeneng',
            'anak_ke'          => 1,
            'jumlah_saudara'   => 1,
            'alamat_desa'      => 'Sumberwudi',
            'alamat_kecamatan' => 'Karanggeneng',
            'alamat_kabupaten' => 'Lamongan',
        ];

        // Sengaja mengirim nama_ayah yang melebihi VARCHAR(150) untuk memicu error 
        // saat proses INSERT ke data_orang_tua (Tahap kedua), agar memicu rollback 
        // data calon_siswa yang sempat masuk di Tahap pertama.
        $dataDenganError['nama_ayah'] = str_repeat('A', 1000);

        $response = $ppdb->registerSiswa($dataDenganError);

        $this->assertFalse($response['status'], 'Pendaftaran harusnya gagal karena manipulasi error');
        
        // Verifikasi bahwa ROLLBACK bekerja sepenuhnya:
        // Cari apakah ada data sisa (sampah) calon_siswa dengan NISN tadi di tabel calon_siswa
        $siswaSampah = $db->table('calon_siswa')->where('nisn', '1122334455')->get()->getRowArray();
        $this->assertNull($siswaSampah, 'Mekanisme Rollback harus menghapus data calon_siswa yang sempat masuk secara parsial');

        // Cari juga di tabel orang tua & berkas, tidak boleh ada sampah dengan siswa_id manapun
        $totalOrangTua = $db->table('data_orang_tua')->countAllResults();
        $this->assertSame(0, $totalOrangTua, 'Tidak boleh ada record sampah di tabel data_orang_tua');

        $totalBerkas = $db->table('kelengkapan_administrasi')->countAllResults();
        $this->assertSame(0, $totalBerkas, 'Tidak boleh ada record sampah di tabel kelengkapan_administrasi');
    }
}
