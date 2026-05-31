<?php
$db = new mysqli('localhost', 'root', '', 'spmb_vibe');
if ($db->connect_error) die("Error: " . $db->connect_error);

// Check users table for panitia_test
$result = $db->query("SELECT u.id, u.username, u.role, ai.secret as email 
    FROM users u 
    LEFT JOIN auth_identities ai ON ai.user_id = u.id AND ai.type='email_password'
    WHERE u.role != 'student' OR u.username='admin'
    ORDER BY u.id");
echo "=== Staff Accounts ===\n";
while ($row = $result->fetch_assoc()) {
    echo "ID:{$row['id']} | username:{$row['username']} | role:{$row['role']} | email:{$row['email']}\n";
}

// Check groups
echo "\n=== Shield Groups ===\n";
$result = $db->query("SELECT u.username, agu.group FROM users u 
    JOIN auth_groups_users agu ON agu.user_id = u.id 
    ORDER BY u.id");
while ($row = $result->fetch_assoc()) {
    echo "username:{$row['username']} | group:{$row['group']}\n";
}
$db->close();
