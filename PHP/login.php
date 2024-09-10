<?php
session_start();
require 'db_connection.php'; // Include your database connection

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Debugging: Log the email being checked
    error_log("Checking email: $email");

    // Perform validation and check against your database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    if ($stmt === false) {
        error_log("Error preparing statement: " . $conn->error);
        die("Error preparing statement.");
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Regenerate session ID
            session_regenerate_id(true);

            // Set session variables for user information
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Debugging: Log successful login
            error_log("Login successful for user: " . $user['username']);

            header("Location: ../HTML/index.html");
            exit();
        }
    }
    $error = "Invalid login credentials.";
    error_log("Login failed: $error");

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
