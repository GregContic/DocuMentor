<?php
require 'config.php';

// Handle verification check
if (isset($_GET['check'])) {
    $id = $_GET['id'];
    // Check if this QR code has been scanned
    // You might want to add a verification_status column to your database
    $query = "SELECT verification_status FROM studentinquiries WHERE temp_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode(['verified' => $row['verification_status'] == 1]);
    } else {
        echo json_encode(['verified' => false]);
    }
    exit;
}

// Rest of your existing verify.php code... 