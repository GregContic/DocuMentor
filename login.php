<?php
require 'config.php';

if (isset($_POST["submit"])) {
    $usernameOrEmail = mysqli_real_escape_string($conn, $_POST["usernameOrEmail"]);
    $password = $_POST["password"];

    // Query to check username or email
    $query = "SELECT * FROM reglog WHERE email = '$usernameOrEmail'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $user["password"])) {
            echo "<script>alert('Login successful!');</script>";
            // Redirect to a dashboard or homepage
            header("Location: admin_index_new.php");
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
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login_form">
        <!-- Login form container -->
        <form action="login.php" method="POST">
            <h3>Login</h3>
            <br>
            <div class="input_box">
                <label for="usernameOrEmail">Username or Email</label>
                <input type="text" id="usernameOrEmail" name="usernameOrEmail" placeholder="Enter username or email" required />
            </div>
            <div class="input_box">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required />
            </div>
            <button type="submit" name="submit">Login</button>
            <p class="sign_up">Don't have an account? <a href="register.php">Register</a></p>
        </form>
    </div>
</body>
</html>
