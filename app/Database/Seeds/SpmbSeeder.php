<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class SpmbSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID'); // Gunakan locale Indonesia agar nama dan alamat realistis
        $db    = \Config\Database::connect();

        // 1. Matikan pemeriksaan foreign key sementara untuk membersihkan tabel secara aman
        $db->query('SET FOREIGN_KEY_CHECKS = 0;');
        
        $db->table('kelengkapan_administrasi')->truncate();
        $db->table('data_orang_tua')->truncate();
        $db->table('calon_siswa')->truncate();
        $db->table('master_sekolah')->truncate();
        
        // Bersihkan data Shield (users & auth tables)
        $db->table('auth_identities')->truncate();
        $db->table('auth_groups_users')->truncate();
        $db->table('auth_permissions_users')->truncate();
        $db->table('auth_logins')->truncate();
        $db->table('users')->truncate();
        
        $db->query('SET FOREIGN_KEY_CHECKS = 1;');

        // 2. Buat Akun Administrator Utama (Panitia)
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

        // 3. Buat Data Master Sekolah Asal
        $sekolahList = [
            'SMP Negeri 1 Karanggeneng',
            'SMP Negeri 2 Karanggeneng',
            'SMP Negeri 1 Lamongan',
            'SMP Negeri 2 Lamongan',
            'MTs Negeri 1 Lamongan',
            'SMP Wahid Hasyim Karanggeneng',
            'MTs Unggul Karanggeneng',
            'SMP Swasta Tunas Bangsa',
        ];

        $sekolahIds = [];
        foreach ($sekolahList as $namaSekolah) {
            $db->table('master_sekolah')->insert([
                'nama_sekolah'   => $namaSekolah,
                'alamat_sekolah' => $faker->address,
            ]);
            $sekolahIds[] = $db->insertID();
        }

        // Sekolah default "Belum Diisi" untuk registrasi awal baru
        $db->table('master_sekolah')->insert([
            'nama_sekolah'   => 'Belum Diisi',
            'alamat_sekolah' => 'Belum Diisi',
        ]);
        $placeholderSekolahId = $db->insertID();

        // 4. Generate 20 Calon Siswa Baru dengan Data Dummy Realistis menggunakan Faker
        $siswaPasswordHash = password_hash('siswa123', PASSWORD_DEFAULT);

        for ($i = 0; $i < 20; $i++) {
            $nisn = $faker->unique()->numerify('##########'); // 10 digit NISN
            $nik  = $faker->unique()->numerify('################'); // 16 digit NIK
            $noKk = $faker->numerify('################'); // 16 digit KK
            $nama = $faker->name;
            $email = $faker->unique()->safeEmail;
            
            $gender = $faker->randomElement(['L', 'P']);
            $tempatLahir = $faker->city;
            $tanggalLahir = $faker->date('Y-m-d', '2009-12-31');

            // a. Simpan data akun pengguna (Shield)
            $db->table('users')->insert([
                'username'   => $nisn,
                'email'      => $email,
                'role'       => 'student',
                'active'     => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $userId = $db->insertID();

            // Identitas login Shield (berdasarkan email)
            $db->table('auth_identities')->insert([
                'user_id'    => $userId,
                'type'       => 'email_password',
                'secret'     => $email,
                'secret2'    => $siswaPasswordHash,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // Tambahkan user ke group 'user'
            $db->table('auth_groups_users')->insert([
                'user_id'    => $userId,
                'group'      => 'user',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // b. Simpan data ke tabel calon_siswa
            $db->table('calon_siswa')->insert([
                'nisn'             => $nisn,
                'nik'              => $nik,
                'nama_lengkap'     => $nama,
                'no_kk'            => $noKk,
                'tempat_lahir'     => $tempatLahir,
                'tanggal_lahir'    => $tanggalLahir,
                'jenis_kelamin'    => $gender,
                'sekolah_id'       => $faker->randomElement($sekolahIds),
                'anak_ke'          => $faker->numberBetween(1, 5),
                'jumlah_saudara'   => $faker->numberBetween(0, 6),
                'alamat_desa'      => $faker->streetName,
                'alamat_kecamatan' => 'Karanggeneng',
                'alamat_kabupaten' => 'Lamongan',
            ]);
            $siswaId = $db->insertID();

            // c. Simpan data ke tabel data_orang_tua
            $db->table('data_orang_tua')->insert([
                'siswa_id'       => $siswaId,
                'nama_ayah'      => $faker->name('male'),
                'pekerjaan_ayah' => $faker->randomElement(['PNS', 'Wiraswasta', 'Buruh', 'Petani', 'TNI/Polri', 'Karyawan Swasta']),
                'nama_ibu'       => $faker->name('female'),
                'pekerjaan_ibu'  => $faker->randomElement(['Ibu Rumah Tangga', 'Guru', 'Wiraswasta', 'Bidan', 'Perawat', 'Karyawan Swasta']),
            ]);

            // d. Simpan data ke tabel kelengkapan_administrasi
            $statusBerkas = $faker->randomElement(['Belum Verifikasi', 'Valid', 'Revisi']);
            $isFinalized  = $faker->randomElement([0, 1]);
            $catatan      = $statusBerkas === 'Revisi' ? 'Hasil scan Kartu Keluarga tidak jelas atau buram, harap unggah kembali.' : null;

            $db->table('kelengkapan_administrasi')->insert([
                'siswa_id'           => $siswaId,
                'fc_ijazah_skl'      => $faker->randomElement([0, 1]),
                'fc_kk'              => $faker->randomElement([0, 1]),
                'status_verifikasi'  => $statusBerkas,
                'is_finalized'       => $isFinalized,
                'catatan_verifikasi' => $catatan,
                'created_at'         => date('Y-m-d H:i:s'),
                'updated_at'         => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
