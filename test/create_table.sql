/*CREATE TABLE IF NOT EXISTS studentinquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    learner_reference_number VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    document_type VARCHAR(50) NOT NULL,
    reason TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    inquiry_date DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
*/

/** archive table:
CREATE TABLE document_archive (
    archive_id INT PRIMARY KEY AUTO_INCREMENT,
    original_id INT,
    StudentName VARCHAR(255),
    StudentLRN VARCHAR(255),
    DocumentType VARCHAR(255),
    Details TEXT,
    Status VARCHAR(50),
    InquiryDate DATETIME,
    CompletionDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    QRCodePath VARCHAR(255),
    FOREIGN KEY (original_id) REFERENCES studentinquiries(id)
);
alter table an error popped up:
-- Add is_archived column to studentinquiries table
ALTER TABLE studentinquiries ADD COLUMN is_archived BOOLEAN DEFAULT 0;

-- Modify the foreign key constraint to allow updates
ALTER TABLE document_archive DROP FOREIGN KEY document_archive_ibfk_1;
ALTER TABLE document_archive ADD CONSTRAINT document_archive_ibfk_1 
    FOREIGN KEY (original_id) REFERENCES studentinquiries(ID) 
    ON UPDATE CASCADE;

--blockchain:
CREATE TABLE document_blockchain (
    id INT AUTO_INCREMENT PRIMARY KEY,
    document_id INT,
    previous_hash VARCHAR(64),
    current_hash VARCHAR(64),
    timestamp DATETIME,
    action VARCHAR(255),
    student_name VARCHAR(255),
    document_type VARCHAR(255),
    status VARCHAR(50),
    FOREIGN KEY (document_id) REFERENCES studentinquiries(ID)
);
ALTER TABLE reglog ADD COLUMN is_admin BOOLEAN DEFAULT FALSE;
UPDATE reglog SET is_admin = TRUE WHERE email = 'admin@example.com';
/
