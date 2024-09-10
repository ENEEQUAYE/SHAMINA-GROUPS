<?php
session_start();
require 'db_connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form inputs
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate and store the new user in your database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the user already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Insert the new user
        $stmt = $conn->prepare("INSERT INTO users (username, name, email, password) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            die("Error preparing insert statement: " . $conn->error);
        }
        $stmt->bind_param("ssss", $username, $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            // Redirect to MainLogin.html after successful registration
            header("Location: ../HTML/MainLogin.html");
            exit;
        } else {
            echo "Registration failed: " . $stmt->error;
        }
    } else {
        echo "User already exists.";
    }
} else {
    echo "Invalid request method.";
}
?>
