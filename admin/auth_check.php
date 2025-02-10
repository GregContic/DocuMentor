<?php
session_start();

// Check if user is logged in and is an admin
function checkAdmin() {
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
        header("Location: /documentor/login.php?error=unauthorized");
        exit();
    }
}

// Call the check
checkAdmin();
?> 