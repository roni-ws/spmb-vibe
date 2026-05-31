<?php
// native script to update database schema with missing columns for Kelulusan and Finalisasi

$host = 'localhost';
$dbName = 'spmb_vibe';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if columns exist in 'berkas' table
    $stmt = $pdo->query("SHOW COLUMNS FROM berkas LIKE 'is_finalized'");
    $hasFinalized = $stmt->fetch();

    $stmt2 = $pdo->query("SHOW COLUMNS FROM berkas LIKE 'status_kelulusan'");
    $hasKelulusan = $stmt2->fetch();

    if (!$hasFinalized) {
        $pdo->exec("ALTER TABLE berkas ADD COLUMN is_finalized TINYINT(1) NOT NULL DEFAULT 0 AFTER status_verifikasi");
        echo "Added 'is_finalized' column to 'berkas' table.\n";
    } else {
        echo "'is_finalized' column already exists.\n";
    }

    if (!$hasKelulusan) {
        $pdo->exec("ALTER TABLE berkas ADD COLUMN status_kelulusan ENUM('Belum Pengumuman', 'Lulus', 'Tidak Lulus') NOT NULL DEFAULT 'Belum Pengumuman' AFTER is_finalized");
        echo "Added 'status_kelulusan' column to 'berkas' table.\n";
    } else {
        echo "'status_kelulusan' column already exists.\n";
    }

    echo "Database schema updated successfully!\n";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
