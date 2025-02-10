<?php
session_start();
require 'config.php';

if (isset($_POST["submit"])) {
    $usernameemail = mysqli_real_escape_string($conn, $_POST["usernameemail"]);
    $password = $_POST["password"];
    
    // Add debug output
    error_log("Attempting login for user: " . $usernameemail);
    
    $result = mysqli_query($conn, "SELECT * FROM reglog WHERE email = '$usernameemail'");
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
        if (password_verify($password, $row["password"])) {
            $_SESSION["login"] = true;
            $_SESSION["id"] = $row["id"];
            
            // Debug output
            error_log("User is_admin value: " . ($row["is_admin"] ? "true" : "false"));
            
            // Set user type based on is_admin column
            if ($row["is_admin"] == 1) {
                $_SESSION["user_type"] = "admin";
                error_log("Setting user_type to admin");
                header("Location: admin/admin_index_new.php");
            } else {
                $_SESSION["user_type"] = "student";
                error_log("Setting user_type to student");
                header("Location: student/student_index.php");
            }
            exit;
        } else {
            echo "<script> alert('Wrong Password'); </script>";
        }
    } else {
        echo "<script> alert('User Not Registered'); </script>";
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
                           name="usernameemail" 
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
