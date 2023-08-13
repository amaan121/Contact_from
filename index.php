<?php
// Validate and sanitize form data
$full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
$phone_number = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

// Check if all fields are filled
if (empty($full_name) || empty($phone_number) || empty($email) || empty($subject) || empty($message)) {
    die("All fields are required.");
}

// Connect to the MySQL database
$servername = "your_server_name";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert form data into the database
$insert_query = "INSERT INTO contact_form (full_name, phone_number, email, subject, message, ip_address, timestamp) 
                VALUES ('$full_name', '$phone_number', '$email', '$subject', '$message', '" . $_SERVER['REMOTE_ADDR'] . "', NOW())";

if ($conn->query($insert_query) === TRUE) {
    // Send email notification to the site owner
    $to = "siteowner@example.com";
    $subject = "New Form Submission";
    $message = "Full Name: $full_name\nPhone Number: $phone_number\nEmail: $email\nSubject: $subject\nMessage: $message";
    mail($to, $subject, $message);

    echo "Form submitted successfully.";
} else {
    echo "Error: " . $insert_query . "<br>" . $conn->error;
}

$conn->close();
?>