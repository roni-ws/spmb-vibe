<?php
// native script to update dummy passwords with correct bcrypt hashes

$host = 'localhost';
$dbName = 'spmb_vibe';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $users = [
        1 => 'admin123',
        2 => 'budi123',
        3 => 'siti123'
    ];

    foreach ($users as $id => $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        
        // Update users table
        $stmt1 = $pdo->prepare("UPDATE users SET password_hash = :hash WHERE id = :id");
        $stmt1->execute(['hash' => $hash, 'id' => $id]);

        // Update auth_identities table
        $stmt2 = $pdo->prepare("UPDATE auth_identities SET secret2 = :hash WHERE user_id = :id AND type = 'email_password'");
        $stmt2->execute(['hash' => $hash, 'id' => $id]);
        
        echo "Updated user ID $id with new hash for password '$password'.\n";
    }

    echo "All dummy passwords updated successfully!\n";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
