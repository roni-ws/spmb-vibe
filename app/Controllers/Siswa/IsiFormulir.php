<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Services\FormulirService;
use CodeIgniter\HTTP\ResponseInterface;

class IsiFormulir extends BaseController
{
    protected FormulirService $formulirService;

    public function __construct()
    {
        $this->formulirService = new FormulirService();
    }

    protected $helpers = ['url', 'html'];

    private function getSiswaOrRedirect(bool $withAdminRedirect = true): \App\Entities\CalonSiswa
    {
        if ($withAdminRedirect) {
            $this->requireSiswa();
        } else {
            $this->requireLoggedIn();
        }

        $user = auth()->user();
        $siswa = model(\App\Models\Siswa\SiswaModel::class)->where('nisn', $user->nisn ?? $user->username)->first();

        if (!$siswa) {
            redirect()->to(base_url('siswa/dashboard'))->with('error', 'Data pendaftar tidak ditemukan.')->throwResponse();
        }

        if ($this->checkFinalized($siswa['id'])) {
            redirect()->to(base_url('siswa/dashboard'))
                ->with('error', 'Data pendaftar Anda telah difinalisasi secara permanen.')
                ->throwResponse();
        }

        return $siswa;
    }

    public function biodata(): ResponseInterface
    {
        $biodata = $this->getSiswaOrRedirect();
        $user = auth()->user();

        return $this->renderSiswaView('siswa/form-biodata', [
            'biodata'      => $biodata,
            'nama_lengkap' => !empty($biodata['nama_lengkap']) ? $biodata['nama_lengkap'] : ($user->nisn ?? $user->username),
            'errors'       => session('errors'),
            'currentStep'  => 1,
            'backUrl'      => 'siswa/dashboard',
            'backLabel'    => 'Batal',
        ]);
    }

    public function updateBiodata(): ResponseInterface
    {
        $biodata = $this->getSiswaOrRedirect(false);

        if (!$this->validateData($this->request->getPost(), 'siswaBiodata')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->formulirService->updateBiodata($biodata['id'], $this->request->getPost([
            'nik', 'no_kk', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir',
            'jenis_kelamin', 'no_hp', 'anak_ke', 'jumlah_saudara',
            'alamat_desa', 'alamat_kabupaten', 'alamat_kecamatan',
        ]));

        return redirect()->to(base_url('siswa/sekolah'))->with('success', 'Biodata diri berhasil disimpan! Silakan lanjutkan mengisi asal sekolah.');
    }

    public function sekolah(): ResponseInterface
    {
        $biodata = $this->getSiswaOrRedirect();
        $user = auth()->user();
        $sekolah = model(\App\Models\Siswa\MasterSekolahModel::class)->find($biodata['sekolah_id']);

        if ($sekolah && $sekolah['nama_sekolah'] === DEFAULT_SEKOLAH) {
            $sekolah['nama_sekolah'] = '';
            $sekolah['alamat_sekolah'] = '';
        }

        return $this->renderSiswaView('siswa/form-sekolah', [
            'sekolah'      => $sekolah,
            'nama_lengkap' => !empty($biodata['nama_lengkap']) ? $biodata['nama_lengkap'] : ($user->nisn ?? $user->username),
            'errors'       => session('errors'),
            'currentStep'  => 2,
            'backUrl'      => 'siswa/biodata',
        ]);
    }

    public function updateSekolah(): ResponseInterface
    {
        $biodata = $this->getSiswaOrRedirect(false);

        if (!$this->validateData($this->request->getPost(), 'siswaSekolah')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $this->formulirService->updateSekolah(
                $biodata['id'],
                $this->request->getPost('nama_sekolah'),
                $this->request->getPost('alamat_sekolah')
            );
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->to(base_url('siswa/orang-tua'))->with('success', 'Data asal sekolah berhasil disimpan! Silakan lanjutkan mengisi data orang tua.');
    }

    public function orangTua(): ResponseInterface
    {
        $biodata = $this->getSiswaOrRedirect();
        $user = auth()->user();

        return $this->renderSiswaView('siswa/form-orang-tua', [
            'orang_tua'    => model(\App\Models\Siswa\OrangTuaModel::class)->where('siswa_id', $biodata['id'])->first(),
            'nama_lengkap' => !empty($biodata['nama_lengkap']) ? $biodata['nama_lengkap'] : ($user->nisn ?? $user->username),
            'errors'       => session('errors'),
            'currentStep'  => 3,
            'backUrl'      => 'siswa/sekolah',
        ]);
    }

    public function updateOrangTua(): ResponseInterface
    {
        $biodata = $this->getSiswaOrRedirect(false);

        if (!$this->validateData($this->request->getPost(), 'siswaOrangTua')) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->formulirService->updateOrangTua($biodata['id'], $this->request->getPost([
            'nama_ayah', 'pekerjaan_ayah', 'nama_ibu', 'pekerjaan_ibu',
        ]));

        return redirect()->to(base_url('siswa/berkas'))->with('success', 'Data orang tua berhasil disimpan! Silakan lanjutkan dengan mengunggah berkas.');
    }

    public function berkas(): ResponseInterface
    {
        $biodata = $this->getSiswaOrRedirect();
        $user = auth()->user();

        return $this->renderSiswaView('siswa/form-berkas', [
            'berkas'       => model(\App\Models\Siswa\AdministrasiModel::class)->where('siswa_id', $biodata['id'])->asArray()->first(),
            'nama_lengkap' => !empty($biodata['nama_lengkap']) ? $biodata['nama_lengkap'] : ($user->nisn ?? $user->username),
            'errors'       => session('errors'),
            'currentStep'  => 4,
            'backUrl'      => 'siswa/orang-tua',
            'submitLabel'  => 'Simpan & Selesai',
            'submitIcon'   => 'check',
        ]);
    }

    public function updateBerkas(): ResponseInterface
    {
        $biodata = $this->getSiswaOrRedirect(false);
        $berkas = model(\App\Models\Siswa\AdministrasiModel::class)->where('siswa_id', $biodata['id'])->asArray()->first();

        $rules = $this->formulirService->getBerkasRules($this->request->getFiles(), $berkas);
        $messages = $this->formulirService->getBerkasMessages();

        if (!$this->validateData($this->request->getPost(), $rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->formulirService->updateBerkas($biodata['id'], $this->request->getFiles());

        return redirect()->to(base_url('siswa/dashboard'))->with('success', 'Seluruh berkas persyaratan berhasil diunggah! Harap lakukan finalisasi pendaftaran untuk mengunci data Anda.');
    }
}
