<?php
$db = new mysqli('localhost', 'root', '', 'spmb_vibe');
if ($db->connect_error) die("Error: " . $db->connect_error);

$result = $db->query("SELECT COUNT(*) as count FROM users WHERE role='student'");
$row = $result->fetch_assoc();
echo "Total student accounts: " . $row['count'] . "\n";

$result = $db->query("SELECT u.id, u.username, b.nama_lengkap, bk.status_verifikasi 
    FROM users u 
    LEFT JOIN biodata b ON b.user_id = u.id
    LEFT JOIN berkas bk ON bk.user_id = u.id
    WHERE u.role='student'
    LIMIT 10");
echo "=== Student Accounts ===\n";
while ($row = $result->fetch_assoc()) {
    echo "ID:{$row['id']} | username:{$row['username']} | name:{$row['nama_lengkap']} | verifikasi:{$row['status_verifikasi']}\n";
}
$db->close();
