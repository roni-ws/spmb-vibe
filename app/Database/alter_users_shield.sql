-- ============================================================
-- Alter users table to be compatible with CodeIgniter Shield
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Drop foreign key constraints yang mereferensi users.id
ALTER TABLE berkas  DROP FOREIGN KEY berkas_ibfk_1;
ALTER TABLE biodata DROP FOREIGN KEY biodata_ibfk_1;
ALTER TABLE sekolah DROP FOREIGN KEY sekolah_ibfk_1;

-- 2. Ubah tipe id menjadi INT UNSIGNED (dibutuhkan Shield)
ALTER TABLE users MODIFY COLUMN id int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

-- 3. Sesuaikan tipe user_id di tabel relasional
ALTER TABLE berkas  MODIFY COLUMN user_id int(11) UNSIGNED NOT NULL;
ALTER TABLE biodata MODIFY COLUMN user_id int(11) UNSIGNED NOT NULL;
ALTER TABLE sekolah MODIFY COLUMN user_id int(11) UNSIGNED NOT NULL;

-- 4. Tambahkan kolom-kolom Shield yang dibutuhkan
ALTER TABLE users
    ADD COLUMN status varchar(255) NULL          AFTER username,
    ADD COLUMN status_message varchar(255) NULL  AFTER status,
    ADD COLUMN active tinyint(1) NOT NULL DEFAULT 0 AFTER status_message,
    ADD COLUMN last_active datetime NULL         AFTER active,
    ADD COLUMN deleted_at datetime NULL          AFTER updated_at;

-- 5. Set semua pengguna existing sebagai active = 1
UPDATE users SET active = 1;

-- 6. Re-create foreign key constraints
ALTER TABLE berkas  ADD CONSTRAINT berkas_ibfk_1  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
ALTER TABLE biodata ADD CONSTRAINT biodata_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;
ALTER TABLE sekolah ADD CONSTRAINT sekolah_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

SET FOREIGN_KEY_CHECKS = 1;
