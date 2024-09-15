<?php
session_start();
require 'db_connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all required fields are set
    $required_fields = ['username', 'name', 'email', 'password'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $errors[] = ucfirst($field) . " is required.";
        }
    }

    if (empty($errors)) {
        // Get the form inputs
        $username = $_POST['username'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validate and store the new user in your database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if user exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, name, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $name, $email, $hashedPassword);
            if ($stmt->execute()) {
                header("Location: ../HTML/index.html");
                exit;
            } else {
                $errors[] = "Registration failed: " . $stmt->error;
            }
        } else {
            $errors[] = "User already exists.";
        }
    }

    // If there are errors, display them
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
} else {
    echo "Invalid request method.";
}
?>