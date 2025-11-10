<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gaming_laptop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>