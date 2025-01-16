<?php
require 'config.php';

// Handle verification check
if (isset($_GET['check'])) {
    $id = $_GET['id'];
    
    // In a real application, you would verify if the QR code was actually scanned
    // For this example, we'll simulate a successful verification
    $verified = true;
    
    if ($verified) {
        // Update verification status in database
        $query = "UPDATE studentinquiries SET verification_status = 1 WHERE temp_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $id);
        $stmt->execute();
    }
    
    echo json_encode(['verified' => $verified]);
    exit;
}

// Handle document verification
if (isset($_GET['id'])) {
    $documentId = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Updated query to use the correct column names from your database
    $query = "SELECT si.*, rl.name as student_name 
             FROM studentinquiries si 
             JOIN reglog rl ON si.StudentName = rl.name 
             WHERE si.id = '$documentId'";
             
    $result = mysqli_query($conn, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['Status'] === 'Approved') {
            echo "<div class='verification-success'>";
            echo "<i class='fas fa-check-circle'></i>";
            echo "<h2>Valid Document</h2>";
            echo "<div class='document-details'>";
            echo "<p><strong>Document Type:</strong> {$row['DocumentType']}</p>";
            echo "<p><strong>Student Name:</strong> {$row['student_name']}</p>";
            echo "<p><strong>Issue Date:</strong> {$row['InquiryDate']}</p>";
            echo "<p><strong>Status:</strong> <span class='status status-approved'>Verified</span></p>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<div class='verification-pending'>";
            echo "<i class='fas fa-clock'></i>";
            echo "<h2>Document Pending Approval</h2>";
            echo "<p>This document is still being processed.</p>";
            echo "</div>";
        }
    }
}
?> 