<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingsModel;
use App\Services\StudentService;
use CodeIgniter\HTTP\ResponseInterface;

class Export extends BaseController
{
    protected $helpers = ['url', 'html', 'form'];

    public function csv(): void
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);

        $studentService = new StudentService();
        $students = $studentService->getExportQuery();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=daftar_pendaftar_spmb_' . date('Ymd_His') . '.csv');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, [
            'ID', 'NISN', 'No. Registrasi', 'NIK', 'No. KK', 'Nama Lengkap',
            'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin', 'Anak Ke-',
            'Jumlah Saudara', 'No. HP', 'Alamat Desa', 'Alamat Kecamatan',
            'Alamat Kabupaten', 'Asal Sekolah', 'Alamat Sekolah',
            'Nama Ayah', 'Pekerjaan Ayah', 'Nama Ibu', 'Pekerjaan Ibu',
            'Status Berkas', 'Status Finalisasi'
        ]);

        foreach ($students as $student) {
            fputcsv($output, [
                $student['id'],
                $student['username'] ? "\t" . $student['username'] : HP_EMPTY,
                $student['no_registrasi'] ? "\t" . $student['no_registrasi'] : HP_EMPTY,
                $student['nik'] ? "\t" . $student['nik'] : HP_EMPTY,
                $student['no_kk'] ? "\t" . $student['no_kk'] : HP_EMPTY,
                $student['nama_lengkap'] ?: DEFAULT_BELUM_MENGISI,
                $student['tempat_lahir'] ?: HP_EMPTY,
                $student['tanggal_lahir'] ?: HP_EMPTY,
                $student['jenis_kelamin'] ?: HP_EMPTY,
                $student['anak_ke'] ?: HP_EMPTY,
                $student['jumlah_saudara'] ?: HP_EMPTY,
                $student['no_hp'] ? "\t" . $student['no_hp'] : HP_EMPTY,
                $student['alamat_desa'] ?: HP_EMPTY,
                $student['alamat_kecamatan'] ?: HP_EMPTY,
                $student['alamat_kabupaten'] ?: HP_EMPTY,
                $student['nama_sekolah'] ?: HP_EMPTY,
                $student['alamat_sekolah'] ?: HP_EMPTY,
                $student['nama_ayah'] ?: HP_EMPTY,
                $student['pekerjaan_ayah'] ?: HP_EMPTY,
                $student['nama_ibu'] ?: HP_EMPTY,
                $student['pekerjaan_ibu'] ?: HP_EMPTY,
                $student['status_verifikasi'] ?: VERIF_STATUS_BELUM,
                $student['is_finalized'] ? 'Selesai' : STATUS_BELUM_FINAL
            ]);
        }
        fclose($output);
        exit;
    }

    public function printData(): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);

        $studentService = new StudentService();
        $students = $studentService->getExportQuery();

        return $this->response->setBody(view('admin/cetak-data', [
            'students'       => $students,
            'logoText'       => SettingsModel::getSetting('logo_text', 'SPMB Unggul Bangsa'),
            'logoImg'        => SettingsModel::getSetting('logo_img', ''),
            'footer_address' => SettingsModel::getSetting('footer_address', 'Jakarta'),
        ]));
    }

    public function printFormulir(int $studentId): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);

        $studentService = new StudentService();
        $student = $studentService->getStudentForCetak((int) $studentId);

        if (!$student) {
            return redirect()->back()->with('error', 'Data pendaftar tidak ditemukan.');
        }

        $settings = [
            'kota_ttd'     => SettingsModel::getSetting('kota_ttd', 'Jakarta'),
            'ketua_panitia' => SettingsModel::getSetting('ketua_panitia', ''),
            'logoText'     => SettingsModel::getSetting('logo_text', 'SPMB Unggul Bangsa'),
            'logoImg'      => SettingsModel::getSetting('logo_img', ''),
        ];
        return $this->response->setBody(view('siswa/cetak-formulir', array_merge(['student' => $student], $settings)));
    }

    public function printArsip(int $studentId): ResponseInterface
    {
        $this->requireGroup([ROLE_ADMIN, ROLE_PANITIA]);

        $studentService = new StudentService();
        $student = $studentService->getStudentForCetak((int) $studentId);

        if (!$student) {
            return redirect()->back()->with('error', 'Data pendaftar tidak ditemukan.');
        }

        $settings = [
            'kota_ttd'      => SettingsModel::getSetting('kota_ttd', 'Jakarta'),
            'ketua_panitia'  => SettingsModel::getSetting('ketua_panitia', ''),
            'logoText'      => SettingsModel::getSetting('logo_text', 'SPMB Unggul Bangsa'),
            'logoImg'       => SettingsModel::getSetting('logo_img', ''),
        ];
        return $this->response->setBody(view('admin/cetak-arsip', array_merge(['student' => $student], $settings)));
    }
}
