<?php
// Load Composer's autoloader
require '../vendor/autoload.php'; // Adjust the path if necessary

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$host = 'localhost'; // Your database host
$user = 'root'; // Your database username
$pass = ''; // Your database password
$db = 'shamina'; // Your database name

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $consultancyType = isset($_POST['consultancy_type']) ? $_POST['consultancy_type'] : ''; 
    $firstName = $_POST['first_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $message = $_POST['message']; // Include additional fields if needed

    // Create an instance of PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'emmanuelneequaye294@gmail.com'; // Replace with your email
        $mail->Password = 'gbrw rvem jsbq rrqq';          // Replace with your email password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('emmanuelneequaye294@gmail.com', 'Shamina Group'); // Sender's email
        $mail->addAddress($email, $firstName);    // Recipient's email (client's email)
        
        // For receiving the booking details
        $mail->addAddress('emmanuelneequaye294@gmail.com', 'Shamina Group'); // Your email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Consultation Booking Confirmation';
        $mail->Body    = "Dear $firstName,<br><br>Thank you for booking a consultation for $consultancyType.<br><br>Your booking details are as follows:<br>
                            <strong>Phone:</strong> $phone<br>
                            <strong>Date:</strong> $date<br>
                            <strong>Time:</strong> $time<br><br>
                            We look forward to speaking with you soon.";

        // Send the email
        $mail->send();

        // Insert booking into database
        $stmt = $conn->prepare("INSERT INTO bookings (consultancy_type, first_name, email, phone, date, time, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $consultancyType, $firstName, $email, $phone, $date, $time, $message);

        if ($stmt->execute()) {
            // Redirect to the consultancy.html page with a success status
            header("Location: ../HTML/consultancy.html?status=success");
        } else {
            // Redirect to consultancy.html with an error status
            header("Location: ../HTML/consultancy.html?status=error");
        }
        $stmt->close();
    } catch (Exception $e) {
        // Redirect to consultancy.html with an error status
        header("Location: ../HTML/consultancy.html?status=error");
    }

    // Close the database connection
    $conn->close();
}
?>
