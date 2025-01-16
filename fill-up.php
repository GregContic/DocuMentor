<?php
require 'config.php';
require_once 'utils/QRGenerator.php';

// Initialize variables
$StudentName = "";
$StudentLRN = "";
$DocumentType = "";
$Details = "";
$successMessage = "";
$errorMessage = "";
$qrCodePath = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    $_SESSION['form_data'] = $_POST;
    
    $StudentName = mysqli_real_escape_string($conn, $_POST["StudentName"]);
    $StudentLRN = mysqli_real_escape_string($conn, $_POST["StudentLRN"]);
    $DocumentType = mysqli_real_escape_string($conn, $_POST["DocumentType"]);
    $Details = mysqli_real_escape_string($conn, $_POST["Details"]);
    
    // Generate temporary QR code for verification
    $tempId = uniqid();
    $qrCodePath = QRGenerator::generateDocumentQR($tempId, $StudentName, $DocumentType);
    
    // Store data in session
    $_SESSION['temp_qr'] = $qrCodePath;
    $_SESSION['temp_id'] = $tempId;
    
    // Redirect to QR verification page
    header("Location: verify_qr.php");
    exit;
}

// Handle QR verification callback
if (isset($_POST['verify_qr'])) {
    session_start();
    $formData = $_SESSION['form_data'];
    
    // Insert into database
    $Status = "Pending";
    $sql = "INSERT INTO studentinquiries (StudentName, StudentLRN, DocumentType, Details, Status) 
            VALUES (?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", 
        $formData['StudentName'],
        $formData['StudentLRN'],
        $formData['DocumentType'],
        $formData['Details'],
        $Status
    );
    
    if ($stmt->execute()) {
        $requestId = $stmt->insert_id;
        // Generate final QR code
        $qrCodePath = QRGenerator::generateDocumentQR($requestId, $formData['StudentName'], $formData['DocumentType']);
        
        // Update database with QR code path
        $updateQr = "UPDATE studentinquiries SET QRCodePath = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQr);
        $stmt->bind_param("si", $qrCodePath, $requestId);
        $stmt->execute();
        
        // Clear session data
        unset($_SESSION['form_data']);
        unset($_SESSION['temp_qr']);
        unset($_SESSION['temp_id']);
        
        echo json_encode(['success' => true, 'message' => 'Request submitted successfully']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Document - DocuMentor</title>
    <link rel="stylesheet" href="css/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="navdiv">
            <div class="nav-header">
                <a href="student_index.php" id="navtitle">
                    <img src="images/logo.png" alt="Documentor Logo" width="50" height="50">
                    DocuMentor
                </a>
            </div>
            <ul class="navlist">
                <li><a href="/documentor/student/student_index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="student_index.php#inquiry" class="active"><i class="fas fa-file-alt"></i> New Request</a></li>
                <li><a href="student_index.php#history"><i class="fas fa-history"></i> History</a></li>
                <li><a href="student_profile.html"><i class="fas fa-user"></i> Profile</a></li>
            </ul>
        </div>
    </div>

    <div class="container">
        <section id="inquiry" class="section">
            <div class="section-header">
                <h2><i class="fas fa-file-alt"></i> Request a Document</h2>
                <p>Please fill in the details below to submit your document request.</p>
            </div>

            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $successMessage; ?>
                    <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $errorMessage; ?>
                    <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            <?php endif; ?>

            <form method="POST" class="inquiry-form">
                <div class="form-group">
                    <label for="StudentName">
                        <i class="fas fa-user"></i> Student Name
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="StudentName" 
                           name="StudentName" 
                           value="<?php echo $StudentName; ?>" 
                           placeholder="Enter your full name"
                           required>
                </div>

                <div class="form-group">
                    <label for="StudentLRN">
                        <i class="fas fa-id-card"></i> Student LRN
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="StudentLRN" 
                           name="StudentLRN" 
                           value="<?php echo $StudentLRN; ?>" 
                           placeholder="Enter your Learner Reference Number"
                           required>
                </div>

                <div class="form-group">
                    <label for="DocumentType">
                        <i class="fas fa-file"></i> Document Type
                    </label>
                    <select class="form-control" 
                            id="DocumentType" 
                            name="DocumentType" 
                            required>
                            <div class="col-sm-6">
                        <option value="">Select Document Type</option>
                        <option value="Transcript of Records (TOR)">Transcript of Records (TOR)</option>
                        <option value="Certificate of Graduation">Certificate of Graduation</option>
                        <option value="Diploma">Diploma</option>
                        <option value="Certificate of Enrollment">Certificate of Enrollment</option>
                        <option value="Affidavit of Lost Documents">Affidavit of Lost Documents</option>
                        <option value="Form 137">Form 137</option>
                        <option value="Certificate of Good Moral">Certificate of Good Moral</option>
                        <option value="Transcript (Form 10)">Transcript (Form 10)</option>
                        <option value="ID Card">ID Card</option>

                </div>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Details">
                        <i class="fas fa-info-circle"></i> Additional Details
                    </label>
                    <textarea class="form-control" 
                              id="Details" 
                              name="Details" 
                              rows="4" 
                              placeholder="Please provide any additional information or specific requirements"><?php echo $Details; ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit Request
                    </button>
                    <a href="student_index.php" class="btn btn-outline-primary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </section>
    </div>

    <!-- QR Code Modal -->
    <div id="qrModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Document Request Submitted</h2>
            <p>Your request has been submitted successfully. Please save this QR code for future reference.</p>
            <div class="qr-container">
                <img id="qrCode" src="" alt="QR Code">
            </div>
            <button class="btn btn-primary" onclick="downloadQR()">
                <i class="fas fa-download"></i> Download QR Code
            </button>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("qrModal");
        var span = document.getElementsByClassName("close")[0];

        // When the page loads, check if we need to show the QR code
        window.onload = function() {
            <?php if (!empty($qrCodePath)): ?>
            showQRCode("<?php echo $qrCodePath; ?>");
            <?php endif; ?>
        };

        // Function to show QR code
        function showQRCode(path) {
            document.getElementById("qrCode").src = path;
            modal.style.display = "block";
        }

        // Close modal when clicking (x)
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Download QR code
        function downloadQR() {
            var link = document.createElement('a');
            link.download = 'document_request_qr.png';
            link.href = document.getElementById('qrCode').src;
            link.click();
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.getElementsByClassName('alert');
            for(var i = 0; i < alerts.length; i++) {
                alerts[i].style.display = 'none';
            }
        }, 5000);
    </script>
</body>
</html>
