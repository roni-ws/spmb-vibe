# SPMB Vibe — Agent Guide

## Stack
CI4.7 + Shield 1.3, PHP 8.2+, MySQL (MySQLi), Tailwind CSS v4, TCPDF, PhpSpreadsheet.

## Quick start
- `npm run build` / `npm run watch` — compile Tailwind from `public/css/input.css` -> `public/css/landing-page.css`
- `composer test` or `vendor/bin/phpunit` — run tests (empty currently)
- `php spark` — CI4 CLI

## App structure
- **Dual user tables:** `users` (admin/panitia), `users_siswa` (students). NISN is student username. Bridged via `UnifiedUserModel`.
- **Auth:** Shield session auth. `session` filter for students, `group:admin,panitia` for admin, `group:admin` for user management. Register has no email verification (`actions.register = null` in `Config/Auth.php`). Login redirect is role-aware (admin->admin/dashboard, student->siswa/dashboard). Login fields: email, username, nisn.
- **Constants file:** `app/Config/Constants.php` — use `VERIF_STATUS_VALID`, `VERIF_ID_BELUM`, `SETTING_IS_OPEN`, etc., never raw strings.
- **Settings:** Dynamic key-value store in `settings` table. Access via `setting('App.' . $key)` or `SettingsModel::getSetting()`.
- **Routes** in `app/Config/Routes.php`. Shield auto-adds routes via `service('auth')->routes($routes)` (magic link, email activation, etc.).
- **Services layer** in `app/Services/` (8 classes). Business logic lives here, not in controllers.
- **Migrations** in `app/Database/Migrations/`. Naming: `YYYY-MM-DD-HHIISS_Description.php`
- **Templating:** CI4 `view()`. Use `esc()` for output. `field_error()` / `has_error()` helpers in `app/Helpers/view_helper.php`.
- **Groups:** `admin`, `panitia`, `developer`, `user`, `beta`. See `Config/AuthGroups.php`.

## Gotchas
- `.env` sets `CI_ENVIRONMENT=development` and DB creds (`spmb_vibe`). Root `.htaccess` rewrites to `public/`.
- No `.vscode/`, `.opencode/`, `scratch/`, `phpunit.xml` in git.
- Auth rate-limiting active on `/login*`, `/register*`, `/auth/*` via `auth-rates` filter.
- Password: min 8 chars, checked against CompositionValidator, NothingPersonalValidator, DictionaryValidator. Similarity max 50%.
- Some models return Entities by default — use `->asArray()` when you need plain arrays.

## Existing plans
None
