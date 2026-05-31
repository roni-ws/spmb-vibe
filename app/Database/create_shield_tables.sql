-- =============================================================================
-- Buat tabel-tabel Shield yang hilang secara manual
-- (Karena tabel users sudah ada dan dimodifikasi secara manual)
-- =============================================================================

USE spmb_vibe;

-- Tabel auth_identities (email/password & token credentials)
CREATE TABLE IF NOT EXISTS `auth_identities` (
    `id`            int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`       int(11) UNSIGNED NOT NULL,
    `type`          varchar(255) NOT NULL,
    `name`          varchar(255) NULL,
    `secret`        varchar(255) NOT NULL,
    `secret2`       varchar(255) NULL,
    `expires`       datetime NULL,
    `extra`         text NULL,
    `force_reset`   tinyint(1) NOT NULL DEFAULT 0,
    `last_used_at`  datetime NULL,
    `created_at`    datetime NULL,
    `updated_at`    datetime NULL,
    CONSTRAINT `pk_auth_identities` PRIMARY KEY(`id`),
    UNIQUE KEY `auth_identities_type_secret` (`type`, `secret`),
    CONSTRAINT `auth_identities_user_id_foreign` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- Tabel auth_logins (log percobaan login)
CREATE TABLE IF NOT EXISTS `auth_logins` (
    `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `ip_address` varchar(255) NULL,
    `user_agent` varchar(255) NULL,
    `id_type`    varchar(255) NOT NULL,
    `identifier` varchar(255) NOT NULL,
    `user_id`    int(11) UNSIGNED NULL,
    `date`       datetime NOT NULL,
    `success`    tinyint(1) NOT NULL,
    CONSTRAINT `pk_auth_logins` PRIMARY KEY(`id`),
    KEY `auth_logins_identifier` (`identifier`),
    KEY `auth_logins_user_id` (`user_id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- Tabel auth_token_logins (personal access tokens)
CREATE TABLE IF NOT EXISTS `auth_token_logins` (
    `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `ip_address` varchar(255) NULL,
    `user_agent` varchar(255) NULL,
    `id_type`    varchar(255) NOT NULL,
    `identifier` varchar(255) NOT NULL,
    `user_id`    int(11) UNSIGNED NULL,
    `date`       datetime NOT NULL,
    `success`    tinyint(1) NOT NULL,
    CONSTRAINT `pk_auth_token_logins` PRIMARY KEY(`id`),
    KEY `auth_token_logins_identifier` (`identifier`),
    KEY `auth_token_logins_user_id` (`user_id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- Tabel auth_remember_tokens (remember me tokens)
CREATE TABLE IF NOT EXISTS `auth_remember_tokens` (
    `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `selector`   varchar(255) NOT NULL,
    `hashedValidator` varchar(255) NOT NULL,
    `user_id`    int(11) UNSIGNED NOT NULL,
    `expires`    datetime NOT NULL,
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    CONSTRAINT `pk_auth_remember_tokens` PRIMARY KEY(`id`),
    CONSTRAINT `auth_remember_tokens_user_id_foreign` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- Tabel auth_groups_users (RBAC: grup per pengguna)
CREATE TABLE IF NOT EXISTS `auth_groups_users` (
    `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`    int(11) UNSIGNED NOT NULL,
    `group`      varchar(255) NOT NULL,
    `created_at` datetime NULL,
    CONSTRAINT `pk_auth_groups_users` PRIMARY KEY(`id`),
    UNIQUE KEY `auth_groups_users_user_id_group` (`user_id`, `group`),
    KEY `auth_groups_users_group` (`group`),
    CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- Tabel auth_permissions_users (RBAC: izin per pengguna)
CREATE TABLE IF NOT EXISTS `auth_permissions_users` (
    `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`    int(11) UNSIGNED NOT NULL,
    `permission` varchar(255) NOT NULL,
    `created_at` datetime NULL,
    CONSTRAINT `pk_auth_permissions_users` PRIMARY KEY(`id`),
    UNIQUE KEY `auth_permissions_users_user_id_permission` (`user_id`, `permission`),
    CONSTRAINT `auth_permissions_users_user_id_foreign` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- Tabel settings (digunakan oleh codeigniter4/settings)
CREATE TABLE IF NOT EXISTS `settings` (
    `id`         int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `class`      varchar(255) NOT NULL,
    `key`        varchar(255) NOT NULL,
    `value`      text NULL,
    `type`       varchar(31) NOT NULL DEFAULT 'string',
    `context`    varchar(255) NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    CONSTRAINT `pk_settings` PRIMARY KEY(`id`),
    UNIQUE KEY `settings_class_key_context` (`class`, `key`, `context`)
) ENGINE = InnoDB DEFAULT CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- =============================================================================
-- Catat migrasi Shield sebagai selesai di tabel migrations
-- agar perintah 'spark migrate' tidak mencoba membuat ulang tabel users
-- =============================================================================
INSERT IGNORE INTO `migrations` (`version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
('2020-12-28-223112', 'CodeIgniter\\Shield\\Database\\Migrations\\CreateAuthTables', 'default', 'CodeIgniter\\Shield', UNIX_TIMESTAMP(), 1);

-- Seed grup RBAC untuk pengguna dummy yang sudah ada
INSERT IGNORE INTO `auth_groups_users` (user_id, `group`, created_at) VALUES
(1, 'admin', NOW()),
(2, 'student', NOW()),
(3, 'student', NOW());

-- Seed auth_identities dengan email/password untuk pengguna dummy
INSERT IGNORE INTO `auth_identities` (user_id, type, secret, secret2, force_reset, created_at, updated_at) VALUES
(1, 'email_password', 'admin@unggulbangsa.sch.id', '$2y$10$HAXTwKFS0WnNpDaVMi0IBee6VGsJERLi72S/rZtd1PCYJYH9UWmsy', 0, NOW(), NOW()),
(2, 'email_password', 'budi.santoso@gmail.com',   '$2y$10$HtD5LVtp0Nu9MXqec701heyiQ78xW1SFBHle06K9Xz0aotgik8ok2', 0, NOW(), NOW()),
(3, 'email_password', 'siti.aminah@gmail.com',    '$2y$10$fkBQULTxG3y6le9tlGEyNO1I7qPsGfi3eUjqcY4Z8Dovj75HH1A7G', 0, NOW(), NOW());
