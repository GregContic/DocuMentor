 <?php
 if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "documentor";

    // Create connection
$connection = new mysqli($servername, $username, $password, $database);


$sql = "DELETE FROM studentinquiries WHERE id=$id";
$connection->query($sql);
 }
 header("Location: /Documentor/admin_index.php");
 exit;
?>