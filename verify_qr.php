<?php
session_start();
if (!isset($_SESSION['temp_qr'])) {
    header("Location: fill-up.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify QR Code - DocuMentor</title>
    <link rel="stylesheet" href="css/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="qr-verification">
            <h2>Scan QR Code to Submit Request</h2>
            <div class="qr-container">
                <img src="<?php echo $_SESSION['temp_qr']; ?>" alt="QR Code">
            </div>
            <p>Please scan this QR code to verify and submit your request</p>
            <div id="status-message"></div>
        </div>
    </div>

    <script>
    // Poll for verification status
    function checkVerification() {
        fetch('verify.php?id=<?php echo $_SESSION['temp_id']; ?>&check=1')
            .then(response => response.json())
            .then(data => {
                if (data.verified) {
                    // Submit the form data
                    fetch('fill-up.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'verify_qr=1'
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            window.location.href = 'student_index.php?success=1';
                        }
                    });
                }
            });
    }

    // Check every 2 seconds
    setInterval(checkVerification, 2000);
    </script>
</body>
</html> 