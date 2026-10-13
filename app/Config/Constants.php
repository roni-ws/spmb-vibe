<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

/*
 | --------------------------------------------------------------------
 | Aplikasi SPMB — Status & Default Values
 | --------------------------------------------------------------------
 */
defined('VERIF_STATUS_VALID')        || define('VERIF_STATUS_VALID', 'Valid');
defined('VERIF_STATUS_REVISI')       || define('VERIF_STATUS_REVISI', 'Revisi');
defined('VERIF_STATUS_TIDAK_VALID')  || define('VERIF_STATUS_TIDAK_VALID', 'Tidak Valid');
defined('VERIF_STATUS_BELUM')        || define('VERIF_STATUS_BELUM', 'Belum Verifikasi');

defined('VERIF_ID_BELUM')    || define('VERIF_ID_BELUM', 1);
defined('VERIF_ID_REVISI')   || define('VERIF_ID_REVISI', 2);
defined('VERIF_ID_VALID')    || define('VERIF_ID_VALID', 3);
defined('VERIF_ID_TIDAK_VALID') || define('VERIF_ID_TIDAK_VALID', 4);

defined('DEFAULT_SEKOLAH')   || define('DEFAULT_SEKOLAH', 'Belum Diisi');
defined('DEFAULT_GENDER')    || define('DEFAULT_GENDER', 'L');
defined('DEFAULT_TTL')       || define('DEFAULT_TTL', '2008-01-01');
defined('REG_PREFIX')        || define('REG_PREFIX', 'REG-');
defined('HP_EMPTY')          || define('HP_EMPTY', '-');
defined('DEFAULT_BELUM_MENGISI') || define('DEFAULT_BELUM_MENGISI', 'Belum Mengisi');
defined('STATUS_BELUM_FINAL')    || define('STATUS_BELUM_FINAL', 'Belum Final');

defined('SETTING_IS_OPEN')      || define('SETTING_IS_OPEN', 'is_open');
defined('SETTING_TAHUN_AJARAN') || define('SETTING_TAHUN_AJARAN', 'tahun_ajaran');
defined('SETTING_LOGO_TEXT')    || define('SETTING_LOGO_TEXT', 'logo_text');
defined('SETTING_FOOTER_DESC')  || define('SETTING_FOOTER_DESC', 'footer_desc');

defined('SETTING_RECAPTCHA_SITE')   || define('SETTING_RECAPTCHA_SITE', 'recaptcha_site_key');
defined('SETTING_RECAPTCHA_SECRET') || define('SETTING_RECAPTCHA_SECRET', 'recaptcha_secret_key');

defined('ROLE_ADMIN')    || define('ROLE_ADMIN', 'admin');
defined('ROLE_PANITIA')  || define('ROLE_PANITIA', 'panitia');
defined('ROLE_STUDENT')  || define('ROLE_STUDENT', 'student');
defined('GROUP_USER')    || define('GROUP_USER', 'user');

defined('UPLOAD_PATH')         || define('UPLOAD_PATH', 'uploads/berkas/');

defined('NOTIF_SISWA_WELCOME')          || define('NOTIF_SISWA_WELCOME', 'welcome');
defined('NOTIF_SISWA_STATUS_CHANGE')    || define('NOTIF_SISWA_STATUS_CHANGE', 'status_change');
defined('NOTIF_SISWA_PENGUMUMAN')       || define('NOTIF_SISWA_PENGUMUMAN', 'pengumuman');
defined('NOTIF_ADMIN_SISWA_BARU')       || define('NOTIF_ADMIN_SISWA_BARU', 'siswa_baru');
defined('NOTIF_ADMIN_SISWA_FINALIZED')  || define('NOTIF_ADMIN_SISWA_FINALIZED', 'siswa_finalized');
defined('NOTIF_DROPDOWN_LIMIT')         || define('NOTIF_DROPDOWN_LIMIT', 5);
