<?php

namespace App\Services;

use App\Models\Siswa\AdministrasiModel;
use App\Models\Siswa\MasterSekolahModel;
use App\Models\Siswa\OrangTuaModel;
use App\Models\Siswa\SiswaModel;
use App\Models\Siswa\StatusPendaftaranModel;
use App\Models\User\SiswaUserModel;
use CodeIgniter\Shield\Entities\User;

class RegistrationService
{
    protected SiswaModel $siswaModel;
    protected MasterSekolahModel $sekolahModel;
    protected OrangTuaModel $orangTuaModel;
    protected AdministrasiModel $administrasiModel;
    protected StatusPendaftaranModel $statusModel;

    public function __construct()
    {
        $this->siswaModel        = model(SiswaModel::class);
        $this->sekolahModel      = model(MasterSekolahModel::class);
        $this->orangTuaModel     = model(OrangTuaModel::class);
        $this->administrasiModel = model(AdministrasiModel::class);
        $this->statusModel       = model(StatusPendaftaranModel::class);
    }

    public function register(string $username, string $email, string $password, string $fullName): User
    {
        $siswaUsers = model(SiswaUserModel::class);

        $user = new User([
            'username' => $username,
            'nisn'     => $username,
            'email'    => $email,
            'password' => $password,
            'active'   => 1,
        ]);

        if (! $siswaUsers->save($user)) {
            throw new \RuntimeException('Gagal membuat akun. Silakan coba beberapa saat lagi.');
        }

        $userId = $siswaUsers->getInsertID();
        $user   = $siswaUsers->findById($userId);
        $user->addGroup(GROUP_USER);

        $siswaId = $this->initializeStudentData($userId, $username, $fullName, $email);

        $siswaNotif = new SiswaNotificationService();
        $siswaNotif->send($siswaId, NOTIF_SISWA_WELCOME,
            'Selamat Bergabung!',
            'Akun pendaftaran Anda di SPMB telah berhasil dibuat. Silakan lengkapi data diri dan dokumen Anda, lalu lakukan finalisasi pendaftaran.'
        );

        $adminNotif = new AdminNotificationService();
        $adminNotif->broadcast(NOTIF_ADMIN_SISWA_BARU,
            'Calon Siswa Baru',
            "{$fullName} ({$username}) baru saja mendaftar. Silakan verifikasi data pendaftar tersebut."
        );

        return $user;
    }

    public function initializeStudentData(int $userId, string $username, string $fullName, string $email): int
    {
        $sekolah = $this->sekolahModel->where('nama_sekolah', DEFAULT_SEKOLAH)->first();
        if ($sekolah) {
            $sekolahId = $sekolah['id'];
        } else {
            $this->sekolahModel->insert([
                'nama_sekolah'   => DEFAULT_SEKOLAH,
                'alamat_sekolah' => DEFAULT_SEKOLAH
            ]);
            $sekolahId = $this->sekolahModel->getInsertID();
        }

        $this->siswaModel->insert([
            'nisn'             => $username,
            'nik'              => REG_PREFIX . str_pad((string)$userId, 12, '0', STR_PAD_LEFT),
            'nama_lengkap'     => $fullName,
            'no_kk'            => KK_SENTINEL,
            'tempat_lahir'     => '',
            'tanggal_lahir'    => DEFAULT_TTL,
            'jenis_kelamin'    => DEFAULT_GENDER,
            'sekolah_id'       => $sekolahId,
            'anak_ke'          => 1,
            'jumlah_saudara'   => 0,
            'alamat_desa'      => '',
            'alamat_kecamatan' => '',
            'alamat_kabupaten' => '',
        ]);
        $siswaId = $this->siswaModel->getInsertID();

        $this->siswaModel->update($siswaId, ['no_registrasi' => $this->generateRegistrationNumber($siswaId)]);

        $this->orangTuaModel->insert([
            'siswa_id'       => $siswaId,
            'nama_ayah'      => '',
            'pekerjaan_ayah' => '',
            'nama_ibu'       => '',
            'pekerjaan_ibu'  => '',
        ]);

        $this->administrasiModel->insert([
            'siswa_id'      => $siswaId,
            'fc_ijazah_skl' => null,
            'fc_kk'         => null,
            'pas_foto'      => null,
        ]);

        $this->statusModel->insert([
            'siswa_id'             => $siswaId,
            'status_verifikasi_id' => 1,
            'is_finalized'         => 0,
            'catatan_verifikasi'   => null,
        ]);

        $siswaUsers = model(SiswaUserModel::class);
        $shieldUser = $siswaUsers->findById($userId);
        if ($shieldUser) {
            $shieldUser->role  = ROLE_STUDENT;
            $shieldUser->email = $email;
            $siswaUsers->save($shieldUser);
        }

        return $siswaId;
    }

    public function createManualStudent(array $data, array $files): int
    {
        $sekolahId = $this->sekolahModel->getOrCreateSekolahId($data['nama_sekolah']);
        $this->sekolahModel->update($sekolahId, [
            'alamat_sekolah' => $data['alamat_sekolah'],
        ]);

        $this->siswaModel->insert([
            'nisn'               => $data['nisn'],
            'nama_lengkap'       => $data['nama_lengkap'],
            'nik'                => $data['nik'],
            'no_kk'              => $data['no_kk'],
            'tempat_lahir'       => $data['tempat_lahir'],
            'tanggal_lahir'      => $data['tanggal_lahir'],
            'jenis_kelamin'      => $data['jenis_kelamin'],
            'anak_ke'            => $data['anak_ke'],
            'jumlah_saudara'     => $data['jumlah_saudara'],
            'no_hp'              => $data['no_hp'],
            'alamat_desa'        => $data['alamat_desa'],
            'alamat_kecamatan'   => $data['alamat_kecamatan'],
            'alamat_kabupaten'   => $data['alamat_kabupaten'],
            'sekolah_id'         => $sekolahId,
        ]);
        $siswaId = $this->siswaModel->getInsertID();

        $this->siswaModel->update($siswaId, ['no_registrasi' => $this->generateRegistrationNumber($siswaId)]);

        $this->orangTuaModel->insert([
            'siswa_id'       => $siswaId,
            'nama_ayah'      => $data['nama_ayah'],
            'pekerjaan_ayah' => $data['pekerjaan_ayah'],
            'nama_ibu'       => $data['nama_ibu'],
            'pekerjaan_ibu'  => $data['pekerjaan_ibu'],
        ]);

        $uploadService = new FileUploadService();
        $pasFotoName = $uploadService->uploadBerkas($files['pas_foto'], $siswaId, 'pas_foto');
        $ijazahName  = $uploadService->uploadBerkas($files['fc_ijazah_skl'], $siswaId, 'ijazah');
        $kkName      = $uploadService->uploadBerkas($files['fc_kk'], $siswaId, 'kk');

        $this->administrasiModel->insert([
            'siswa_id'      => $siswaId,
            'pas_foto'      => $pasFotoName,
            'fc_ijazah_skl' => $ijazahName,
            'fc_kk'         => $kkName,
        ]);

        $this->statusModel->insert([
            'siswa_id'             => $siswaId,
            'status_verifikasi_id' => 1,
            'is_finalized'         => 0,
        ]);

        return $siswaId;
    }

    private function generateRegistrationNumber(int $siswaId): string
    {
        return REG_PREFIX . date('Y') . '-' . $siswaId;
    }
}
