<?php
require_once 'blockchain_utils.php';

$servername = "localhost";
$username = "root";
$password = "";
$database = "documentor";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $connection->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $status = $_POST['status'];
    
    // Start transaction
    $connection->begin_transaction();
    
    try {
        // Get current document info
        $stmt = $connection->prepare("SELECT StudentName, DocumentType FROM studentinquiries WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $document = $result->fetch_assoc();
        
        // Update the status
        $stmt = $connection->prepare("UPDATE studentinquiries SET Status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error updating status: " . $stmt->error);
        }
        
        // Add blockchain record
        $blockchain = new Block($connection);
        $action = "Status updated to: " . $status;
        if (!$blockchain->addBlock($id, $document['StudentName'], $document['DocumentType'], $status, $action)) {
            throw new Exception("Error adding blockchain record");
        }
        
        // Handle archiving if status is Completed
        if ($status === 'Completed') {
            // Insert into archive
            $archiveQuery = "INSERT INTO document_archive 
                            (original_id, StudentName, StudentLRN, DocumentType, Details, Status, InquiryDate, QRCodePath, CompletionDate) 
                            SELECT id, StudentName, StudentLRN, DocumentType, Details, Status, InquiryDate, QRCodePath, NOW() 
                            FROM studentinquiries 
                            WHERE id = ?";
            
            $archiveStmt = $connection->prepare($archiveQuery);
            $archiveStmt->bind_param("i", $id);
            
            if (!$archiveStmt->execute()) {
                throw new Exception("Error archiving record: " . $archiveStmt->error);
            }
            
            // Mark as archived
            $updateQuery = "UPDATE studentinquiries SET is_archived = 1 WHERE id = ?";
            $updateStmt = $connection->prepare($updateQuery);
            $updateStmt->bind_param("i", $id);
            
            if (!$updateStmt->execute()) {
                throw new Exception("Error marking record as archived: " . $updateStmt->error);
            }
        }
        
        // Verify blockchain integrity
        if (!$blockchain->verifyChain($id)) {
            throw new Exception("Blockchain integrity check failed");
        }
        
        // Commit transaction
        $connection->commit();
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Rollback transaction on error
        $connection->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 