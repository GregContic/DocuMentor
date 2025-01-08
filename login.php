<?php
require 'config.php';

if (isset($_POST["submit"])) {
    $usernameOrEmail = mysqli_real_escape_string($conn, $_POST["usernameOrEmail"]);
    $password = $_POST["password"];

    // Modified query to also fetch the user's role
    $query = "SELECT * FROM reglog WHERE email = '$usernameOrEmail'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $user["password"])) {
            echo "<script>alert('Login successful!');</script>";
            // Check user role and redirect accordingly
            if ($user["role"] === "admin") {
                header("Location: /documentor/admin/admin_index_new.php");
            } else {
                header("Location: /documentor/student_index.php");
            }
            exit(); // Add exit after redirect
        } else {
            echo "<script>alert('Incorrect password.');</script>";
        }
    } else {
        echo "<script>alert('No user found with that username or email.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DocuMentor</title>
    <link rel="stylesheet" href="/documentor/css/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="images/logo.png" alt="DocuMentor Logo" width="80" height="80">
                <h1>DocuMentor</h1>
                <p>Document Request Management System</p>
            </div>
            <form action="login.php" method="POST" class="login-form">
                <div class="form-group">
                    <label for="usernameOrEmail">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type="text" 
                           class="form-control"
                           id="usernameOrEmail" 
                           name="usernameOrEmail" 
                           placeholder="Enter your email" 
                           required>
                </div>
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" 
                           class="form-control"
                           id="password" 
                           name="password" 
                           placeholder="Enter your password" 
                           required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
                <p class="register-link">
                    Don't have an account? <a href="register.php">Register here</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>
