<?php
include 'db_connection.php';

$username = 'admin';
$password = password_hash('123', PASSWORD_DEFAULT); // Secure hash

$sql = "INSERT INTO admin (username, password) VALUES ('$username', '$password')";
$conn->query($sql);

echo "Admin user created!";
?>
