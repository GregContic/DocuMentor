<?php
require_once '/documentor/utils/QRGenerator.php';

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    
    // Generate QR code
    $qrPath = QRGenerator::generateDocumentQR($id, $StudentName, $DocumentType);
    
    // Update database with QR code path and approved status
    $sql = "UPDATE studentinquiries SET 
            Status = 'Approved',
            QRCodePath = '$qrPath'
            WHERE id = $id";
            
    $result = $connection->query($sql);
    
    if ($result) {
        header("location: /DocuMentor/admin/admin_index_new.php");
        exit;
    }
} 