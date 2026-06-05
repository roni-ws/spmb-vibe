<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class SpmbSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        
        // Initialize Faker with Indonesian locale for hyper-realistic data
        $faker = Factory::create('id_ID');

        // 1. Temporarily disable foreign keys to safely truncate all tables
        $db->query('SET FOREIGN_KEY_CHECKS = 0;');
        
        $db->table('status_pendaftaran')->truncate();
        $db->table('status_verifikasi')->truncate();
        $db->table('kelengkapan_administrasi')->truncate();
        $db->table('data_orang_tua')->truncate();
        $db->table('calon_siswa')->truncate();
        $db->table('master_sekolah')->truncate();
        
        // Clean Shield tables (including both users and users_siswa)
        $db->table('auth_identities')->truncate();
        $db->table('auth_groups_users')->truncate();
        $db->table('auth_permissions_users')->truncate();
        $db->table('auth_logins')->truncate();
        $db->table('users')->truncate();
        $db->table('users_siswa')->truncate();
        
        $db->query('SET FOREIGN_KEY_CHECKS = 1;');

        // Reset AUTO_INCREMENT on users_siswa to our secure range
        $db->query("ALTER TABLE users_siswa AUTO_INCREMENT = 1000000");

        // 1b. Seed Verification Status Master Table
        $db->table('status_verifikasi')->insertBatch([
            ['id' => 1, 'nama_status' => 'Belum Verifikasi'],
            ['id' => 2, 'nama_status' => 'Revisi'],
            ['id' => 3, 'nama_status' => 'Valid'],
            ['id' => 4, 'nama_status' => 'Tidak Valid'],
        ]);

        // 2. Create main Administrator account
        $adminPasswordHash = password_hash('securePassword123', PASSWORD_DEFAULT);
        
        $db->table('users')->insert([
            'username'   => 'admin',
            'email'      => 'admin@unggulbangsa.sch.id',
            'role'       => 'admin',
            'active'     => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $adminId = $db->insertID();

        $db->table('auth_identities')->insert([
            'user_id'    => $adminId,
            'type'       => 'email_password',
            'secret'     => 'admin@unggulbangsa.sch.id',
            'secret2'    => $adminPasswordHash,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $db->table('auth_groups_users')->insert([
            'user_id'    => $adminId,
            'group'      => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // 3. Create realistic middle school origins (Master Sekolah)
        $sekolahIds = [];
        for ($s = 0; $s < 10; $s++) {
            $namaSekolah = "SMP Negeri " . ($s + 1) . " " . $faker->city;
            $db->table('master_sekolah')->insert([
                'nama_sekolah'   => $namaSekolah,
                'alamat_sekolah' => $faker->streetAddress . ", " . $faker->city,
            ]);
            $sekolahIds[] = $db->insertID();
        }

        // Placeholder for initial registration
        $db->table('master_sekolah')->insert([
            'nama_sekolah'   => 'Belum Diisi',
            'alamat_sekolah' => 'Belum Diisi',
        ]);
        $placeholderSekolahId = $db->insertID();

        // Parents job pools
        $pekerjaanAyahList = ['PNS', 'Karyawan Swasta', 'Wiraswasta', 'Petani', 'Buruh', 'TNI/Polri', 'Wirausaha'];
        $pekerjaanIbuList  = ['PNS', 'Karyawan Swasta', 'Wiraswasta', 'Petani', 'Ibu Rumah Tangga', 'Guru'];

        // Indonesian Subdistricts
        $kecamatanList = ['Karanggeneng', 'Lamongan', 'Babat', 'Paciran', 'Solokuro', 'Mantup', 'Sekaran', 'Kembangbahu', 'Sukodadi', 'Turi'];

        // 4. Generate 20 Calon Siswa Baru using Faker
        $siswaPasswordHash = password_hash('siswa123', PASSWORD_DEFAULT);

        for ($i = 0; $i < 20; $i++) {
            // Generate valid, realistic NISN (10 digits)
            $nisn = $faker->numerify('##########');
            // NIK (16 digits)
            $nik  = $faker->numerify('35################');
            $noKk = $faker->numerify('35################');
            
            $gender = $faker->randomElement(['L', 'P']);
            $nama = $faker->name($gender === 'L' ? 'male' : 'female');
            $email = strtolower(str_replace([' ', '.'], '', $nama)) . $i . '@example.com';
            
            $tempatLahir = $faker->city;
            // Approx 14-16 years old
            $tanggalLahir = $faker->dateTimeBetween('-16 years', '-14 years')->format('Y-m-d');

            // a. Save user account to users_siswa table
            $db->table('users_siswa')->insert([
                'username'   => $nisn,
                'email'      => $email,
                'role'       => 'student',
                'active'     => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $userId = $db->insertID();

            // Login identities in Shield table
            $db->table('auth_identities')->insert([
                'user_id'    => $userId,
                'type'       => 'email_password',
                'secret'     => $email,
                'secret2'    => $siswaPasswordHash,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // Add user to 'user' (student) group
            $db->table('auth_groups_users')->insert([
                'user_id'    => $userId,
                'group'      => 'user',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // b. Insert candidate profile details
            $now = date('Y-m-d H:i:s');
            $db->table('calon_siswa')->insert([
                'nisn'             => $nisn,
                'nik'              => $nik,
                'nama_lengkap'     => $nama,
                'no_kk'            => $noKk,
                'no_hp'            => $faker->phoneNumber,
                'tempat_lahir'     => $tempatLahir,
                'tanggal_lahir'    => $tanggalLahir,
                'jenis_kelamin'    => $gender,
                'sekolah_id'       => $faker->randomElement($sekolahIds),
                'anak_ke'          => $faker->numberBetween(1, 4),
                'jumlah_saudara'   => $faker->numberBetween(0, 5),
                'alamat_desa'      => $faker->streetAddress,
                'alamat_kecamatan' => $faker->randomElement($kecamatanList),
                'alamat_kabupaten' => $faker->city,
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
            $siswaId = $db->insertID();
            $db->table('calon_siswa')->where('id', $siswaId)->update([
                'no_registrasi' => 'REG-' . date('Y') . '-' . $siswaId,
            ]);

            // c. Insert parents data
            $db->table('data_orang_tua')->insert([
                'siswa_id'       => $siswaId,
                'nama_ayah'      => $faker->name('male'),
                'pekerjaan_ayah' => $faker->randomElement($pekerjaanAyahList),
                'nama_ibu'       => $faker->name('female'),
                'pekerjaan_ibu'  => $faker->randomElement($pekerjaanIbuList),
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);

            // d. Insert registration documents
            $statusBerkas = $faker->randomElement(['Belum Verifikasi', 'Valid', 'Revisi']);
            $isFinalized  = ($statusBerkas === 'Valid' || $faker->boolean(70)) ? 1 : 0;
            $catatan      = $statusBerkas === 'Revisi' ? 'Harap unggah ulang berkas Kartu Keluarga Anda.' : null;

            $db->table('kelengkapan_administrasi')->insert([
                'siswa_id'           => $siswaId,
                'fc_ijazah_skl'      => 'ijazah_' . $siswaId . '.pdf',
                'fc_kk'              => 'kk_' . $siswaId . '.pdf',
                'pas_foto'           => 'foto_' . $siswaId . '.jpg',
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ]);

            // e. Insert application status
            $statusMap = [
                'Belum Verifikasi' => 1,
                'Revisi'           => 2,
                'Valid'            => 3,
            ];
            $statusBerkasId = $statusMap[$statusBerkas];

            $db->table('status_pendaftaran')->insert([
                'siswa_id'             => $siswaId,
                'status_verifikasi_id' => $statusBerkasId,
                'is_finalized'         => $isFinalized,
                'catatan_verifikasi'   => $catatan,
                'created_at'           => date('Y-m-d H:i:s'),
                'updated_at'           => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
