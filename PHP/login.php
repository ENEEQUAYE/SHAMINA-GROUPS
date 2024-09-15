<?php
session_start();
require 'db_connection.php'; // Include your database connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize email and password
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    // Debugging: Log the email being checked
    error_log("Checking email: $email");

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: ../HTML/index.html");
        exit();
    } else {
        $error = "Invalid login credentials.";
    }
} else {
    $error = "Invalid login credentials.";
}

    // Debugging: If headers have already been sent
    if (headers_sent()) {
        error_log("Headers already sent.");
    }
}
?>

<!-- Display error message if present -->
<?php if (!empty($error)) : ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>
