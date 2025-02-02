<?php
class Block {
    private $connection;
    
    public function __construct($connection) {
        $this->connection = $connection;
    }
    
    // Generate hash based on document data
    private function generateHash($documentId, $studentName, $documentType, $status, $timestamp, $previousHash) {
        $data = $documentId . $studentName . $documentType . $status . $timestamp . $previousHash;
        return hash('sha256', $data);
    }
    
    // Add new block to blockchain
    public function addBlock($documentId, $studentName, $documentType, $status, $action) {
        // Get the previous hash
        $stmt = $this->connection->prepare("SELECT current_hash FROM document_blockchain 
                                          WHERE document_id = ? 
                                          ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("i", $documentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $previousHash = $result->num_rows > 0 ? $result->fetch_assoc()['current_hash'] : '0';
        
        // Generate new hash
        $timestamp = date('Y-m-d H:i:s');
        $currentHash = $this->generateHash($documentId, $studentName, $documentType, $status, $timestamp, $previousHash);
        
        // Insert new block
        $stmt = $this->connection->prepare("INSERT INTO document_blockchain 
                                          (document_id, previous_hash, current_hash, timestamp, action, 
                                           student_name, document_type, status) 
                                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("isssssss", 
            $documentId, 
            $previousHash, 
            $currentHash, 
            $timestamp,
            $action,
            $studentName,
            $documentType,
            $status
        );
        
        return $stmt->execute();
    }
    
    // Verify blockchain integrity
    public function verifyChain($documentId) {
        $stmt = $this->connection->prepare("SELECT * FROM document_blockchain 
                                          WHERE document_id = ? 
                                          ORDER BY id ASC");
        $stmt->bind_param("i", $documentId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $previousHash = '0';
        
        while ($block = $result->fetch_assoc()) {
            // Verify previous hash matches
            if ($block['previous_hash'] !== $previousHash) {
                return false;
            }
            
            // Verify current hash is correct
            $calculatedHash = $this->generateHash(
                $block['document_id'],
                $block['student_name'],
                $block['document_type'],
                $block['status'],
                $block['timestamp'],
                $block['previous_hash']
            );
            
            if ($calculatedHash !== $block['current_hash']) {
                return false;
            }
            
            $previousHash = $block['current_hash'];
        }
        
        return true;
    }
}
?> 