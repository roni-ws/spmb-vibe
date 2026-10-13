<?php
$logoText    = \App\Models\SettingsModel::getSetting('logo_text', 'SPMB Unggul Bangsa');
$footerEmail = \App\Models\SettingsModel::getSetting('footer_email', 'smawahasmodel@gmail.com');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="x-apple-disable-message-reformatting">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Kode Aktivasi Akun <?= esc($logoText) ?></title>
</head>
<body style="margin:0; padding:0; background-color:#f4f5f7; font-family:'Segoe UI',Arial,Helvetica,sans-serif;">
    <table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;">
        <tr>
            <td style="padding:24px 16px;">
                <div style="max-width:480px; margin:0 auto; background:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <div style="background:#002045; padding:24px; text-align:center;">
                        <h1 style="color:#ffffff; font-size:18px; margin:0; font-weight:600;"><?= esc($logoText) ?></h1>
                        <p style="color:#94a3b8; font-size:13px; margin:4px 0 0 0;">Penerimaan Siswa Baru</p>
                    </div>

                    <!-- Body -->
                    <div style="padding:32px 24px;">
                        <p style="font-size:15px; color:#334155; line-height:1.6; margin:0 0 20px 0;">
                            Halo <strong><?= esc($user->username) ?></strong>,
                        </p>
                        <p style="font-size:15px; color:#334155; line-height:1.6; margin:0 0 24px 0;">
                            Gunakan kode verifikasi di bawah ini untuk mengaktifkan akun SPMB Anda.
                        </p>

                        <!-- Code Box -->
                        <div style="background:#f1f5f9; border-radius:12px; padding:24px; text-align:center; margin-bottom:24px;">
                            <span style="font-size:36px; font-weight:700; letter-spacing:8px; color:#002045; font-family:monospace;"><?= esc($code) ?></span>
                        </div>

                        <p style="font-size:14px; color:#64748b; line-height:1.6; margin:0 0 8px 0;">
                            Kode ini hanya berlaku sementara. Jangan bagikan kode ini kepada siapa pun.
                        </p>

                        <hr style="border:none; border-top:1px solid #e2e8f0; margin:24px 0;">

                        <!-- Account Info -->
                        <p style="font-size:13px; color:#64748b; margin:0 0 4px 0;"><strong style="color:#334155;">Username:</strong> <?= esc($user->username) ?></p>
                        <p style="font-size:13px; color:#64748b; margin:0 0 4px 0;"><strong style="color:#334155;">Alamat IP:</strong> <?= esc($ipAddress) ?></p>
                        <p style="font-size:13px; color:#64748b; margin:0 0 4px 0;"><strong style="color:#334155;">Perangkat:</strong> <?= esc($userAgent) ?></p>
                        <p style="font-size:13px; color:#64748b; margin:0 0 4px 0;"><strong style="color:#334155;">Tanggal:</strong> <?= esc($date) ?></p>

                        <hr style="border:none; border-top:1px solid #e2e8f0; margin:24px 0;">

                        <!-- Spam Notice -->
                        <div style="background:#fef9c3; border-radius:8px; padding:12px 16px; border:1px solid #fde047;">
                            <p style="font-size:13px; color:#854d0e; margin:0;">
                                <strong>Tip:</strong> Jika tidak menemukan email ini di kotak masuk (inbox), silakan periksa folder <strong>Spam</strong> atau <strong>Promosi</strong> di aplikasi email Anda.
                            </p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div style="background:#f8fafc; padding:16px 24px; text-align:center; border-top:1px solid #e2e8f0;">
                        <p style="font-size:12px; color:#94a3b8; margin:0;">
                            <?= esc($logoText) ?> &bull; <?= esc($footerEmail) ?>
                        </p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
