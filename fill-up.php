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
$tempData = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['verify_complete'])) {
        // Handle the completion submission
        $tempData = json_decode(base64_decode($_POST['temp_data']), true);
        
        // Insert into database
        $sql = "INSERT INTO studentinquiries (StudentName, StudentLRN, DocumentType, Details, Status, QRCodePath) 
                VALUES (?, ?, ?, ?, ?, ?)";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", 
            $tempData['StudentName'],
            $tempData['StudentLRN'],
            $tempData['DocumentType'],
            $tempData['Details'],
            $tempData['Status'],
            $tempData['QRCodePath']
        );
        
        if ($stmt->execute()) {
            header("Location: /documentor/student/student_index.php");
            exit;
        } else {
            $errorMessage = "Error submitting request. Please try again.";
        }
    } else {
        // Initial form submission - generate QR and show modal
        $StudentName = mysqli_real_escape_string($conn, $_POST["StudentName"]);
        $StudentLRN = mysqli_real_escape_string($conn, $_POST["StudentLRN"]);
        $DocumentType = mysqli_real_escape_string($conn, $_POST["DocumentType"]);
        $Details = mysqli_real_escape_string($conn, $_POST["Details"]);
        $Status = "Pending";

        // Generate QR code
        $tempId = uniqid();
        $qrCodePath = QRGenerator::generateDocumentQR($tempId, $StudentName, $DocumentType);

        // Store data temporarily
        $tempData = [
            'StudentName' => $StudentName,
            'StudentLRN' => $StudentLRN,
            'DocumentType' => $DocumentType,
            'Details' => $Details,
            'Status' => $Status,
            'QRCodePath' => $qrCodePath
        ];
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
            <h2>Verify Document Request</h2>
            <p>Please scan this QR code to verify your document request.</p>
            <div class="qr-container">
                <img id="qrCode" src="" alt="QR Code">
            </div>
            <p class="qr-instructions">Scanning this QR code will redirect you to our verification website.</p>
            <div class="verification-actions">
                <button class="btn btn-primary" onclick="downloadQR()">
                    <i class="fas fa-download"></i> Download QR Code
                </button>
                <form method="POST" id="completeForm">
                    <input type="hidden" name="verify_complete" value="1">
                    <input type="hidden" name="temp_data" value="<?php echo isset($tempData) ? base64_encode(json_encode($tempData)) : ''; ?>">
                    <button type="submit" class="btn btn-success" id="completeBtn">
                        <i class="fas fa-check-circle"></i> Complete Submission
                    </button>
                </form>
            </div>
            <p class="verification-notice">Please click 'Complete Submission' after scanning the QR code.</p>
        </div>
    </div>

    <style>
    .verification-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 1.5rem;
    }

    .verification-notice {
        margin-top: 15px;
        color: #e74c3c;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .qr-instructions {
        color: #666;
        margin: 15px 0;
        font-style: italic;
    }

    .btn-success {
        background: #2ecc71;
        color: white;
    }

    .btn-success:hover {
        background: #27ae60;
    }
    </style>

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
            if (!confirm("Are you sure you want to cancel the submission?")) {
                return;
            }
            modal.style.display = "none";
            window.location.href = "/documentor/student/student_index.php";
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == modal) {
                if (!confirm("Are you sure you want to cancel the submission?")) {
                    return;
                }
                modal.style.display = "none";
                window.location.href = "/documentor/student/student_index.php";
            }
        }

        // Download QR code
        function downloadQR() {
            var link = document.createElement('a');
            link.download = 'document_request_qr.png';
            link.href = document.getElementById('qrCode').src;
            link.click();
        }
    </script>
</body>
</html>
