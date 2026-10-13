# SPMB Vibe — Agent Guide

CI4.7 + Shield 1.3 school admission app (SPMB). PHP 8.2+, MySQL (MySQLi), Tailwind CSS v4, TCPDF, PhpSpreadsheet, Grid.js (vanilla JS, jQuery present).

## Dev commands
- `php spark serve` — dev server on :8080 (matches `app.baseURL` in `.env`). Alternative: Caddy HTTPS — see `CADDY_GUIDE.md` + `Caddyfile` (`caddy.exe` + `php-cgi -b 127.0.0.1:9000`).
- `npm run build` / `npm run watch` — Tailwind. Run after ANY change in `app/Views/**/*.php` or `public/js/**/*.js`, since `input.css` `@source`-scans both into `public/css/landing-page.css`.
- **CSS gotcha:** `landing-page.css` is the only generated file; it is loaded by every layout via `partials/head-content.php`. `public/css/admin.css` and `print-*.css` are hand-written (do NOT rebuild), loaded additionally by admin/siswa/print layouts.
- `composer test` (= `phpunit`, config `phpunit.dist.xml`; no `phpunit.xml` — do not create one). Tests are CI4 scaffolding only; no real tests yet.
- No JS bundler/test step; JS is plain `<script>` files loaded per layout.

## Architecture
- **Dual user tables:** `users` (admin/panitia), `users_siswa` (students). NISN doubles as student username. `UnifiedUserModel` dynamically switches its `$table` between the two and bridges Shield `auth_identities` for passwords; `findByCredentials()` matches `nisn` when login field is `username`.
- **Auth (Shield session):** `session` filter = students, `group:admin,panitia` = admin area, `group:admin` = user management/settings/pengumuman. `Auth::loginRedirect()` is role-aware. Valid login fields: email, username, nisn.
- **Registration requires email activation:** `Auth::$actions['register'] = EmailActivator::class`, with custom views `auth/email_activate_show.php` / `auth/email_activate_email.php` (also custom `reset_password.php`, `magic_link_email.php`). `Register` controller redirects into Shield's `auth/a/show` flow.
- **Password rules are unusual:** `Auth::$minimumPasswordLength = 1`; real rule is the `regex_match` in `Config/Validation.php::$pendaftaranSiswa` (must contain letter + digit + symbol). `PwnedValidator` is disabled. Don't "fix" the min length or add `min_length[8]`.
- **Constants:** `app/Config/Constants.php` — use `VERIF_STATUS_*`, `VERIF_STATUS_ID_*`, `ROLE_*`, `NOTIF_*`, `SETTING_*`, etc., never raw strings.
- **Settings:** key-value `settings` table; read/write via `SettingsModel::getSetting($key)` / `setSetting()`, which wrap `setting('App.'.$key)`. Called only by admin group.
- **Services** (`app/Services/`, 8 classes) hold business logic; controllers stay thin. `BaseController` has shared guards (`requireGroup`, `requireSiswa`, `canCetakBukti`) and settings loaders.
- **Routes:** `app/Config/Routes.php`; Shield appends its own (`auth/a/*`, magic link, etc.). Not using auto-routing.
- **Views:** CI4 `view()`. `esc()` for output; `field_error()` / `has_error()` in `app/Helpers/view_helper.php`. Layouts in `app/Views/layouts/`, shared parts in `partials/` and `components/`.
- **Groups:** `admin`, `panitia`, `developer`, `user`, `beta` (`Config/AuthGroups.php`). Students are `user`.

## Gotchas
- **`.env` overrides `Config/*.php`.** Current `.env`: `cookie.secure = true` and `app.CSPEnabled = false`, `app.forceGlobalSecureRequests = false`. `cookie.secure=true` needs HTTPS — for plain `php spark serve` over HTTP you must set it false or login cookies won't persist. Don't flip `Database.php` for `DBDebug`; use `.env`.
- **CSRF `Security::$regenerate = false` is intentional** so the register AJAX `check-email`/`check-username` calls get a stable token. CSRF is global; excepted routes: `admin|siswa/notifications/mark-as-read`.
- Rate limiting (`auth-rates`) on `login*`, `register*`, `auth/*`, `forgot-password*`, `reset-password*`.
- Uploaded berkas live in `public/uploads/` (`FCPATH . 'uploads/'`), guarded by `public/uploads/.htaccess` (php engine off, image/pdf allowlist). Filenames use `random_bytes`.
- Some models return Entities by default — use `->asArray()` for plain arrays.
- Third-party JS/CSS is vendored under `public/vendor/` and `public/assets/` (Grid.js, jQuery, ApexCharts), not installed via npm.
- `.\caddy.exe`, `database_spmb_vibe.sql`, `schema.sql`, `builds`, `-w` are local-only/untracked; don't rely on or commit them.

## Existing plans (`.agent/`)
`plan_perbaikan_keamanan.md`, `plan_perbaikan_verifikasi_berkas.md`, `plan_migrasi_gridjs.md`, `clean_code_architecture.md`, `catatan.md` (session log of auth/CSP/CSRF decisions).
