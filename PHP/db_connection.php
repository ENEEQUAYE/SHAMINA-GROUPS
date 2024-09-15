<?php
$host = 'localhost'; // Your database host
$user = 'root'; // Your database username
$pass = ''; // Your database password
$db = 'shamina'; // Your database name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

