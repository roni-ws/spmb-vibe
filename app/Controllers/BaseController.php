<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    protected function requireGroup(string|array $groups, string $redirect = 'login'): void
    {
        if (!auth()->loggedIn() || !auth()->user()->inGroup(...(array)$groups)) {
            $msg = 'Akses ditolak.';
            if ($this->request->isAJAX()) {
                $this->response->setJSON(['status' => 'error', 'message' => $msg])->send();
                exit;
            }
            redirect()->to(base_url($redirect))->with('error', $msg)->throwResponse();
        }
    }

    protected function requireAdmin(string $redirect = 'login'): void
    {
        $this->requireGroup('admin', $redirect);
    }

    protected function requireLoggedIn(string $redirect = 'login'): void
    {
        if (!auth()->loggedIn()) {
            redirect()->to(base_url($redirect))
                ->with('error', 'Silakan login terlebih dahulu.')
                ->throwResponse();
        }
    }

    protected function redirectAdminIfNeeded(): void
    {
        $user = auth()->user();
        if ($user && $user->inGroup('admin')) {
            redirect()->to(base_url('admin/dashboard'))->throwResponse();
        }
    }

    protected function getLoginRules(): array
    {
        return [
            'identifier' => 'required',
            'password'   => 'required',
        ];
    }

    protected function deleteStudentByUserId(int $userId): bool
    {
        $db = Database::connect();
        $student = $db->table('calon_siswa')->where('id', $userId)->get()->getRowArray();
        if (!$student) return false;

        $nisn = $student['nisn'];
        $userSiswa = $db->table('users_siswa')->where('username', $nisn)->get()->getRowArray();
        if ($userSiswa) {
            $uId = $userSiswa['id'];
            foreach (['auth_identities', 'auth_groups_users', 'auth_remember_tokens', 'auth_permissions_users'] as $table) {
                $db->table($table)->where('user_id', $uId)->delete();
            }
            $db->table('users_siswa')->where('id', $uId)->delete();
        }

        return (bool) $db->table('calon_siswa')->where('id', $userId)->delete();
    }

    protected function getStatusPendaftaranBySiswaId(int $siswaId): ?array
    {
        $db = Database::connect();

        return $db->table('status_pendaftaran')
            ->select('status_pendaftaran.*, status_verifikasi.nama_status as status_verifikasi')
            ->join('status_verifikasi', 'status_verifikasi.id = status_pendaftaran.status_verifikasi_id', 'left')
            ->where('siswa_id', $siswaId)
            ->get()
            ->getRowArray() ?: null;
    }

    protected function needsRevision(?string $statusVerifikasi): bool
    {
        return in_array($statusVerifikasi, ['Revisi', 'Tidak Valid'], true);
    }

    protected function checkFinalized(int $siswaId): bool
    {
        $status = $this->getStatusPendaftaranBySiswaId($siswaId);

        if (!$status) {
            return false;
        }

        if ($this->needsRevision($status['status_verifikasi'] ?? null)) {
            return false;
        }

        return (int) $status['is_finalized'] === 1;
    }

    protected function canCetakBukti(?array $statusPendaftaran): bool
    {
        if (!$statusPendaftaran) {
            return false;
        }

        return (int) ($statusPendaftaran['is_finalized'] ?? 0) === 1
            && ($statusPendaftaran['status_verifikasi'] ?? '') === 'Valid';
    }

    protected function getSiswaLayoutData(?string $nisn = null): array
    {
        $nisn = $nisn ?? (auth()->loggedIn() ? auth()->user()->username : null);

        if (!$nisn) {
            return [
                'nama_lengkap'     => 'Siswa',
                'can_cetak_bukti'  => false,
            ];
        }

        $db     = Database::connect();
        $siswa  = $db->table('calon_siswa')->where('nisn', $nisn)->get()->getRowArray();
        $status = $siswa ? $this->getStatusPendaftaranBySiswaId((int) $siswa['id']) : null;

        return [
            'nama_lengkap'    => ! empty($siswa['nama_lengkap']) ? $siswa['nama_lengkap'] : $nisn,
            'can_cetak_bukti' => $this->canCetakBukti($status),
        ];
    }
}
