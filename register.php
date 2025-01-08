<?php
require 'config.php';

if (isset($_POST["submit"])) {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $number = mysqli_real_escape_string($conn, $_POST["number"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    // Check for duplicate username or email
    $duplicate = mysqli_query($conn, "SELECT * FROM reglog WHERE name = '$name' OR email = '$email'");
    
    if (mysqli_num_rows($duplicate) > 0) {
        echo "<script>alert('Username or Email already exists');</script>";
    } else {
        if ($password === $confirmPassword) {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert into the database
            $query = "INSERT INTO reglog (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Registration successful');</script>";
            } else {
                echo "<script>alert('Registration failed. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Passwords do not match. Please try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DocuMentor</title>
    <link rel="stylesheet" href="css/admin_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="images/logo.png" alt="DocuMentor Logo" width="80" height="80">
                <h1>Create Account</h1>
                <p>Join DocuMentor today</p>
            </div>
            <form action="register.php" method="POST" class="login-form">
                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user"></i> Full Name
                    </label>
                    <input type="text" 
                           class="form-control"
                           id="name" 
                           name="name" 
                           placeholder="Enter your full name" 
                           required>
                </div>
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email Address
                    </label>
                    <input type="email" 
                           class="form-control"
                           id="email" 
                           name="email" 
                           placeholder="Enter your email" 
                           required>
                </div>
                <div class="form-group">
                    <label for="number">
                        <i class="fas fa-phone"></i> Phone Number
                    </label>
                    <div class="phone-input">
                        <span class="phone-prefix">+63</span>
                        <input type="tel" 
                               class="form-control"
                               id="number" 
                               name="number" 
                               placeholder="9XX XXX XXXX"
                               pattern="\d{10}"
                               minlength="10" 
                               maxlength="10"
                               required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <input type="password" 
                           class="form-control"
                           id="password" 
                           name="password" 
                           placeholder="Create a password" 
                           required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">
                        <i class="fas fa-lock"></i> Confirm Password
                    </label>
                    <input type="password" 
                           class="form-control"
                           id="confirmPassword" 
                           name="confirmPassword" 
                           placeholder="Confirm your password" 
                           required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
                <p class="register-link">
                    Already have an account? <a href="login.php">Login here</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>