<?php
session_start();
require 'config.php';

if (!isset($_SESSION['temp_qr']) || !isset($_SESSION['form_data'])) {
    header("Location: fill-up.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Request - DocuMentor</title>
    <link rel="stylesheet" href="css/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="verification-card">
            <h2>Verify Your Request</h2>
            <p>Please scan this QR code to verify your request</p>
            
            <div class="qr-container">
                <img src="<?php echo $_SESSION['temp_qr']; ?>" alt="Verification QR Code">
            </div>

            <div id="verification-status">
                <p>Waiting for verification...</p>
            </div>

            <button class="btn btn-primary" onclick="checkVerification()">
                <i class="fas fa-check-circle"></i> Complete Submission
            </button>
        </div>
    </div>

    <script>
        function checkVerification() {
            // Simulate verification (in a real app, this would check if QR was scanned)
            fetch('verify.php?check=1&id=<?php echo $_SESSION['temp_id']; ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.verified) {
                        submitRequest();
                    } else {
                        document.getElementById('verification-status').innerHTML = 
                            '<p class="error">Please scan the QR code first</p>';
                    }
                });
        }

        function submitRequest() {
            fetch('fill-up.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'verify_qr=1'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Request submitted successfully!');
                    window.location.href = 'student/student_index.php';
                } else {
                    alert('Error submitting request. Please try again.');
                }
            });
        }

        // Check verification status every 5 seconds
        setInterval(checkVerification, 5000);
    </script>
</body>
</html> 