<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{

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
        $this->requireGroup(ROLE_ADMIN, $redirect);
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
        if ($user && $user->inGroup(ROLE_ADMIN)) {
            redirect()->to(base_url('admin/dashboard'))->throwResponse();
        }
    }

    protected function requireSiswa(): void
    {
        $this->requireLoggedIn();
        $this->redirectAdminIfNeeded();
    }

    protected function getSiteSettings(): array
    {
        return [
            'logoText'  => \App\Models\SettingsModel::getSetting('logo_text', 'SPMB Unggul Bangsa'),
            'logoImg'   => \App\Models\SettingsModel::getSetting('logo_img', ''),
        ];
    }

    protected function getPublicSettings(): array
    {
        return [
            'logoText'       => \App\Models\SettingsModel::getSetting('logo_text', 'SPMB Unggul Bangsa'),
            'logoImg'        => \App\Models\SettingsModel::getSetting('logo_img', ''),
            'is_open'        => \App\Models\SettingsModel::getSetting('is_open', '1') === '1',
            'footer_desc'    => \App\Models\SettingsModel::getSetting('footer_desc', ''),
            'footer_phone'   => \App\Models\SettingsModel::getSetting('footer_phone', ''),
            'footer_email'   => \App\Models\SettingsModel::getSetting('footer_email', ''),
            'footer_address' => \App\Models\SettingsModel::getSetting('footer_address', ''),
        ];
    }

    protected function renderSiswaView(string $view, array $data = []): ResponseInterface
    {
        $user = auth()->user();
        $nisn = $user ? ($user->nisn ?? $user->username) : null;

        return $this->response->setBody(view($view, array_merge($this->getSiteSettings(), $this->getSiswaLayoutData($nisn), $data)));
    }

    protected function redirectByRole(): ResponseInterface
    {
        $user = auth()->user();

        if ($user && ($user->inGroup(ROLE_ADMIN) || $user->inGroup(ROLE_PANITIA))) {
            return redirect()->to(base_url('admin/dashboard'));
        }

        return redirect()->to(base_url('siswa/dashboard'));
    }

    protected function getRegistrationStatus(int $siswaId): ?array
    {
        return model(\App\Models\Siswa\StatusPendaftaranModel::class)
            ->getWithVerifikasiBySiswaId($siswaId);
    }

    protected function needsRevision(?string $statusVerifikasi): bool
    {
        return in_array($statusVerifikasi, [VERIF_STATUS_REVISI, VERIF_STATUS_TIDAK_VALID], true);
    }

    protected function checkFinalized(int $siswaId): bool
    {
        $status = $this->getRegistrationStatus($siswaId);

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
            && ($statusPendaftaran['status_verifikasi'] ?? '') === VERIF_STATUS_VALID;
    }

    protected function getSiswaLayoutData(?string $nisn = null): array
    {
        $nisn = $nisn ?? (auth()->loggedIn() ? (auth()->user()->nisn ?? auth()->user()->username) : null);

        if (!$nisn) {
            return [
                'nama_lengkap'     => 'Siswa',
                'user_email'       => '',
                'can_cetak_bukti'  => false,
            ];
        }

        $siswa = model(\App\Models\Siswa\SiswaModel::class)
            ->where('nisn', $nisn)
            ->first();
        $status = $siswa ? $this->getRegistrationStatus((int) $siswa['id']) : null;
        $shieldUser = auth()->user();

        return [
            'nama_lengkap'    => ! empty($siswa['nama_lengkap']) ? $siswa['nama_lengkap'] : $nisn,
            'user_email'      => $shieldUser ? $shieldUser->email : '',
            'can_cetak_bukti' => $this->canCetakBukti($status),
        ];
    }
}
