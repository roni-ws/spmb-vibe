<?php
use App\Models\SettingsModel;

$kopContent = SettingsModel::getSetting('kop_content', '<h2 style="margin:0; font-size:18px; text-transform:uppercase;">PANITIA PENERIMAAN SISWA BARU (SPMB)</h2><h3 style="margin:2px 0; font-size:16px; font-weight:600;">SMA UNGGUL BANGSA</h3><p style="margin:2px 0;">Jl. Raya Pendidikan No. 45, Jakarta Pusat</p><p style="margin:2px 0;">Telp: (021) 555 0123 | Website: spmb.unggulbangsa.sch.id</p>');

$logoImg = SettingsModel::getSetting('logo_img');
if (!empty($logoImg) && file_exists(FCPATH . $logoImg)) {
    $logoUrl = base_url($logoImg);
} else {
    $logoUrl = base_url('images/logo-default.png');
}
?>
