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
    <title>Atur Ulang Kata Sandi <?= esc($logoText) ?></title>
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
                            Kami menerima permintaan untuk mengatur ulang kata sandi akun SPMB Anda. Klik tombol di bawah ini untuk melanjutkan:
                        </p>

                        <!-- CTA Button -->
                        <div style="text-align:center; margin-bottom:24px;">
                            <a href="<?= url_to('reset-password') ?>?token=<?= $token ?>"
                               style="display:inline-block; background:#002045; color:#ffffff; font-size:16px; font-weight:600; font-family:'Segoe UI',Arial,Helvetica,sans-serif; text-decoration:none; border-radius:12px; padding:14px 32px; box-shadow:0 2px 8px rgba(0,32,69,0.2);">
                                Atur Ulang Kata Sandi
                            </a>
                        </div>

                        <!-- Fallback Link -->
                        <p style="font-size:13px; color:#64748b; line-height:1.6; margin:0 0 24px 0; word-break:break-all;">
                            Jika tombol di atas tidak berfungsi, salin dan buka tautan berikut di browser Anda:<br>
                            <span style="color:#002045;"><?= url_to('reset-password') ?>?token=<?= $token ?></span>
                        </p>

                        <p style="font-size:13px; color:#94a3b8; line-height:1.6; margin:0 0 24px 0;">
                            Tautan ini berlaku selama <?= setting('Auth.magicLinkLifetime') / 60 ?> menit dan hanya dapat digunakan satu kali. Jika Anda tidak merasa meminta atur ulang kata sandi, abaikan email ini.
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
