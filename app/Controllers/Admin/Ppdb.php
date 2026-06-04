<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MasterSekolahModel;
use App\Models\SiswaModel;

class Ppdb extends BaseController
{
    /**
     * Memvalidasi input NISN dan NIK calon siswa.
     * 
     * Aturan:
     * 1. NIK harus persis 16 karakter angka.
     * 2. NISN harus persis 10 karakter angka.
     * 3. NIK & NISN harus unik (belum terdaftar di database).
     * 
     * @param array $data Input data calon siswa
     * @return array Array berisi pesan error, kosong jika valid
     */
    public function validateSiswa(array $data): array
    {
        $errors = [];
        $siswaModel = new SiswaModel();

        // 1. Validasi NIK: Harus persis 16 karakter angka
        if (empty($data['nik'])) {
            $errors['nik'] = 'NIK tidak boleh kosong.';
        } elseif (!preg_match('/^[0-9]{16}$/', $data['nik'])) {
            $errors['nik'] = 'NIK harus persis 16 digit angka.';
        }

        // 2. Validasi NISN: Harus persis 10 karakter angka
        if (empty($data['nisn'])) {
            $errors['nisn'] = 'NISN tidak boleh kosong.';
        } elseif (!preg_match('/^[0-9]{10}$/', $data['nisn'])) {
            $errors['nisn'] = 'NISN harus persis 10 digit angka.';
        }

        // 3. Validasi Keunikan NIK & NISN di database calon_siswa
        if (empty($errors['nik'])) {
            $existingNik = $siswaModel->where('nik', $data['nik'])->first();
            if ($existingNik) {
                $errors['nik'] = 'NIK sudah terdaftar di sistem.';
            }
        }

        if (empty($errors['nisn'])) {
            $existingNisn = $siswaModel->where('nisn', $data['nisn'])->first();
            if ($existingNisn) {
                $errors['nisn'] = 'NISN sudah terdaftar di sistem.';
            }
        }

        return $errors;
    }

    /**
     * Mendaftarkan calon siswa baru beserta data orang tua dan berkas administrasi
     * menggunakan Database Transaction untuk menjaga integritas data.
     * 
     * @param array $data Input data lengkap calon siswa, orang tua, dan berkas
     * @return array Response status ['status' => true/false, 'message' => string, 'siswa_id' => int/null, 'errors' => array]
     */
    public function registerSiswa(array $data): array
    {
        // 1. Validasi NIK & NISN terlebih dahulu
        $validationErrors = $this->validateSiswa($data);
        if (!empty($validationErrors)) {
            return [
                'status'  => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validationErrors
            ];
        }

        $db = \Config\Database::connect();
        
        // Mulai Database Transaction
        $db->transBegin();

        try {
            // 2. Dapatkan atau buat sekolah_id dari nama sekolah asal
            $namaSekolah = $data['asal_sekolah'] ?? '';
            if (empty($namaSekolah)) {
                throw new \Exception('Asal sekolah wajib diisi.');
            }
            $sekolahId = (new MasterSekolahModel())->getOrCreateSekolahId($namaSekolah, $data['alamat_sekolah'] ?? null);

            // 3. Masukkan data ke tabel calon_siswa
            $siswaModel = new SiswaModel();
            $siswaData = [
                'nisn'             => $data['nisn'],
                'nik'              => $data['nik'],
                'nama_lengkap'     => $data['nama_lengkap'],
                'no_kk'            => $data['no_kk'],
                'tempat_lahir'     => $data['tempat_lahir'],
                'tanggal_lahir'    => $data['tanggal_lahir'],
                'jenis_kelamin'    => $data['jenis_kelamin'],
                'sekolah_id'       => $sekolahId,
                'anak_ke'          => $data['anak_ke'] ?? 1,
                'jumlah_saudara'   => $data['jumlah_saudara'] ?? 0,
                'alamat_desa'      => $data['alamat_desa'] ?? '',
                'alamat_kecamatan' => $data['alamat_kecamatan'] ?? '',
                'alamat_kabupaten' => $data['alamat_kabupaten'] ?? '',
            ];

            if (!$siswaModel->insert($siswaData)) {
                throw new \Exception('Gagal menyimpan data calon siswa.');
            }

            // Dapatkan ID siswa yang baru saja dimasukkan
            $siswaId = $siswaModel->getInsertID();

            // 4. Masukkan data ke tabel data_orang_tua
            $orangTuaModel = new \App\Models\OrangTuaModel();
            $orangTuaData = [
                'siswa_id'       => $siswaId,
                'nama_ayah'      => $data['nama_ayah'] ?? '',
                'pekerjaan_ayah' => $data['pekerjaan_ayah'] ?? '',
                'nama_ibu'       => $data['nama_ibu'] ?? '',
                'pekerjaan_ibu'  => $data['pekerjaan_ibu'] ?? '',
            ];

            if (!$orangTuaModel->insert($orangTuaData)) {
                throw new \Exception('Gagal menyimpan data orang tua calon siswa.');
            }

            // 5. Masukkan data ke tabel kelengkapan_administrasi
            $administrasiModel = new \App\Models\AdministrasiModel();
            $administrasiData = [
                'siswa_id'           => $siswaId,
                'fc_ijazah_skl'      => $data['fc_ijazah_skl'] ?? null,
                'fc_kk'              => $data['fc_kk'] ?? null,
                'pas_foto'           => $data['pas_foto'] ?? null,
            ];

            if (!$administrasiModel->insert($administrasiData)) {
                throw new \Exception('Gagal menyimpan kelengkapan administrasi.');
            }

            // 6. Masukkan data ke tabel status_pendaftaran
            $statusPendaftaranModel = new \App\Models\StatusPendaftaranModel();
            $statusData = [
                'siswa_id'             => $siswaId,
                'status_verifikasi_id' => $data['status_verifikasi_id'] ?? 1,
                'is_finalized'         => isset($data['is_finalized']) ? (int)$data['is_finalized'] : 0,
                'catatan_verifikasi'   => $data['catatan_verifikasi'] ?? null,
            ];

            if (!$statusPendaftaranModel->insert($statusData)) {
                throw new \Exception('Gagal menyimpan status pendaftaran.');
            }

            // Jika semua query berhasil, lakukan COMMIT
            if ($db->transStatus() === false) {
                $db->transRollback();
                return [
                    'status'  => false,
                    'message' => 'Transaksi database gagal atau status tidak valid.',
                ];
            } else {
                $db->transCommit();
                return [
                    'status'   => true,
                    'message'  => 'Pendaftaran calon siswa berhasil disimpan.',
                    'siswa_id' => $siswaId
                ];
            }

        } catch (\Throwable $e) {
            // Jika ada exception/error, lakukan ROLLBACK untuk membatalkan semua query sebelumnya
            $db->transRollback();
            log_message('error', 'Pendaftaran Error: ' . $e->getMessage());
            return [
                'status'  => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba beberapa saat lagi.'
            ];
        }
    }
}

