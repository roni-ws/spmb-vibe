<?php

namespace App\Services;

use App\Models\Siswa\AdministrasiModel;
use App\Models\Siswa\MasterSekolahModel;
use App\Models\Siswa\OrangTuaModel;
use App\Models\Siswa\SiswaModel;
use App\Models\Siswa\StatusPendaftaranModel;
use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Database\BaseConnection;
use Config\Database;
use App\Services\SiswaNotificationService;

class StudentService
{
    protected BaseConnection $db;
    protected AdministrasiModel $administrasiModel;
    protected SiswaModel $siswaModel;
    protected MasterSekolahModel $sekolahModel;
    protected OrangTuaModel $orangTuaModel;
    protected StatusPendaftaranModel $statusModel;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db                = $db ?? Database::connect();
        $this->administrasiModel = model(AdministrasiModel::class);
        $this->siswaModel        = model(SiswaModel::class);
        $this->sekolahModel      = model(MasterSekolahModel::class);
        $this->orangTuaModel     = model(OrangTuaModel::class);
        $this->statusModel       = model(StatusPendaftaranModel::class);
    }

    private function baseStudentQuery(): BaseBuilder
    {
        return $this->db->table('calon_siswa')
            ->join('master_sekolah', 'master_sekolah.id = calon_siswa.sekolah_id', 'left')
            ->join('kelengkapan_administrasi', 'kelengkapan_administrasi.siswa_id = calon_siswa.id', 'left')
            ->join('data_orang_tua', 'data_orang_tua.siswa_id = calon_siswa.id', 'left')
            ->join('status_pendaftaran', 'status_pendaftaran.siswa_id = calon_siswa.id', 'left')
            ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left');
    }

    public function getAllStudentsWithDetail(): array
    {
        return $this->baseStudentQuery()
            ->select('
                calon_siswa.id, 
                calon_siswa.nisn as username, 
                calon_siswa.no_registrasi,
                calon_siswa.nik, 
                calon_siswa.created_at,
                calon_siswa.nama_lengkap, 
                calon_siswa.tempat_lahir,
                calon_siswa.tanggal_lahir,
                calon_siswa.jenis_kelamin,
                calon_siswa.nisn,
                calon_siswa.no_kk,
                calon_siswa.no_hp,
                calon_siswa.anak_ke,
                calon_siswa.jumlah_saudara,
                calon_siswa.alamat_desa,
                calon_siswa.alamat_kecamatan,
                calon_siswa.alamat_kabupaten,
                calon_siswa.alamat_sekolah,
                master_sekolah.nama_sekolah,
                kelengkapan_administrasi.pas_foto,
                kelengkapan_administrasi.fc_ijazah_skl,
                kelengkapan_administrasi.fc_kk,
                data_orang_tua.nama_ayah,
                data_orang_tua.pekerjaan_ayah,
                data_orang_tua.nama_ibu,
                data_orang_tua.pekerjaan_ibu,
                status_pendaftaran.status_verifikasi_id,
                status_verifikasi.nama_status as status_verifikasi, 
                status_pendaftaran.is_finalized,
                status_pendaftaran.catatan_verifikasi
            ')
            ->orderBy('calon_siswa.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    private function getStatsQuery(): array
    {
        return $this->db->table('calon_siswa')
            ->select('calon_siswa.id, status_verifikasi.nama_status as status_verifikasi')
            ->join('status_pendaftaran', 'status_pendaftaran.siswa_id = calon_siswa.id', 'left')
            ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left')
            ->orderBy('calon_siswa.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    private function computeStats(array $students): array
    {
        $stats = ['total' => count($students), 'pending' => 0, 'valid' => 0, 'invalid' => 0];
        foreach ($students as $student) {
            $status = $student['status_verifikasi'] ?? VERIF_STATUS_BELUM;
            if ($status === VERIF_STATUS_VALID) {
                $stats['valid']++;
            } elseif (in_array($status, [VERIF_STATUS_TIDAK_VALID, VERIF_STATUS_REVISI])) {
                $stats['invalid']++;
            } else {
                $stats['pending']++;
            }
        }
        return $stats;
    }

    public function getStats(): array
    {
        return $this->computeStats($this->getStatsQuery());
    }

    public function getMonthlyTrends(): array
    {
        $monthlyTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));

            $indonesianMonths = [
                'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Apr',
                'May' => 'Mei', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Agu',
                'Sep' => 'Sep', 'Oct' => 'Okt', 'Nov' => 'Nov', 'Dec' => 'Des'
            ];
            $displayMonth = $indonesianMonths[$monthName] ?? $monthName;

            $monthlyTrends[$date] = [
                'name'  => $displayMonth,
                'count' => 0
            ];
        }

        $trendBuilder = Database::connect()->table('calon_siswa')
            ->select("DATE_FORMAT(calon_siswa.created_at, '%Y-%m') as month_key, COUNT(calon_siswa.id) as count")
            ->where('calon_siswa.created_at IS NOT NULL')
            ->groupBy("month_key");
        $trendsRaw = $trendBuilder->get()->getResultArray();

        foreach ($trendsRaw as $row) {
            $key = $row['month_key'];
            if (isset($monthlyTrends[$key])) {
                $monthlyTrends[$key]['count'] = (int)$row['count'];
            }
        }

        return $monthlyTrends;
    }

    public function getRecentStudents(int $limit = 4): array
    {
        return $this->db->table('calon_siswa')
            ->select('calon_siswa.id, calon_siswa.nisn as username, calon_siswa.nama_lengkap, calon_siswa.created_at')
            ->orderBy('calon_siswa.id', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function verifyStudent(int $siswaId, int $statusId, ?string $catatan = null): void
    {
        $statusRecord = $this->statusModel->find($siswaId);

        if (! $statusRecord || ! $statusRecord->is_finalized) {
            throw new \RuntimeException(
                'Siswa belum melakukan finalisasi. Verifikasi tidak diizinkan.'
            );
        }

        $updateData = [
            'status_verifikasi_id' => $statusId,
            'catatan_verifikasi'   => $catatan,
        ];

        if (in_array((int) $statusId, [VERIF_ID_REVISI, VERIF_ID_TIDAK_VALID], true)) {
            $updateData['is_finalized'] = 0;
        }

        if ($statusRecord) {
            $this->statusModel->update($siswaId, $updateData);
        } else {
            $this->statusModel->insert(array_merge([
                'siswa_id'     => $siswaId,
                'is_finalized' => 0,
            ], $updateData));
        }

        $statusNames = [
            VERIF_ID_VALID       => 'Valid',
            VERIF_ID_REVISI      => 'Butuh Revisi',
            VERIF_ID_TIDAK_VALID => 'Tidak Valid',
        ];
        $label = $statusNames[(int) $statusId] ?? 'Unknown';

        $notif = new SiswaNotificationService();
        $notif->send($siswaId, NOTIF_SISWA_STATUS_CHANGE,
            'Status Berkas: ' . $label,
            $catatan ? "Catatan panitia: {$catatan}" : null
        );
    }

    public function deleteStudent(int $userId): bool
    {
        $student = $this->siswaModel->find($userId);
        if (!$student) {
            return false;
        }

        $siswaUserModel = model(\App\Models\User\SiswaUserModel::class);
        $uId = $student['user_id'] ?? null;
        if ($uId) {
            $siswaUserModel->builder('auth_identities')->where('user_id', $uId)->delete();
            $siswaUserModel->builder('auth_groups_users')->where('user_id', $uId)->delete();
            $siswaUserModel->builder('auth_remember_tokens')->where('user_id', $uId)->delete();
            $siswaUserModel->builder('auth_permissions_users')->where('user_id', $uId)->delete();
            $siswaUserModel->delete($uId, true);
        }

        return (bool) $this->siswaModel->delete($userId);
    }

    public function getStudentByUserId(int $userId): ?array
    {
        return $this->siswaModel->asArray()->where('user_id', $userId)->first();
    }

    public function getStudentByNisn(string $nisn): ?array
    {
        return $this->siswaModel->asArray()->where('nisn', $nisn)->first();
    }

    public function getStudentSchool(int $siswaId): ?array
    {
        $siswa = $this->siswaModel->find($siswaId);
        if (!$siswa || empty($siswa['sekolah_id'])) {
            return null;
        }
        return $this->sekolahModel->asArray()->find($siswa['sekolah_id']);
    }

    public function getStudentBerkas(int $siswaId): ?array
    {
        return $this->administrasiModel->asArray()->where('siswa_id', $siswaId)->first();
    }

    public function unfinalizeStudent(int $siswaId): void
    {
        $this->statusModel->update($siswaId, ['is_finalized' => 0]);
    }

    public function finalizeStudent(int $siswaId): void
    {
        $statusRecord = $this->statusModel->find($siswaId);

        if ($statusRecord) {
            $this->statusModel->update($siswaId, ['is_finalized' => 1]);
        } else {
            $this->statusModel->insert([
                'siswa_id'             => $siswaId,
                'is_finalized'         => 1,
                'status_verifikasi_id' => 1,
            ]);
        }
    }

    public function getRegistrationStatus(int $siswaId): ?array
    {
        $row = $this->statusModel
            ->select('status_pendaftaran.*, status_verifikasi.nama_status as status_verifikasi')
            ->join('status_verifikasi', 'status_pendaftaran.status_verifikasi_id = status_verifikasi.id')
            ->where('status_pendaftaran.siswa_id', $siswaId)
            ->first();

        return $row ? $row->toArray() : null;
    }

    public function needsRevision(?string $statusVerifikasi): bool
    {
        return in_array($statusVerifikasi, [VERIF_STATUS_REVISI, VERIF_STATUS_TIDAK_VALID], true);
    }

    public function calculateProgress(?array $biodata, ?array $sekolah, ?array $berkas): array
    {
        $hasBiodata = ! empty($biodata) && ! empty($biodata['nik']);
        $hasSekolah = ! empty($sekolah) && ($sekolah['nama_sekolah'] ?? '') !== DEFAULT_SEKOLAH;
        $hasBerkas  = ! empty($berkas) && (!empty($berkas['fc_ijazah_skl']) && !empty($berkas['fc_kk']) && !empty($berkas['pas_foto']));

        $progressStep    = 1;
        $progressPercent = 25;
        if ($hasBiodata) { $progressStep = 2; $progressPercent += 25; }
        if ($hasSekolah) { $progressStep = 3; $progressPercent += 25; }
        if ($hasBerkas)  { $progressStep = 4; $progressPercent += 25; }

        return compact('hasBiodata', 'hasSekolah', 'hasBerkas', 'progressStep', 'progressPercent');
    }

    public function syncRevisionStatus(int $siswaId, ?string $statusVerifikasi, int $isFinalized): int
    {
        if ($this->needsRevision($statusVerifikasi) && $isFinalized === 1) {
            $this->unfinalizeStudent($siswaId);
            return 0;
        }
        return $isFinalized;
    }

    public function updateStudent(int $userId, array $data): bool
    {
        $sekolahId = $this->sekolahModel->getOrCreateSekolahId($data['nama_sekolah']);

        $result = $this->siswaModel->update($userId, [
            'nama_lengkap'       => $data['nama_lengkap'],
            'nik'                => $data['nik'],
            'no_kk'              => $data['no_kk'],
            'tempat_lahir'       => $data['tempat_lahir'],
            'tanggal_lahir'      => $data['tanggal_lahir'],
            'jenis_kelamin'      => $data['jenis_kelamin'],
            'sekolah_id'         => $sekolahId,
            'alamat_sekolah'     => $data['alamat_sekolah'] ?? null,
            'anak_ke'            => $data['anak_ke'],
            'jumlah_saudara'     => $data['jumlah_saudara'],
            'no_hp'              => $data['no_hp'],
            'alamat_desa'        => $data['alamat_desa'],
            'alamat_kecamatan'   => $data['alamat_kecamatan'],
            'alamat_kabupaten'   => $data['alamat_kabupaten'],
            'sekolah_id'         => $sekolahId,
        ]);

        if (! $result) {
            return false;
        }

        $orangTua = $this->orangTuaModel->find($userId);
        $orangTuaData = [
            'nama_ayah'      => $data['nama_ayah'],
            'pekerjaan_ayah' => $data['pekerjaan_ayah'],
            'nama_ibu'       => $data['nama_ibu'],
            'pekerjaan_ibu'  => $data['pekerjaan_ibu'],
        ];
        if ($orangTua) {
            $this->orangTuaModel->update($userId, $orangTuaData);
        } else {
            $orangTuaData['siswa_id'] = $userId;
            $this->orangTuaModel->insert($orangTuaData);
        }

        return true;
    }

    public function getExportQuery(): array
    {
        return $this->baseStudentQuery()
            ->select('
                calon_siswa.id,
                calon_siswa.nisn as username,
                calon_siswa.no_registrasi,
                calon_siswa.nik,
                calon_siswa.no_kk,
                calon_siswa.nama_lengkap,
                calon_siswa.tempat_lahir,
                calon_siswa.tanggal_lahir,
                calon_siswa.jenis_kelamin,
                calon_siswa.anak_ke,
                calon_siswa.jumlah_saudara,
                calon_siswa.no_hp,
                calon_siswa.alamat_desa,
                calon_siswa.alamat_kecamatan,
                calon_siswa.alamat_kabupaten,
                master_sekolah.nama_sekolah,
                data_orang_tua.nama_ayah,
                data_orang_tua.pekerjaan_ayah,
                data_orang_tua.nama_ibu,
                data_orang_tua.pekerjaan_ibu,
                status_verifikasi.nama_status as status_verifikasi,
                status_pendaftaran.is_finalized
            ')
            ->orderBy('calon_siswa.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function getStudentForCetak(int $studentId): ?array
    {
        return $this->baseStudentQuery()
            ->select('
                calon_siswa.id, 
                calon_siswa.nisn, 
                calon_siswa.no_registrasi,
                calon_siswa.nik, 
                calon_siswa.nama_lengkap, 
                calon_siswa.no_kk,
                calon_siswa.tempat_lahir,
                calon_siswa.tanggal_lahir,
                calon_siswa.jenis_kelamin,
                calon_siswa.no_hp,
                calon_siswa.anak_ke,
                calon_siswa.jumlah_saudara,
                calon_siswa.nisn as username, 
                calon_siswa.created_at,
                CONCAT(calon_siswa.alamat_desa, \', \', calon_siswa.alamat_kecamatan, \', \', calon_siswa.alamat_kabupaten) as alamat,
                data_orang_tua.nama_ayah,
                data_orang_tua.pekerjaan_ayah,
                data_orang_tua.nama_ibu,
                data_orang_tua.pekerjaan_ibu,
                master_sekolah.nama_sekolah, 
                calon_siswa.alamat_sekolah, 
                kelengkapan_administrasi.fc_ijazah_skl,
                kelengkapan_administrasi.fc_kk,
                kelengkapan_administrasi.pas_foto,
                status_verifikasi.nama_status as status_verifikasi, 
                status_pendaftaran.is_finalized,
                status_pendaftaran.catatan_verifikasi
            ')
            ->where('calon_siswa.id', $studentId)
            ->get()
            ->getRowArray();
    }

    public function getStudentProfileByUserId(int $userId): ?array
    {
        return $this->db->table('calon_siswa')
            ->select('calon_siswa.*, status_verifikasi.nama_status as status_verifikasi, status_pendaftaran.is_finalized')
            ->join('status_pendaftaran', 'status_pendaftaran.siswa_id = calon_siswa.id', 'left')
            ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left')
            ->where('calon_siswa.user_id', $userId)
            ->get()
            ->getRowArray();
    }

    public function getStudentProfileByNisn(string $nisn): ?array
    {
        return $this->db->table('calon_siswa')
            ->select('calon_siswa.*, status_verifikasi.nama_status as status_verifikasi, status_pendaftaran.is_finalized')
            ->join('status_pendaftaran', 'status_pendaftaran.siswa_id = calon_siswa.id', 'left')
            ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left')
            ->where('calon_siswa.nisn', $nisn)
            ->get()
            ->getRowArray();
    }

    public function getStudentForCetakByUserId(int $userId): ?array
    {
        return $this->baseStudentQuery()
            ->select('
                calon_siswa.id, 
                calon_siswa.nisn, 
                calon_siswa.nisn as username, 
                calon_siswa.nisn as email, 
                calon_siswa.created_at as created_at,
                calon_siswa.no_registrasi,
                calon_siswa.nik, 
                calon_siswa.nama_lengkap, 
                calon_siswa.no_kk,
                calon_siswa.tempat_lahir,
                calon_siswa.tanggal_lahir,
                calon_siswa.jenis_kelamin,
                calon_siswa.no_hp,
                calon_siswa.anak_ke,
                calon_siswa.jumlah_saudara,
                CONCAT(calon_siswa.alamat_desa, \', \', calon_siswa.alamat_kecamatan, \', \', calon_siswa.alamat_kabupaten) as alamat,
                data_orang_tua.nama_ayah,
                data_orang_tua.pekerjaan_ayah,
                data_orang_tua.nama_ibu,
                data_orang_tua.pekerjaan_ibu,
                master_sekolah.nama_sekolah, 
                calon_siswa.alamat_sekolah, 
                kelengkapan_administrasi.fc_ijazah_skl,
                kelengkapan_administrasi.fc_kk,
                kelengkapan_administrasi.pas_foto,
                status_verifikasi.nama_status as status_verifikasi, 
                status_pendaftaran.is_finalized,
                \'Belum Pengumuman\' as status_kelulusan
            ')
            ->where('calon_siswa.user_id', $userId)
            ->get()
            ->getRowArray();
    }

    public function getStudentForCetakByNisn(string $nisn): ?array
    {
        return $this->baseStudentQuery()
            ->select('
                calon_siswa.id, 
                calon_siswa.nisn, 
                calon_siswa.nisn as username, 
                calon_siswa.nisn as email, 
                calon_siswa.created_at as created_at,
                calon_siswa.no_registrasi,
                calon_siswa.nik, 
                calon_siswa.nama_lengkap, 
                calon_siswa.no_kk,
                calon_siswa.tempat_lahir,
                calon_siswa.tanggal_lahir,
                calon_siswa.jenis_kelamin,
                calon_siswa.no_hp,
                calon_siswa.anak_ke,
                calon_siswa.jumlah_saudara,
                CONCAT(calon_siswa.alamat_desa, \', \', calon_siswa.alamat_kecamatan, \', \', calon_siswa.alamat_kabupaten) as alamat,
                data_orang_tua.nama_ayah,
                data_orang_tua.pekerjaan_ayah,
                data_orang_tua.nama_ibu,
                data_orang_tua.pekerjaan_ibu,
                master_sekolah.nama_sekolah, 
                calon_siswa.alamat_sekolah, 
                kelengkapan_administrasi.fc_ijazah_skl,
                kelengkapan_administrasi.fc_kk,
                kelengkapan_administrasi.pas_foto,
                status_verifikasi.nama_status as status_verifikasi, 
                status_pendaftaran.is_finalized,
                \'Belum Pengumuman\' as status_kelulusan
            ')
            ->where('calon_siswa.nisn', $nisn)
            ->get()
            ->getRowArray();
    }
}
