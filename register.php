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
<!-- Source Codes By CodingNepal - www.codingnepalweb.com -->
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registration Form</title>
  <link rel="stylesheet" href="css/register.css" />
</head>
<body>
  <div class="register_form">
    <!-- register form container -->
    <form action="register.php" method="POST">
    <h3>Register</h3>
    <div class="input_box">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Enter name" required />
    </div>
    <div class="input_box">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="Enter email address" required />
    </div>
    <div class="input_box">
        <label for="number">Phone Number</label>
        <input type="number" id="number" name="number" placeholder="Enter Phone Number" required />
    </div>
    
    <div class="input_box">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required />
    </div>
    <div class="input_box">
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required />
    </div>
    <button type="submit" name="submit">Register</button>
    <p class="sign_up">Already have an account? <a href="login.php">Log In</a></p>
</form>

  </div>
  
</body>
</html>