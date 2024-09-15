<?php
include 'db_connection.php'; // This is your existing connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $consultancy_type = $_POST['consultancy_type'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $message = $_POST['message'];

    // Prepare SQL query to insert booking into the database
    $sql = "INSERT INTO bookings (consultancy_type, first_name, last_name, email, phone, date, time, message)
            VALUES ('$consultancy_type', '$first_name', '$last_name', '$email', '$phone', '$date', '$time', '$message')";

    if (mysqli_query($conn, $sql)) {
        echo "Booking successful!";
        // You can add the email confirmation functionality here
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    mysqli_close($conn); // Close the connection
}
?>
