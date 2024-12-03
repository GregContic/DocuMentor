<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "documentor";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$learner_reference_number = $_POST['learner_reference_number'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$document_type = $_POST['document_type'];
$reason = $_POST['reason'];
$status = 'pending'; // Default status for new inquiries
$inquiry_date = date('Y-m-d H:i:s'); // Current timestamp

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO studentinquiries (first_name, last_name, learner_reference_number, email, phone, document_type, reason, status, inquiry_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", $first_name, $last_name, $learner_reference_number, $email, $phone, $document_type, $reason, $status, $inquiry_date);

// Execute the statement
if ($stmt->execute()) {
    // Redirect back to student dashboard with success message
    header("Location: student_index.html?status=success");
    exit();
} else {
    // Redirect back with error message
    header("Location: student_index.html?status=error");
    exit();
}

$stmt->close();
$conn->close();
?>
