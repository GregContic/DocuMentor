<?php
$conn = mysqli_connect("localhost", "root", "", "documentor");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
