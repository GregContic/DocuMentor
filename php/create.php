<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "documentor";

// Create connection
$connection = new mysqli($servername, $username, $password, $database);

$StudentName = "";
$StudentLRN = "";
$DocumentType = "";
$Details = "";
$Status = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $StudentName = $_POST["studentName"];
    $StudentLRN = $_POST["studentLRN"];
    $DocumentType = $_POST["docType"];
    $Details = $_POST["details"];
    $Status = "Pending";

    do {
        if (empty($StudentName) || empty($StudentLRN) || empty($DocumentType) || empty($Details) || empty($Status)) {
            $errorMessage = "All the fields are required";
            break;
        }

        // add new client to database
        $sql = "INSERT INTO studentinquiries (StudentName, StudentLRN, DocumentType, Details, Status)" . 
                " VALUES ('$StudentName', '$StudentLRN', '$DocumentType', '$Details', '$Status')";

        
        $result = $connection->query($sql);

        if (!$result) {
            $errorMessage = "Error: ". $connection->error;
            break;
        }

        $StudentName = "";
        $StudentLRN = "";
        $DocumentType = "";
        $Details = "";
        $Status = "";

        $successMessage = "Added successfully";

        header("Location: /DocuMentor/admin_index.php");
        exit;

    } while (false);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <h2>New Inquiry</h2>

        <?php 
        if (!empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                 <strong> $errorMessage </strong>
                 <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
         }
         ?>

        <form method="post">
            <div class="row mb-3">
                <label for="studentName" class="col-sm-2 col-form-label">Student Name:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="studentName" name="studentName" value="<?php echo $StudentName; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="studentLRN" class="col-sm-2 col-form-label">Student Learner's Reference Number:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="studentLRN" name="studentLRN" value="<?php echo $StudentLRN; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="docType" class="col-sm-2 col-form-label">Document Type:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="docType" name="docType" value="<?php echo $DocumentType; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="details" class="col-sm-2 col-form-label">Details:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="details" name="details" value="<?php echo $Details; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label for="status" class="col-sm-2 col-form-label">Status:</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="status" name="status" value="<?php echo $Status; ?>">
                </div>
            </div>

            <?php
            if (!empty($successMessage)){
                echo "
                <div class='row mb-3'>
                    <div class='alert alert-success alert-dismissible fade show' role='alert'>
                     <strong> $successMessage </strong>
                     <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                     </div>
                </div>
                ";
            }

            ?>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/DocuMentor/admin_index.php">Cancel</a>
                </div>
            </div>
            </div>
        </form>
    </div>
</body>
</html>