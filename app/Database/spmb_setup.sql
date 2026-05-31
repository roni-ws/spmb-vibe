-- ====================================================================
-- Database Schema & Dummy Data Setup for SPMB SMA Unggul Bangsa
-- ====================================================================

-- Pastikan database spmb_vibe sedang digunakan
USE spmb_vibe;

-- Hapus tabel yang ada demi kebersihan setup (urutan berdasarkan dependensi)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS berkas;
DROP TABLE IF EXISTS sekolah;
DROP TABLE IF EXISTS biodata;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------------------
-- 1. Tabel Akun Pengguna (Admin & Siswa)
-- --------------------------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') NOT NULL DEFAULT 'student',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 2. Tabel Biodata Diri & Orang Tua / Wali Calon Siswa
-- --------------------------------------------------------------------
CREATE TABLE biodata (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nama_lengkap VARCHAR(255) NOT NULL,
    nik VARCHAR(16) NOT NULL UNIQUE,
    tempat_lahir VARCHAR(100) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    no_hp VARCHAR(15) NOT NULL,
    nama_ayah VARCHAR(255) NOT NULL,
    pekerjaan_ayah VARCHAR(150) NOT NULL,
    nama_ibu VARCHAR(255) NOT NULL,
    pekerjaan_ibu VARCHAR(150) NOT NULL,
    alamat TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 3. Tabel Data Asal Sekolah Calon Siswa
-- --------------------------------------------------------------------
CREATE TABLE sekolah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nama_sekolah VARCHAR(255) NOT NULL,
    npsn VARCHAR(8) NOT NULL,
    tahun_lulus INT NOT NULL,
    nilai_rata_rata DECIMAL(5,2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------------
-- 4. Tabel Dokumen Berkas & Status Verifikasi
-- --------------------------------------------------------------------
CREATE TABLE berkas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    file_foto VARCHAR(255) DEFAULT NULL,
    file_ijazah VARCHAR(255) DEFAULT NULL,
    file_kk VARCHAR(255) DEFAULT NULL,
    status_verifikasi ENUM('Pending', 'Valid', 'Tidak Valid', 'Revisi') NOT NULL DEFAULT 'Pending',
    is_finalized TINYINT(1) NOT NULL DEFAULT 0,
    status_kelulusan ENUM('Belum Pengumuman', 'Lulus', 'Tidak Lulus') NOT NULL DEFAULT 'Belum Pengumuman',
    catatan_verifikasi TEXT DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ====================================================================
-- SEEDING DATA DUMMY (Indonesian Localized & Bcrypt Hashed Passwords)
-- ====================================================================

-- 1. Seeding Tabel Users
-- Password untuk semua akun dummy adalah username diikuti '123' (Contoh: admin123, budi123, siti123)
-- Hash di bawah dihasilkan menggunakan standar password_hash() Bcrypt PHP
INSERT INTO users (id, username, email, password_hash, role) VALUES
(1, 'admin', 'admin@unggulbangsa.sch.id', '$2y$10$HAXTwKFS0WnNpDaVMi0IBee6VGsJERLi72S/rZtd1PCYJYH9UWmsy', 'admin'),
(2, 'budi', 'budi.santoso@gmail.com', '$2y$10$HtD5LVtp0Nu9MXqec701heyiQ78xW1SFBHle06K9Xz0aotgik8ok2', 'student'),
(3, 'siti', 'siti.aminah@gmail.com', '$2y$10$fkBQULTxG3y6le9tlGEyNO1I7qPsGfi3eUjqcY4Z8Dovj75HH1A7G', 'student');

-- 2. Seeding Tabel Biodata
INSERT INTO biodata (id, user_id, nama_lengkap, nik, tempat_lahir, tanggal_lahir, jenis_kelamin, no_hp, nama_ayah, pekerjaan_ayah, nama_ibu, pekerjaan_ibu, alamat) VALUES
(1, 2, 'Budi Santoso', '3171012345670001', 'Jakarta', '2010-05-12', 'L', '081234567890', 'Supardi Santoso', 'Wiraswasta', 'Siti Rahmawati', 'Ibu Rumah Tangga', 'Jl. Merdeka No. 12, RT 03/RW 04, Kel. Gambir, Kec. Gambir, Jakarta Pusat'),
(2, 3, 'Siti Aminah', '3171012345670002', 'Bandung', '2010-09-24', 'P', '082345678901', 'Ahmad Hidayat', 'PNS', 'Laila Sari', 'Guru', 'Jl. Dago Elos No. 45, RT 01/RW 02, Kel. Dago, Kec. Coblong, Kota Bandung');

-- 3. Seeding Tabel Sekolah
INSERT INTO sekolah (id, user_id, nama_sekolah, npsn, tahun_lulus, nilai_rata_rata) VALUES
(1, 2, 'SMP Negeri 1 Jakarta', '20104523', 2026, 88.50),
(2, 3, 'SMP Islam Terpadu Bandung', '20235678', 2026, 92.75);

-- 4. Seeding Tabel Berkas
INSERT INTO berkas (id, user_id, file_foto, file_ijazah, file_kk, status_verifikasi, catatan_verifikasi) VALUES
(1, 2, 'foto_budi_hash.jpg', 'ijazah_budi_hash.pdf', 'kk_budi_hash.pdf', 'Pending', NULL),
(2, 3, 'foto_siti_hash.jpg', 'ijazah_siti_hash.pdf', 'kk_siti_hash.pdf', 'Valid', 'Berkas biodata diri dan nilai rapor SMP dinyatakan lengkap dan valid oleh panitia.');
